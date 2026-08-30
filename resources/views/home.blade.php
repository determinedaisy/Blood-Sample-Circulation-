<!--
    Dynamic stats on this page read from an optional $stats array, all with safe
    fallbacks so the page never errors if it isn't wired up yet. When ready, pass
    something like this from your controller:

    return view('home', [
        'stats' => [
            'total_samples'      => BloodSample::count(),
            'acceptance_rate'    => round(BloodSample::where('status','accepted')->count()
                                     / max(BloodSample::whereIn('status', ['accepted','rejected'])->count(), 1) * 100),
            'registered_members' => User::count(),
            'partner_labs'       => Laboratory::count(),
        ],
    ]);
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Sample Circulation System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#101B24', inksoft: '#55636D', surface: '#FFFFFF', bg: '#F5F8F9',
                        line: '#DEE5E8', linesoft: '#EDF1F2', blood: '#C41E3A', blooddark: '#9E1730',
                        cold: '#0E7C86', coldsoft: '#E3F1F1', amber: '#B5730C', ambersoft: '#FBF0DF',
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
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
        .barcode {
            background-image: repeating-linear-gradient(90deg,
                #101B24 0px, #101B24 2px, transparent 2px, transparent 4px,
                #101B24 4px, #101B24 5px, transparent 5px, transparent 8px,
                #101B24 8px, #101B24 9px, transparent 9px, transparent 13px,
                #101B24 13px, #101B24 15px, transparent 15px, transparent 17px);
            background-size: 34px 100%; background-repeat: repeat-x;
        }
        .chain-dot { width: 5px; height: 5px; border-radius: 9999px; background: #0E7C86; flex-shrink: 0; }
        .chain-line { height: 1px; background: repeating-linear-gradient(90deg, #B9D6D8 0, #B9D6D8 3px, transparent 3px, transparent 6px); flex: 1 1 auto; min-width: 10px; }
    </style>
</head>
<body class="bg-bg text-ink font-sans antialiased min-h-screen">

    <!-- Site Header / Nav -->
    <header class="border-b border-line bg-surface sticky top-0 z-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 py-4 flex items-center justify-between gap-4">
            <a href="/" class="flex items-center gap-3">
                <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 32 32" fill="none">
                    <path d="M16 3C13 8 8 15.5 8 20.5C8 25.2 11.6 29 16 29C20.4 29 24 25.2 24 20.5C24 15.5 19 8 16 3Z" stroke="#C41E3A" stroke-width="1.6" fill="none"/>
                    <rect x="9.4" y="21.5" width="13.2" height="4" rx="0.5" fill="#101B24"/>
                    <rect x="9.4" y="21.5" width="1" height="4" fill="#F5F8F9"/><rect x="11.4" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="13.1" y="21.5" width="1.4" height="4" fill="#F5F8F9"/><rect x="15.3" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="16.6" y="21.5" width="1" height="4" fill="#F5F8F9"/><rect x="18.4" y="21.5" width="0.6" height="4" fill="#F5F8F9"/>
                    <rect x="20" y="21.5" width="1.4" height="4" fill="#F5F8F9"/>
                </svg>
                <div class="leading-tight">
                    <p class="font-mono text-[11px] tracking-[0.2em] text-cold uppercase font-semibold">Blood Bank System</p>
                    <h1 class="text-lg font-semibold tracking-tight text-ink -mt-0.5">Sample Circulation</h1>
                </div>
            </a>

            <nav class="flex items-center gap-4">
                @guest
                    <a href="{{ url('/login') }}" class="text-sm font-medium text-inksoft hover:text-ink transition-colors">Log in</a>
                    <a href="{{ url('/register') }}" class="inline-flex items-center rounded-md bg-blood px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blood transition-colors">
                        Register
                    </a>
                @endguest

                @auth
                    <a href="/" class="text-sm font-semibold text-ink border-b-2 border-blood pb-1">Dashboard</a>
                    <div class="relative">
                        <button id="userMenuBtn" type="button" aria-haspopup="true" aria-expanded="false"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 hover:bg-bg focus:outline-none focus:ring-2 focus:ring-cold transition-colors">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-coldsoft text-cold font-mono text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="text-sm font-semibold text-ink hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-inksoft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 rounded-md border border-line bg-surface shadow-lg py-1 z-30">
                            <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-ink hover:bg-bg">Profile settings</a>
                            <div class="border-t border-linesoft my-1"></div>
                            <form method="POST" action="{{ url('/logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-semibold text-blood hover:bg-bg">Log out</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ========================================== --}}
    {{-- GUEST VIEW --}}
    {{-- ========================================== --}}
    @guest
        <main>
            <!-- Hero: message + live-look preview, no repeated buttons -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pt-14 pb-16">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-center">
                    <div class="lg:col-span-3">
                        <p class="font-mono text-[11px] tracking-[0.2em] text-cold uppercase font-semibold mb-3">Cold-chain custody, end to end</p>
                        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-ink leading-[1.1]">
                            Every blood sample,<br class="hidden sm:block"> tracked from vein to vault.
                        </h1>
                        <p class="mt-5 text-inksoft leading-relaxed max-w-lg text-[15px]">
                            One system for donors, patients, doctors, and lab staff to move samples through collection, review, transport, and storage &mdash; with a full audit trail at every step.
                        </p>
                        <dl class="flex flex-wrap gap-x-8 gap-y-3 mt-8">
                            <div>
                                <dt class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase">Samples tracked</dt>
                                <dd class="font-mono text-2xl font-semibold text-ink mt-0.5">{{ $stats['total_samples'] ?? '30+' }}</dd>
                            </div>
                            <div>
                                <dt class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase">Acceptance rate</dt>
                                <dd class="font-mono text-2xl font-semibold text-ink mt-0.5">{{ $stats['acceptance_rate'] ?? 76 }}%</dd>
                            </div>
                            <div>
                                <dt class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase">Partner labs</dt>
                                <dd class="font-mono text-2xl font-semibold text-ink mt-0.5">{{ $stats['partner_labs'] ?? 5 }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Live specimen preview -->
                    <div class="lg:col-span-2">
                        <div class="rounded-lg border border-line bg-surface p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-mono text-[10px] tracking-[0.18em] text-inksoft uppercase">Specimen</p>
                                    <p class="font-mono text-xl font-bold text-ink">SMP-241</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Accepted
                                </span>
                            </div>
                            <div class="barcode h-7 w-full rounded-sm mt-4" aria-hidden="true"></div>
                            <div class="grid grid-cols-3 gap-2 mt-4">
                                <div class="rounded bg-coldsoft px-2 py-2 text-center">
                                    <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Type</p>
                                    <p class="font-mono text-sm font-semibold text-ink mt-0.5">O+</p>
                                </div>
                                <div class="rounded bg-coldsoft px-2 py-2 text-center">
                                    <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Fridge</p>
                                    <p class="font-mono text-sm font-semibold text-ink mt-0.5">R-02</p>
                                </div>
                                <div class="rounded bg-coldsoft px-2 py-2 text-center">
                                    <p class="font-mono text-[9px] tracking-[0.1em] text-inksoft uppercase">Rack</p>
                                    <p class="font-mono text-sm font-semibold text-ink mt-0.5">3</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dashed border-line flex items-center gap-1.5">
                                <span class="chain-dot"></span><span class="chain-line"></span>
                                <span class="chain-dot"></span><span class="chain-line"></span>
                                <span class="chain-dot"></span>
                            </div>
                            <p class="text-[11px] text-inksoft mt-2">Collected &rarr; Reviewed &rarr; Stored</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats readout -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <div class="rounded-lg bg-ink px-6 py-6 grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <div>
                        <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Samples tracked</p>
                        <p class="font-mono text-3xl font-semibold text-white mt-1">{{ $stats['total_samples'] ?? 30 }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Registered members</p>
                        <p class="font-mono text-3xl font-semibold text-white mt-1">{{ $stats['registered_members'] ?? 47 }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Collection centers</p>
                        <p class="font-mono text-3xl font-semibold text-white mt-1">{{ $stats['collection_centers'] ?? 5 }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Partner labs</p>
                        <p class="font-mono text-3xl font-semibold text-white mt-1">{{ $stats['partner_labs'] ?? 5 }}</p>
                    </div>
                </div>
            </section>

            <!-- Process: how a sample moves -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-1">The lifecycle</p>
                <h2 class="text-2xl font-bold tracking-tight text-ink mb-8">How a sample moves through the system</h2>

                <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-x-6 gap-y-8">
                    @php
                        $lifecycle = [
                            ['n' => '01', 'title' => 'Request', 'text' => 'A patient or receptionist creates a sample request.'],
                            ['n' => '02', 'title' => 'Collect', 'text' => 'A collector confirms collection and the sample gets a unique barcode.'],
                            ['n' => '03', 'title' => 'Review', 'text' => 'Lab staff accept or reject the sample against quality criteria.'],
                            ['n' => '04', 'title' => 'Transport', 'text' => 'Accepted samples move between centers and laboratories, logged in transit.'],
                            ['n' => '05', 'title' => 'Store', 'text' => 'The sample is shelved in cold storage with a tracked fridge, shelf, and rack.'],
                        ];
                    @endphp
                    @foreach($lifecycle as $step)
                        <li class="border-t-2 border-coldsoft pt-3">
                            <span class="font-mono text-xs font-bold text-cold">{{ $step['n'] }}</span>
                            <h3 class="font-semibold text-ink mt-1.5">{{ $step['title'] }}</h3>
                            <p class="text-sm text-inksoft mt-1 leading-relaxed">{{ $step['text'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </section>

            <!-- Feature grid -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-1">What's inside</p>
                <h2 class="text-2xl font-bold tracking-tight text-ink mb-8">Every portal, one system</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path stroke-linecap="round" d="M5 10.5h14M8 6.75h.01M8 14.5h.01"/></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Cold storage inventory</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Refrigerator, shelf, and rack tracking for every stored sample.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25M16.5 18.75h-2.25m0-12h-4.5A2.25 2.25 0 007.5 9v.75m6.75-3v3m0 0h5.25" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Transportation logistics</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Every hand-off between centers and labs is logged and traceable.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5.25L4.5 17a2 2 0 001.8 2.9h11.4a2 2 0 001.8-2.9L15 8.25V3M9 3h6" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Sample review &amp; QC</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Lab staff accept or reject on quality criteria, with instant response.</p>
                    </div>
                    <div class="rounded-lg border border-blood/30 bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-blood/10 text-blood mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78A1.5 1.5 0 0022.18 18L13.71 3.86a1.5 1.5 0 00-2.42 0z" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Emergency SOS</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">One tap routes a patient to the nearest bank or compatible donor.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75h12M6 3.75a1.5 1.5 0 00-1.5 1.5v13.5A1.5 1.5 0 006 20.25h12a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5M9 9.75h6M9 13.5h6" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Equipment &amp; pharmacy store</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Browse and purchase lab equipment, medicine, and home-kit supplies.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><rect x="4" y="5.5" width="16" height="15" rx="1.5"/><path stroke-linecap="round" d="M8 3.5v4M16 3.5v4M4 10h16"/></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Appointment booking</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Schedule a collection at your preferred center, date, and time.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Donor badge system</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Milestone badges unlock store discounts and faster processing.</p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-coldsoft text-cold mb-3">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l3-9 3 9m-6 0h6m6 0l3-9 3 9m-6 0h6M9 21h6M12 3v18" /></svg>
                        </span>
                        <h3 class="font-semibold text-ink text-sm">Dashboards &amp; reports</h3>
                        <p class="text-xs text-inksoft mt-1 leading-relaxed">Operational statistics and circulation summaries for admins.</p>
                    </div>
                </div>
            </section>

            <!-- Roles -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-1">Access, by role</p>
                <h2 class="text-2xl font-bold tracking-tight text-ink mb-8">Built for every seat in the system</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-lg bg-surface border border-line p-5">
                        <p class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase mb-2">Patient / Donor</p>
                        <p class="text-sm text-inksoft leading-relaxed">Book appointments, request samples, track status, and manage donations &mdash; toggle between patient and donor at any time.</p>
                    </div>
                    <div class="rounded-lg bg-surface border border-line p-5">
                        <p class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase mb-2">Doctor</p>
                        <p class="text-sm text-inksoft leading-relaxed">Approve donation and collection requests, review flagged samples, and prioritize urgent cases.</p>
                    </div>
                    <div class="rounded-lg bg-surface border border-line p-5">
                        <p class="font-mono text-[10px] tracking-[0.14em] text-inksoft uppercase mb-2">Lab staff</p>
                        <p class="text-sm text-inksoft leading-relaxed">Accept or reject incoming samples, manage inventory, and monitor storage duration.</p>
                    </div>
                    <div class="rounded-lg bg-ink p-5">
                        <p class="font-mono text-[10px] tracking-[0.14em] text-cold uppercase mb-2">Admin</p>
                        <p class="text-sm text-white/70 leading-relaxed">Full oversight: dashboards, reports, user management, and blacklist controls.</p>
                    </div>
                </div>
            </section>

            <!-- Track sample (guest utility) -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <div class="rounded-lg border border-line bg-surface p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-5">
                    <div>
                        <p class="font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold mb-1">Public lookup</p>
                        <h4 class="text-lg font-semibold text-ink">Already have a sample code?</h4>
                        <p class="text-sm text-inksoft mt-1">Track its live status and chain-of-custody history &mdash; no account needed.</p>
                    </div>
                    <form id="guestTrackForm" class="flex gap-2 flex-shrink-0 w-full md:w-auto">
                        <label for="publicSampleCode" class="sr-only">Sample code</label>
                        <input type="text" id="publicSampleCode" required placeholder="e.g. SMP-001"
                               class="flex-1 md:w-48 font-mono rounded-md border border-line px-3 py-2.5 text-sm text-ink placeholder:text-inksoft/60 focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold">
                        <button type="submit" class="inline-flex justify-center items-center rounded-md bg-ink px-4 py-2.5 text-sm font-semibold text-white hover:bg-ink/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ink transition-colors">
                            Track
                        </button>
                    </form>
                </div>
            </section>

            <!-- Final CTA -->
            <section class="mx-auto max-w-7xl px-5 sm:px-8 pb-16">
                <div class="rounded-lg bg-ink px-6 sm:px-10 py-10 flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Ready to get started?</h2>
                        <p class="text-white/60 mt-1 text-sm">Create an account to book collections, donate, or manage samples.</p>
                    </div>
                    <a href="{{ url('/register') }}" class="inline-flex items-center rounded-md bg-blood px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-ink focus:ring-blood transition-colors flex-shrink-0">
                        Create your account
                    </a>
                </div>
            </section>
        </main>

        <footer class="border-t border-line">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 py-6 flex items-center justify-between flex-wrap gap-3">
                <p class="font-mono text-xs text-inksoft">Blood Bank System &mdash; Sample Circulation</p>
                <p class="text-xs text-inksoft">&copy; {{ date('Y') }} All rights reserved.</p>
            </div>
        </footer>
    @endguest

    {{-- ========================================== --}}
    {{-- AUTH VIEW --}}
    {{-- ========================================== --}}
    @auth
        <main class="mx-auto max-w-7xl px-5 sm:px-8 py-10">
            <!-- Welcome + Session -->
            <div class="rounded-lg bg-ink px-6 py-5 mb-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="font-mono text-[10px] tracking-[0.2em] text-cold uppercase">Welcome back</p>
                    <h2 class="text-2xl font-semibold text-white mt-0.5">{{ Auth::user()->name }}</h2>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 font-mono text-[11px] tracking-[0.1em] text-white uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Session active
                </span>
            </div>

            <!-- Track Sample (Auth) -->
            <div class="rounded-lg border border-line bg-surface p-5 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="text-base font-semibold text-ink">Track chain of custody</h5>
                    <p class="text-sm text-inksoft mt-0.5">Enter a sample code (e.g. SMP-001) to view its audit history.</p>
                </div>
                <form id="authTrackForm" class="flex gap-2 flex-shrink-0">
                    <label for="authSampleCode" class="sr-only">Sample code</label>
                    <input type="text" id="authSampleCode" required placeholder="e.g. SMP-001"
                           class="font-mono rounded-md border border-line px-3 py-2 text-sm text-ink w-44 placeholder:text-inksoft/60 focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold">
                    <button type="submit" class="inline-flex items-center rounded-md bg-blood px-4 py-2 text-sm font-semibold text-white hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blood transition-colors">
                        Track
                    </button>
                </form>
            </div>

            <!-- Section: Patient / Donor Portal -->
            <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-3">Patient &amp; donor portal</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="rounded-lg border border-line bg-surface p-5 flex flex-col hover:shadow-sm transition-shadow">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-coldsoft text-cold mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75c-1.9 3.1-5.25 8.6-5.25 11.4a5.25 5.25 0 0010.5 0c0-2.8-3.35-8.3-5.25-11.4z" /></svg>
                    </span>
                    <h5 class="font-semibold text-ink">My blood samples &amp; donations</h5>
                    <p class="text-sm text-inksoft mt-1 flex-grow">View your personal donation history, track submitted samples, and register new donations.</p>
                    <a href="{{ url('/my-blood-samples') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-cold hover:text-cold/80">
                        My donations &rarr;
                    </a>
                </div>
                <div class="rounded-lg border border-blood/30 bg-surface p-5 flex flex-col hover:shadow-sm transition-shadow">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-blood/10 text-blood mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78A1.5 1.5 0 0022.18 18L13.71 3.86a1.5 1.5 0 00-2.42 0z" /></svg>
                    </span>
                    <h5 class="font-semibold text-ink">Emergency SOS &amp; requests</h5>
                    <p class="text-sm text-inksoft mt-1 flex-grow">View active emergency requests or broadcast an urgent donation alert.</p>
                    <a href="{{ url('/sos') }}" class="mt-4 inline-flex items-center justify-center rounded-md bg-blood px-4 py-2 text-sm font-semibold text-white hover:bg-blooddark transition-colors">
                        Emergency SOS panel
                    </a>
                </div>
            </div>

            <!-- Section: Staff & Circulation -->
            <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-3">Staff &amp; circulation management</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-lg border border-line bg-surface p-5 flex flex-col hover:shadow-sm transition-shadow">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-coldsoft text-cold mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5.25L4.5 17a2 2 0 001.8 2.9h11.4a2 2 0 001.8-2.9L15 8.25V3M9 3h6M8.25 13.5h7.5" /></svg>
                    </span>
                    <h5 class="font-semibold text-ink">Blood sample reviews</h5>
                    <p class="text-sm text-inksoft mt-1 flex-grow">Review incoming samples, verify collection quality, and approve or reject.</p>
                    <a href="{{ url('/blood-samples') }}" class="mt-4 inline-flex items-center rounded-md border border-line px-4 py-2 text-sm font-semibold text-ink hover:bg-bg transition-colors justify-center">
                        Manage samples
                    </a>
                </div>
                <div class="rounded-lg border border-line bg-surface p-5 flex flex-col hover:shadow-sm transition-shadow">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-coldsoft text-cold mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-12h-4.5A2.25 2.25 0 007.5 9v.75m6.75-3v3m0 0h5.25m-5.25 0v6.75" /></svg>
                    </span>
                    <h5 class="font-semibold text-ink">Transportation logistics</h5>
                    <p class="text-sm text-inksoft mt-1 flex-grow">Monitor transport status and track active shipments between centers and labs.</p>
                    <a href="{{ url('/transportation') }}" class="mt-4 inline-flex items-center rounded-md border border-line px-4 py-2 text-sm font-semibold text-ink hover:bg-bg transition-colors justify-center">
                        View logistics
                    </a>
                </div>
                <div class="rounded-lg border border-line bg-surface p-5 flex flex-col hover:shadow-sm transition-shadow">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-coldsoft text-cold mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path stroke-linecap="round" d="M5 10.5h14M8 6.75h.01M8 14.5h.01"/></svg>
                    </span>
                    <h5 class="font-semibold text-ink">Cold storage inventory</h5>
                    <p class="text-sm text-inksoft mt-1 flex-grow">Manage refrigerators, racks, shelves, and stock levels for accepted samples.</p>
                    <a href="{{ url('/inventory') }}" class="mt-4 inline-flex items-center rounded-md border border-line px-4 py-2 text-sm font-semibold text-ink hover:bg-bg transition-colors justify-center">
                        View inventory
                    </a>
                </div>
            </div>

            <!-- Section: Administration -->
            <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-3">Administration</p>
            <div class="rounded-lg bg-ink p-6 flex items-center justify-between flex-wrap gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-md bg-white/10 text-white flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7.5 3v5.25c0 4.66-3.19 8.94-7.5 10.05-4.31-1.11-7.5-5.39-7.5-10.05V6L12 3z" /></svg>
                    </span>
                    <div>
                        <h5 class="font-semibold text-white">Admin dashboard</h5>
                        <p class="text-sm text-white/60 mt-0.5">Full system oversight, user management, and system-wide configuration.</p>
                    </div>
                </div>
                <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:bg-white/90 transition-colors flex-shrink-0">
                    Open admin panel
                </a>
            </div>
        </main>
    @endauth

    <script>
        // User dropdown menu
        (function () {
            const btn = document.getElementById('userMenuBtn');
            const menu = document.getElementById('userMenu');
            if (!btn || !menu) return;
            const close = () => { menu.classList.add('hidden'); btn.setAttribute('aria-expanded', 'false'); };
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const willOpen = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', String(willOpen));
            });
            document.addEventListener('click', (e) => { if (!menu.contains(e.target) && e.target !== btn) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
        })();

        // Sample tracking redirects
        const wireTrackForm = (formId, inputId) => {
            const form = document.getElementById(formId);
            const input = document.getElementById(inputId);
            if (!form || !input) return;
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const code = input.value.trim();
                if (code) window.location.href = '/sample-history/' + encodeURIComponent(code);
            });
        };
        wireTrackForm('guestTrackForm', 'publicSampleCode');
        wireTrackForm('authTrackForm', 'authSampleCode');
    </script>
</body>
</html>