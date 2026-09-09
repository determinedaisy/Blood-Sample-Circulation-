<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Application Details
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))

                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>

            @endif

            <div class="bg-white rounded-xl shadow p-6">

                <div class="flex justify-between items-center mb-6">

                    <div>

                        <h3 class="text-xl font-bold">
                            {{ $donorApplication->request_type_name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            Submitted {{ $donorApplication->created_at->format('d M Y, h:i A') }}
                        </p>

                    </div>

                    @if ($donorApplication->status === 'pending')

                        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>

                    @elseif ($donorApplication->status === 'approved')

                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>

                    @else

                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>

                    @endif

                </div>

                <div class="grid md:grid-cols-2 gap-6">

                    <div>
                        <strong>Blood Group</strong>
                        <p>{{ $donorApplication->blood_group }}</p>
                    </div>

                    <div>
                        <strong>Age</strong>
                        <p>{{ $donorApplication->age ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Gender</strong>
                        <p>{{ $donorApplication->gender ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Height</strong>
                        <p>{{ $donorApplication->height ? $donorApplication->height . ' cm' : 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Weight</strong>
                        <p>{{ $donorApplication->weight ? $donorApplication->weight . ' kg' : 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Blood Pressure</strong>
                        <p>
                            @if ($donorApplication->blood_pressure_systolic && $donorApplication->blood_pressure_diastolic)
                                {{ $donorApplication->blood_pressure_systolic }}/{{ $donorApplication->blood_pressure_diastolic }}
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>

                    <div>
                        <strong>Hemoglobin</strong>
                        <p>{{ $donorApplication->hemoglobin ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Phone</strong>
                        <p>{{ $donorApplication->phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <strong>Previous Donations</strong>
                        <p>{{ $donorApplication->previous_donation_count }}</p>
                    </div>

                    <div>
                        <strong>Previous Donation Date</strong>
                        <p>
                            {{ $donorApplication->previous_donation_date?->format('d M Y') ?? 'None provided' }}
                        </p>
                    </div>

                </div>

                <div class="mt-8 space-y-5">

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

                @if ($donorApplication->request_type === 'request')

                    <div class="mt-8 border-t pt-6">

                        <h3 class="font-semibold text-lg mb-4">
                            Blood Request
                        </h3>

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>
                                <strong>Units</strong>
                                <p>{{ $donorApplication->requested_units ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <strong>Urgency</strong>
                                <p>{{ $donorApplication->urgency ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <strong>Hospital</strong>
                                <p>{{ $donorApplication->hospital_name ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <strong>Required Date</strong>
                                <p>
                                    {{ $donorApplication->required_date?->format('d M Y') ?? 'Not provided' }}
                                </p>
                            </div>

                        </div>

                        <div class="mt-4">

                            <strong>Reason</strong>

                            <p class="mt-1 whitespace-pre-line">
                                {{ $donorApplication->request_reason ?: 'Not provided' }}
                            </p>

                        </div>

                    </div>

                @endif

                @if ($donorApplication->doctor)

                    <div class="mt-8 border-t pt-6">

                        <h3 class="font-semibold text-lg">
                            Doctor Review
                        </h3>

                        <p class="mt-2">
                            Reviewed by:
                            <strong>{{ $donorApplication->doctor->name }}</strong>
                        </p>

                        @if ($donorApplication->reviewed_at)

                            <p class="text-sm text-gray-500">
                                {{ $donorApplication->reviewed_at->format('d M Y, h:i A') }}
                            </p>

                        @endif

                        <div class="mt-4">

                            <strong>Doctor's Note</strong>

                            <p class="mt-1 whitespace-pre-line">
                                {{ $donorApplication->doctor_note ?: 'No note provided.' }}
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>