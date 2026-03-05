namespace BeerHub.API;

public enum BatchQueueStatus
{
    Pending,
    Running
}

public sealed class BatchQueueItem
{
    public Guid Id { get; } = Guid.NewGuid();
    public BatchRequest Request { get; }
    public BatchQueueStatus Status { get; set; } = BatchQueueStatus.Pending;

    public BatchQueueItem(BatchRequest request)
    {
        Request = request;
    }
}