
using Opc.UaFx.Client;

namespace BeerHub.API;

public sealed class BatchQueueService
{
    private readonly List<BatchQueueItem> _queue = new List<BatchQueueItem>();
    private readonly object _lock = new object();

    private readonly BatchStarter _starter;
    private readonly OpcClientWrapper _wrapper;
    private readonly BatchData _batchRepository;
    private readonly CancellationTokenSource _cts = new CancellationTokenSource();
    private readonly Task _workerTask;

    private BatchQueueItem? _current;
    private int _producedAtStart;

    public BatchQueueService(
        BatchStarter starter,
        OpcClientWrapper wrapper,
        BatchData batchRepository)
    {
        _starter = starter;
        _wrapper = wrapper;
        _batchRepository = batchRepository;

        _workerTask = Task.Run(() => WorkerLoop(_cts.Token));
    }

    public IReadOnlyList<BatchQueueItem> GetQueue()
    {
        lock (_lock)
        {
            return _queue.ToList();
        }
    }

    public BatchQueueItem Enqueue(BatchRequest request)
    {
        BatchQueueItem item = new BatchQueueItem(request);
        lock (_lock)
        {
            _queue.Add(item);
        }
        return item;
    }

    public void RemoveMany(IEnumerable<Guid> ids)
    {
        HashSet<Guid> idSet = [.. ids];
        lock (_lock)
        {
            int before = _queue.Count;
            _queue.RemoveAll(x => idSet.Contains(x.Id));
            int after = _queue.Count;
        }
    }

    private async void SendCommands(OpcClient client, CancellationToken token, PackMlCommand cmd)
    {
        OpcNodes.CubeCommand.CntrlCmd.Write(client, (int)cmd);
        OpcNodes.CubeCommand.CmdChangeRequest.Write(client, true);
        await Task.Delay(500, token);
    }

    private async Task WorkerLoop(CancellationToken token)
    {
        while (!token.IsCancellationRequested)
        {
            try
            {
                OpcClient client = _wrapper.Client;
                int state = OpcNodes.CubeStatus.StateCurrent.Read(client);

                // Handle Aborted (9)
                if (state == 9)
                {
                    SendCommands(client, token, PackMlCommand.Clear);
                    SendCommands(client, token, PackMlCommand.Reset);
                    // After this, machine should move toward Idle (4)
                    continue;
                }

                if (_current != null)
                {
                    BatchQueueItem running = _current;
                    BatchRequest r = running.Request;

                    int producedNow = OpcNodes.CubeAdmin.ProdProcessedCount.Read(client);
                    int producedDelta = producedNow - _producedAtStart;

                    bool quantityReached = producedDelta >= r.Quantity;
                    bool stateIsCompleted = state == 17;
                    bool stateIsIdleOrStopped = state == 4 || state == 2;

                    if (stateIsCompleted || (quantityReached && stateIsIdleOrStopped))
                    {
                        int beerTypeIdForDb = (int)r.BeerType;
                        float temp = OpcNodes.CubeStatus.Temperature.Read(client);
                        float hum = OpcNodes.CubeStatus.Humidity.Read(client);
                        float vib = OpcNodes.CubeStatus.Vibration.Read(client);

                        try
                        {
                            await _batchRepository.InsertBatchAsync(
                                beerTypeId: beerTypeIdForDb,
                                speed: (int)r.Speed,
                                quantity: r.Quantity,
                                defective: 0,
                                avgTemperature: temp,
                                avgHumidity: hum,
                                avgVibration: vib,
                                presetId: r.PresetId,
                                userId: r.UserId
                            );
                        }
                        catch
                        {
                            // ignore DB errors, batch is still considered finished
                        }

                        lock (_lock)
                        {
                            _queue.RemoveAll(x => x.Id == running.Id);
                        }

                        _current = null;

                        if (state == 17)
                        {
                            SendCommands(client, token, PackMlCommand.Reset);
                        }
                    }
                }

                bool hasItems;
                lock (_lock)
                {
                    hasItems = _queue.Count > 0;
                }
                if (!hasItems)
                {
                    await Task.Delay(500, token);
                    continue;
                }

                if (state == 17 && _current == null)
                {
                    SendCommands(client, token, PackMlCommand.Reset);

                    for (int i = 0; i < 10; i++)
                    {
                        state = OpcNodes.CubeStatus.StateCurrent.Read(client);
                        if (state == 4)
                        {
                            break;
                        }
                        await Task.Delay(500, token);
                    }
                }

                // If Idle and no current, start next batch automatically
                if (state == 4 && _current == null)
                {
                    BatchQueueItem? next = null;
                    lock (_lock)
                    {
                        if (_queue.Count > 0)
                        {
                            next = _queue[0];
                        }
                    }

                    if (next != null)
                    {
                        _current = next;
                        _current.Status = BatchQueueStatus.Running;

                        BatchRequest r = next.Request;

                        _producedAtStart = OpcNodes.CubeAdmin.ProdProcessedCount.Read(client);

                        _starter.StartBatch(r);
                    }
                }

                await Task.Delay(500, token);
            }
            catch (TaskCanceledException)
            {
                // ignore
            }
            catch
            {
                await Task.Delay(1000, token);
            }
        }
    }
}