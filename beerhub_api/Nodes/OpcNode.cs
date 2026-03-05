namespace BeerHub.API;

public static class OpcNodes
{
    private const string NamespaceIdentifier = "ns=6;s=::Program:";

    public static class CubeAdmin
    {
        private const string Prefix = NamespaceIdentifier + "Cube.Admin.";
        public static readonly Descriptor<float> CurrentProductId = new(Prefix, "Parameter[0].Value");
        public static readonly Descriptor<int> ProdDefectiveCount = new(Prefix, "ProdDefectiveCount");
        public static readonly Descriptor<int> ProdProcessedCount = new(Prefix, "ProdProcessedCount");
        public static readonly Descriptor<int> StopReasonId = new(Prefix, "StopReason.Id");
        public static readonly Descriptor<int> StopReasonValue = new(Prefix, "StopReason.Value");
    }

    public static class CubeCommand
    {
        private const string Prefix = NamespaceIdentifier + "Cube.Command.";
        public static readonly Descriptor<float> MachSpeed = new(Prefix, "MachSpeed");
        public static readonly Descriptor<int> CntrlCmd = new(Prefix, "CntrlCmd");
        public static readonly Descriptor<bool> CmdChangeRequest = new(Prefix, "CmdChangeRequest");
        public static readonly Descriptor<float> BatchId = new(Prefix, "Parameter[0].Value");
        public static readonly Descriptor<float> ProductId = new(Prefix, "Parameter[1].Value");
        public static readonly Descriptor<float> ProductsAmount = new(Prefix, "Parameter[2].Value");
    }

    public static class CubeStatus
    {
        private const string Prefix = NamespaceIdentifier + "Cube.Status.";
        public static readonly Descriptor<float> CurMachSpeed = new(Prefix, "CurMachSpeed");
        public static readonly Descriptor<float> MachSpeed = new(Prefix, "MachSpeed");
        public static readonly Descriptor<float> BatchId = new(Prefix, "Parameter[0].Value");
        public static readonly Descriptor<float> BatchAmount = new(Prefix, "Parameter[1].Value");
        public static readonly Descriptor<float> Humidity = new(Prefix, "Parameter[2].Value");
        public static readonly Descriptor<float> Temperature = new(Prefix, "Parameter[3].Value");
        public static readonly Descriptor<float> Vibration = new(Prefix, "Parameter[3].Value");
        public static readonly Descriptor<int> StateCurrent = new(Prefix, "StateCurrent");
    }

    public static class Inventory
    {
        private const string Prefix = NamespaceIdentifier + "Inventory.";
        public static readonly Descriptor<float> Barley = new(Prefix, "Barley");
        public static readonly Descriptor<float> Hops = new(Prefix, "Hops");
        public static readonly Descriptor<float> Malt = new(Prefix, "Malt");
        public static readonly Descriptor<float> Wheat = new(Prefix, "Wheat");
        public static readonly Descriptor<float> Yeast = new(Prefix, "Yeast");
    }

    public static class Maintenance
    {
        private const string Prefix = NamespaceIdentifier + "Maintenance.";
        public static readonly Descriptor<ushort> MaintenanceCount = new(Prefix, "Counter");
        public static readonly Descriptor<byte> MaintenanceState = new(Prefix, "State");
        public static readonly Descriptor<ushort> MaintenanceTrigger = new(Prefix, "Trigger");
    }

    public static class Product
    {
        private const string Prefix = NamespaceIdentifier + "product.";
        public static readonly Descriptor<ushort> ProductBad = new(Prefix, "bad");
        public static readonly Descriptor<ushort> ProductGood = new(Prefix, "good");
        public static readonly Descriptor<ushort> ProduceTargetAmount = new(Prefix, "produce_amount");
        public static readonly Descriptor<ushort> ProducedAmount = new(Prefix, "produced");
    }

    public static class Misc
    {
        public static readonly Descriptor<bool> FillingInventory = new(NamespaceIdentifier, "FillingInventory");
    }
}

public enum BeerType
{
    Pilsner = 0,
    Wheat = 1,
    Ipa = 2,
    Stout = 3,
    Ale = 4,
    AlcoholFree = 5
}

public enum PackMlCommand
{
    Reset = 1,
    Start = 2,
    Stop = 3,
    Abort = 4,
    Clear = 5
}