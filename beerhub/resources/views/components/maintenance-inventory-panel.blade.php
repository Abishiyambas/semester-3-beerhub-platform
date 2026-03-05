@props([
    'machineData' => null,
])

@php
    $maintenanceCounter = (int) ($machineData['maintenanceCounter'] ?? 0);
    $barley = (int) ($machineData['barley'] ?? 0);
    $hops = (int) ($machineData['hops'] ?? 0);
    $malt = (int) ($machineData['malt'] ?? 0);
    $wheat = (int) ($machineData['wheat'] ?? 0);
    $yeast = (int) ($machineData['yeast'] ?? 0);

    $maintenanceMax = 32767; // ushort / 2
    $inventoryMax = 35000;

    $pct = function (int|float $value, int|float $max) {
        if ($max <= 0) {
            return 0;
        }
        return round(($value / $max) * 100);
    };
@endphp

<div class="bg-white rounded-lg shadow p-4 space-y-4 h-full flex flex-col">
    @if (!$machineData)
        <p class="text-sm text-red-500">
            Could not load machine data.
        </p>
    @else
        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-gray-700">Maintenance</h3>

            <div>
                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                    <span>Maintenance counter</span>
                    {{-- <span>{{ $maintenanceCounter }} / {{ $maintenanceMax }}</span> --}}
                </div>

                @php $maintenancePct = $pct($maintenanceCounter, $maintenanceMax); @endphp
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bg-blue-600" style="width: {{ $maintenancePct }}%;"></div>
                </div>

                <div class="text-right text-xs text-gray-500 mt-1">
                    {{ $maintenancePct }} %
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="space-y-3 flex-1 overflow-y-auto">
            <h3 class="text-sm font-semibold text-gray-700">Inventory</h3>

            @foreach ([
        'Barley' => $barley,
        'Hops' => $hops,
        'Malt' => $malt,
        'Wheat' => $wheat,
        'Yeast' => $yeast,
    ] as $label => $value)
                @php $invPct = $pct($value, $inventoryMax); @endphp

                <div class="mb-2">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                        <span>{{ $label }}</span>
                        {{-- <span>{{ $value }} / {{ $inventoryMax }}</span> --}}
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full bg-green-600" style="width: {{ $invPct }}%;"></div>
                    </div>

                    <div class="text-right text-xs text-gray-500 mt-1">
                        {{ $invPct }} %
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
