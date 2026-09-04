<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Inventory Management</h2>
                <p class="mt-1 text-sm text-gray-600">Track accepted samples and their cold-storage locations.</p>
            </div>
            <a href="{{ route('inventory.create') }}"
               class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                + Register Sample
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Units Stored</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $inventories->count() }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Active Refrigerators</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $inventories->pluck('refrigerator')->unique()->count() }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Storage Locations</p>
                    <p class="mt-2 text-3xl font-bold text-green-700">{{ $inventories->pluck('storage_location')->unique()->count() }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Stored Sample Locations</h3>
                            <p class="mt-1 text-sm text-gray-500">Search the inventory ledger by sample or storage information.</p>
                        </div>
                        <div class="w-full sm:max-w-md">
                            <label for="ledgerSearch" class="sr-only">Search inventory</label>
                            <input id="ledgerSearch" type="search" placeholder="Search sample, refrigerator, shelf or location"
                                   class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p id="resultCount" class="mt-2 text-right text-xs text-gray-500">{{ $inventories->count() }} {{ Str::plural('record', $inventories->count()) }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                            <tr>
                                <th class="px-5 py-4">Sample</th>
                                <th class="px-5 py-4">Refrigerator</th>
                                <th class="px-5 py-4">Shelf</th>
                                <th class="px-5 py-4">Rack</th>
                                <th class="px-5 py-4">Storage Location</th>
                            </tr>
                        </thead>
                        <tbody id="ledgerBody" class="divide-y divide-gray-100 bg-white">
                            @forelse($inventories as $item)
                                <tr class="inventory-row hover:bg-gray-50"
                                    data-search="{{ strtolower($item->blood_sample_id.' '.$item->refrigerator.' '.$item->shelf.' '.$item->rack.' '.$item->storage_location) }}">
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-semibold text-indigo-700">#{{ str_pad($item->blood_sample_id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </td>
                                    <td class="px-5 py-4"><span class="rounded-lg bg-blue-50 px-2.5 py-1.5 font-semibold text-blue-800">{{ $item->refrigerator }}</span></td>
                                    <td class="px-5 py-4 text-gray-700">{{ $item->shelf }}</td>
                                    <td class="px-5 py-4 text-gray-700">{{ $item->rack }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full bg-amber-50 px-3 py-1.5 font-medium text-amber-800">{{ $item->storage_location }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-14 text-center"><p class="font-semibold text-gray-900">No samples stored yet</p><p class="mt-1 text-sm text-gray-500">Register an accepted sample to begin tracking its storage location.</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="noResults" class="hidden border-t border-gray-200 px-6 py-10 text-center text-sm text-gray-500">No inventory records match your search.</div>
            </section>

            <section class="space-y-5">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Accepted Sample Status</h3>
                    <p class="mt-1 text-sm text-gray-500">Review accepted samples before and after collector pickup.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-xs font-semibold uppercase text-gray-500">Total Accepted</p><p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalSamples }}</p></div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm"><p class="text-xs font-semibold uppercase text-amber-700">Available</p><p class="mt-2 text-2xl font-bold text-amber-900">{{ $availableSamples }}</p></div>
                    <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm"><p class="text-xs font-semibold uppercase text-green-700">Collected</p><p class="mt-2 text-2xl font-bold text-green-900">{{ $collectedSamples }}</p></div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm font-semibold text-gray-800">Filter accepted samples</p>
                        <div class="inline-flex self-start overflow-hidden rounded-xl border border-gray-300">
                            <button type="button" data-filter="available" class="sample-filter-btn bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Available</button>
                            <button type="button" data-filter="collected" class="sample-filter-btn border-l border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Collected</button>
                            <button type="button" data-filter="all" class="sample-filter-btn border-l border-gray-300 bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">All</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr><th class="px-5 py-4">Sample</th><th class="px-5 py-4">Blood Type</th><th class="px-5 py-4">Collector</th><th class="px-5 py-4">Status</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($bloodSamples as $sample)
                                    <tr class="accepted-sample-row hover:bg-gray-50" data-status="{{ $sample->collected_by ? 'collected' : 'available' }}">
                                        <td class="px-5 py-4 font-semibold text-indigo-700">{{ $sample->sample_code ?? 'Not assigned' }}</td>
                                        <td class="px-5 py-4 text-gray-700">{{ $sample->blood_type ?? 'Not specified' }}</td>
                                        <td class="px-5 py-4">
                                            @if($sample->collector)
                                                <p class="font-medium text-gray-900">{{ $sample->collector->name }}</p><p class="mt-1 text-xs text-gray-500">ID {{ $sample->collector->id }}</p>
                                            @else
                                                <span class="text-gray-500">Not assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            @if($sample->collected_by)
                                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Collected</span>
                                            @else
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">Available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No accepted samples found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const search = document.getElementById('ledgerSearch');
            const inventoryRows = Array.from(document.querySelectorAll('.inventory-row'));
            const resultCount = document.getElementById('resultCount');
            const noResults = document.getElementById('noResults');

            search?.addEventListener('input', () => {
                const query = search.value.trim().toLowerCase();
                let visible = 0;
                inventoryRows.forEach(row => {
                    const matches = row.dataset.search.includes(query);
                    row.classList.toggle('hidden', !matches);
                    if (matches) visible++;
                });
                if (resultCount) resultCount.textContent = `${visible} record${visible === 1 ? '' : 's'}`;
                noResults?.classList.toggle('hidden', visible !== 0 || inventoryRows.length === 0);
            });

            const filterButtons = document.querySelectorAll('.sample-filter-btn');
            const acceptedRows = document.querySelectorAll('.accepted-sample-row');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const selected = button.dataset.filter;
                    acceptedRows.forEach(row => row.classList.toggle('hidden', selected !== 'all' && row.dataset.status !== selected));
                    filterButtons.forEach(item => {
                        item.classList.remove('bg-indigo-600', 'text-white');
                        item.classList.add('bg-white', 'text-gray-600');
                    });
                    button.classList.remove('bg-white', 'text-gray-600');
                    button.classList.add('bg-indigo-600', 'text-white');
                });
            });
        });
    </script>
</x-app-layout>
