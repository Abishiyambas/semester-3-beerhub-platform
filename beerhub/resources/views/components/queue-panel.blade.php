@props([
    'queueItems' => [],
])

<div class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Queue</h2>

        @if (empty($queueItems))
            <button type="button"
                class="px-3 py-1.5 bg-red-300 text-white text-xs font-medium rounded cursor-not-allowed" disabled>
                Delete
            </button>
        @endif
    </div>

    @if (empty($queueItems))
        <p class="text-sm text-gray-500">No items in queue.</p>
    @else
        <form method="POST" action="{{ route('dashboard.queue.destroySelected') }}" class="flex-1 flex flex-col">
            @csrf
            @method('DELETE')

            <div class="flex items-center justify-end mb-2">
                <button type="submit"
                    class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700"
                    onclick="return confirm('Delete selected queue items?')">
                    Delete
                </button>
            </div>

            <div class="flex-1 overflow-y-auto border border-gray-200 rounded">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left w-8"></th>
                            <th class="px-3 py-2 text-left">Batch ID</th>
                            <th class="px-3 py-2 text-left">Beer type</th>
                            <th class="px-3 py-2 text-right">Quantity</th>
                            <th class="px-3 py-2 text-right">Speed</th>
                            <th class="px-3 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($queueItems as $item)
                            <tr>
                                <td class="px-3 py-2 align-top">
                                    <input type="checkbox" name="queue_ids[]" value="{{ $item['id'] }}"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $item['batchId'] ?? '—' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $item['beerType'] ?? '—' }}
                                </td>
                                <td class="px-3 py-2 text-right align-top">
                                    {{ $item['quantity'] ?? '—' }}
                                </td>
                                <td class="px-3 py-2 text-right align-top">
                                    {{ $item['speed'] ?? '—' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    @php $status = $item['status'] ?? 'Unknown'; @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs
                                        @class([
                                            'bg-yellow-100 text-yellow-800' => $status === 'Pending',
                                            'bg-blue-100 text-blue-800' => $status === 'Running',
                                            'bg-green-100 text-green-800' => $status === 'Done',
                                            'bg-red-100 text-red-800' => $status === 'Failed',
                                            'bg-gray-100 text-gray-800' => !in_array($status, [
                                                'Pending',
                                                'Running',
                                                'Done',
                                                'Failed',
                                            ]),
                                        ])
                                    ">
                                        {{ $status === 'Running' ? 'Crafting' : $status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    @endif
</div>
