namespace BeerHub.API;

public class BatchStarter
{
    private readonly OpcClientWrapper _wrapper;

    public BatchStarter(OpcClientWrapper wrapper)
    {
        _wrapper = wrapper;
        _wrapper.Connect();
    }

    public void StartBatch(BatchRequest req)
    {
        Opc.UaFx.Client.OpcClient client = _wrapper.Client;

        float max = req.BeerType switch
        {
            BeerType.Pilsner     => 600,
            BeerType.Wheat       => 300,
            BeerType.Ipa         => 150,
            BeerType.Stout       => 200,
            BeerType.Ale         => 100,
            BeerType.AlcoholFree => 125,
            _ => throw new ArgumentOutOfRangeException(nameof(req.BeerType))
        };

        if (req.Speed < 0 || req.Speed > max)
        {
            throw new ArgumentOutOfRangeException(
                nameof(req.Speed),
                $"Speed must be between 0 and {max} for {req.BeerType}");
        }

        OpcNodes.CubeCommand.BatchId.Write(client, req.BatchId);
        OpcNodes.CubeCommand.ProductId.Write(client, (int)req.BeerType);
        OpcNodes.CubeCommand.ProductsAmount.Write(client, req.Quantity);
        OpcNodes.CubeCommand.MachSpeed.Write(client, req.Speed);

        //Start command
        OpcNodes.CubeCommand.CntrlCmd.Write(client, (int)PackMlCommand.Start);
        OpcNodes.CubeCommand.CmdChangeRequest.Write(client, true);
    }
}