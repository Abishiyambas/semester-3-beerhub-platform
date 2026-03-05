@props([
    'status' => null,
    'mode' => 'dashboard', // 'dashboard', 'presets', 'batch-logs'
])

<div class="flex items-center justify-between w-full">
    <div class="flex items-center gap-4">
        @if ($mode === 'batch-logs')
            <a href="{{ route('dashboard.index') }}" class="px-3 py-2 bg-gray-800 text-white rounded">
                ← Back to Dashboard
            </a>
            <a href="{{ route('dashboard.presets') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Preset</a>
        @elseif ($mode === 'presets')
            <a href="{{ route('dashboard.index') }}" class="px-3 py-2 bg-gray-800 text-white rounded">
                ← Back to Dashboard
            </a>
            <a href="{{ route('dashboard.batch-logs') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Batch Logs</a>
        @else
            <a href="{{ route('dashboard.batch-logs') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Batch
                Logs</a>
            <a href="{{ route('dashboard.presets') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Preset</a>
        @endif

        <form method="POST" action="{{ route('dashboard.command.send', ['id' => 1]) }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">
                Reset
            </button>
        </form>

        <form method="POST" action="{{ route('dashboard.command.send', ['id' => 2]) }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">
                Start
            </button>
        </form>

        <form method="POST" action="{{ route('dashboard.command.send', ['id' => 3]) }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">
                Stop
            </button>
        </form>

        <form method="POST" action="{{ route('dashboard.command.send', ['id' => 4]) }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">
                Abort
            </button>
        </form>

        <form method="POST" action="{{ route('dashboard.command.send', ['id' => 5]) }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">
                Clear
            </button>
        </form>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Status:</span>
            <span class="px-3 py-1 rounded bg-white text-sm text-gray-900 border border-gray-300">
                {{ $status ?? 'Disconnected' }}
            </span>
        </div>

        <form method="POST" action="{{ route('dashboard.logout') }}">
            @csrf
            <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded text-sm">
                Logout
            </button>
        </form>
    </div>
</div>
