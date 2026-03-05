using Opc.UaFx.Client;

namespace BeerHub.API;

public class Descriptor<T>
{
    public string NodeId { get; }
    public string Path { get; }

    public Descriptor(string prefix, string path)
    {
        NodeId = prefix + path;
        Path = path;
    }

    public T Read(OpcClient client)
    {
        Opc.UaFx.OpcValue value = client.ReadNode(NodeId);
        if (value == null || value.Value == null)
            return default!;

        return (T)Convert.ChangeType(value.Value, typeof(T));
    }

    public void Write(OpcClient client, T value)
    {
        client.WriteNode(NodeId, value);
    }
}