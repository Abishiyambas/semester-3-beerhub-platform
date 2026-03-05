<x-layout title="Beer Hub">
    <x-slot:header>
        <x-header :status="$headerStatus ?? 'Disconnected'" mode="dashboard" />
    </x-slot:header>

    <x-panel-grid>
        <x-panel>
            <x-preset-panel :beerTypes="$beerTypes" :selectedPreset="$selectedPreset" />
        </x-panel>

        <x-panel>
            <x-queue-panel :queueItems="$queueItems" />
        </x-panel>

        <x-panel>
            <x-maintenance-inventory-panel :machineData="$machineData" />
        </x-panel>
    </x-panel-grid>
</x-layout>
