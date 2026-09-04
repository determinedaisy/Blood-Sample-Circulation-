@php
    $collectorRouteStops = $homeCollections
        ->filter(fn ($collection) =>
            $collection->latitude !== null
            && $collection->longitude !== null
            && $collection->status !== 'cancelled'
        )
        ->map(fn ($collection) => [
            'id' => $collection->id,
            'date' => $collection->preferred_date->format('Y-m-d'),
            'time' => $collection->preferred_time,
            'patient' => $collection->patient?->name ?? 'Patient',
            'address' => $collection->address,
            'lat' => (float) $collection->latitude,
            'lng' => (float) $collection->longitude,
            'routeOrder' => $collection->route_order ? (int) $collection->route_order : null,
            'status' => $collection->status,
        ])
        ->values();

    $collectorRouteDates = $collectorRouteStops
        ->pluck('date')
        ->unique()
        ->sort()
        ->values();
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .collector-stop-icon {
        align-items: center;
        background: #e11d48;
        border: 3px solid #fff;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
        color: #fff;
        display: flex;
        font-size: 13px;
        font-weight: 800;
        height: 32px;
        justify-content: center;
        width: 32px;
    }

    .collector-current-icon {
        align-items: center;
        background: #2563eb;
        border: 3px solid #fff;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
        color: #fff;
        display: flex;
        font-size: 15px;
        height: 34px;
        justify-content: center;
        width: 34px;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Home Collections</h2>
            <p class="mt-1 text-sm text-gray-600">
                View your assigned visits and update each collection as it progresses.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="mx-auto w-full max-w-[1800px] px-3 sm:px-5 lg:px-6">
            @if(session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($collectorRouteStops->isNotEmpty())
                <section class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 via-white to-rose-50 px-5 py-5 sm:px-6">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-widest text-rose-600">GPS collection route</div>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">My Daily Route</h3>
                                <p class="mt-1 text-sm text-gray-500">Follow the numbered order saved by the administrator, or open the full road route in Google Maps.</p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                                <div class="sm:min-w-[190px]">
                                    <label for="collector-route-date" class="text-xs font-bold uppercase tracking-wide text-gray-500">Route date</label>
                                    <select id="collector-route-date" class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                        @foreach($collectorRouteDates as $date)
                                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="collector-gps-button"
                                        class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                    ⌖ Start from my GPS
                                </button>
                                <button type="button" id="collector-google-route-button"
                                        class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700">
                                    Open full route ↗
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid xl:grid-cols-12">
                        <div class="border-b border-gray-200 p-4 sm:p-5 xl:col-span-8 xl:border-b-0 xl:border-r">
                            <div id="collector-route-map" class="h-[470px] w-full overflow-hidden rounded-xl bg-slate-100"></div>
                        </div>

                        <aside class="p-5 xl:col-span-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Today's road plan</div>
                                    <div id="collector-route-estimate" class="mt-1 font-bold text-gray-900">Calculating…</div>
                                </div>
                                <span id="collector-route-count" class="rounded-full bg-rose-50 px-3 py-1.5 text-sm font-bold text-rose-700">0 stops</span>
                            </div>

                            <div id="collector-route-message" class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                                Loading your saved route…
                            </div>

                            <div id="collector-route-list" class="mt-4 space-y-2 overflow-y-auto xl:max-h-[315px]"></div>
                        </aside>
                    </div>
                </section>
            @endif

            @if($homeCollections->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">
                    <div class="text-4xl">🏠</div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No home collections assigned</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Home visits assigned by the administrator will appear here.
                    </p>
                </div>
            @else
                <div class="mb-5 flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900">Assigned visits</h3>
                        <p class="text-sm text-gray-500">Complete each step in order during the home visit.</p>
                    </div>
                    <div class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                        {{ $homeCollections->count() }} {{ $homeCollections->count() === 1 ? 'appointment' : 'appointments' }}
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach($homeCollections as $homeCollection)
                        @php
                            $sampleCode = $homeCollection->sampleRequest?->bloodSample?->sample_code ?? 'Not created';
                            $sampleType = $homeCollection->sampleRequest?->sample_type ?? 'Sample type unavailable';
                            $statusLabel = ucwords(str_replace('_', ' ', $homeCollection->status));
                            $statusClass = match ($homeCollection->status) {
                                'assigned' => 'bg-blue-100 text-blue-800',
                                'on_the_way' => 'bg-amber-100 text-amber-800',
                                'arrived' => 'bg-purple-100 text-purple-800',
                                'collected' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $workflowSteps = [
                                ['key' => 'assigned', 'label' => 'Assigned'],
                                ['key' => 'on_the_way', 'label' => 'On the way'],
                                ['key' => 'arrived', 'label' => 'Arrived'],
                                ['key' => 'collected', 'label' => 'Collected'],
                            ];
                            $statusOrder = [
                                'assigned' => 0,
                                'on_the_way' => 1,
                                'arrived' => 2,
                                'collected' => 3,
                            ];
                            $currentStep = $statusOrder[$homeCollection->status] ?? -1;
                        @endphp

                        <article id="collector-home-card-{{ $homeCollection->id }}" class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <header class="border-b border-gray-200 bg-gradient-to-r from-blue-50 via-white to-indigo-50 px-5 py-5 sm:px-6">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold uppercase tracking-wider text-blue-600">
                                            @if($homeCollection->route_order)
                                                Route stop #{{ $homeCollection->route_order }} ·
                                            @endif
                                            Sample
                                        </div>
                                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $sampleCode }}</h3>
                                            <span class="text-sm text-gray-500">{{ $sampleType }}</span>
                                        </div>
                                    </div>

                                    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </header>

                            <div class="grid lg:grid-cols-12">
                                <section class="p-5 sm:p-6 lg:col-span-8 xl:col-span-9">
                                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg">👤</div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold uppercase tracking-wide text-gray-500">Patient</div>
                                                    <div class="mt-1 truncate font-semibold text-gray-900">
                                                        {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                                    </div>
                                                    <div class="truncate text-sm text-gray-500">
                                                        {{ $homeCollection->patient->email ?? 'No email available' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-lg">📅</div>
                                                <div>
                                                    <div class="text-xs font-bold uppercase tracking-wide text-gray-500">Appointment</div>
                                                    <div class="mt-1 font-semibold text-gray-900">
                                                        {{ $homeCollection->preferred_date->format('d M Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $homeCollection->preferred_time }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 sm:col-span-2 xl:col-span-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex min-w-0 items-center gap-3">
                                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-lg">📍</div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold uppercase tracking-wide text-gray-500">Location</div>
                                                        @if($homeCollection->latitude && $homeCollection->longitude)
                                                            <div class="mt-1 truncate text-sm font-medium text-gray-700">
                                                                {{ $homeCollection->latitude }}, {{ $homeCollection->longitude }}
                                                            </div>
                                                        @else
                                                            <div class="mt-1 text-sm text-gray-400">GPS unavailable</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5 rounded-xl border border-gray-200 p-4">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold uppercase tracking-wide text-gray-500">Collection address</div>
                                                <p class="mt-2 leading-6 text-gray-900">{{ $homeCollection->address }}</p>
                                            </div>

                                            @if($homeCollection->latitude && $homeCollection->longitude)
                                                <a href="https://www.google.com/maps?q={{ $homeCollection->latitude }},{{ $homeCollection->longitude }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex shrink-0 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                                    Open in Maps
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if($homeCollection->instructions)
                                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
                                            <div class="text-xs font-bold uppercase tracking-wide text-amber-700">Patient instructions</div>
                                            <p class="mt-2 text-sm leading-6 text-amber-900">{{ $homeCollection->instructions }}</p>
                                        </div>
                                    @endif
                                </section>

                                <aside class="border-t border-gray-200 bg-gray-50 p-5 sm:p-6 lg:col-span-4 lg:border-l lg:border-t-0 xl:col-span-3">
                                    <h4 class="font-bold text-gray-900">Collection progress</h4>
                                    <p class="mt-1 text-sm text-gray-500">Update the visit after completing the current step.</p>

                                    <ol class="mt-5 space-y-1">
                                        @foreach($workflowSteps as $index => $step)
                                            @php
                                                $isComplete = $currentStep > $index;
                                                $isCurrent = $currentStep === $index;
                                            @endphp

                                            <li class="relative flex gap-3 pb-4 last:pb-0">
                                                @if(!$loop->last)
                                                    <span class="absolute left-[15px] top-8 h-[calc(100%-1rem)] w-0.5 {{ $isComplete ? 'bg-green-300' : 'bg-gray-200' }}"></span>
                                                @endif

                                                <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 text-xs font-bold
                                                    {{ $isComplete ? 'border-green-500 bg-green-500 text-white' : ($isCurrent ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white text-gray-400') }}">
                                                    {{ $isComplete ? '✓' : $index + 1 }}
                                                </span>

                                                <div class="pt-1">
                                                    <div class="text-sm font-semibold {{ $isComplete ? 'text-green-700' : ($isCurrent ? 'text-blue-700' : 'text-gray-400') }}">
                                                        {{ $step['label'] }}
                                                    </div>
                                                    @if($isCurrent)
                                                        <div class="mt-0.5 text-xs text-gray-500">Current stage</div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>

                                    <div class="mt-6 border-t border-gray-200 pt-5">
                                        @if($homeCollection->status === 'assigned')
                                            <p class="mb-3 text-sm text-gray-600">Start when you leave for the patient's address.</p>
                                            <form method="POST" action="{{ route('home-collections.collector.start', $homeCollection) }}" class="js-collector-workflow-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-blue-700">
                                                    Start Trip
                                                </button>
                                            </form>
                                        @elseif($homeCollection->status === 'on_the_way')
                                            <p class="mb-3 text-sm text-gray-600">Confirm after reaching the patient's address.</p>
                                            <form method="POST" action="{{ route('home-collections.collector.arrive', $homeCollection) }}" class="js-collector-workflow-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full rounded-lg bg-purple-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-purple-700">
                                                    Mark as Arrived
                                                </button>
                                            </form>
                                        @elseif($homeCollection->status === 'arrived')
                                            <p class="mb-3 text-sm text-gray-600">Confirm only after the sample has been collected.</p>
                                            <form method="POST" action="{{ route('home-collections.collector.collect', $homeCollection) }}" class="js-collector-workflow-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        onclick="return confirm('Confirm that this sample has been collected?')"
                                                        class="w-full rounded-lg bg-green-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-green-700">
                                                    Confirm Sample Collected
                                                </button>
                                            </form>
                                        @elseif($homeCollection->status === 'collected')
                                            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                                                <div class="font-semibold">✓ Collection completed</div>
                                                @if($homeCollection->collected_at)
                                                    <div class="mt-1 text-sm text-green-700">
                                                        {{ $homeCollection->collected_at->format('d M Y, h:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-500">
                                                No collection action is available for this status.
                                            </div>
                                        @endif
                                    </div>
                                </aside>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($collectorRouteStops->isNotEmpty())
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const allStops = @json($collectorRouteStops);
                const dateSelect = document.getElementById('collector-route-date');
                const routeList = document.getElementById('collector-route-list');
                const routeCount = document.getElementById('collector-route-count');
                const estimate = document.getElementById('collector-route-estimate');
                const message = document.getElementById('collector-route-message');
                const gpsButton = document.getElementById('collector-gps-button');
                const googleButton = document.getElementById('collector-google-route-button');
                const map = L.map('collector-route-map').setView([23.8103, 90.4125], 11);
                const routeLayers = L.layerGroup().addTo(map);
                let visibleStops = [];
                let currentLocation = null;
                let requestSerial = 0;

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                function timeRank(value) {
                    const match = String(value || '').match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
                    if (!match) return 9999;
                    let hour = Number(match[1]) % 12;
                    if (match[3].toUpperCase() === 'PM') hour += 12;
                    return hour * 60 + Number(match[2]);
                }

                function sortStops(stops) {
                    return [...stops].sort(function (a, b) {
                        const aOrder = a.routeOrder === null ? 9999 : a.routeOrder;
                        const bOrder = b.routeOrder === null ? 9999 : b.routeOrder;
                        return aOrder - bOrder || timeRank(a.time) - timeRank(b.time) || a.id - b.id;
                    });
                }

                function setMessage(text, tone) {
                    const classes = {
                        blue: 'border-blue-200 bg-blue-50 text-blue-800',
                        green: 'border-green-200 bg-green-50 text-green-800',
                        amber: 'border-amber-200 bg-amber-50 text-amber-800',
                        red: 'border-red-200 bg-red-50 text-red-800'
                    };
                    message.className = 'mt-4 rounded-xl border px-4 py-3 text-sm ' + (classes[tone] || classes.blue);
                    message.textContent = text;
                }

                function stopIcon(number) {
                    return L.divIcon({
                        className: '',
                        html: '<div class="collector-stop-icon">' + number + '</div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });
                }

                function currentIcon() {
                    return L.divIcon({
                        className: '',
                        html: '<div class="collector-current-icon">⌖</div>',
                        iconSize: [34, 34],
                        iconAnchor: [17, 17]
                    });
                }

                function readableDuration(seconds) {
                    const minutes = Math.max(1, Math.round(seconds / 60));
                    if (minutes < 60) return minutes + ' min';
                    return Math.floor(minutes / 60) + ' hr ' + (minutes % 60) + ' min';
                }

                function fallbackDistance(points) {
                    let kilometres = 0;
                    for (let index = 1; index < points.length; index++) {
                        const previous = points[index - 1];
                        const current = points[index];
                        const lat1 = previous.lat * Math.PI / 180;
                        const lat2 = current.lat * Math.PI / 180;
                        const deltaLat = (current.lat - previous.lat) * Math.PI / 180;
                        const deltaLng = (current.lng - previous.lng) * Math.PI / 180;
                        const a = Math.sin(deltaLat / 2) ** 2
                            + Math.cos(lat1) * Math.cos(lat2) * Math.sin(deltaLng / 2) ** 2;
                        kilometres += 6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    }
                    return kilometres;
                }

                function navigateTo(stop) {
                    const parameters = new URLSearchParams({
                        api: '1',
                        travelmode: 'driving',
                        destination: stop.lat + ',' + stop.lng
                    });
                    window.open('https://www.google.com/maps/dir/?' + parameters.toString(), '_blank', 'noopener');
                }

                function openFullGoogleRoute() {
                    if (!visibleStops.length) return;
                    const destination = visibleStops[visibleStops.length - 1];
                    const parameters = new URLSearchParams({
                        api: '1',
                        travelmode: 'driving',
                        destination: destination.lat + ',' + destination.lng
                    });

                    if (currentLocation) {
                        parameters.set('origin', currentLocation.lat + ',' + currentLocation.lng);
                    }

                    const waypoints = visibleStops
                        .slice(0, -1)
                        .map(stop => stop.lat + ',' + stop.lng);
                    if (waypoints.length) parameters.set('waypoints', waypoints.join('|'));
                    window.open('https://www.google.com/maps/dir/?' + parameters.toString(), '_blank', 'noopener');
                }

                function addListItem(stop, index) {
                    const card = document.createElement('div');
                    card.className = stop.status === 'collected'
                        ? 'flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-3'
                        : 'flex items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 shadow-sm';

                    const number = document.createElement('span');
                    number.className = stop.status === 'collected'
                        ? 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-600 text-xs font-bold text-white'
                        : 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white';
                    number.textContent = stop.status === 'collected' ? '✓' : String(index + 1);

                    const details = document.createElement('div');
                    details.className = 'min-w-0 flex-1';
                    const patient = document.createElement('div');
                    patient.className = 'truncate text-sm font-bold text-gray-900';
                    patient.textContent = stop.patient;
                    const time = document.createElement('div');
                    time.className = 'mt-0.5 text-xs font-semibold text-indigo-700';
                    time.textContent = stop.time;
                    const address = document.createElement('div');
                    address.className = 'mt-1 line-clamp-2 text-xs leading-5 text-gray-500';
                    address.textContent = stop.address;
                    details.append(patient, time, address);

                    const navigate = document.createElement('button');
                    navigate.type = 'button';
                    navigate.className = 'shrink-0 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100';
                    navigate.textContent = 'Navigate';
                    navigate.addEventListener('click', () => navigateTo(stop));

                    card.append(number, details, navigate);
                    routeList.appendChild(card);
                }

                async function renderRoute() {
                    const thisRequest = ++requestSerial;
                    routeLayers.clearLayers();
                    routeList.innerHTML = '';
                    visibleStops = sortStops(allStops.filter(stop => stop.date === dateSelect.value));
                    routeCount.textContent = visibleStops.length + (visibleStops.length === 1 ? ' stop' : ' stops');
                    googleButton.disabled = visibleStops.length === 0;

                    if (!visibleStops.length) {
                        estimate.textContent = 'No route';
                        setMessage('No mapped appointments exist for this date.', 'amber');
                        return;
                    }

                    visibleStops.forEach(function (stop, index) {
                        addListItem(stop, index);
                        const marker = L.marker([stop.lat, stop.lng], { icon: stopIcon(index + 1) }).addTo(routeLayers);
                        const popup = document.createElement('div');
                        const title = document.createElement('strong');
                        title.textContent = (index + 1) + '. ' + stop.patient;
                        const detail = document.createElement('div');
                        detail.textContent = stop.time + ' · ' + stop.address;
                        popup.append(title, detail);
                        marker.bindPopup(popup);
                    });

                    const routePoints = currentLocation
                        ? [currentLocation, ...visibleStops]
                        : [...visibleStops];

                    if (currentLocation) {
                        L.marker([currentLocation.lat, currentLocation.lng], { icon: currentIcon() })
                            .addTo(routeLayers)
                            .bindPopup('Your current starting location');
                    }

                    const bounds = L.latLngBounds(routePoints.map(point => [point.lat, point.lng]));
                    map.fitBounds(bounds, { padding: [35, 35], maxZoom: 16 });

                    if (routePoints.length === 1) {
                        estimate.textContent = 'Single stop';
                        setMessage('This route has one appointment. Use Navigate when you are ready.', 'blue');
                        return;
                    }

                    estimate.textContent = 'Calculating road route…';
                    const coordinates = routePoints.map(point => point.lng + ',' + point.lat).join(';');
                    try {
                        const response = await fetch(
                            'https://router.project-osrm.org/route/v1/driving/' + coordinates
                            + '?overview=full&geometries=geojson&steps=false'
                        );
                        const data = await response.json();
                        if (!response.ok || data.code !== 'Ok' || !data.routes?.length) {
                            throw new Error('No route');
                        }
                        if (thisRequest !== requestSerial) return;

                        L.geoJSON(data.routes[0].geometry, {
                            style: { color: '#2563eb', weight: 6, opacity: 0.82 }
                        }).addTo(routeLayers);
                        estimate.textContent = (data.routes[0].distance / 1000).toFixed(1) + ' km · ' + readableDuration(data.routes[0].duration);
                        setMessage(
                            currentLocation
                                ? 'Road directions start from your current GPS position and follow the saved stop order.'
                                : 'This route follows the administrator’s saved numbered stop order.',
                            'green'
                        );
                    } catch (error) {
                        if (thisRequest !== requestSerial) return;
                        L.polyline(routePoints.map(point => [point.lat, point.lng]), {
                            color: '#94a3b8', weight: 4, dashArray: '8 8'
                        }).addTo(routeLayers);
                        estimate.textContent = fallbackDistance(routePoints).toFixed(1) + ' km approx.';
                        setMessage('The road service is temporarily unavailable. The stop order and individual Navigate buttons still work.', 'amber');
                    }
                }

                gpsButton.addEventListener('click', function () {
                    if (!navigator.geolocation) {
                        setMessage('This browser cannot read your GPS. Open the full route and Google Maps can use your phone location.', 'red');
                        return;
                    }

                    gpsButton.disabled = true;
                    gpsButton.textContent = 'Finding GPS…';
                    setMessage('Waiting for location permission…', 'blue');
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            currentLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            gpsButton.disabled = false;
                            gpsButton.textContent = '⌖ Update my GPS';
                            renderRoute();
                        },
                        function () {
                            gpsButton.disabled = false;
                            gpsButton.textContent = '⌖ Try GPS again';
                            setMessage('GPS permission was unavailable. You can still open the full route in Google Maps.', 'red');
                        },
                        { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                    );
                });

                googleButton.addEventListener('click', openFullGoogleRoute);
                dateSelect.addEventListener('change', renderRoute);

                const today = new Date().toISOString().slice(0, 10);
                if ([...dateSelect.options].some(option => option.value === today)) {
                    dateSelect.value = today;
                }
                renderRoute();
                window.setTimeout(function () { map.invalidateSize(); }, 200);
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showCollectorMessage(text, isError) {
                const existing = document.getElementById('collector-workflow-toast');
                if (existing) existing.remove();

                const toast = document.createElement('div');
                toast.id = 'collector-workflow-toast';
                toast.className = 'fixed right-5 top-20 z-[1000] max-w-sm rounded-xl px-5 py-4 text-sm font-semibold text-white shadow-xl '
                    + (isError ? 'bg-red-600' : 'bg-green-600');
                toast.textContent = text;
                document.body.appendChild(toast);
                window.setTimeout(() => toast.remove(), 4000);
            }

            document.addEventListener('submit', async function (event) {
                const form = event.target.closest('.js-collector-workflow-form');
                if (!form) return;
                event.preventDefault();

                const article = form.closest('article[id]');
                const button = form.querySelector('button[type="submit"]');
                const originalText = button?.textContent;

                if (button) {
                    button.disabled = true;
                    button.textContent = 'Updating…';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form)
                    });
                    const html = await response.text();
                    const parsed = new DOMParser().parseFromString(html, 'text/html');
                    const replacement = article ? parsed.getElementById(article.id) : null;

                    if (!response.ok || !replacement) {
                        const pageError = parsed.querySelector('.border-red-200')?.textContent.trim();
                        throw new Error(pageError || 'The collection status could not be updated.');
                    }

                    article.replaceWith(replacement);
                    const success = parsed.querySelector('.mb-5.border-green-200')?.textContent.trim();
                    showCollectorMessage(success || 'Collection status updated successfully.', false);
                } catch (error) {
                    if (button) {
                        button.disabled = false;
                        button.textContent = originalText;
                    }
                    showCollectorMessage(error.message || 'The collection status could not be updated.', true);
                }
            });
        });
    </script>
</x-app-layout>
