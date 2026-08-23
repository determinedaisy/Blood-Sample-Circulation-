<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Create Blood Sample Request
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Create a blood sample request for a registered patient.
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                <form
                    method="POST"
                    action="{{ route('sample-requests.receptionist.store') }}"
                >
                    @csrf

                    <div class="space-y-5">

                        {{-- Patient --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Registered Patient
                            </label>

                            <select
                                name="patient_id"
                                required
                                class="w-full rounded-xl border-gray-300"
                            >
                                <option value="">
                                    Select patient
                                </option>

                                @foreach($patients as $patient)
                                    <option
                                        value="{{ $patient->id }}"
                                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}
                                    >
                                        {{ $patient->name }}
                                        — {{ $patient->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Sample Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Sample Type
                            </label>

                            <select
                                name="sample_type"
                                required
                                class="w-full rounded-xl border-gray-300"
                            >
                                <option value="">
                                    Select sample type
                                </option>

                                <option
                                    value="Whole Blood"
                                    {{ old('sample_type') === 'Whole Blood' ? 'selected' : '' }}
                                >
                                    Whole Blood
                                </option>

                                <option
                                    value="Plasma"
                                    {{ old('sample_type') === 'Plasma' ? 'selected' : '' }}
                                >
                                    Plasma
                                </option>

                                <option
                                    value="Serum"
                                    {{ old('sample_type') === 'Serum' ? 'selected' : '' }}
                                >
                                    Serum
                                </option>
                            </select>
                        </div>


                        {{-- Blood Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Blood Type
                            </label>

                            <select
                                name="blood_type"
                                class="w-full rounded-xl border-gray-300"
                            >
                                <option value="">
                                    Select blood type
                                </option>

                                @foreach([
                                    'A+', 'A-',
                                    'B+', 'B-',
                                    'AB+', 'AB-',
                                    'O+', 'O-'
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


                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                class="w-full rounded-xl border-gray-300"
                                placeholder="Optional notes..."
                            >{{ old('notes') }}</textarea>
                        </div>


                        {{-- Submit --}}
                        <div class="flex justify-end">

                            <button
                                type="submit"
                                class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700"
                            >
                                Create Sample Request
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>