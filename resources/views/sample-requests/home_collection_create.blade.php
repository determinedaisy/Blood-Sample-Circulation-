<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-rose-600">Home Sample Collection</p>
                <h2 class="mt-1 text-2xl font-bold text-gray-900">Choose your collection location</h2>
                <p class="mt-1 text-sm text-gray-500">Add your address, appointment time, and an exact map pin for the collector.</p>
            </div>
            <a href="{{ route('sample-requests.patient.index') }}"
               class="inline-flex w-fit items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Back to requests
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-full max-w-[1500px] px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 grid gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:grid-cols-3">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Sample code</div>
                    <div class="mt-1 font-bold text-rose-700">{{ $sampleRequest->bloodSample->sample_code ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Sample type</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $sampleRequest->sample_type }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Blood type</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $sampleRequest->blood_type ?? 'Not specified' }}</div>
                </div>
            </div>

            <form id="home-collection-form" method="POST"
                  action="{{ route('sample-requests.home-collection.store', $sampleRequest) }}"
                  class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                @csrf

                <div class="grid xl:grid-cols-12">
                    <section class="space-y-6 p-5 sm:p-7 xl:col-span-5">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Appointment details</h3>
                            <p class="mt-1 text-sm text-gray-500">The administrator uses these details to schedule your collector.</p>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700">Complete home address</label>
                            <textarea id="address" name="address" rows="4" required
                                      placeholder="House, road, area, city and a nearby landmark"
                                      class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">{{ old('address') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">The map pin identifies the exact position; the address helps the collector recognize the building.</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="preferred_date" class="block text-sm font-semibold text-gray-700">Preferred date</label>
                                <input type="date" id="preferred_date" name="preferred_date" required
                                       min="{{ now()->format('Y-m-d') }}" value="{{ old('preferred_date') }}"
                                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                            </div>

                            <div>
                                <label for="preferred_time" class="block text-sm font-semibold text-gray-700">Time slot</label>
                                <select id="preferred_time" name="preferred_time" required
                                        class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                    <option value="">Select a time</option>
                                    @foreach([
                                        '08:00 AM - 10:00 AM',
                                        '10:00 AM - 12:00 PM',
                                        '12:00 PM - 02:00 PM',
                                        '02:00 PM - 04:00 PM',
                                        '04:00 PM - 06:00 PM',
                                    ] as $slot)
                                        <option value="{{ $slot }}" @selected(old('preferred_time') === $slot)>{{ $slot }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="instructions" class="block text-sm font-semibold text-gray-700">
                                Collector instructions <span class="font-normal text-gray-400">(optional)</span>
                            </label>
                            <textarea id="instructions" name="instructions" rows="3"
                                      placeholder="Apartment number, gate, landmark, or call-before-arrival note"
                                      class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">{{ old('instructions') }}</textarea>
                        </div>
                    </section>

                    <section class="border-t border-gray-200 bg-slate-50 p-5 sm:p-7 xl:col-span-7 xl:border-l xl:border-t-0">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pin your exact GPS location</h3>
                                <p class="mt-1 text-sm text-gray-500">Use your device location, or click the map and drag the pin.</p>
                            </div>
                            <button type="button" id="use-location-button"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700">
                                <span class="mr-2">⌖</span> Use my current location
                            </button>
                        </div>

                        <div class="mt-5 overflow-hidden rounded-2xl border border-gray-300 bg-gray-200 shadow-inner">
                            <div id="patient-location-map" class="h-[430px] w-full"></div>
                        </div>

                        <div id="location-message" class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            No location selected yet. Press the GPS button or click your home on the map.
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500">
                            <span id="coordinate-text">Latitude and longitude will appear here.</span>
                            <button type="button" id="clear-location-button" class="hidden font-semibold text-rose-600 hover:text-rose-800">Clear pin</button>
                        </div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    </section>
                </div>

                <div class="flex flex-col gap-4 border-t border-gray-200 bg-white px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <p class="text-sm text-gray-500">Your exact pin is used only to organize and navigate the collector's route.</p>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-6 py-3 font-semibold text-white shadow-sm hover:bg-rose-700">
                        Schedule home collection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const defaultLocation = [23.8103, 90.4125];
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const message = document.getElementById('location-message');
            const coordinateText = document.getElementById('coordinate-text');
            const useLocationButton = document.getElementById('use-location-button');
            const clearButton = document.getElementById('clear-location-button');
            const form = document.getElementById('home-collection-form');
            let marker = null;

            const map = L.map('patient-location-map').setView(defaultLocation, 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function setLocation(latitude, longitude, accuracy) {
                const lat = Number(latitude);
                const lng = Number(longitude);

                if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                    return;
                }

                latitudeInput.value = lat.toFixed(7);
                longitudeInput.value = lng.toFixed(7);

                if (!marker) {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    marker.on('dragend', function () {
                        const position = marker.getLatLng();
                        setLocation(position.lat, position.lng);
                    });
                } else {
                    marker.setLatLng([lat, lng]);
                }

                map.setView([lat, lng], 17);
                coordinateText.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                message.className = 'mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800';
                message.textContent = accuracy
                    ? 'Location selected from your device (accuracy about ' + Math.round(accuracy) + ' metres). Drag the pin if needed.'
                    : 'Location selected. Drag the pin to place it exactly at your entrance.';
                clearButton.classList.remove('hidden');
            }

            function clearLocation() {
                latitudeInput.value = '';
                longitudeInput.value = '';
                coordinateText.textContent = 'Latitude and longitude will appear here.';
                message.className = 'mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800';
                message.textContent = 'No location selected yet. Press the GPS button or click your home on the map.';
                clearButton.classList.add('hidden');
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }
                map.setView(defaultLocation, 12);
            }

            map.on('click', function (event) {
                setLocation(event.latlng.lat, event.latlng.lng);
            });

            useLocationButton.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    message.className = 'mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800';
                    message.textContent = 'This browser cannot read GPS. Click your location on the map instead.';
                    return;
                }

                useLocationButton.disabled = true;
                useLocationButton.textContent = 'Finding your location…';
                message.textContent = 'Waiting for your device location permission…';

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        setLocation(position.coords.latitude, position.coords.longitude, position.coords.accuracy);
                        useLocationButton.disabled = false;
                        useLocationButton.innerHTML = '<span class="mr-2">⌖</span> Update my location';
                    },
                    function () {
                        message.className = 'mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800';
                        message.textContent = 'GPS permission was unavailable. Click your home on the map to place the pin manually.';
                        useLocationButton.disabled = false;
                        useLocationButton.innerHTML = '<span class="mr-2">⌖</span> Try GPS again';
                    },
                    { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                );
            });

            clearButton.addEventListener('click', clearLocation);

            form.addEventListener('submit', function (event) {
                if (!latitudeInput.value || !longitudeInput.value) {
                    event.preventDefault();
                    message.className = 'mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800';
                    message.textContent = 'Please select your home location on the map before scheduling.';
                    document.getElementById('patient-location-map').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            if (latitudeInput.value && longitudeInput.value) {
                setLocation(latitudeInput.value, longitudeInput.value);
            }

            window.setTimeout(function () { map.invalidateSize(); }, 200);
        });
    </script>
</x-app-layout>
