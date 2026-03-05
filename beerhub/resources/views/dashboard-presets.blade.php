<x-layout title="Presets">
    <x-slot:header>
        <x-header :status="$headerStatus ?? 'Disconnected'" mode="presets" />
    </x-slot:header>

    <x-panel-grid>
        <x-panel>
            <h2 class="text-lg font-semibold mb-4">Select a preset</h2>

            @if ($presets->isEmpty())
                <p class="text-sm text-gray-500">No presets available.</p>
            @else
                <ul class="divide-y divide-gray-200 border border-gray-200 rounded">
                    @foreach ($presets as $preset)
                        <li class="px-3 py-2 text-sm hover:bg-gray-100">
                            <a href="{{ route('dashboard.index', ['preset' => $preset->id]) }}" class="block">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $preset->name }}</span>
                                    <span class="text-xs text-gray-500">
                                        {{ $preset->quantity }} @ {{ $preset->speed }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400">
                                    Beer type: {{ $preset->beerType->name ?? 'Unknown' }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-panel>
    </x-panel-grid>
</x-layout>
