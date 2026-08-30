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

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">


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



                        {{-- GPS --}}

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                <div>

                                    <div class="font-semibold text-gray-900">
                                        📍 GPS Location
                                    </div>

                                    <div
                                        id="location-status"
                                        class="text-sm text-gray-500 mt-1"
                                    >
                                        Optional — helps the collector locate you.
                                    </div>

                                </div>


                                <button
                                    type="button"
                                    onclick="getCurrentLocation()"
                                    class="inline-flex justify-center px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800"
                                >
                                    Use Current Location
                                </button>

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



    <script>

        function updateCollectionMethod() {

            const selected = document.querySelector(
                'input[name="collection_method"]:checked'
            );

            const homeFields =
                document.getElementById('home-collection-fields');

            const homeOption =
                document.getElementById('home-option');

            const centerOption =
                document.getElementById('center-option');

            const address =
                document.getElementById('address');

            const preferredDate =
                document.getElementById('preferred_date');

            const preferredTime =
                document.getElementById('preferred_time');


            if (selected && selected.value === 'home') {

                homeFields.classList.remove('hidden');

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

            } else {

                homeFields.classList.add('hidden');

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


        function getCurrentLocation() {

            const status =
                document.getElementById('location-status');

            if (!navigator.geolocation) {

                status.textContent =
                    'Geolocation is not supported by this browser.';

                return;
            }


            status.textContent =
                'Getting your location...';


            navigator.geolocation.getCurrentPosition(

                function(position) {

                    document.getElementById('latitude').value =
                        position.coords.latitude;

                    document.getElementById('longitude').value =
                        position.coords.longitude;

                    status.textContent =
                        '✓ Current location saved successfully.';
                },

                function() {

                    status.textContent =
                        'Could not access your location. You can still submit using your address.';
                }

            );
        }


        document.addEventListener(
            'DOMContentLoaded',
            updateCollectionMethod
        );

    </script>

</x-app-layout>