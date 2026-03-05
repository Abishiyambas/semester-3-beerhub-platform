using Opc.UaFx.Client;

namespace BeerHub.API;

public class OpcClientWrapper : IDisposable
{
    private OpcClient? _client;
    private readonly string _serverUrl;

    public OpcClientWrapper(string serverUrl)
    {
        _serverUrl = serverUrl;
    }

    public OpcClient Client
    {
        get
        {
            if (_client == null)
            {
                _client = new OpcClient(_serverUrl);
                _client.Connect();
            }
            return _client;
        }
    }

    public void Connect() => _ = Client;

    public void Disconnect()
    {
        if (_client != null)
        {
            _client.Disconnect();
            _client = null;
        }
    }

    public void Dispose() => Disconnect();
}