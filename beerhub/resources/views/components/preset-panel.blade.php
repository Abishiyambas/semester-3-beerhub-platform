@php
    $isEditing = isset($selectedPreset);
@endphp

<div class="bg-white rounded-lg shadow p-4 space-y-4">
    <h2 class="text-lg font-semibold">
        Preset
        @if ($isEditing)
            <span class="text-sm text-gray-500">– Editing "{{ $selectedPreset->name }}"</span>
        @endif
    </h2>

    <form method="POST"
        action="{{ $isEditing ? route('dashboard.presets.update', $selectedPreset) : route('dashboard.presets.store') }}"
        class="space-y-4" id="preset-save-form">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        @if ($selectedPreset)
            <input type="hidden" name="preset_id" value="{{ $selectedPreset->id }}">
        @endif

        <div>
            <label for="preset-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="preset-name" type="text" name="name" value="{{ old('name', $selectedPreset->name ?? '') }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        <div>
            <label for="preset-beer-type-id" class="block text-sm font-medium text-gray-700">Beer type</label>
            <select id="preset-beer-type-id" name="beer_type_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                required>
                <option value="">Select beer type</option>
                @foreach ($beerTypes as $beerType)
                    <option value="{{ $beerType->id }}" @selected((old('beer_type_id', $selectedPreset->beer_type_id ?? null) ?? '') == $beerType->id)>
                        {{ $beerType->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="preset-quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input id="preset-quantity" type="number" name="quantity" min="1"
                value="{{ old('quantity', $selectedPreset->quantity ?? '') }}" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        <div>
            <label for="preset-speed" class="block text-sm font-medium text-gray-700">Speed</label>
            <input id="preset-speed" type="number" name="speed" min="1" max="100"
                value="{{ old('speed', $selectedPreset->speed ?? '') }}" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
    </form>

    <div class="flex items-center justify-between pt-2">
        <form method="POST" action="{{ route('dashboard.presets.start') }}" class="inline-flex" id="preset-start-form">
            @csrf

            <input type="hidden" name="name" id="start-name">
            <input type="hidden" name="beer_type_id" id="start-beer-type-id">
            <input type="hidden" name="quantity" id="start-quantity">
            <input type="hidden" name="speed" id="start-speed">

            @if ($selectedPreset)
                <input type="hidden" name="preset_id" value="{{ $selectedPreset->id }}">
            @endif

            <button type="submit"
                class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700"
                onclick="
                    event.preventDefault();
                    document.getElementById('start-name').value =
                        document.getElementById('preset-name').value;
                    document.getElementById('start-beer-type-id').value =
                        document.getElementById('preset-beer-type-id').value;
                    document.getElementById('start-quantity').value =
                        document.getElementById('preset-quantity').value;
                    document.getElementById('start-speed').value =
                        document.getElementById('preset-speed').value;

                    document.getElementById('preset-start-form').submit();
                ">
                Start
            </button>
        </form>

        <div class="flex items-center gap-2">
            @if ($isEditing)
                <a href="{{ route('dashboard.index') }}"
                    class="px-3 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded hover:bg-gray-400">
                    Cancel
                </a>
            @endif

            <button type="submit" form="preset-save-form"
                class="px-3 py-2 text-sm font-medium rounded text-white
                       {{ $isEditing ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                {{ $isEditing ? 'Update' : 'Save' }}
            </button>
        </div>
    </div>
</div>
