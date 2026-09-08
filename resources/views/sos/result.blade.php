<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Emergency Blood SOS
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Nearby compatible blood donors
            </p>

        </div>

    </x-slot>


    {{-- ================================================================
         PAGE
    ================================================================= --}}

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ============================================================
                 SUCCESS MESSAGE
            ============================================================= --}}

            @if(session('success'))

                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800 shadow-sm">
                    {{ session('success') }}
                </div>

            @endif


            {{-- ============================================================
                 ERROR MESSAGE
            ============================================================= --}}

            @if(session('error'))

                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4 text-red-800 shadow-sm">
                    {{ session('error') }}
                </div>

            @endif


            {{-- ============================================================
                 PAGE HEADING
            ============================================================= --}}

            <div class="mb-6">

                <h1 class="text-3xl font-bold text-red-600 dark:text-red-400">
                    🚨 Emergency Blood Donors
                </h1>

                <p class="mt-2 text-blue-600 dark:text-blue-400 font-medium">
                    Medically compatible donors within 2 km of your emergency location.
                </p>

            </div>


            {{-- ============================================================
                 EMERGENCY INFORMATION
            ============================================================= --}}

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
            >

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">


                    {{-- BLOOD GROUP --}}

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Requested Blood Group
                        </p>

                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ $emergencyRequest->blood_group }}
                        </p>

                    </div>


                    {{-- COMPATIBLE GROUPS --}}

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Compatible Donor Groups
                        </p>

                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ implode(', ', $compatibleGroups ?? []) }}
                        </p>

                    </div>


                    {{-- STATUS --}}

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Emergency Status
                        </p>

                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                            {{ ucfirst($emergencyRequest->status) }}
                        </p>

                    </div>


                    {{-- LOCATION --}}

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Emergency Location
                        </p>

                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $emergencyRequest->latitude }},
                            {{ $emergencyRequest->longitude }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ============================================================
                 MAP
            ============================================================= --}}

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8"
            >

                <div class="p-5 border-b border-gray-200 dark:border-gray-700">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                        <div>

                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                📍 Emergency Road Map
                            </h2>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Road distance and estimated driving time are calculated automatically.
                            </p>

                        </div>


                        <div class="text-sm text-gray-600 dark:text-gray-300">

                            <span class="inline-flex items-center gap-2">

                                <span class="w-3 h-3 rounded-full bg-red-600"></span>

                                Your location

                            </span>


                            <span class="inline-flex items-center gap-2 ml-4">

                                <span class="w-3 h-3 rounded-full bg-blue-600"></span>

                                Donor

                            </span>

                        </div>

                    </div>

                </div>


                <div
                    id="sosMap"
                    style="height: 500px; width: 100%;"
                    class="z-0"
                ></div>


                {{-- ROUTE INFORMATION --}}

                <div
                    id="routeInfo"
                    class="hidden p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900"
                >

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">


                        <div>

                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Donor
                            </p>

                            <p
                                id="routeDonorName"
                                class="font-semibold text-gray-900 dark:text-white mt-1"
                            >
                                -
                            </p>

                        </div>


                        <div>

                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Road Distance
                            </p>

                            <p
                                id="routeDistance"
                                class="font-semibold text-blue-600 dark:text-blue-400 mt-1"
                            >
                                -
                            </p>

                        </div>


                        <div>

                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Estimated Travel Time
                            </p>

                            <p
                                id="routeDuration"
                                class="font-semibold text-green-600 dark:text-green-400 mt-1"
                            >
                                -
                            </p>

                        </div>


                        <div>

                            <a
                                id="googleMapsRoute"
                                href="#"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hidden w-full text-center bg-gray-900 hover:bg-gray-700 text-white font-semibold py-2.5 px-4 rounded-xl transition"
                            >
                                🗺️ Open in Google Maps
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================================================
                 DONOR LIST
            ============================================================= --}}

            <div class="mb-4">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Compatible Donors
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Showing verified, willing and available donors who are medically compatible
                    and geographically within 2 km.
                </p>

            </div>


            @if($donors->count() > 0)

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                    @foreach($donors as $donor)

                        @php

                            $alreadyRequested = in_array(
                                $donor->id,
                                $requestedDonors ?? []
                            );

                            $geographicDistance = is_numeric($donor->distance ?? null)
                                ? (float) $donor->distance
                                : null;

                        @endphp


                        <div
                            id="donor-card-{{ $donor->id }}"
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
                        >


                            {{-- DONOR HEADER --}}

                            <div class="p-5 border-b border-gray-200 dark:border-gray-700">

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center"
                                        >
                                            <span class="text-xl">
                                                🩸
                                            </span>
                                        </div>


                                        <div>

                                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                                                {{ $donor->user->name ?? 'Blood Donor' }}
                                            </h3>

                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Verified donor
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-bold"
                                    >
                                        {{ $donor->blood_group }}
                                    </span>

                                </div>

                            </div>


                            {{-- DONOR INFORMATION --}}

                            <div class="p-5">

                                <div class="space-y-3">


                                    {{-- MEDICAL COMPATIBILITY --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Blood Compatibility
                                        </span>

                                        <span class="font-bold text-red-600 dark:text-red-400">
                                            {{ $donor->blood_group }}
                                            ✓
                                        </span>

                                    </div>


                                    {{-- GEOGRAPHIC DISTANCE --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Distance
                                        </span>

                                        <span
                                            class="font-bold text-blue-600 dark:text-blue-400"
                                            id="distance-{{ $donor->id }}"
                                        >

                                            @if($geographicDistance !== null)

                                                {{ number_format($geographicDistance, 2) }} km

                                            @else

                                                Calculating...

                                            @endif

                                        </span>

                                    </div>


                                    {{-- ROAD DISTANCE --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Road Distance
                                        </span>

                                        <span
                                            id="road-distance-{{ $donor->id }}"
                                            class="font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            Calculating...
                                        </span>

                                    </div>


                                    {{-- ETA --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Estimated Time
                                        </span>

                                        <span
                                            id="road-duration-{{ $donor->id }}"
                                            class="font-semibold text-green-600 dark:text-green-400"
                                        >
                                            Calculating...
                                        </span>

                                    </div>


                                    {{-- AVAILABILITY --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Availability
                                        </span>

                                        @if($donor->is_available)

                                            <span class="text-green-600 font-semibold">
                                                Available
                                            </span>

                                        @else

                                            <span class="text-gray-500 font-semibold">
                                                Unavailable
                                            </span>

                                        @endif

                                    </div>


                                    {{-- WILLINGNESS --}}

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Willing to Donate
                                        </span>

                                        @if($donor->is_willing)

                                            <span class="text-green-600 font-semibold">
                                                Yes
                                            </span>

                                        @else

                                            <span class="text-red-600 font-semibold">
                                                No
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                {{-- BUTTONS --}}

                                <div class="mt-6 space-y-3">


                                    {{-- VIEW ROAD ROUTE --}}

                                    <button
                                        type="button"
                                        onclick="showDonorRoute(
                                            {{ $donor->id }},
                                            {{ (float) $donor->latitude }},
                                            {{ (float) $donor->longitude }},
                                            @js($donor->user->name ?? 'Blood Donor')
                                        )"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded-xl transition"
                                    >
                                        🗺️ View Road Route
                                    </button>


                                    {{-- REQUEST BLOOD --}}

                                    @if($alreadyRequested)

                                        <button
                                            type="button"
                                            disabled
                                            class="w-full bg-gray-400 text-white font-bold py-3 px-5 rounded-xl cursor-not-allowed"
                                        >
                                            ✓ Request Already Sent
                                        </button>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('donor.request', $donor->id) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-5 rounded-xl transition"
                                            >
                                                🩸 Request Blood
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-10 text-center"
                >

                    <div class="text-5xl mb-5">
                        🩸
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        No Compatible Donors Found
                    </h2>

                    <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                        There are currently no verified, available and medically compatible
                        donors within 2 km of your emergency location.
                    </p>

                    <p class="mt-3 text-sm text-blue-600 dark:text-blue-400 font-semibold">
                        Compatible groups:
                        {{ implode(', ', $compatibleGroups ?? []) }}
                    </p>

                    <a
                        href="{{ route('sos.index') }}"
                        class="inline-block mt-6 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition"
                    >
                        ← Send SOS Again
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         LEAFLET CSS
    ================================================================= --}}

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />


    {{-- ================================================================
         LEAFLET JS
    ================================================================= --}}

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Patient location
        |--------------------------------------------------------------------------
        */

        const patientLatitude =
            Number(
                @json((float) $emergencyRequest->latitude)
            );

        const patientLongitude =
            Number(
                @json((float) $emergencyRequest->longitude)
            );


        let sosMap = null;

        let currentRoute = null;

        const donorMarkers = {};


        /*
        |--------------------------------------------------------------------------
        | Coordinate validation
        |--------------------------------------------------------------------------
        */

        function validCoordinate(
            latitude,
            longitude
        ) {

            return (
                Number.isFinite(latitude) &&
                Number.isFinite(longitude) &&
                latitude >= -90 &&
                latitude <= 90 &&
                longitude >= -180 &&
                longitude <= 180
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Format minutes
        |--------------------------------------------------------------------------
        */

        function formatDuration(minutes) {

            minutes =
                Math.max(
                    1,
                    Math.round(
                        Number(minutes)
                    )
                );


            if (minutes < 60) {

                return minutes + ' min';

            }


            const hours =
                Math.floor(
                    minutes / 60
                );

            const remaining =
                minutes % 60;


            if (remaining > 0) {

                return (
                    hours
                    + ' hr '
                    + remaining
                    + ' min'
                );

            }


            return hours + ' hr';

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate road route for donor card
        |--------------------------------------------------------------------------
        |
        | This runs automatically for every compatible donor.
        |
        */

        async function calculateDonorRoadRoute(
            donorId,
            donorLatitude,
            donorLongitude
        ) {

            const distanceElement =
                document.getElementById(
                    'road-distance-' + donorId
                );

            const durationElement =
                document.getElementById(
                    'road-duration-' + donorId
                );


            if (!distanceElement || !durationElement) {

                return;

            }


            const url =
                'https://router.project-osrm.org/route/v1/driving/'
                +
                patientLongitude
                +
                ','
                +
                patientLatitude
                +
                ';'
                +
                donorLongitude
                +
                ','
                +
                donorLatitude
                +
                '?overview=false';


            try {

                const response =
                    await fetch(
                        url,
                        {
                            method: 'GET',

                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'OSRM HTTP ' +
                        response.status
                    );

                }


                const data =
                    await response.json();


                if (
                    data.code !== 'Ok'
                    ||
                    !data.routes
                    ||
                    data.routes.length === 0
                ) {

                    throw new Error(
                        'No route available.'
                    );

                }


                const route =
                    data.routes[0];


                const roadDistanceKm =
                    Number(
                        route.distance
                    ) / 1000;


                const roadDurationMinutes =
                    Math.max(
                        1,
                        Math.round(
                            Number(
                                route.duration
                            ) / 60
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE DONOR CARD
                |--------------------------------------------------------------------------
                */

                distanceElement.textContent =
                    roadDistanceKm.toFixed(2)
                    + ' km';


                durationElement.textContent =
                    formatDuration(
                        roadDurationMinutes
                    );


            }
            catch (error) {

                console.error(
                    'Donor road calculation failed:',
                    donorId,
                    error
                );


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT FALLBACK
                |--------------------------------------------------------------------------
                |
                | If OSRM temporarily fails, do NOT erase the donor's
                | geographic distance.
                |
                */

                const geographicElement =
                    document.getElementById(
                        'distance-' + donorId
                    );


                if (
                    geographicElement
                    &&
                    geographicElement.textContent
                ) {

                    distanceElement.textContent =
                        geographicElement.textContent
                        + ' (direct)';

                }
                else {

                    distanceElement.textContent =
                        'Route unavailable';

                }


                /*
                |--------------------------------------------------------------------------
                | Approximate ETA fallback
                |--------------------------------------------------------------------------
                |
                | This is NOT used for eligibility.
                | It only prevents the UI from showing "-".
                |
                | 25 km/h is used as a conservative urban emergency
                | driving estimate.
                |
                */

                const geographicDistance =
                    Number(
                        @json(
                            collect($donors)
                                ->pluck('distance', 'id')
                                ->toArray()
                        )[donorId]
                    );


                if (
                    Number.isFinite(
                        geographicDistance
                    )
                    &&
                    geographicDistance > 0
                ) {

                    const estimatedMinutes =
                        Math.max(
                            1,
                            Math.round(
                                (
                                    geographicDistance
                                    / 25
                                ) * 60
                            )
                        );


                    durationElement.textContent =
                        '~'
                        +
                        formatDuration(
                            estimatedMinutes
                        );

                }
                else {

                    durationElement.textContent =
                        'Unavailable';

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Automatically calculate road information for ALL donors
        |--------------------------------------------------------------------------
        */

        function calculateAllDonorRoutes() {

            @foreach($donors as $donor)

                calculateDonorRoadRoute(
                    {{ $donor->id }},
                    {{ (float) $donor->latitude }},
                    {{ (float) $donor->longitude }}
                );

            @endforeach

        }


        /*
        |--------------------------------------------------------------------------
        | Initialize map
        |--------------------------------------------------------------------------
        */

        function initializeSOSMap() {

            if (
                !validCoordinate(
                    patientLatitude,
                    patientLongitude
                )
            ) {

                console.error(
                    'Invalid emergency location:',
                    patientLatitude,
                    patientLongitude
                );

                return;

            }


            sosMap =
                L.map(
                    'sosMap',
                    {
                        center: [
                            patientLatitude,
                            patientLongitude
                        ],

                        zoom: 14,

                        zoomControl: true,

                        scrollWheelZoom: true
                    }
                );


            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {

                    maxZoom: 19,

                    attribution:
                        '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'

                }
            ).addTo(
                sosMap
            );


            /*
            |--------------------------------------------------------------------------
            | Patient marker
            |--------------------------------------------------------------------------
            */

            const patientIcon =
                L.divIcon({

                    className:
                        'sos-patient-marker',

                    html: `
                        <div style="
                            width:34px;
                            height:34px;
                            background:#dc2626;
                            border:4px solid white;
                            border-radius:50%;
                            box-shadow:0 2px 8px rgba(0,0,0,.35);
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            color:white;
                            font-size:16px;
                        ">
                            🚨
                        </div>
                    `,

                    iconSize: [
                        34,
                        34
                    ],

                    iconAnchor: [
                        17,
                        17
                    ]

                });


            L.marker(
                [
                    patientLatitude,
                    patientLongitude
                ],
                {
                    icon:
                        patientIcon
                }
            )
                .addTo(
                    sosMap
                )
                .bindPopup(
                    '<strong>🚨 Your Emergency Location</strong>'
                );


            /*
            |--------------------------------------------------------------------------
            | Donor markers
            |--------------------------------------------------------------------------
            */

            @foreach($donors as $donor)

                @if($donor->latitude !== null && $donor->longitude !== null)

                    addDonorMarker(
                        {{ $donor->id }},
                        {{ (float) $donor->latitude }},
                        {{ (float) $donor->longitude }},
                        @js($donor->user->name ?? 'Blood Donor'),
                        @js($donor->blood_group)
                    );

                @endif

            @endforeach


            /*
            |--------------------------------------------------------------------------
            | Fit map
            |--------------------------------------------------------------------------
            */

            const points = [
                [
                    patientLatitude,
                    patientLongitude
                ]
            ];


            Object.values(
                donorMarkers
            ).forEach(
                function(marker) {

                    if (marker) {

                        points.push(
                            marker.getLatLng()
                        );

                    }

                }
            );


            if (points.length > 1) {

                const bounds =
                    L.latLngBounds(
                        points
                    );


                sosMap.fitBounds(
                    bounds,
                    {
                        padding: [
                            50,
                            50
                        ],

                        maxZoom: 15
                    }
                );

            }


            setTimeout(
                function() {

                    if (sosMap) {

                        sosMap.invalidateSize();

                    }

                },
                300
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Donor marker
        |--------------------------------------------------------------------------
        */

        function addDonorMarker(
            donorId,
            latitude,
            longitude,
            donorName,
            bloodGroup
        ) {

            if (
                !validCoordinate(
                    latitude,
                    longitude
                )
            ) {

                return;

            }


            const donorIcon =
                L.divIcon({

                    className:
                        'sos-donor-marker',

                    html: `
                        <div style="
                            width:32px;
                            height:32px;
                            background:#2563eb;
                            border:4px solid white;
                            border-radius:50%;
                            box-shadow:0 2px 8px rgba(0,0,0,.35);
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            color:white;
                            font-size:15px;
                        ">
                            🩸
                        </div>
                    `,

                    iconSize: [
                        32,
                        32
                    ],

                    iconAnchor: [
                        16,
                        16
                    ]

                });


            const marker =
                L.marker(
                    [
                        latitude,
                        longitude
                    ],
                    {
                        icon:
                            donorIcon
                    }
                )
                .addTo(
                    sosMap
                );


            marker.bindPopup(`

                <div style="
                    min-width:230px;
                    font-family:Arial,sans-serif;
                ">

                    <strong style="
                        font-size:16px;
                        display:block;
                        margin-bottom:8px;
                    ">
                        🩸 ${escapeHtml(donorName)}
                    </strong>

                    <div style="margin-bottom:5px;">
                        Blood Group:
                        <strong>
                            ${escapeHtml(bloodGroup)}
                        </strong>
                    </div>

                    <button
                        type="button"
                        onclick="showDonorRoute(
                            ${Number(donorId)},
                            ${Number(latitude)},
                            ${Number(longitude)},
                            ${JSON.stringify(donorName)}
                        )"
                        style="
                            background:#2563eb;
                            color:white;
                            border:none;
                            padding:9px 12px;
                            border-radius:8px;
                            cursor:pointer;
                            font-weight:600;
                            width:100%;
                        "
                    >
                        🗺️ Show Road Route
                    </button>

                </div>

            `);


            donorMarkers[donorId] =
                marker;

        }


        /*
        |--------------------------------------------------------------------------
        | Show road route
        |--------------------------------------------------------------------------
        */

        async function showDonorRoute(
            donorId,
            donorLatitude,
            donorLongitude,
            donorName
        ) {

            if (!sosMap) {

                return;

            }


            if (
                !validCoordinate(
                    donorLatitude,
                    donorLongitude
                )
            ) {

                alert(
                    'This donor does not have a valid location.'
                );

                return;

            }


            if (
                donorMarkers[donorId]
            ) {

                donorMarkers[donorId]
                    .openPopup();

            }


            if (currentRoute) {

                sosMap.removeLayer(
                    currentRoute
                );

                currentRoute =
                    null;

            }


            const routeInfo =
                document.getElementById(
                    'routeInfo'
                );


            routeInfo.classList.remove(
                'hidden'
            );


            document.getElementById(
                'routeDonorName'
            ).textContent =
                donorName;


            document.getElementById(
                'routeDistance'
            ).textContent =
                'Calculating road distance...';


            document.getElementById(
                'routeDuration'
            ).textContent =
                'Calculating ETA...';


            const googleMapsRoute =
                document.getElementById(
                    'googleMapsRoute'
                );


            googleMapsRoute.href =
                'https://www.google.com/maps/dir/?api=1'
                +
                '&origin='
                +
                encodeURIComponent(
                    patientLatitude
                    + ','
                    + patientLongitude
                )
                +
                '&destination='
                +
                encodeURIComponent(
                    donorLatitude
                    + ','
                    + donorLongitude
                )
                +
                '&travelmode=driving';


            googleMapsRoute.classList.remove(
                'hidden'
            );


            /*
            |--------------------------------------------------------------------------
            | OSRM
            |--------------------------------------------------------------------------
            */

            const url =
                'https://router.project-osrm.org/route/v1/driving/'
                +
                patientLongitude
                + ','
                + patientLatitude
                +
                ';'
                +
                donorLongitude
                + ','
                + donorLatitude
                +
                '?overview=full&geometries=geojson&steps=true';


            try {

                const response =
                    await fetch(
                        url,
                        {
                            method: 'GET',

                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'Routing server returned HTTP '
                        +
                        response.status
                    );

                }


                const data =
                    await response.json();


                if (
                    data.code !== 'Ok'
                    ||
                    !data.routes
                    ||
                    data.routes.length === 0
                ) {

                    throw new Error(
                        'No road route found.'
                    );

                }


                const route =
                    data.routes[0];


                /*
                |--------------------------------------------------------------------------
                | Draw route
                |--------------------------------------------------------------------------
                */

                currentRoute =
                    L.geoJSON(
                        route.geometry,
                        {
                            style: {
                                weight: 7,
                                opacity: 0.9
                            }
                        }
                    )
                    .addTo(
                        sosMap
                    );


                /*
                |--------------------------------------------------------------------------
                | Road distance
                |--------------------------------------------------------------------------
                */

                const roadDistanceKm =
                    Number(
                        route.distance
                    ) / 1000;


                document.getElementById(
                    'routeDistance'
                ).textContent =
                    roadDistanceKm.toFixed(2)
                    + ' km';


                /*
                |--------------------------------------------------------------------------
                | ETA
                |--------------------------------------------------------------------------
                */

                const totalMinutes =
                    Math.max(
                        1,
                        Math.round(
                            Number(
                                route.duration
                            ) / 60
                        )
                    );


                document.getElementById(
                    'routeDuration'
                ).textContent =
                    formatDuration(
                        totalMinutes
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE DONOR CARD TOO
                |--------------------------------------------------------------------------
                */

                const cardDistance =
                    document.getElementById(
                        'road-distance-' + donorId
                    );

                const cardDuration =
                    document.getElementById(
                        'road-duration-' + donorId
                    );


                if (cardDistance) {

                    cardDistance.textContent =
                        roadDistanceKm.toFixed(2)
                        + ' km';

                }


                if (cardDuration) {

                    cardDuration.textContent =
                        formatDuration(
                            totalMinutes
                        );

                }


                /*
                |--------------------------------------------------------------------------
                | Fit route
                |--------------------------------------------------------------------------
                */

                if (
                    currentRoute
                    &&
                    currentRoute.getBounds().isValid()
                ) {

                    sosMap.fitBounds(
                        currentRoute.getBounds(),
                        {
                            padding: [
                                50,
                                50
                            ]
                        }
                    );

                }

            }
            catch (error) {

                console.error(
                    'Road route error:',
                    error
                );


                /*
                |--------------------------------------------------------------------------
                | FALLBACK TO GEOGRAPHIC DISTANCE
                |--------------------------------------------------------------------------
                */

                const geographicDistance =
                    Number(
                        @json(
                            collect($donors)
                                ->pluck('distance', 'id')
                                ->toArray()
                        )[donorId]
                    );


                if (
                    Number.isFinite(
                        geographicDistance
                    )
                ) {

                    document.getElementById(
                        'routeDistance'
                    ).textContent =
                        geographicDistance.toFixed(2)
                        + ' km direct';

                }
                else {

                    document.getElementById(
                        'routeDistance'
                    ).textContent =
                        'Route unavailable';

                }


                /*
                |--------------------------------------------------------------------------
                | Fallback ETA
                |--------------------------------------------------------------------------
                */

                if (
                    Number.isFinite(
                        geographicDistance
                    )
                    &&
                    geographicDistance > 0
                ) {

                    const estimatedMinutes =
                        Math.max(
                            1,
                            Math.round(
                                (
                                    geographicDistance
                                    / 25
                                ) * 60
                            )
                        );


                    document.getElementById(
                        'routeDuration'
                    ).textContent =
                        '~'
                        +
                        formatDuration(
                            estimatedMinutes
                        );

                }
                else {

                    document.getElementById(
                        'routeDuration'
                    ).textContent =
                        'Unavailable';

                }


                /*
                |--------------------------------------------------------------------------
                | Update donor card fallback
                |--------------------------------------------------------------------------
                */

                const cardDistance =
                    document.getElementById(
                        'road-distance-' + donorId
                    );

                const cardDuration =
                    document.getElementById(
                        'road-duration-' + donorId
                    );


                if (
                    cardDistance
                    &&
                    Number.isFinite(
                        geographicDistance
                    )
                ) {

                    cardDistance.textContent =
                        geographicDistance.toFixed(2)
                        + ' km direct';

                }


                if (
                    cardDuration
                    &&
                    Number.isFinite(
                        geographicDistance
                    )
                    &&
                    geographicDistance > 0
                ) {

                    const fallbackMinutes =
                        Math.max(
                            1,
                            Math.round(
                                (
                                    geographicDistance
                                    / 25
                                ) * 60
                            )
                        );


                    cardDuration.textContent =
                        '~'
                        +
                        formatDuration(
                            fallbackMinutes
                        );

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            return String(value)

                .replace(
                    /&/g,
                    '&amp;'
                )

                .replace(
                    /</g,
                    '&lt;'
                )

                .replace(
                    />/g,
                    '&gt;'
                )

                .replace(
                    /"/g,
                    '&quot;'
                )

                .replace(
                    /'/g,
                    '&#039;'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Start everything
        |--------------------------------------------------------------------------
        */

        function startSOSPage() {

            initializeSOSMap();

            /*
             * Calculate road distance and ETA for every donor
             * immediately after the page loads.
             */
            calculateAllDonorRoutes();

        }


        if (
            document.readyState ===
            'loading'
        ) {

            document.addEventListener(
                'DOMContentLoaded',
                startSOSPage
            );

        }
        else {

            startSOSPage();

        }


        /*
        |--------------------------------------------------------------------------
        | Resize map
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function() {

                if (sosMap) {

                    setTimeout(
                        function() {

                            sosMap.invalidateSize();

                        },
                        100
                    );

                }

            }
        );

    </script>


</x-app-layout>