using System.Collections.Generic;
using System.Linq;
using Opc.UaFx.Client;
using System.Text.Json;

namespace BeerHub.API;

public static class MachineEndpoints
{
    public static void MapMachineRoutes(this WebApplication app)
    {
        app.MapGet("/machine/maintenance-inventory", (OpcClientWrapper wrapper) =>
        {
            OpcClient client = wrapper.Client;

            MaintenanceInventoryResponse response = new MaintenanceInventoryResponse(
                MaintenanceCounter: OpcNodes.Maintenance.MaintenanceCount.Read(client),
                Barley: OpcNodes.Inventory.Barley.Read(client),
                Hops: OpcNodes.Inventory.Hops.Read(client),
                Malt: OpcNodes.Inventory.Malt.Read(client),
                Wheat: OpcNodes.Inventory.Wheat.Read(client),
                Yeast: OpcNodes.Inventory.Yeast.Read(client)
            );

            return Results.Ok(response);
        }).WithName("GetMaintenanceAndInventory");

        app.MapGet("/machine/status", (OpcClientWrapper wrapper) =>
        {
            OpcClient client = wrapper.Client;

            MachineStatusResponse response = new MachineStatusResponse(
                State: OpcNodes.CubeStatus.StateCurrent.Read(client),
                ProducedCount: OpcNodes.CubeAdmin.ProdProcessedCount.Read(client),
                DefectiveCount: OpcNodes.CubeAdmin.ProdDefectiveCount.Read(client),
                StopReasonId: OpcNodes.CubeAdmin.StopReasonId.Read(client),
                CurrentSpeed: OpcNodes.CubeStatus.MachSpeed.Read(client),
                Humidity: OpcNodes.CubeStatus.Humidity.Read(client),
                Temperature: OpcNodes.CubeStatus.Temperature.Read(client),
                Vibration: OpcNodes.CubeStatus.Vibration.Read(client)
            );

            return Results.Ok(response);
        }).WithName("GetMachineStatus");

        app.MapPost("/machine/queue", (BatchQueueService queueService, BatchRequest req) =>
        {
            BatchQueueItem item = queueService.Enqueue(req);

            return Results.Ok(new
            {
                Message = "Batch enqueued",
                item.Id,
                req.BatchId,
                req.BeerType,
                req.Quantity,
                req.Speed,
                req.UserId,
                req.PresetId,
                Status = item.Status.ToString()
            });
        }).WithName("EnqueueBatch");

        app.MapGet("/machine/queue", (BatchQueueService queueService) =>
        {
            IReadOnlyList<BatchQueueItem> items = queueService.GetQueue();

            IEnumerable<object> response = items.Select(x => new
            {
                x.Id,
                x.Request.BatchId,
                x.Request.BeerType,
                x.Request.Quantity,
                x.Request.Speed,
                x.Request.UserId,
                x.Request.PresetId,
                Status = x.Status.ToString()
            });

            return Results.Ok(response);
        }).WithName("GetBatchQueue");

        app.MapDelete("/machine/queue/remove", async (HttpContext context, BatchQueueService queueService) =>
        {
            using var reader = new StreamReader(context.Request.Body);
            string body = await reader.ReadToEndAsync();

            Guid[] ids;
            try
            {
                ids = JsonSerializer.Deserialize<Guid[]>(body) ?? Array.Empty<Guid>();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Failed to deserialize IDs: " + ex.Message);
                return Results.BadRequest(new { Message = "Invalid JSON for GUID array." });
            }

            queueService.RemoveMany(ids);

            IReadOnlyList<BatchQueueItem> items = queueService.GetQueue();
            var response = items.Select(x => new
            {
                x.Id,
                x.Request.BatchId,
                x.Request.BeerType,
                x.Request.Quantity,
                x.Request.Speed,
                x.Request.UserId,
                x.Request.PresetId,
                Status = x.Status.ToString()
            });

            return Results.Ok(response);
        }).WithName("RemoveBatchQueueItems");

        app.MapPost("/machine/command/{id:int}", (OpcClientWrapper wrapper, int id) =>
        {
            OpcClient client = wrapper.Client;

            if (!Enum.IsDefined(typeof(PackMlCommand), id))
            {
                return Results.BadRequest(new { Message = $"Unknown command id: {id}" });
            }

            PackMlCommand cmd = (PackMlCommand)id;
            OpcNodes.CubeCommand.CntrlCmd.Write(client, (int)cmd);
            OpcNodes.CubeCommand.CmdChangeRequest.Write(client, true);

            return Results.Ok(new
            {
                Message = "Command sent",
                CommandId = id,
                CommandName = cmd.ToString()
            });
        }).WithName("SendMachineCommand");
    }
}