<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Review Application
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Review the patient's submitted information before making a decision.
            </p>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))

                <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>

            @endif

            @if ($errors->any())

                <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">

                    <ul class="list-disc ml-5">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- Patient -->

            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-lg font-semibold mb-5">
                    Patient Information
                </h3>

                <div class="grid md:grid-cols-3 gap-5">

                    <div>
                        <span class="text-sm text-gray-500">
                            Name
                        </span>

                        <p class="font-semibold">
                            {{ $donorApplication->patient->user->name }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Email
                        </span>

                        <p>
                            {{ $donorApplication->patient->user->email }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Phone
                        </span>

                        <p>
                            {{ $donorApplication->phone }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Blood Group
                        </span>

                        <p class="font-semibold">
                            {{ $donorApplication->blood_group }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Application Type
                        </span>

                        <p>
                            {{ $donorApplication->request_type_name }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Current Status
                        </span>

                        <p class="font-semibold">
                            {{ $donorApplication->status_name }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- Medical Information -->

            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-lg font-semibold mb-5">
                    Medical Information
                </h3>

                <div class="grid md:grid-cols-3 gap-5">

                    <div>
                        <span class="text-sm text-gray-500">
                            Age
                        </span>

                        <p>
                            {{ $donorApplication->age ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Gender
                        </span>

                        <p>
                            {{ $donorApplication->gender ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Height
                        </span>

                        <p>
                            {{ $donorApplication->height ? $donorApplication->height . ' cm' : 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Weight
                        </span>

                        <p>
                            {{ $donorApplication->weight ? $donorApplication->weight . ' kg' : 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Blood Pressure
                        </span>

                        <p>
                            @if (
                                $donorApplication->blood_pressure_systolic &&
                                $donorApplication->blood_pressure_diastolic
                            )
                                {{ $donorApplication->blood_pressure_systolic }}/{{ $donorApplication->blood_pressure_diastolic }}
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Hemoglobin
                        </span>

                        <p>
                            {{ $donorApplication->hemoglobin ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Previous Donations
                        </span>

                        <p>
                            {{ $donorApplication->previous_donation_count }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Last Donation
                        </span>

                        <p>
                            {{ $donorApplication->previous_donation_date?->format('d M Y') ?? 'None provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Smoking
                        </span>

                        <p>
                            {{ $donorApplication->smoking_status ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">
                            Pregnancy
                        </span>

                        <p>
                            {{ $donorApplication->pregnancy_status ?? 'Not provided' }}
                        </p>
                    </div>

                </div>

                <div class="mt-6 space-y-5">

                    <div>
                        <strong>Medical Conditions</strong>

                        <p class="mt-1 whitespace-pre-line text-gray-700">
                            {{ $donorApplication->medical_conditions ?: 'None provided' }}
                        </p>
                    </div>

                    <div>
                        <strong>Current Medications</strong>

                        <p class="mt-1 whitespace-pre-line text-gray-700">
                            {{ $donorApplication->current_medications ?: 'None provided' }}
                        </p>
                    </div>

                    <div>
                        <strong>Recent Illness / Surgery</strong>

                        <p class="mt-1 whitespace-pre-line text-gray-700">
                            {{ $donorApplication->recent_illness_or_surgery ?: 'None provided' }}
                        </p>
                    </div>

                    <div>
                        <strong>Allergies</strong>

                        <p class="mt-1 whitespace-pre-line text-gray-700">
                            {{ $donorApplication->allergies ?: 'None provided' }}
                        </p>
                    </div>

                    <div>
                        <strong>Additional Medical Information</strong>

                        <p class="mt-1 whitespace-pre-line text-gray-700">
                            {{ $donorApplication->additional_medical_information ?: 'None provided' }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- Blood Request Details -->

            @if ($donorApplication->request_type === 'request')

                <div class="bg-white rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold mb-5">
                        Blood Request
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <span class="text-sm text-gray-500">
                                Units Requested
                            </span>

                            <p>
                                {{ $donorApplication->requested_units ?? 'Not provided' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">
                                Urgency
                            </span>

                            <p>
                                {{ $donorApplication->urgency ?? 'Not provided' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">
                                Hospital
                            </span>

                            <p>
                                {{ $donorApplication->hospital_name ?? 'Not provided' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">
                                Required Date
                            </span>

                            <p>
                                {{ $donorApplication->required_date?->format('d M Y') ?? 'Not provided' }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-5">

                        <strong>Hospital Location</strong>

                        <p class="mt-1 whitespace-pre-line">
                            {{ $donorApplication->hospital_location ?: 'Not provided' }}
                        </p>

                    </div>

                    <div class="mt-5">

                        <strong>Reason / Details</strong>

                        <p class="mt-1 whitespace-pre-line">
                            {{ $donorApplication->request_reason ?: 'Not provided' }}
                        </p>

                    </div>

                </div>

            @endif

            <!-- Doctor Decision -->

            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-lg font-semibold mb-5">
                    Doctor Decision
                </h3>

                @if ($donorApplication->status === 'pending')

                    <form
                        method="POST"
                        action="{{ route('doctor.donor-applications.approve', $donorApplication) }}"
                        class="mb-4"
                    >

                        @csrf
                        @method('PATCH')

                        <label class="block text-sm font-medium mb-2">
                            Doctor Note
                        </label>

                        <textarea
                            name="doctor_note"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 mb-4"
                            placeholder="Add an explanation or instructions..."
                        ></textarea>

                        <button
                            type="submit"
                            class="w-full px-5 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700"
                        >
                            ✓ Approve Application
                        </button>

                    </form>

                    <form
                        method="POST"
                        action="{{ route('doctor.donor-applications.reject', $donorApplication) }}"
                    >

                        @csrf
                        @method('PATCH')

                        <label class="block text-sm font-medium mb-2">
                            Reason for Rejection
                        </label>

                        <textarea
                            name="doctor_note"
                            rows="4"
                            required
                            class="w-full rounded-lg border-gray-300 mb-4"
                            placeholder="Explain why the application is rejected..."
                        ></textarea>

                        <button
                            type="submit"
                            class="w-full px-5 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700"
                        >
                            ✕ Reject Application
                        </button>

                    </form>

                @else

                    <div class="rounded-lg bg-gray-50 p-5">

                        <p class="font-semibold">
                            This application has already been reviewed.
                        </p>

                        <p class="mt-2">
                            Decision:
                            <strong>
                                {{ $donorApplication->status_name }}
                            </strong>
                        </p>

                        @if ($donorApplication->doctor)

                            <p class="mt-2">
                                Doctor:
                                {{ $donorApplication->doctor->name }}
                            </p>

                        @endif

                        @if ($donorApplication->reviewed_at)

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $donorApplication->reviewed_at->format('d M Y, h:i A') }}
                            </p>

                        @endif

                        @if ($donorApplication->doctor_note)

                            <div class="mt-4">

                                <strong>
                                    Doctor's Note
                                </strong>

                                <p class="mt-1 whitespace-pre-line">
                                    {{ $donorApplication->doctor_note }}
                                </p>

                            </div>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>