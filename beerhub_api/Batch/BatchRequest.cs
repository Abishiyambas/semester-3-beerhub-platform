namespace BeerHub.API;

public record BatchRequest(
    int BatchId,
    BeerType BeerType,
    int Quantity,
    float Speed,
    int UserId,
    int? PresetId
);