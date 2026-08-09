<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | Blood Bank System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#101B24',
                        inksoft: '#55636D',
                        surface: '#FFFFFF',
                        bg: '#F5F8F9',
                        line: '#DEE5E8',
                        linesoft: '#EDF1F2',
                        blood: '#C41E3A',
                        blooddark: '#9E1730',
                        cold: '#0E7C86',
                        coldsoft: '#E3F1F1',
                        amber: '#B5730C',
                        ambersoft: '#FBF0DF',
                    },
                    fontFamily: {
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
                        sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <style>
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }
        .barcode {
            background-image: repeating-linear-gradient(
                90deg,
                #101B24 0px, #101B24 2px,
                transparent 2px, transparent 4px,
                #101B24 4px, #101B24 5px,
                transparent 5px, transparent 8px,
                #101B24 8px, #101B24 9px,
                transparent 9px, transparent 13px,
                #101B24 13px, #101B24 15px,
                transparent 15px, transparent 17px
            );
            background-size: 34px 100%;
            background-repeat: repeat-x;
        }
        .chain-dot { width: 5px; height: 5px; border-radius: 9999px; background: #0E7C86; flex-shrink: 0; }
        .chain-line { height: 1px; background: repeating-linear-gradient(90deg, #B9D6D8 0, #B9D6D8 3px, transparent 3px, transparent 6px); flex: 1 1 auto; min-width: 10px; }
    </style>
</head>
<body class="bg-bg text-ink font-sans antialiased min-h-screen">

    <!-- Top Bar -->
    <header class="border-b border-line bg-surface">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 32 32" fill="none">
                    <path d="M16 3C13 8 8 15.5 8 20.5C8 25.2 11.6 29 16 29C20.4 29 24 25.2 24 20.5C24 15.5 19 8 16 3Z" stroke="#C41E3A" stroke-width="1.6" fill="none"/>
                    <rect x="9.4" y="21.5" width="13.2" height="4" rx="0.5" fill="#101B24"/>
                    <rect x="9.4" y="21.5" width="1" height="4" fill="#F5F8F9"/>
                    <rect x="11.4" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="13.1" y="21.5" width="1.4" height="4" fill="#F5F8F9"/>
                    <rect x="15.3" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="16.6" y="21.5" width="1" height="4" fill="#F5F8F9"/>
                    <rect x="18.4" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="20" y="21.5" width="1.4" height="4" fill="#F5F8F9"/>
                </svg>
                <div class="leading-tight">
                    <p class="font-mono text-[11px] tracking-[0.2em] text-cold uppercase font-semibold">Blood Bank System</p>
                    <h1 class="text-xl font-semibold tracking-tight text-ink -mt-0.5">Inventory</h1>
                </div>
            </div>
            <a href="{{ route('inventory.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-blood px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blood transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Register sample
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 sm:px-8 py-8">

        <!-- Success Alert -->
        @if(session('success'))
            <div class="rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 mb-6 flex items-start gap-3">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Ledger Readout Strip -->
        <div class="rounded-lg bg-ink px-6 py-5 mb-6 grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div>
                <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Units stored</p>
                <p class="font-mono text-3xl font-semibold text-white mt-1">{{ str_pad($inventories->count(), 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Refrigerators active</p>
                <p class="font-mono text-3xl font-semibold text-white mt-1">{{ str_pad($inventories->pluck('refrigerator')->unique()->count(), 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Storage zones</p>
                <p class="font-mono text-3xl font-semibold text-white mt-1">{{ str_pad($inventories->pluck('storage_location')->unique()->count(), 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-inksoft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M18 10.5a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/></svg>
                <input id="ledgerSearch" type="text" placeholder="Search by sample ID, refrigerator, shelf, rack, or location&hellip;"
                       class="w-full rounded-md border border-line bg-surface pl-9 pr-3 py-2.5 text-sm text-ink placeholder:text-inksoft/70 focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold">
            </div>
            <p id="resultCount" class="font-mono text-xs text-inksoft self-center whitespace-nowrap">{{ $inventories->count() }} record{{ $inventories->count() === 1 ? '' : 's' }}</p>
        </div>

        <!-- Ledger Table -->
        <div class="rounded-lg border border-line bg-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-line bg-linesoft">
                            <th scope="col" class="py-3 pl-5 pr-3 text-left font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold">Sample</th>
                            <th scope="col" class="px-3 py-3 text-left font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold">Cold-chain path</th>
                            <th scope="col" class="px-3 py-3 text-left font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold">Storage location</th>
                        </tr>
                    </thead>
                    <tbody id="ledgerBody" class="divide-y divide-linesoft">
                        @forelse($inventories as $item)
                        <tr class="hover:bg-bg transition-colors"
                            data-search="{{ strtolower($item->blood_sample_id.' '.$item->refrigerator.' '.$item->shelf.' '.$item->rack.' '.$item->storage_location) }}">
                            <td class="py-4 pl-5 pr-3 align-middle">
                                <div class="inline-flex items-center gap-2">
                                    <span class="barcode w-6 h-7 rounded-sm flex-shrink-0" aria-hidden="true"></span>
                                    <span class="font-mono text-sm font-semibold text-ink">#{{ str_pad($item->blood_sample_id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-middle">
                                <div class="flex items-center gap-1.5 max-w-md">
                                    <div class="text-center">
                                        <p class="font-mono text-[9px] tracking-[0.12em] text-inksoft/80 uppercase">Fridge</p>
                                        <p class="font-mono text-xs font-semibold text-ink bg-coldsoft rounded px-2 py-1 mt-0.5">{{ $item->refrigerator }}</p>
                                    </div>
                                    <span class="chain-dot"></span><span class="chain-line"></span><span class="chain-dot"></span>
                                    <div class="text-center">
                                        <p class="font-mono text-[9px] tracking-[0.12em] text-inksoft/80 uppercase">Shelf</p>
                                        <p class="font-mono text-xs font-semibold text-ink bg-coldsoft rounded px-2 py-1 mt-0.5">{{ $item->shelf }}</p>
                                    </div>
                                    <span class="chain-dot"></span><span class="chain-line"></span><span class="chain-dot"></span>
                                    <div class="text-center">
                                        <p class="font-mono text-[9px] tracking-[0.12em] text-inksoft/80 uppercase">Rack</p>
                                        <p class="font-mono text-xs font-semibold text-ink bg-coldsoft rounded px-2 py-1 mt-0.5">{{ $item->rack }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-middle">
                                <span class="inline-flex items-center rounded-full bg-ambersoft px-3 py-1 text-xs font-semibold text-amber">
                                    {{ $item->storage_location }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center">
                                <svg class="mx-auto h-10 w-10 text-line" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                <h3 class="mt-3 text-sm font-semibold text-ink">The ledger is empty</h3>
                                <p class="mt-1 text-sm text-inksoft">Register your first sample to start tracking cold-chain storage.</p>
                                <a href="{{ route('inventory.create') }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-blood hover:text-blooddark">
                                    Register sample &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- No search results state (hidden by default, shown by JS) -->
        <div id="noResults" class="hidden mt-4 rounded-lg border border-dashed border-line py-10 text-center">
            <p class="text-sm text-inksoft">No samples match that search.</p>
        </div>
    </main>

    <script>
        const searchInput = document.getElementById('ledgerSearch');
        const rows = Array.from(document.querySelectorAll('#ledgerBody tr[data-search]'));
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');

        if (searchInput && rows.length) {
            searchInput.addEventListener('input', () => {
                const q = searchInput.value.trim().toLowerCase();
                let visible = 0;
                rows.forEach(row => {
                    const match = row.dataset.search.includes(q);
                    row.classList.toggle('hidden', !match);
                    if (match) visible++;
                });
                resultCount.textContent = `${visible} record${visible === 1 ? '' : 's'}`;
                noResults.classList.toggle('hidden', visible !== 0);
            });
        }
    </script>
</body>
</html>