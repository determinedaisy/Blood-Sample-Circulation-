<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-blue-600">
                    Doctor Case Review
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-1">

                    Patient Case

                    @if($sampleRequest->bloodSample)
                        —
                        {{ $sampleRequest->bloodSample->sample_code }}
                    @endif

                </h2>

            </div>


            <a
                href="{{ route('sample-requests.doctor.index') }}"
                class="px-4 py-2 bg-white border border-gray-300
                       rounded-lg text-sm font-semibold text-gray-700
                       hover:bg-gray-50"
            >
                ← Back to Assigned Samples
            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- PATIENT INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-900">
                        Patient Information
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Patient associated with this sample request.
                    </p>

                </div>


                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Patient Name
                        </div>

                        <div class="mt-1 font-semibold text-gray-900">
                            {{ $sampleRequest->patient->name ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Email
                        </div>

                        <div class="mt-1 text-gray-900">
                            {{ $sampleRequest->patient->email ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Blood Type
                        </div>

                        <div class="mt-1">

                            <span
                                class="inline-flex px-3 py-1 rounded-full
                                       bg-red-50 text-red-700 font-bold"
                            >
                                {{ $sampleRequest->blood_type ?? 'Not specified' }}
                            </span>

                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Patient ID
                        </div>

                        <div class="mt-1 text-gray-900">
                            #{{ $sampleRequest->patient_id }}
                        </div>

                    </div>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- REQUEST INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-900">
                        Request Information
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Details associated with this blood sample request.
                    </p>

                </div>


                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requested Sample
                            </div>

                            <div class="mt-1 font-semibold text-gray-900">
                                {{ $sampleRequest->sample_type }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requested By
                            </div>

                            <div class="mt-1 font-semibold text-gray-900">
                                {{ $sampleRequest->requester->name ?? 'Unknown' }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">

                                @if(
                                    (int) $sampleRequest->requested_by ===
                                    (int) $sampleRequest->patient_id
                                )

                                    Patient

                                @else

                                    Receptionist / Staff

                                @endif

                            </div>

                        </div>


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Request Date
                            </div>

                            <div class="mt-1 text-gray-900">
                                {{ $sampleRequest->created_at->format('d M Y, h:i A') }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Approved By
                            </div>

                            <div class="mt-1 text-gray-900">
                                {{ $sampleRequest->approver->name ?? 'Not available' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Request Status
                            </div>

                            <div class="mt-1">

                                @if($sampleRequest->status === 'approved')

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-green-100 text-green-800
                                               text-sm font-semibold"
                                    >
                                        Approved
                                    </span>

                                @elseif($sampleRequest->status === 'pending')

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-yellow-100 text-yellow-800
                                               text-sm font-semibold"
                                    >
                                        Pending
                                    </span>

                                @elseif($sampleRequest->status === 'declined')

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-red-100 text-red-800
                                               text-sm font-semibold"
                                    >
                                        Declined
                                    </span>

                                @else

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-gray-100 text-gray-700
                                               text-sm font-semibold"
                                    >
                                        {{ ucfirst($sampleRequest->status) }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Assigned Doctor
                            </div>

                            <div class="mt-1 font-semibold text-gray-900">
                                {{ $sampleRequest->assignedDoctor->name ?? Auth::user()->name }}
                            </div>

                        </div>

                    </div>


                    {{-- Request Notes --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">
                            Clinical Reason / Request Notes
                        </div>


                        @if($sampleRequest->notes)

                            <div
                                class="rounded-xl bg-blue-50
                                       border border-blue-100
                                       p-4 text-gray-800"
                            >
                                {{ $sampleRequest->notes }}
                            </div>

                        @else

                            <div
                                class="rounded-xl bg-gray-50
                                       border border-gray-200
                                       p-4 text-gray-500"
                            >
                                No clinical notes were provided with this request.
                            </div>

                        @endif

                    </div>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- SAMPLE INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-900">
                        Sample Information
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Collection, transportation and laboratory information.
                    </p>

                </div>


                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sample Code
                        </div>

                        <div class="mt-1 text-lg font-bold text-blue-700">
                            {{ $sampleRequest->bloodSample->sample_code ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sample Type
                        </div>

                        <div class="mt-1 font-semibold text-gray-900">
                            {{ $sampleRequest->bloodSample->sample_type ?? $sampleRequest->sample_type }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sample Status
                        </div>

                        <div class="mt-1">

                            @if($sampleRequest->bloodSample)

                                @if($sampleRequest->bloodSample->status === 'accepted')

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-green-100 text-green-800
                                               text-sm font-semibold"
                                    >
                                        Accepted
                                    </span>

                                @elseif($sampleRequest->bloodSample->status === 'rejected')

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-red-100 text-red-800
                                               text-sm font-semibold"
                                    >
                                        Rejected
                                    </span>

                                @else

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               bg-blue-100 text-blue-800
                                               text-sm font-semibold"
                                    >
                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $sampleRequest->bloodSample->status
                                            )
                                        ) }}
                                    </span>

                                @endif

                            @else

                                <span class="text-gray-500">
                                    Not available
                                </span>

                            @endif

                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Collection Center
                        </div>

                        <div class="mt-1 text-gray-900">
                            {{ $transportation?->collectionCenter?->name ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Collector
                        </div>

                        <div class="mt-1 text-gray-900">
                            {{ $transportation?->transporter?->name ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Laboratory
                        </div>

                        <div class="mt-1 text-gray-900">
                            {{ $transportation?->laboratory?->name ?? 'Not available' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Delivery Status
                        </div>

                        <div class="mt-1">

                            @if($transportation?->status === 'delivered')

                                <span
                                    class="inline-flex px-3 py-1 rounded-full
                                           bg-green-100 text-green-800
                                           text-sm font-semibold"
                                >
                                    ✓ Delivered
                                </span>

                            @elseif($transportation)

                                <span
                                    class="inline-flex px-3 py-1 rounded-full
                                           bg-yellow-100 text-yellow-800
                                           text-sm font-semibold"
                                >
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $transportation->status
                                        )
                                    ) }}
                                </span>

                            @else

                                <span
                                    class="inline-flex px-3 py-1 rounded-full
                                           bg-gray-100 text-gray-600
                                           text-sm font-semibold"
                                >
                                    Not available
                                </span>

                            @endif

                        </div>

                    </div>


                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Delivered At
                        </div>

                        <div class="mt-1 text-gray-900">

                            @if(
                                $transportation
                                && isset($transportation->delivered_at)
                                && $transportation->delivered_at
                            )

                                {{ \Carbon\Carbon::parse(
                                    $transportation->delivered_at
                                )->format('d M Y, h:i A') }}

                            @elseif($transportation?->status === 'delivered')

                                Delivered

                            @else

                                Not available

                            @endif

                        </div>

                    </div>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- MEDICAL REPORT WORKFLOW --}}
            {{-- ===================================================== --}}

            <div
                class="bg-white rounded-2xl
                       border border-gray-200 shadow-sm"
            >

                <div class="p-6">

                    <div
                        class="rounded-xl bg-blue-50
                               border border-blue-100
                               p-5"
                    >

                        <div class="flex items-start gap-4">

                            <div
                                class="flex h-11 w-11 shrink-0
                                       items-center justify-center
                                       rounded-full bg-blue-100
                                       text-xl"
                            >
                                🩺
                            </div>


                            <div class="flex-1">

                                @php($medicalReport = $sampleRequest->bloodSample?->sampleReport)

                                <h3 class="font-semibold text-blue-900">
                                    {{ $medicalReport
                                        ? 'Medical Report '.ucfirst(str_replace('_', ' ', $medicalReport->status))
                                        : 'Awaiting Laboratory Results' }}
                                </h3>

                                <p class="text-sm text-blue-700 mt-1">
                                    This blood sample has been delivered to the laboratory
                                    and assigned to you. Review the patient, request and
                                    sample information above.
                                </p>

                                @if($medicalReport?->status === 'published')
                                    <p class="text-xs text-green-700 mt-3">
                                        Published {{ $medicalReport->published_at?->format('d M Y, h:i A') }} and available to the patient.
                                    </p>
                                @elseif($medicalReport?->status === 'lab_submitted')
                                    <p class="text-xs text-indigo-700 mt-3">
                                        Laboratory staff submitted the verified values and the system generated the formatted PDF. Review them before generating the explanation and publishing.
                                    </p>
                                @elseif($medicalReport)
                                    <p class="text-xs text-amber-700 mt-3">
                                        This report is still private. Complete the doctor review and explanation before publishing.
                                    </p>
                                @else
                                    <p class="text-xs text-blue-600 mt-3">
                                        Laboratory staff have not submitted test results yet. The report will appear here automatically after submission.
                                    </p>
                                @endif

                                @if($medicalReport)
                                    <a
                                        href="{{ route('sample-reports.doctor.edit', $sampleRequest) }}"
                                        class="inline-flex items-center mt-4 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700"
                                    >
                                        Open Medical Report
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>
