<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample History - {{ $sample->sample_code }}</title>
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
        .link-node { width: 10px; height: 10px; border-radius: 9999px; background: #0E7C86; border: 2px solid #E3F1F1; flex-shrink: 0; }
        .link-rail { width: 1px; flex: 1 1 auto; background: repeating-linear-gradient(180deg, #B9D6D8 0, #B9D6D8 3px, transparent 3px, transparent 6px); min-height: 16px; }
    </style>
</head>
<body class="bg-bg text-ink font-sans antialiased min-h-screen">

    <!-- Site Header / Nav -->
    <header class="border-b border-line bg-surface sticky top-0 z-20">
        <div class="mx-auto max-w-5xl px-5 sm:px-8 py-4 flex items-center justify-between gap-4">
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
                @endguest
                @auth
                    <a href="/" class="text-sm font-medium text-inksoft hover:text-ink transition-colors">&larr; Back to dashboard</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-5 sm:px-8 py-10">

        @php
            $statusStyles = [
                'pending'    => ['bg' => 'bg-ambersoft', 'text' => 'text-amber', 'dot' => 'bg-amber'],
                'collected'  => ['bg' => 'bg-coldsoft',  'text' => 'text-cold',  'dot' => 'bg-cold'],
                'in_transit' => ['bg' => 'bg-coldsoft',  'text' => 'text-cold',  'dot' => 'bg-cold'],
                'accepted'   => ['bg' => 'bg-emerald-50','text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
                'rejected'   => ['bg' => 'bg-red-50',    'text' => 'text-blood', 'dot' => 'bg-blood'],
            ];
            $statusKey = strtolower(str_replace(' ', '_', $sample->status));
            $status = $statusStyles[$statusKey] ?? ['bg' => 'bg-linesoft', 'text' => 'text-inksoft', 'dot' => 'bg-inksoft'];
        @endphp

        <!-- Sample Header / Label Card -->
        <div class="rounded-lg border border-line bg-surface p-6 sm:p-7 mb-8">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-mono text-[11px] tracking-[0.14em] text-inksoft uppercase font-semibold mb-1">Chain of custody</p>
                    <h2 class="font-mono text-2xl sm:text-3xl font-bold text-ink">{{ $sample->sample_code }}</h2>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full {{ $status['bg'] }} {{ $status['text'] }} px-3.5 py-1.5 text-xs font-semibold uppercase tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                    {{ strtoupper($sample->status) }}
                </span>
            </div>
            <div class="barcode h-7 w-full max-w-xs rounded-sm mt-5" aria-hidden="true"></div>
        </div>

        <!-- Timeline -->
        <div class="rounded-lg border border-line bg-surface p-6 sm:p-8">
            <p class="font-mono text-[11px] tracking-[0.14em] text-cold uppercase font-semibold mb-6">Custody timeline</p>

            @if($histories->isEmpty())
                <div class="py-14 text-center">
                    <svg class="mx-auto h-10 w-10 text-line" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <h3 class="mt-3 text-sm font-semibold text-ink">No history recorded yet</h3>
                    <p class="mt-1 text-sm text-inksoft">Custody events will appear here as this sample moves through the system.</p>
                </div>
            @else
                <ol class="space-y-0">
                    @foreach($histories as $index => $history)
                        <li class="flex gap-4">
                            <!-- Rail -->
                            <div class="flex flex-col items-center">
                                <span class="link-node mt-1.5"></span>
                                @if(!$loop->last)
                                    <span class="link-rail"></span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="pb-7 flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 flex-wrap">
                                    <h5 class="font-semibold text-ink">{{ $history->action }}</h5>
                                    <time class="font-mono text-xs text-inksoft whitespace-nowrap">{{ $history->created_at->format('d M Y, h:i A') }}</time>
                                </div>
                                @if($history->description)
                                    <p class="text-sm text-inksoft mt-1">{{ $history->description }}</p>
                                @endif
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                                    <p class="font-mono text-[11px] text-inksoft">
                                        <span class="uppercase tracking-wide text-inksoft/70">Location</span>
                                        &nbsp;{{ $history->location ?? 'N/A' }}
                                    </p>
                                    <p class="font-mono text-[11px] text-inksoft">
                                        <span class="uppercase tracking-wide text-inksoft/70">Handler</span>
                                        &nbsp;{{ $history->user ? $history->user->name : 'System generated' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </main>
</body>
</html>