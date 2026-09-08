<x-app-layout>

```
<x-slot name="header">

    <div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🩸 Emergency Blood Donation
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Navigate to the patient's emergency location
        </p>
    </div>

</x-slot>


<div class="py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


        @if(session('success'))

            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800">
                {{ session('success') }}
            </div>

        @endif


        {{-- HEADER --}}

        <div class="mb-6">

            <h1 class="text-3xl font-bold text-green-600 dark:text-green-400">
                🩸 Donation Accepted
            </h1>

            <p class="mt-2 font-bold text-blue-600 dark:text-blue-300">
                Thank you for responding to this emergency. Please travel to the patient's location.
            </p>

        </div>


        {{-- STATUS --}}

        <div
            class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-2xl p-6 mb-6"
        >

            <div class="flex items-center gap-4">

                <div
                    class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center"
                >
                    <span class="text-2xl">✓</span>
                </div>

                <div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        You accepted the emergency request
                    </h2>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        Request #{{ $donorRequest->id }}
                    </p>

                </div>

            </div>

        </div>


        {{-- PATIENT INFORMATION --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        >

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-5">
                Emergency Details
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                <div class="rounded-xl bg-gray-50 dark:bg-gray-900 p-5">

                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Patient
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                        {{ $donorRequest->patient->user->name ?? 'Emergency Patient' }}
                    </p>

                </div>


                <div class="rounded-xl bg-red-50 dark:bg-red-950/30 p-5">

                    <p class="text-xs uppercase tracking-wide text-red-600 dark:text-red-400">
                        Blood Group
                    </p>

                    <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $donorRequest->emergencyRequest->blood_group ?? 'N/A' }}
                    </p>

                </div>


                <div class="rounded-xl bg-blue-50 dark:bg-blue-950/30 p-5">

                    <p class="text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        Emergency Location
                    </p>

                    <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">
                        {{ $donorRequest->patient_latitude }},
                        {{ $donorRequest->patient_longitude }}
                    </p>

                </div>

            </div>

        </div>


        {{-- NAVIGATION SUMMARY --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        >

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        🧭 Navigation to Patient
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        The blue line shows the road route from your current location to the patient.
                    </p>

                </div>


                <a
                    id="googleNavigationButton"
                    href="#"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm"
                >
                    🧭 Start Navigation
                </a>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">


                <div class="rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900 p-5">

                    <p class="text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        Road Distance
                    </p>

                    <p
                        id="routeDistance"
                        class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Calculating...
                    </p>

                </div>


                <div class="rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-100 dark:border-green-900 p-5">

                    <p class="text-xs uppercase tracking-wide text-green-600 dark:text-green-400">
                        Estimated Travel Time
                    </p>

                    <p
                        id="routeEta"
                        class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Calculating...
                    </p>

                </div>

            </div>


            <div
                id="routeStatus"
                class="mt-4 rounded-xl bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-300"
            >
                🔄 Calculating the best road route...
            </div>

        </div>


        {{-- MAP --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6"
        >

            <div class="p-5 border-b border-gray-200 dark:border-gray-700">

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    📍 Live Route to Patient
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Follow the blue road route to reach the patient's emergency location.
                </p>

            </div>

            <div
                id="donorTrackMap"
                style="height: 550px; width: 100%;"
            ></div>

        </div>


        {{-- MAP LEGEND --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        >

            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                Map Guide
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="flex items-center gap-3">
                    <div
                        style="
                            width:18px;
                            height:18px;
                            border-radius:50%;
                            background:#dc2626;
                            border:2px solid white;
                            box-shadow:0 1px 4px rgba(0,0,0,.3);
                        "
                    ></div>

                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Patient emergency location
                    </span>
                </div>


                <div class="flex items-center gap-3">
                    <div
                        style="
                            width:18px;
                            height:18px;
                            border-radius:50%;
                            background:#2563eb;
                            border:2px solid white;
                            box-shadow:0 1px 4px rgba(0,0,0,.3);
                        "
                    ></div>

                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Your current location
                    </span>
                </div>


                <div class="flex items-center gap-3">

                    <div
                        style="
                            width:35px;
                            height:6px;
                            border-radius:999px;
                            background:#2563eb;
                        "
                    ></div>

                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Road navigation route
                    </span>

                </div>

            </div>

        </div>


        {{-- LOCATION UPDATE --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        >

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        📡 Live Location
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Your location is continuously updated while travelling to the patient.
                    </p>

                </div>


                <span
                    id="locationStatus"
                    class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-semibold"
                >
                    Waiting for GPS...
                </span>

            </div>

        </div>


        {{-- COMPLETE --}}

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >

            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Donation Completed?
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                Only mark this emergency as completed after you have reached the patient
                and successfully completed the blood donation.
            </p>


            <form
                method="POST"
                action="{{ route('donor.request.complete', $donorRequest->id) }}"
                class="mt-5"
                onsubmit="return confirm('Have you successfully completed the blood donation?');"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition"
                >
                    ✓ Complete Emergency Donation
                </button>

            </form>

        </div>

    </div>

</div>


{{-- LEAFLET --}}

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
/>

<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>


<style>

    .donor-current-location-marker {
        background: transparent;
        border: none;
    }

    .patient-emergency-marker {
        background: transparent;
        border: none;
    }

</style>


<script>

    const patientLatitude =
        Number(@json((float) $donorRequest->patient_latitude));

    const patientLongitude =
        Number(@json((float) $donorRequest->patient_longitude));


    const initialDonorLatitude =
        Number(@json((float) $donorRequest->donor_latitude));

    const initialDonorLongitude =
        Number(@json((float) $donorRequest->donor_longitude));


    const updateLocationUrl =
        @json(route(
            'donor.request.update-location',
            $donorRequest->id
        ));


    let trackMap = null;

    let donorMarker = null;

    let routeLine = null;

    let routeCasing = null;

    let currentDonorLatitude = initialDonorLatitude;

    let currentDonorLongitude = initialDonorLongitude;

    let lastRouteLatitude = null;

    let lastRouteLongitude = null;

    let routeRequestInProgress = false;

    let watchId = null;


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


    function formatDistance(
        meters
    ) {

        if (
            !Number.isFinite(meters)
        ) {

            return 'Unavailable';

        }


        if (
            meters >= 1000
        ) {

            return (
                (meters / 1000).toFixed(2)
                + ' km'
            );

        }


        return (
            Math.round(meters)
            + ' m'
        );

    }


    function formatDuration(
        seconds
    ) {

        if (
            !Number.isFinite(seconds)
        ) {

            return 'Unavailable';

        }


        const totalMinutes =
            Math.max(
                1,
                Math.round(
                    seconds / 60
                )
            );


        const hours =
            Math.floor(
                totalMinutes / 60
            );


        const minutes =
            totalMinutes % 60;


        if (
            hours > 0
        ) {

            if (
                minutes > 0
            ) {

                return (
                    hours
                    + ' hr '
                    + minutes
                    + ' min'
                );

            }


            return (
                hours
                + ' hr'
            );

        }


        return (
            totalMinutes
            + ' min'
        );

    }


    function setRouteStatus(
        message,
        type = 'normal'
    ) {

        const element =
            document.getElementById(
                'routeStatus'
            );


        if (!element) {
            return;
        }


        element.textContent =
            message;


        if (
            type === 'success'
        ) {

            element.className =
                'mt-4 rounded-xl bg-green-50 dark:bg-green-950/30 px-4 py-3 text-sm font-semibold text-green-700 dark:text-green-300';

        }
        else if (
            type === 'error'
        ) {

            element.className =
                'mt-4 rounded-xl bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm font-semibold text-red-700 dark:text-red-300';

        }
        else {

            element.className =
                'mt-4 rounded-xl bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-300';

        }

    }


    function updateGoogleMapsLink() {

        const button =
            document.getElementById(
                'googleNavigationButton'
            );


        if (!button) {
            return;
        }


        if (
            !validCoordinate(
                currentDonorLatitude,
                currentDonorLongitude
            ) ||
            !validCoordinate(
                patientLatitude,
                patientLongitude
            )
        ) {

            button.href = '#';

            return;

        }


        const googleMapsUrl =
            'https://www.google.com/maps/dir/?api=1'
            + '&origin='
            + encodeURIComponent(
                currentDonorLatitude
                + ','
                + currentDonorLongitude
            )
            + '&destination='
            + encodeURIComponent(
                patientLatitude
                + ','
                + patientLongitude
            )
            + '&travelmode=driving';


        button.href =
            googleMapsUrl;

    }


    function createPatientMarker() {

        const patientIcon =
            L.divIcon({

                className:
                    'patient-emergency-marker',

                html: `
                    <div style="
                        width:40px;
                        height:40px;
                        background:#dc2626;
                        border:4px solid white;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        box-shadow:0 2px 10px rgba(0,0,0,.35);
                        color:white;
                        font-size:19px;
                    ">
                        🚨
                    </div>
                `,

                iconSize: [40, 40],

                iconAnchor: [20, 20]

            });


        L.marker(
            [
                patientLatitude,
                patientLongitude
            ],
            {
                icon: patientIcon
            }
        )
        .addTo(trackMap)
        .bindPopup(
            '<strong>🚨 Patient Emergency Location</strong><br>Destination'
        );

    }


    function createDonorMarker(
        latitude,
        longitude
    ) {

        const donorIcon =
            L.divIcon({

                className:
                    'donor-current-location-marker',

                html: `
                    <div style="
                        width:40px;
                        height:40px;
                        background:#2563eb;
                        border:4px solid white;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        box-shadow:0 2px 10px rgba(0,0,0,.35);
                        color:white;
                        font-size:18px;
                    ">
                        🩸
                    </div>
                `,

                iconSize: [40, 40],

                iconAnchor: [20, 20]

            });


        donorMarker =
            L.marker(
                [
                    latitude,
                    longitude
                ],
                {
                    icon: donorIcon
                }
            )
            .addTo(trackMap)
            .bindPopup(
                '<strong>🩸 Your Current Location</strong>'
            );

    }


    function initializeTrackingMap() {

        if (
            !validCoordinate(
                patientLatitude,
                patientLongitude
            )
        ) {

            setRouteStatus(
                '⚠ Patient location is invalid.',
                'error'
            );

            return;

        }


        trackMap =
            L.map(
                'donorTrackMap'
            ).setView(
                [
                    patientLatitude,
                    patientLongitude
                ],
                14
            );


        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,

                attribution:
                    '&copy; OpenStreetMap contributors'
            }
        )
        .addTo(trackMap);


        createPatientMarker();


        if (
            validCoordinate(
                initialDonorLatitude,
                initialDonorLongitude
            )
        ) {

            currentDonorLatitude =
                initialDonorLatitude;

            currentDonorLongitude =
                initialDonorLongitude;


            createDonorMarker(
                initialDonorLatitude,
                initialDonorLongitude
            );


            updateGoogleMapsLink();


            fitMapToBothLocations();

        }
        else {

            setRouteStatus(
                '📡 Waiting for your GPS location...'
            );

        }


        setTimeout(
            function () {

                trackMap.invalidateSize();

            },
            300
        );


        if (
            validCoordinate(
                currentDonorLatitude,
                currentDonorLongitude
            )
        ) {

            calculateRoadRoute(
                currentDonorLatitude,
                currentDonorLongitude
            );

        }

    }


    function fitMapToBothLocations() {

        if (
            !trackMap
        ) {

            return;

        }


        const points = [];


        if (
            validCoordinate(
                patientLatitude,
                patientLongitude
            )
        ) {

            points.push([
                patientLatitude,
                patientLongitude
            ]);

        }


        if (
            validCoordinate(
                currentDonorLatitude,
                currentDonorLongitude
            )
        ) {

            points.push([
                currentDonorLatitude,
                currentDonorLongitude
            ]);

        }


        if (
            points.length >= 2
        ) {

            trackMap.fitBounds(
                L.latLngBounds(points),
                {
                    padding: [60, 60],
                    maxZoom: 16
                }
            );

        }

    }


    async function calculateRoadRoute(
        donorLatitude,
        donorLongitude
    ) {

        if (
            !trackMap ||
            !validCoordinate(
                donorLatitude,
                donorLongitude
            ) ||
            !validCoordinate(
                patientLatitude,
                patientLongitude
            )
        ) {

            return;

        }


        if (
            routeRequestInProgress
        ) {

            return;

        }


        routeRequestInProgress = true;


        setRouteStatus(
            '🔄 Calculating the road route from your location to the patient...'
        );


        const url =
            'https://router.project-osrm.org/route/v1/driving/'
            + donorLongitude
            + ','
            + donorLatitude
            + ';'
            + patientLongitude
            + ','
            + patientLatitude
            + '?overview=full&geometries=geojson&steps=true';


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


            if (
                !response.ok
            ) {

                throw new Error(
                    'Routing service returned HTTP '
                    + response.status
                );

            }


            const data =
                await response.json();


            if (
                data.code !== 'Ok' ||
                !data.routes ||
                !data.routes.length
            ) {

                throw new Error(
                    'No road route was found.'
                );

            }


            const route =
                data.routes[0];


            const distanceElement =
                document.getElementById(
                    'routeDistance'
                );


            const etaElement =
                document.getElementById(
                    'routeEta'
                );


            if (
                distanceElement
            ) {

                distanceElement.textContent =
                    formatDistance(
                        route.distance
                    );

            }


            if (
                etaElement
            ) {

                etaElement.textContent =
                    formatDuration(
                        route.duration
                    );

            }


            if (
                routeCasing
            ) {

                trackMap.removeLayer(
                    routeCasing
                );

                routeCasing = null;

            }


            if (
                routeLine
            ) {

                trackMap.removeLayer(
                    routeLine
                );

                routeLine = null;

            }


            routeCasing =
                L.geoJSON(
                    route.geometry,
                    {
                        style: {
                            color: '#ffffff',
                            weight: 11,
                            opacity: 0.9
                        }
                    }
                )
                .addTo(trackMap);


            routeLine =
                L.geoJSON(
                    route.geometry,
                    {
                        style: {
                            color: '#2563eb',
                            weight: 7,
                            opacity: 0.95,
                            lineCap: 'round',
                            lineJoin: 'round'
                        }
                    }
                )
                .addTo(trackMap);


            routeLine.bindPopup(
                '<strong>🔵 Navigation Route</strong><br>'
                + formatDistance(route.distance)
                + ' • '
                + formatDuration(route.duration)
            );


            const routeBounds =
                routeLine.getBounds();


            if (
                routeBounds.isValid()
            ) {

                trackMap.fitBounds(
                    routeBounds,
                    {
                        padding: [60, 60],
                        maxZoom: 17
                    }
                );

            }


            lastRouteLatitude =
                donorLatitude;

            lastRouteLongitude =
                donorLongitude;


            setRouteStatus(
                '✓ Road route ready. Follow the blue line to the patient.',
                'success'
            );

        }
        catch (error) {

            console.error(
                'Route calculation error:',
                error
            );


            setRouteStatus(
                '⚠ Unable to calculate the road route right now. You can still use Start Navigation.',
                'error'
            );

        }
        finally {

            routeRequestInProgress =
                false;

        }

    }


    function distanceBetweenCoordinates(
        latitude1,
        longitude1,
        latitude2,
        longitude2
    ) {

        const earthRadius =
            6371;


        const latDifference =
            (
                latitude2 -
                latitude1
            ) *
            Math.PI /
            180;


        const lonDifference =
            (
                longitude2 -
                longitude1
            ) *
            Math.PI /
            180;


        const a =
            Math.sin(
                latDifference / 2
            ) *
            Math.sin(
                latDifference / 2
            ) +
            Math.cos(
                latitude1 *
                Math.PI /
                180
            ) *
            Math.cos(
                latitude2 *
                Math.PI /
                180
            ) *
            Math.sin(
                lonDifference / 2
            ) *
            Math.sin(
                lonDifference / 2
            );


        const c =
            2 *
            Math.atan2(
                Math.sqrt(a),
                Math.sqrt(1 - a)
            );


        return earthRadius * c;

    }


    function updateDonorLocation(
        position
    ) {

        const latitude =
            Number(
                position.coords.latitude
            );

        const longitude =
            Number(
                position.coords.longitude
            );


        if (
            !validCoordinate(
                latitude,
                longitude
            )
        ) {

            locationError({
                message:
                    'Invalid GPS coordinates.'
            });

            return;

        }


        currentDonorLatitude =
            latitude;

        currentDonorLongitude =
            longitude;


        const status =
            document.getElementById(
                'locationStatus'
            );


        if (
            status
        ) {

            status.textContent =
                '📡 Location updating...';

            status.className =
                'inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold';

        }


        if (
            donorMarker
        ) {

            donorMarker.setLatLng([
                latitude,
                longitude
            ]);

        }
        else {

            createDonorMarker(
                latitude,
                longitude
            );

        }


        updateGoogleMapsLink();


        fetch(
            updateLocationUrl,
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json',

                    'X-CSRF-TOKEN':
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute(
                                'content'
                            )
                },

                body: JSON.stringify({

                    latitude:
                        latitude,

                    longitude:
                        longitude

                })
            }
        )
        .then(
            response => {

                if (
                    !response.ok
                ) {

                    throw new Error(
                        'Location update failed with HTTP '
                        + response.status
                    );

                }

                return response.json();

            }
        )
        .then(
            data => {

                if (
                    data.success
                ) {

                    if (
                        status
                    ) {

                        status.textContent =
                            '✓ Live location updated';

                        status.className =
                            'inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold';

                    }

                }

            }
        )
        .catch(
            error => {

                console.error(
                    'Location update error:',
                    error
                );


                if (
                    status
                ) {

                    status.textContent =
                        '⚠ Location update failed';

                    status.className =
                        'inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold';

                }

            }
        );


        let shouldRecalculate =
            false;


        if (
            !validCoordinate(
                lastRouteLatitude,
                lastRouteLongitude
            )
        ) {

            shouldRecalculate =
                true;

        }
        else {

            const movement =
                distanceBetweenCoordinates(
                    lastRouteLatitude,
                    lastRouteLongitude,
                    latitude,
                    longitude
                );


            if (
                movement >= 0.05
            ) {

                shouldRecalculate =
                    true;

            }

        }


        if (
            shouldRecalculate &&
            !routeRequestInProgress
        ) {

            calculateRoadRoute(
                latitude,
                longitude
            );

        }

    }


    function locationError(
        error
    ) {

        console.error(
            'GPS error:',
            error
        );


        const status =
            document.getElementById(
                'locationStatus'
            );


        if (
            status
        ) {

            status.textContent =
                '⚠ GPS unavailable';

            status.className =
                'inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold';

        }

    }


    function startLocationTracking() {

        if (
            !navigator.geolocation
        ) {

            const status =
                document.getElementById(
                    'locationStatus'
                );


            if (
                status
            ) {

                status.textContent =
                    '⚠ GPS not supported by browser';

                status.className =
                    'inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold';

            }


            return;

        }


        watchId =
            navigator.geolocation.watchPosition(
                updateDonorLocation,
                locationError,
                {
                    enableHighAccuracy: true,

                    maximumAge: 5000,

                    timeout: 15000
                }
            );

    }


    const navigationButton =
        document.getElementById(
            'googleNavigationButton'
        );


    if (
        navigationButton
    ) {

        navigationButton.addEventListener(
            'click',
            function (event) {

                if (
                    navigationButton.href ===
                    window.location.href + '#'
                ) {

                    event.preventDefault();

                    alert(
                        'Waiting for a valid donor GPS location. Please allow location access first.'
                    );

                }

            }
        );

    }


    if (
        document.readyState ===
        'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                initializeTrackingMap();

                startLocationTracking();

            }
        );

    }
    else {

        initializeTrackingMap();

        startLocationTracking();

    }

</script>
```

</x-app-layout>
