<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Donor & Blood Application
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Submit your information for doctor review.
            </p>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())

                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">

                    <h3 class="font-semibold text-red-800 mb-2">
                        Please correct the following:
                    </h3>

                    <ul class="list-disc ml-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('donor-applications.store') }}"
                class="space-y-6"
            >

                @csrf

                <!-- Application Type -->

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        What do you want to do?
                    </h3>

                    <p class="text-sm text-gray-500 mt-1 mb-5">
                        Your application will be reviewed by a doctor.
                    </p>

                    <div class="grid md:grid-cols-2 gap-4">

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="request_type"
                                value="donate"
                                class="peer sr-only"
                                {{ old('request_type', 'donate') === 'donate' ? 'checked' : '' }}
                            >

                            <div class="border-2 border-gray-200 rounded-xl p-5 peer-checked:border-red-500 peer-checked:bg-red-50">

                                <div class="text-2xl mb-2">
                                    🩸
                                </div>

                                <div class="font-semibold text-gray-900">
                                    Donate Blood
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    Register as a blood donor and have your eligibility reviewed.
                                </div>

                            </div>

                        </label>

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="request_type"
                                value="request"
                                class="peer sr-only"
                                {{ old('request_type') === 'request' ? 'checked' : '' }}
                            >

                            <div class="border-2 border-gray-200 rounded-xl p-5 peer-checked:border-blue-500 peer-checked:bg-blue-50">

                                <div class="text-2xl mb-2">
                                    🏥
                                </div>

                                <div class="font-semibold text-gray-900">
                                    Request Blood
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    Submit a blood requirement for doctor review.
                                </div>

                            </div>

                        </label>

                    </div>

                </div>

                <!-- Basic Information -->

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-5">
                        Basic Information
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Blood Group *
                            </label>

                            <select
                                name="blood_group"
                                required
                                class="w-full rounded-lg border-gray-300"
                            >
                                <option value="">Select blood group</option>

                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)

                                    <option
                                        value="{{ $group }}"
                                        {{ old('blood_group', $patient->blood_group) === $group ? 'selected' : '' }}
                                    >
                                        {{ $group }}
                                    </option>

                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $patient->phone) }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Age
                            </label>

                            <input
                                type="number"
                                name="age"
                                min="16"
                                max="100"
                                value="{{ old('age', $patient->date_of_birth?->age) }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Gender
                            </label>

                            <select
                                name="gender"
                                class="w-full rounded-lg border-gray-300"
                            >
                                <option value="">Select</option>

                                <option
                                    value="male"
                                    {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}
                                >
                                    Male
                                </option>

                                <option
                                    value="female"
                                    {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}
                                >
                                    Female
                                </option>

                                <option
                                    value="other"
                                    {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>
                        </div>

                    </div>

                </div>

                <!-- Medical Information -->

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
                        Medical Information
                    </h3>

                    <p class="text-sm text-gray-500 mb-5">
                        Provide accurate information. The doctor will make the final decision.
                    </p>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Height (cm)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="height"
                                value="{{ old('height') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Weight (kg)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="weight"
                                value="{{ old('weight') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Blood Pressure — Systolic
                            </label>

                            <input
                                type="number"
                                name="blood_pressure_systolic"
                                value="{{ old('blood_pressure_systolic') }}"
                                placeholder="e.g. 120"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Blood Pressure — Diastolic
                            </label>

                            <input
                                type="number"
                                name="blood_pressure_diastolic"
                                value="{{ old('blood_pressure_diastolic') }}"
                                placeholder="e.g. 80"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Hemoglobin
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                name="hemoglobin"
                                value="{{ old('hemoglobin') }}"
                                placeholder="g/dL"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Previous Donation Count
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="previous_donation_count"
                                value="{{ old('previous_donation_count', 0) }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Previous Donation Date
                            </label>

                            <input
                                type="date"
                                name="previous_donation_date"
                                value="{{ old('previous_donation_date') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Smoking Status
                            </label>

                            <select
                                name="smoking_status"
                                class="w-full rounded-lg border-gray-300"
                            >
                                <option value="">Prefer not to say</option>
                                <option value="never">Never</option>
                                <option value="occasionally">Occasionally</option>
                                <option value="regularly">Regularly</option>
                                <option value="former">Former smoker</option>
                            </select>

                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Pregnancy Status
                        </label>

                        <select
                            name="pregnancy_status"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="">Not applicable / Prefer not to say</option>
                            <option value="not_pregnant">Not pregnant</option>
                            <option value="pregnant">Pregnant</option>
                            <option value="recently_pregnant">Recently pregnant</option>
                        </select>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Medical Conditions
                        </label>

                        <textarea
                            name="medical_conditions"
                            rows="3"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="List any medical conditions..."
                        >{{ old('medical_conditions') }}</textarea>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Current Medications
                        </label>

                        <textarea
                            name="current_medications"
                            rows="3"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="List any current medications..."
                        >{{ old('current_medications') }}</textarea>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Recent Illness or Surgery
                        </label>

                        <textarea
                            name="recent_illness_or_surgery"
                            rows="3"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="Describe any recent illness, surgery, hospitalization, etc."
                        >{{ old('recent_illness_or_surgery') }}</textarea>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Allergies
                        </label>

                        <textarea
                            name="allergies"
                            rows="2"
                            class="w-full rounded-lg border-gray-300"
                        >{{ old('allergies') }}</textarea>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Additional Medical Information
                        </label>

                        <textarea
                            name="additional_medical_information"
                            rows="3"
                            class="w-full rounded-lg border-gray-300"
                        >{{ old('additional_medical_information') }}</textarea>

                    </div>

                </div>

                <!-- Blood Request -->

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
                        Blood Request Information
                    </h3>

                    <p class="text-sm text-gray-500 mb-5">
                        Complete these fields if you are requesting blood.
                    </p>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Required Units
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="20"
                                name="requested_units"
                                value="{{ old('requested_units') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Urgency
                            </label>

                            <select
                                name="urgency"
                                class="w-full rounded-lg border-gray-300"
                            >
                                <option value="">Select</option>
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Hospital Name
                            </label>

                            <input
                                type="text"
                                name="hospital_name"
                                value="{{ old('hospital_name') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Required Date
                            </label>

                            <input
                                type="date"
                                name="required_date"
                                value="{{ old('required_date') }}"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Hospital Location
                        </label>

                        <textarea
                            name="hospital_location"
                            rows="2"
                            class="w-full rounded-lg border-gray-300"
                        >{{ old('hospital_location') }}</textarea>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium mb-1">
                            Reason / Additional Request Details
                        </label>

                        <textarea
                            name="request_reason"
                            rows="4"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="Explain why blood is needed..."
                        >{{ old('request_reason') }}</textarea>

                    </div>

                </div>

                <!-- Submit -->

                <div class="flex justify-end gap-3">

                    <a
                        href="{{ route('donor-applications.index') }}"
                        class="px-5 py-3 rounded-lg border border-gray-300 bg-white"
                    >
                        My Applications
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700"
                    >
                        Submit for Doctor Review
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>