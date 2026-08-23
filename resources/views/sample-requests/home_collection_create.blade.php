

<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-semibold text-purple-600">
                    Home Sample Collection
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-1">
                    Schedule Home Collection
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Choose when and where you would like your sample collected.
                </p>

            </div>


            <a
                href="{{ route('sample-requests.patient.index') }}"
                class="px-4 py-2 bg-white border border-gray-300
                       rounded-lg text-sm font-semibold
                       text-gray-700 hover:bg-gray-50"
            >
                ← Back
            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ERRORS --}}
            @if($errors->any())

                <div
                    class="mb-6 rounded-xl border
                           border-red-200 bg-red-50
                           px-5 py-4 text-red-800"
                >

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- SAMPLE SUMMARY --}}
            <div
                class="bg-white border border-gray-200
                       rounded-2xl shadow-sm mb-6"
            >

                <div
                    class="px-6 py-5
                           border-b border-gray-200"
                >

                    <h3 class="font-bold text-lg text-gray-900">
                        Sample Information
                    </h3>

                </div>


                <div
                    class="p-6 grid grid-cols-1
                           sm:grid-cols-3 gap-6"
                >

                    <div>

                        <div
                            class="text-xs uppercase
                                   font-semibold
                                   tracking-wide
                                   text-gray-500"
                        >
                            Sample Code
                        </div>

                        <div
                            class="mt-1
                                   font-bold
                                   text-blue-700"
                        >
                            {{ $sampleRequest
                                ->bloodSample
                                ->sample_code
                                ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div
                            class="text-xs uppercase
                                   font-semibold
                                   tracking-wide
                                   text-gray-500"
                        >
                            Sample Type
                        </div>

                        <div
                            class="mt-1
                                   font-semibold
                                   text-gray-900"
                        >
                            {{ $sampleRequest->sample_type }}
                        </div>

                    </div>


                    <div>

                        <div
                            class="text-xs uppercase
                                   font-semibold
                                   tracking-wide
                                   text-gray-500"
                        >
                            Blood Type
                        </div>

                        <div
                            class="mt-1
                                   font-semibold
                                   text-gray-900"
                        >
                            {{ $sampleRequest->blood_type
                                ?? 'Not specified' }}
                        </div>

                    </div>

                </div>

            </div>



            {{-- HOME COLLECTION FORM --}}
            <div
                class="bg-white border border-gray-200
                       rounded-2xl shadow-sm"
            >

                <form
                    method="POST"
                    action="{{ route(
                        'sample-requests.home-collection.store',
                        $sampleRequest
                    ) }}"
                >

                    @csrf


                    <div class="p-6 space-y-7">


                        {{-- ADDRESS --}}
                        <div>

                            <label
                                for="address"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700"
                            >
                                Home Address
                            </label>


                            <textarea
                                id="address"
                                name="address"
                                rows="3"
                                required
                                placeholder="House number, road, area, city..."
                                class="mt-2 block w-full
                                       rounded-lg
                                       border-gray-300
                                       shadow-sm
                                       focus:border-purple-500
                                       focus:ring-purple-500"
                            >{{ old('address') }}</textarea>


                            <p class="text-xs text-gray-500 mt-2">
                                Enter enough information for the collector to find you.
                            </p>

                        </div>



                        {{-- GPS --}}
                        <div
                            class="rounded-xl
                                   bg-purple-50
                                   border border-purple-100
                                   p-5"
                        >

                            <div
                                class="flex flex-col
                                       sm:flex-row
                                       sm:items-center
                                       sm:justify-between
                                       gap-4"
                            >

                                <div>

                                    <div
                                        class="font-semibold
                                               text-purple-900"
                                    >
                                        Current GPS Location
                                    </div>

                                    <p
                                        id="location-status"
                                        class="text-sm
                                               text-purple-700
                                               mt-1"
                                    >
                                        Optional, but useful for route planning and locating your home.
                                    </p>

                                </div>


                                <button
                                    type="button"
                                    onclick="getCurrentLocation()"
                                    class="shrink-0
                                           px-4 py-2
                                           rounded-lg
                                           bg-purple-600
                                           text-white
                                           text-sm
                                           font-semibold
                                           hover:bg-purple-700"
                                >
                                    📍 Use Current Location
                                </button>

                            </div>


                            <input
                                type="hidden"
                                id="latitude"
                                name="latitude"
                                value="{{ old('latitude') }}"
                            >


                            <input
                                type="hidden"
                                id="longitude"
                                name="longitude"
                                value="{{ old('longitude') }}"
                            >

                        </div>



                        {{-- DATE --}}
                        <div>

                            <label
                                for="preferred_date"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700"
                            >
                                Preferred Collection Date
                            </label>


                            <input
                                type="date"
                                id="preferred_date"
                                name="preferred_date"
                                required
                                min="{{ now()->format('Y-m-d') }}"
                                value="{{ old('preferred_date') }}"
                                class="mt-2 block w-full
                                       rounded-lg
                                       border-gray-300
                                       shadow-sm
                                       focus:border-purple-500
                                       focus:ring-purple-500"
                            >

                        </div>



                        {{-- TIME --}}
                        <div>

                            <label
                                for="preferred_time"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700"
                            >
                                Preferred Time Slot
                            </label>


                            <select
                                id="preferred_time"
                                name="preferred_time"
                                required
                                class="mt-2 block w-full
                                       rounded-lg
                                       border-gray-300
                                       shadow-sm
                                       focus:border-purple-500
                                       focus:ring-purple-500"
                            >

                                <option value="">
                                    Select Time Slot
                                </option>


                                <option
                                    value="08:00 AM - 10:00 AM"
                                    @selected(
                                        old('preferred_time')
                                        ===
                                        '08:00 AM - 10:00 AM'
                                    )
                                >
                                    08:00 AM - 10:00 AM
                                </option>


                                <option
                                    value="10:00 AM - 12:00 PM"
                                    @selected(
                                        old('preferred_time')
                                        ===
                                        '10:00 AM - 12:00 PM'
                                    )
                                >
                                    10:00 AM - 12:00 PM
                                </option>


                                <option
                                    value="12:00 PM - 02:00 PM"
                                    @selected(
                                        old('preferred_time')
                                        ===
                                        '12:00 PM - 02:00 PM'
                                    )
                                >
                                    12:00 PM - 02:00 PM
                                </option>


                                <option
                                    value="02:00 PM - 04:00 PM"
                                    @selected(
                                        old('preferred_time')
                                        ===
                                        '02:00 PM - 04:00 PM'
                                    )
                                >
                                    02:00 PM - 04:00 PM
                                </option>


                                <option
                                    value="04:00 PM - 06:00 PM"
                                    @selected(
                                        old('preferred_time')
                                        ===
                                        '04:00 PM - 06:00 PM'
                                    )
                                >
                                    04:00 PM - 06:00 PM
                                </option>

                            </select>

                        </div>



                        {{-- INSTRUCTIONS --}}
                        <div>

                            <label
                                for="instructions"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700"
                            >
                                Collector Instructions

                                <span
                                    class="font-normal
                                           text-gray-400"
                                >
                                    (Optional)
                                </span>

                            </label>


                            <textarea
                                id="instructions"
                                name="instructions"
                                rows="3"
                                placeholder="Apartment number, nearby landmark, call before arriving..."
                                class="mt-2 block w-full
                                       rounded-lg
                                       border-gray-300
                                       shadow-sm
                                       focus:border-purple-500
                                       focus:ring-purple-500"
                            >{{ old('instructions') }}</textarea>

                        </div>


                    </div>



                    <div
                        class="px-6 py-5
                               bg-gray-50
                               border-t
                               border-gray-200
                               flex items-center
                               justify-between"
                    >

                        <div class="text-xs text-gray-500">
                            Admin will assign a collector after reviewing your request.
                        </div>


                        <button
                            type="submit"
                            class="px-6 py-2.5
                                   rounded-lg
                                   bg-purple-600
                                   text-white
                                   font-semibold
                                   hover:bg-purple-700"
                        >
                            Schedule Home Collection
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>



    <script>

        function getCurrentLocation() {

            const status =
                document.getElementById(
                    'location-status'
                );

            if (!navigator.geolocation) {

                status.innerText =
                    'Your browser does not support GPS location.';

                return;
            }


            status.innerText =
                'Getting your current location...';


            navigator.geolocation
                .getCurrentPosition(

                    function(position) {

                        document
                            .getElementById(
                                'latitude'
                            )
                            .value =
                            position
                                .coords
                                .latitude;


                        document
                            .getElementById(
                                'longitude'
                            )
                            .value =
                            position
                                .coords
                                .longitude;


                        status.innerText =
                            '✓ GPS location added successfully.';
                    },


                    function(error) {

                        if (
                            error.code
                            ===
                            error.PERMISSION_DENIED
                        ) {

                            status.innerText =
                                'Location permission was denied. You can still continue using your written address.';

                        } else {

                            status.innerText =
                                'Could not determine your location. You can still continue using your written address.';
                        }

                    }

                );

        }

    </script>

</x-app-layout>