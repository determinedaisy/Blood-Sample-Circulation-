<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sample | Blood Bank System</title>
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
        .barcode-lg {
            background-image: repeating-linear-gradient(
                90deg,
                #101B24 0px, #101B24 3px,
                transparent 3px, transparent 6px,
                #101B24 6px, #101B24 8px,
                transparent 8px, transparent 11px,
                #101B24 11px, #101B24 12px,
                transparent 12px, transparent 17px,
                #101B24 17px, #101B24 20px,
                transparent 20px, transparent 23px
            );
            background-size: 46px 100%;
            background-repeat: repeat-x;
        }
        .label-stub::before {
            content: '';
            position: absolute;
            top: 0; left: -1px; right: -1px;
            height: 10px;
            background: repeating-linear-gradient(90deg, transparent, transparent 5px, #DEE5E8 5px, #DEE5E8 6px);
        }
    </style>
</head>
<body class="bg-bg text-ink font-sans antialiased min-h-screen">

    <!-- Top Bar -->
    <header class="border-b border-line bg-surface">
        <div class="mx-auto max-w-5xl px-5 sm:px-8 py-4 flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" class="text-inksoft hover:text-ink transition-colors" aria-label="Back to inventory">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div class="leading-tight">
                <p class="font-mono text-[11px] tracking-[0.2em] text-cold uppercase font-semibold">Blood Bank System</p>
                <h1 class="text-xl font-semibold tracking-tight text-ink -mt-0.5">Register sample</h1>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-5 sm:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

            <!-- Form -->
            <div class="lg:col-span-3 rounded-lg border border-line bg-surface p-6 sm:p-8">
                <p class="text-sm text-inksoft mb-6">Log a new unit into cold storage. Every field feeds the specimen label shown on the right.</p>

                <form action="{{ route('inventory.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Section: Identity -->
                    <fieldset>
                        <legend class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-3">Sample identity</legend>
                        <label for="blood_sample_id" class="block text-sm font-semibold text-ink mb-1">Blood sample ID</label>
                        <div>
    

    <select
        id="blood_sample_id"
        name="blood_sample_id"
        required
        class="w-full rounded-md border-gray-300"
    >
        <option value="">
            Select an accepted blood sample
        </option>

        @foreach($bloodSamples as $sample)
            <option
                value="{{ $sample->id }}"
                {{ old('blood_sample_id') == $sample->id ? 'selected' : '' }}
            >
                {{ $sample->sample_code }}
                —
                {{ $sample->blood_type ?? 'Unknown blood type' }}
            </option>
        @endforeach
    </select>

    @error('blood_sample_id')
        <p class="text-sm text-red-600 mt-1">
            {{ $message }}
        </p>
    @enderror
</div>
                     
                    </fieldset>

                    <div class="border-t border-linesoft"></div>

                    <!-- Section: Storage -->
                    <fieldset>
                        <legend class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-3">Storage assignment</legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="refrigerator" class="block text-sm font-semibold text-ink mb-1">Refrigerator unit</label>
                                <input type="text" id="refrigerator" name="refrigerator" required placeholder="R-02"
                                       class="block w-full rounded-md border border-line px-3 py-2.5 text-ink shadow-sm focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold sm:text-sm">
                            </div>
                            <div>
                                <label for="shelf" class="block text-sm font-semibold text-ink mb-1">Shelf number</label>
                                <input type="text" id="shelf" name="shelf" required placeholder="B"
                                       class="block w-full rounded-md border border-line px-3 py-2.5 text-ink shadow-sm focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold sm:text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="rack" class="block text-sm font-semibold text-ink mb-1">Rack identifier</label>
                                <input type="text" id="rack" name="rack" required placeholder="3"
                                       class="block w-full rounded-md border border-line px-3 py-2.5 text-ink shadow-sm focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold sm:text-sm">
                            </div>
                            <div>
                                <label for="storage_location" class="block text-sm font-semibold text-ink mb-1">Storage location</label>
                                <input type="text" id="storage_location" name="storage_location" required placeholder="Central Lab"
                                       class="block w-full rounded-md border border-line px-3 py-2.5 text-ink shadow-sm focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold sm:text-sm">
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="inline-flex justify-center items-center rounded-md bg-blood py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blood transition-colors">
                            Save to inventory
                        </button>
                        <a href="{{ route('inventory.index') }}" class="text-sm font-medium text-inksoft hover:text-ink">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Live Specimen Label Preview -->
            <div class="lg:col-span-2 lg:sticky lg:top-8">
                <p class="font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold mb-3">Label preview</p>
                <div class="relative label-stub bg-white border border-line rounded-md shadow-sm pt-5 pb-5 px-5 overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-mono text-[10px] tracking-[0.18em] text-inksoft uppercase">Specimen</p>
                            <p id="previewId" class="font-mono text-2xl font-bold text-ink leading-tight">#0000</p>
                        </div>
                        <svg class="w-6 h-6 text-blood flex-shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 2.625c-1.42 2.14-7.5 10.975-7.5 14.625a7.5 7.5 0 0015 0c0-3.65-6.08-12.485-7.5-14.625z" stroke="currentColor" stroke-width="1.5"/></svg>
                    </div>

                    <div class="barcode-lg h-9 w-full rounded-sm mt-4" aria-hidden="true"></div>

                    <div class="grid grid-cols-3 gap-2 mt-4">
                        <div class="rounded bg-coldsoft px-2 py-2 text-center">
                            <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Fridge</p>
                            <p id="previewFridge" class="font-mono text-sm font-semibold text-ink mt-0.5">&mdash;</p>
                        </div>
                        <div class="rounded bg-coldsoft px-2 py-2 text-center">
                            <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Shelf</p>
                            <p id="previewShelf" class="font-mono text-sm font-semibold text-ink mt-0.5">&mdash;</p>
                        </div>
                        <div class="rounded bg-coldsoft px-2 py-2 text-center">
                            <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Rack</p>
                            <p id="previewRack" class="font-mono text-sm font-semibold text-ink mt-0.5">&mdash;</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-dashed border-line">
                        <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Location</p>
                        <p id="previewLocation" class="text-sm font-semibold text-amber mt-0.5">&mdash;</p>
                    </div>
                </div>
                <p class="text-xs text-inksoft mt-3">This is how the sample will read on the printed cold-storage label once saved.</p>
            </div>
        </div>
    </main>

    <script>
        const bind = (inputId, previewId, format) => {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (!input || !preview) return;
            input.addEventListener('input', () => {
                const v = input.value.trim();
                preview.textContent = v ? (format ? format(v) : v) : (previewId === 'previewId' ? '#0000' : '\u2014');
            });
        };
        bind('blood_sample_id', 'previewId', v => '#' + v.padStart(4, '0').slice(-4));
        bind('refrigerator', 'previewFridge');
        bind('shelf', 'previewShelf');
        bind('rack', 'previewRack');
        bind('storage_location', 'previewLocation');
    </script>
</body>
</html>