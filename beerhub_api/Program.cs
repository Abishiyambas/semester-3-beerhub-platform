
using BeerHub.API;

WebApplicationBuilder builder = WebApplication.CreateBuilder(args);

builder.Services.AddOpenApi();

// Takes Environment values from docker-compose
string dbHost = Environment.GetEnvironmentVariable("DB_HOST") ?? "localhost";
string dbPort = Environment.GetEnvironmentVariable("DB_PORT") ?? "3306";
string dbDatabase = Environment.GetEnvironmentVariable("DB_DATABASE") ?? "beerhub";
string dbUsername = Environment.GetEnvironmentVariable("DB_USERNAME") ?? "root";
string dbPassword = Environment.GetEnvironmentVariable("DB_PASSWORD") ?? "rootsecret";
string opcUaEndpoint = Environment.GetEnvironmentVariable("OpcUa__EndpointUrl") ?? "opc.tcp://host.docker.internal:4840";

// OPC client wrapper as a singleton
builder.Services.AddSingleton(_ => new OpcClientWrapper(opcUaEndpoint)); 
// Batch starter service
builder.Services.AddSingleton<BatchStarter>();

// MySQL connection string
string mysqlConnectionString = $"Server={dbHost};Port={dbPort};Database={dbDatabase};User Id={dbUsername};Password={dbPassword};";
builder.Services.AddSingleton(new BatchData(mysqlConnectionString));

// Queue service as singleton
builder.Services.AddSingleton<BatchQueueService>();

WebApplication app = builder.Build();

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
}

app.UseHttpsRedirection();

// Map routes from separate class
app.MapMachineRoutes();

app.Run();

// DTOs
public record MaintenanceInventoryResponse(
    ushort MaintenanceCounter,
    float Barley,
    float Hops,
    float Malt,
    float Wheat,
    float Yeast
);

public record MachineStatusResponse(
    int State,
    int ProducedCount,
    int DefectiveCount,
    int StopReasonId,
    float CurrentSpeed,
    float Humidity,
    float Temperature,
    float Vibration
);

public record RemoveQueueItemsRequest(
    IEnumerable<Guid> Ids
);
