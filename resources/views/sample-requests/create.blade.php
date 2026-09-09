<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .patient-map-pin {
        align-items: center;
        background: #7c3aed;
        border: 3px solid #fff;
        border-radius: 9999px 9999px 9999px 0;
        box-shadow: 0 3px 10px rgba(15, 23, 42, .35);
        color: #fff;
        display: flex;
        font-size: 16px;
        height: 38px;
        justify-content: center;
        transform: rotate(-45deg);
        width: 38px;
    }

    .patient-map-pin span {
        transform: rotate(45deg);
    }

    /*
     * Important:
     * Leaflet needs an actual height on the map container.
     */
    #request-location-map {
        height: 380px;
        width: 100%;
        min-height: 380px;
        z-index: 1;
    }

    .leaflet-container {
        font-family: inherit;
    }
</style>

<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Request Blood Sample
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Submit a blood sample request and choose how you want
                your sample to be collected.
            </p>
        </div>

    </x-slot>


    <div class="py-10 bg-gray-50 min-h-screen">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <div class="font-semibold text-red-800 mb-2">
                        Please fix the following:
                    </div>

                    <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                id="sample-request-form"
                method="POST"
                action="{{ route('sample-requests.store') }}"
                class="space-y-6"
            >

                @csrf


                {{-- ===================================================== --}}
                {{-- SAMPLE INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

                    <div class="px-6 py-5 border-b border-gray-200">

                        <h3 class="text-lg font-bold text-gray-900">
                            Sample Information
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Tell us what type of blood sample you require.
                        </p>

                    </div>


                    <div class="p-6 space-y-5">

                        {{-- SAMPLE TYPE --}}

                        <div>

                            <label
                                for="sample_type"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Sample Type
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="sample_type"
                                name="sample_type"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option value="">
                                    Select Sample Type
                                </option>

                                <option
                                    value="Whole Blood"
                                    {{ old('sample_type') === 'Whole Blood' ? 'selected' : '' }}
                                >
                                    Whole Blood
                                </option>

                                <option
                                    value="Serum"
                                    {{ old('sample_type') === 'Serum' ? 'selected' : '' }}
                                >
                                    Serum
                                </option>

                                <option
                                    value="Plasma"
                                    {{ old('sample_type') === 'Plasma' ? 'selected' : '' }}
                                >
                                    Plasma
                                </option>

                            </select>

                            @error('sample_type')
                                <div class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- BLOOD TYPE --}}

                        <div>

                            <label
                                for="blood_type"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Blood Type
                            </label>

                            <select
                                id="blood_type"
                                name="blood_type"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option value="">
                                    Select blood type
                                </option>

                                @foreach([
                                    'A+',
                                    'A-',
                                    'B+',
                                    'B-',
                                    'AB+',
                                    'AB-',
                                    'O+',
                                    'O-'
                                ] as $bloodType)

                                    <option
                                        value="{{ $bloodType }}"
                                        {{ old('blood_type') === $bloodType ? 'selected' : '' }}
                                    >
                                        {{ $bloodType }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- NOTES --}}

                        <div>

                            <label
                                for="notes"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Medical Notes
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                placeholder="Add any relevant information..."
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >{{ old('notes') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- COLLECTION METHOD --}}
                {{-- ===================================================== --}}

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

                    <div class="px-6 py-5 border-b border-gray-200">

                        <h3 class="text-lg font-bold text-gray-900">
                            How would you like your sample collected?
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Choose your collection method before submitting the request.
                        </p>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- NORMAL COLLECTION --}}

                            <label
                                id="center-option"
                                class="relative cursor-pointer rounded-2xl border-2 p-5 transition hover:border-blue-400"
                            >

                                <input
                                    type="radio"
                                    name="collection_method"
                                    value="center"
                                    class="absolute top-5 right-5"
                                    {{ old('collection_method', 'center') === 'center' ? 'checked' : '' }}
                                    onchange="updateCollectionMethod()"
                                >

                                <div class="text-3xl">
                                    🏥
                                </div>

                                <div class="font-bold text-gray-900 mt-3">
                                    Collection Center
                                </div>

                                <p class="text-sm text-gray-500 mt-2">
                                    Visit a collection center and have your
                                    sample collected there.
                                </p>

                            </label>


                            {{-- HOME COLLECTION --}}

                            <label
                                id="home-option"
                                class="relative cursor-pointer rounded-2xl border-2 p-5 transition hover:border-purple-400"
                            >

                                <input
                                    type="radio"
                                    name="collection_method"
                                    value="home"
                                    class="absolute top-5 right-5"
                                    {{ old('collection_method') === 'home' ? 'checked' : '' }}
                                    onchange="updateCollectionMethod()"
                                >

                                <div class="text-3xl">
                                    🏠
                                </div>

                                <div class="font-bold text-gray-900 mt-3">
                                    Home Collection
                                </div>

                                <p class="text-sm text-gray-500 mt-2">
                                    A sample collector will travel to your
                                    address and collect the sample.
                                </p>

                            </label>

                        </div>


                        @error('collection_method')

                            <div class="text-sm text-red-600 mt-3">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- HOME COLLECTION DETAILS --}}
                {{-- ===================================================== --}}

                <div
                    id="home-collection-fields"
                    class="bg-white rounded-2xl border border-purple-200 shadow-sm hidden"
                >

                    <div class="px-6 py-5 border-b border-purple-100 bg-purple-50 rounded-t-2xl">

                        <div class="flex items-center gap-3">

                            <div class="text-2xl">
                                🏠
                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-purple-900">
                                    Home Collection Details
                                </h3>

                                <p class="text-sm text-purple-700 mt-1">
                                    Tell the collector where and when to visit you.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-6 space-y-5">

                        {{-- ADDRESS --}}

                        <div>

                            <label
                                for="address"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Collection Address
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="address"
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="House, road, area, city"
                                class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            >

                            @error('address')

                                <div class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- DATE --}}

                            <div>

                                <label
                                    for="preferred_date"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Preferred Date
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="preferred_date"
                                    type="date"
                                    name="preferred_date"
                                    value="{{ old('preferred_date') }}"
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                >

                                @error('preferred_date')

                                    <div class="text-sm text-red-600 mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- TIME --}}

                            <div>

                                <label
                                    for="preferred_time"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Preferred Time
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="preferred_time"
                                    name="preferred_time"
                                    class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                >

                                    <option value="">
                                        Select time
                                    </option>

                                    <option
                                        value="Morning (8 AM - 12 PM)"
                                        {{ old('preferred_time') === 'Morning (8 AM - 12 PM)' ? 'selected' : '' }}
                                    >
                                        Morning (8 AM - 12 PM)
                                    </option>

                                    <option
                                        value="Afternoon (12 PM - 4 PM)"
                                        {{ old('preferred_time') === 'Afternoon (12 PM - 4 PM)' ? 'selected' : '' }}
                                    >
                                        Afternoon (12 PM - 4 PM)
                                    </option>

                                    <option
                                        value="Evening (4 PM - 8 PM)"
                                        {{ old('preferred_time') === 'Evening (4 PM - 8 PM)' ? 'selected' : '' }}
                                    >
                                        Evening (4 PM - 8 PM)
                                    </option>

                                </select>

                                @error('preferred_time')

                                    <div class="text-sm text-red-600 mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- INSTRUCTIONS --}}

                        <div>

                            <label
                                for="instructions"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Instructions for Collector
                            </label>

                            <textarea
                                id="instructions"
                                name="instructions"
                                rows="3"
                                placeholder="Example: Call when you arrive, apartment 4B..."
                                class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            >{{ old('instructions') }}</textarea>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- GPS LOCATION --}}
                        {{-- ===================================================== --}}

                        <div class="overflow-hidden rounded-2xl border border-purple-200 bg-purple-50">

                            <div class="flex flex-col gap-3 border-b border-purple-200 p-4 sm:flex-row sm:items-center sm:justify-between">

                                <div>

                                    <div class="font-semibold text-purple-950">
                                        📍 Exact GPS Location
                                    </div>

                                    <div
                                        id="location-status"
                                        class="text-sm text-purple-700 mt-1"
                                    >
                                        Click the map to drop a pin, or use your current location.
                                    </div>

                                </div>


                                <button
                                    type="button"
                                    onclick="getCurrentLocation()"
                                    id="current-location-button"
                                    class="inline-flex justify-center rounded-lg bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-800"
                                >
                                    Use Current Location
                                </button>

                            </div>


                            <div class="bg-white p-3">

                                <div
                                    id="request-location-map"
                                    class="w-full rounded-xl bg-gray-200"
                                ></div>

                                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 px-1 text-xs text-gray-500">

                                    <span id="selected-coordinates">
                                        No map pin selected.
                                    </span>

                                    <button
                                        type="button"
                                        id="clear-map-pin"
                                        class="hidden font-semibold text-purple-700 hover:text-purple-900"
                                    >
                                        Clear pin
                                    </button>

                                </div>

                            </div>


                            <input
                                id="latitude"
                                type="hidden"
                                name="latitude"
                                value="{{ old('latitude') }}"
                            >

                            <input
                                id="longitude"
                                type="hidden"
                                name="longitude"
                                value="{{ old('longitude') }}"
                            >

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- SUBMIT --}}
                {{-- ===================================================== --}}

                <div class="flex items-center justify-end gap-3">

                    <a
                        href="{{ route('sample-requests.patient.index') }}"
                        class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold shadow-sm hover:bg-blue-700"
                    >
                        Submit Request
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- LEAFLET JAVASCRIPT --}}
    {{-- ===================================================== --}}

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>

        let requestLocationMap = null;
        let requestLocationMarker = null;


        /*
         * Custom purple map pin.
         */
        function mapPinIcon() {

            return L.divIcon({

                className: '',

                html: '<div class="patient-map-pin"><span>●</span></div>',

                iconSize: [38, 38],

                iconAnchor: [19, 38]

            });

        }


        /*
         * Set / update selected location.
         */
        function setMapLocation(latitude, longitude, accuracy = null) {

            const lat = Number(latitude);
            const lng = Number(longitude);

            if (
                !Number.isFinite(lat) ||
                !Number.isFinite(lng)
            ) {
                return;
            }


            document.getElementById('latitude').value =
                lat.toFixed(7);

            document.getElementById('longitude').value =
                lng.toFixed(7);


            /*
             * Create marker if it doesn't exist.
             */
            if (!requestLocationMarker) {

                requestLocationMarker = L.marker(
                    [lat, lng],
                    {
                        draggable: true,
                        icon: mapPinIcon()
                    }
                ).addTo(requestLocationMap);


                /*
                 * Update coordinates when user drags marker.
                 */
                requestLocationMarker.on(
                    'dragend',
                    function () {

                        const point =
                            requestLocationMarker.getLatLng();

                        setMapLocation(
                            point.lat,
                            point.lng
                        );

                    }
                );

            } else {

                requestLocationMarker.setLatLng(
                    [lat, lng]
                );

            }


            /*
             * Move map to selected location.
             */
            requestLocationMap.setView(
                [lat, lng],
                17,
                {
                    animate: true
                }
            );


            document.getElementById(
                'selected-coordinates'
            ).textContent =
                'Selected: ' +
                lat.toFixed(6) +
                ', ' +
                lng.toFixed(6);


            document.getElementById(
                'clear-map-pin'
            ).classList.remove('hidden');


            const status =
                document.getElementById('location-status');


            if (accuracy !== null) {

                status.textContent =
                    '✓ GPS found (about ' +
                    Math.round(accuracy) +
                    ' metres accuracy). Drag the pin if needed.';

            } else {

                status.textContent =
                    '✓ Map pin selected. Drag it to your exact entrance.';

            }

        }


        /*
         * Initialize Leaflet map.
         */
        function initializeRequestMap() {

            const mapElement =
                document.getElementById(
                    'request-location-map'
                );


            if (!mapElement) {
                return;
            }


            /*
             * Prevent duplicate initialization.
             */
            if (requestLocationMap) {
                return;
            }


            /*
             * Default location: Dhaka.
             */
            requestLocationMap =
                L.map(
                    'request-location-map',
                    {
                        zoomControl: true
                    }
                ).setView(
                    [23.8103, 90.4125],
                    12
                );


            /*
             * OpenStreetMap tiles.
             */
            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,

                    attribution:
                        '&copy; OpenStreetMap contributors'
                }
            ).addTo(requestLocationMap);


            /*
             * Click anywhere on map to select location.
             */
            requestLocationMap.on(
                'click',
                function (event) {

                    setMapLocation(
                        event.latlng.lat,
                        event.latlng.lng
                    );

                }
            );


            /*
             * Restore old coordinates after
             * validation failure.
             */
            const oldLatitude =
                document.getElementById('latitude').value;

            const oldLongitude =
                document.getElementById('longitude').value;


            if (
                oldLatitude &&
                oldLongitude
            ) {

                setMapLocation(
                    oldLatitude,
                    oldLongitude
                );

            }


            /*
             * Clear selected pin.
             */
            document
                .getElementById('clear-map-pin')
                .addEventListener(
                    'click',
                    function () {

                        document.getElementById(
                            'latitude'
                        ).value = '';

                        document.getElementById(
                            'longitude'
                        ).value = '';


                        document.getElementById(
                            'selected-coordinates'
                        ).textContent =
                            'No map pin selected.';


                        document.getElementById(
                            'location-status'
                        ).textContent =
                            'Click the map to drop a pin, or use your current location.';


                        this.classList.add('hidden');


                        if (requestLocationMarker) {

                            requestLocationMap.removeLayer(
                                requestLocationMarker
                            );

                            requestLocationMarker = null;

                        }

                    }
                );


            /*
             * Give Leaflet time to calculate its size.
             */
            setTimeout(
                function () {

                    requestLocationMap.invalidateSize();

                },
                300
            );

        }


        /*
         * Show/hide home collection fields.
         */
        function updateCollectionMethod() {

            const selected =
                document.querySelector(
                    'input[name="collection_method"]:checked'
                );


            const homeFields =
                document.getElementById(
                    'home-collection-fields'
                );


            const homeOption =
                document.getElementById(
                    'home-option'
                );


            const centerOption =
                document.getElementById(
                    'center-option'
                );


            const address =
                document.getElementById(
                    'address'
                );


            const preferredDate =
                document.getElementById(
                    'preferred_date'
                );


            const preferredTime =
                document.getElementById(
                    'preferred_time'
                );


            if (
                selected &&
                selected.value === 'home'
            ) {

                homeFields.classList.remove(
                    'hidden'
                );


                homeOption.classList.add(
                    'border-purple-500',
                    'bg-purple-50'
                );


                centerOption.classList.remove(
                    'border-blue-500',
                    'bg-blue-50'
                );


                address.required = true;

                preferredDate.required = true;

                preferredTime.required = true;


                /*
                 * Leaflet maps can render incorrectly when
                 * their parent is hidden during initialization.
                 */
                setTimeout(
                    function () {

                        if (requestLocationMap) {

                            requestLocationMap.invalidateSize();

                        }

                    },
                    200
                );


            } else {

                homeFields.classList.add(
                    'hidden'
                );


                centerOption.classList.add(
                    'border-blue-500',
                    'bg-blue-50'
                );


                homeOption.classList.remove(
                    'border-purple-500',
                    'bg-purple-50'
                );


                address.required = false;

                preferredDate.required = false;

                preferredTime.required = false;

            }

        }


        /*
         * Get user's current GPS location.
         */
        function getCurrentLocation() {

            const status =
                document.getElementById(
                    'location-status'
                );


            const button =
                document.getElementById(
                    'current-location-button'
                );


            if (!navigator.geolocation) {

                status.textContent =
                    'Geolocation is not supported by this browser.';

                return;

            }


            status.textContent =
                'Getting your current GPS location...';


            button.disabled = true;

            button.textContent =
                'Finding location...';


            navigator.geolocation.getCurrentPosition(

                function (position) {

                    setMapLocation(

                        position.coords.latitude,

                        position.coords.longitude,

                        position.coords.accuracy

                    );


                    button.disabled = false;

                    button.textContent =
                        'Use Current Location';

                },


                function (error) {

                    button.disabled = false;

                    button.textContent =
                        'Use Current Location';


                    let message =
                        'Could not access GPS. ';


                    switch (error.code) {

                        case error.PERMISSION_DENIED:

                            message +=
                                'Location permission was denied. Please allow location access in your browser, or click your location on the map.';

                            break;


                        case error.POSITION_UNAVAILABLE:

                            message +=
                                'Your location is currently unavailable. Click your location on the map instead.';

                            break;


                        case error.TIMEOUT:

                            message +=
                                'The GPS request timed out. Please try again or click your location on the map.';

                            break;


                        default:

                            message +=
                                'Click your location on the map instead.';

                    }


                    status.textContent =
                        message;

                },

                {
                    enableHighAccuracy: true,

                    timeout: 15000,

                    maximumAge: 0

                }

            );

        }


        /*
         * Page initialization.
         */
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                initializeRequestMap();

                updateCollectionMethod();


                /*
                 * Validate GPS before submitting
                 * a home collection request.
                 */
                document
                    .getElementById(
                        'sample-request-form'
                    )
                    .addEventListener(
                        'submit',
                        function (event) {

                            const selected =
                                document.querySelector(
                                    'input[name="collection_method"]:checked'
                                );


                            if (
                                selected &&
                                selected.value === 'home' &&
                                (
                                    !document.getElementById('latitude').value ||
                                    !document.getElementById('longitude').value
                                )
                            ) {

                                event.preventDefault();


                                document.getElementById(
                                    'location-status'
                                ).textContent =
                                    'Please click your home on the map or use your current GPS before submitting.';


                                document.getElementById(
                                    'home-collection-fields'
                                ).scrollIntoView(
                                    {
                                        behavior: 'smooth',
                                        block: 'center'
                                    }
                                );


                                /*
                                 * Make sure map is correctly
                                 * rendered after scrolling.
                                 */
                                setTimeout(
                                    function () {

                                        if (requestLocationMap) {

                                            requestLocationMap.invalidateSize();

                                        }

                                    },
                                    500
                                );

                            }

                        }
                    );


                /*
                 * Fix map rendering if browser window
                 * changes size.
                 */
                window.addEventListener(
                    'resize',
                    function () {

                        if (requestLocationMap) {

                            requestLocationMap.invalidateSize();

                        }

                    }
                );

            }
        );

    </script>

</x-app-layout>
