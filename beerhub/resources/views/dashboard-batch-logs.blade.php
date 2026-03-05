<x-layout title="Batch Logs">
    <x-slot:header>
        <x-header :status="$headerStatus ?? 'Disconnected'" mode="batch-logs" />
    </x-slot:header>

    <div class="p-4">
        <h1 class="text-xl font-semibold mb-4">Batch Logs</h1>

        @if ($batches->isEmpty())
            <p class="text-sm text-gray-500">No batches have been logged yet.</p>
        @else
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Batch ID</th>
                            <th class="px-4 py-2 text-left">Beer type</th>
                            <th class="px-4 py-2 text-right">Quantity</th>
                            <th class="px-4 py-2 text-right">Speed</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Created at</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($batches as $batch)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ $batch->id }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $batch->batch_id ?? ($batch->batchId ?? '–') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $batch->beerType->name ?? ($batch->beer_type_id ?? '–') }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    {{ $batch->quantity ?? '–' }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    {{ $batch->speed ?? '–' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $batch->status ?? 'Finished' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ optional($batch->created_at)->format('Y-m-d H:i') ?? '–' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (method_exists($batches, 'links'))
                <div class="mt-4">
                    {{ $batches->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layout>
