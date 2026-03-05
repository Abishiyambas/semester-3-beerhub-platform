using MySql.Data.MySqlClient;

namespace BeerHub.API;

public sealed class BatchData
{
    private readonly string _connectionString;

    public BatchData(string connectionString)
    {
        _connectionString = connectionString;
    }

    public async Task InsertBatchAsync(
        int beerTypeId,
        int speed,
        int quantity,
        int defective,
        float avgTemperature,
        float avgHumidity,
        float avgVibration,
        int? presetId,
        int userId)
    {
        await using MySqlConnection connection = new MySqlConnection(_connectionString);
        await connection.OpenAsync();

        const string sql = @"
            INSERT INTO batches
                (beer_type_id, speed, quantity, defective,
                 avg_temperature, avg_humidity, avg_vibration,
                 preset_id, user_id, created_at)
            VALUES
                (@beer_type_id, @speed, @quantity, @defective,
                 @avg_temperature, @avg_humidity, @avg_vibration,
                 @preset_id, @user_id, NOW());";

        await using MySqlCommand cmd = new MySqlCommand(sql, connection);

        cmd.Parameters.AddWithValue("@beer_type_id", beerTypeId);
        cmd.Parameters.AddWithValue("@speed", speed);
        cmd.Parameters.AddWithValue("@quantity", quantity);
        cmd.Parameters.AddWithValue("@defective", defective);
        cmd.Parameters.AddWithValue("@avg_temperature", avgTemperature);
        cmd.Parameters.AddWithValue("@avg_humidity", avgHumidity);
        cmd.Parameters.AddWithValue("@avg_vibration", avgVibration);

        if (presetId.HasValue)
            cmd.Parameters.AddWithValue("@preset_id", presetId.Value);
        else
            cmd.Parameters.AddWithValue("@preset_id", DBNull.Value);

        cmd.Parameters.AddWithValue("@user_id", userId);

        await cmd.ExecuteNonQueryAsync();
    }
}