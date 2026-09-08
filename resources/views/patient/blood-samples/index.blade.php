<x-app-layout>

    {{-- ================================================= --}}
    {{-- SESSION MESSAGES --}}
    {{-- ================================================= --}}

    @if(session('error'))

        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">

            <div
                class="p-4 mb-4 text-sm text-red-800 rounded-xl
                       bg-red-50 border border-red-200 shadow-sm"
                role="alert"
            >

                <span class="font-extrabold">
                    Payment Error:
                </span>

                {{ session('error') }}

            </div>

        </div>

    @endif


    @if(session('success'))

        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">

            <div
                class="p-4 mb-4 text-sm text-green-800 rounded-xl
                       bg-green-50 border border-green-200 shadow-sm"
                role="alert"
            >

                <span class="font-extrabold">
                    Success:
                </span>

                {{ session('success') }}

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold text-gray-900">
                    My Blood Samples
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View your completed blood sample history and laboratory results.
                </p>

            </div>


            <a
                href="{{ route('patient.blood-samples.create') }}"
                class="inline-flex items-center px-5 py-2.5
                       bg-blue-600 text-white rounded-xl
                       font-semibold hover:bg-blue-700
                       shadow-sm transition"
            >
                + Donate Blood Sample
            </a>

        </div>

    </x-slot>


    {{-- ================================================= --}}
    {{-- PAGE STYLES --}}
    {{-- ================================================= --}}

    <style>

        .samples-page {
            background: #f4f6f9;
            min-height: 100vh;
            padding: 32px 20px 60px;
        }

        .samples-container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .sample-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .sample-card-body {
            padding: 28px 30px;
        }

        .sample-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .sample-eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .sample-code {
            margin-top: 5px;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .sample-meta {
            margin-top: 5px;
            color: #64748b;
            font-size: 14px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-green {
            background: #dcfce7;
            color: #15803d;
        }

        .status-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-yellow {
            background: #fef3c7;
            color: #a16207;
        }

        .status-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-purple {
            background: #ede9fe;
            color: #6d28d9;
        }

        .status-sky {
            background: #e0f2fe;
            color: #0369a1;
        }

        .info-grid {
            margin-top: 26px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 14px;
            padding: 16px;
        }

        .info-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
        }

        .info-value {
            margin-top: 5px;
            color: #0f172a;
            font-size: 14px;
            font-weight: 700;
        }

        .review-box {
            margin-top: 22px;
            border-radius: 16px;
            padding: 18px 20px;
        }

        .review-green {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .review-red {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .review-yellow {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .review-blue {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .review-purple {
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            color: #5b21b6;
        }

        .review-sky {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #075985;
        }

        .review-title {
            font-weight: 800;
            font-size: 15px;
        }

        .review-text {
            margin-top: 6px;
            font-size: 14px;
            line-height: 1.6;
        }

        .empty-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .qr-code {
            width: 110px;
            height: 110px;
        }

        .qr-code img,
        .qr-code canvas {
            display: block;
            width: 110px !important;
            height: 110px !important;
        }

        @media (max-width: 900px) {

            .info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }

        @media (max-width: 600px) {

            .sample-top {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .sample-card-body {
                padding: 22px 18px;
            }

        }

    </style>


    {{-- ================================================= --}}
    {{-- SAMPLES --}}
    {{-- ================================================= --}}

    <div class="samples-page">

        <div class="samples-container">

            @forelse($bloodSamples as $sample)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | OVERALL SAMPLE STATUS
                    |--------------------------------------------------------------------------
                    */

                    $overallStatus = $sample->overallStatus();


                    /*
                    |--------------------------------------------------------------------------
                    | LATEST TRANSPORTATION
                    |--------------------------------------------------------------------------
                    */

                    $transportation = $sample
                        ->transportations
                        ->sortByDesc('id')
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORTATION STATUS
                    |--------------------------------------------------------------------------
                    |
                    | Normally this comes from the transportation record.
                    |
                    | However, if the blood sample has already received a final
                    | laboratory result (accepted/rejected), it has necessarily
                    | reached the laboratory.
                    |
                    | Therefore, if an old/incomplete record has no transportation
                    | entry, we display "Delivered" instead of misleading the
                    | patient with "Not assigned".
                    |
                    */

                    $transportationStatus =
                        $transportation?->status;


                    if (
                        !$transportationStatus
                        && in_array(
                            $sample->status,
                            ['accepted', 'rejected'],
                            true
                        )
                    ) {

                        $transportationStatus = 'delivered';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | COLLECTOR
                    |--------------------------------------------------------------------------
                    */

                    $collectorName =
                        $sample->collector?->name
                        ??
                        $transportation?->transporter?->name
                        ??
                        'Not assigned';


                    /*
                    |--------------------------------------------------------------------------
                    | QR CODE URL
                    |--------------------------------------------------------------------------
                    */

                    $sampleCardUrl =
                        url('/sample/card/' . $sample->sample_code);

                @endphp


                {{-- ================================================= --}}
                {{-- SAMPLE CARD --}}
                {{-- ================================================= --}}

                <div class="sample-card">

                    <div class="sample-card-body">


                        {{-- ================================================= --}}
                        {{-- TOP SECTION --}}
                        {{-- ================================================= --}}

                        <div class="sample-top">


                            <div>

                                <div class="sample-eyebrow">
                                    Blood Sample
                                </div>


                                <div class="sample-code">
                                    {{ $sample->sample_code ?? 'Not Assigned' }}
                                </div>


                                <div class="sample-meta">

                                    {{ $sample->sample_type ?? 'Sample type not specified' }}

                                    @if($sample->blood_type)

                                        · {{ $sample->blood_type }}

                                    @endif

                                </div>


                                {{-- ================================================= --}}
                                {{-- QR CODE --}}
                                {{-- ================================================= --}}

                                <div
                                    class="mt-4 p-3 bg-white border border-gray-200
                                           rounded-xl inline-block shadow-sm"
                                >

                                    <div
                                        class="qr-code"
                                        data-url="{{ $sampleCardUrl }}"
                                    ></div>

                                    <p class="text-xs text-gray-500 text-center mt-2">
                                        Scan to view sample
                                    </p>

                                </div>


                                {{-- ================================================= --}}
                                {{-- BKASH PAYMENT --}}
                                {{-- ================================================= --}}

                                <div class="mt-4">

                                    @if(
                                        $sample->payment
                                        &&
                                        $sample->payment->status === 'completed'
                                    )

                                        <div
                                            class="inline-flex items-center gap-2
                                                   px-4 py-2
                                                   bg-green-50
                                                   border border-green-200
                                                   text-green-700
                                                   rounded-xl
                                                   font-bold text-sm"
                                        >

                                            <span>✓</span>

                                            Paid

                                            (TrxID:
                                            {{ $sample->payment->transaction_id }})

                                        </div>

                                    @else

                                        <a
                                            href="{{ route(
                                                'payment.bkash.initiate',
                                                $sample->sample_code
                                            ) }}"
                                            class="inline-flex items-center justify-center
                                                   w-full sm:w-auto
                                                   px-6 py-2.5
                                                   bg-[#e2136e]
                                                   hover:bg-[#c70f61]
                                                   text-white
                                                   font-bold
                                                   rounded-xl
                                                   shadow-sm
                                                   transition-colors duration-200"
                                        >
                                            Pay Lab Fee via bKash (500 BDT)
                                        </a>

                                    @endif

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- STATUS BADGE --}}
                            {{-- ================================================= --}}

                            <div>

                                @if($overallStatus === 'accepted')

                                    <span class="status-badge status-green">
                                        ✓ Accepted
                                    </span>

                                @elseif($overallStatus === 'rejected')

                                    <span class="status-badge status-red">
                                        ✕ Rejected
                                    </span>

                                @elseif($overallStatus === 'delivered')

                                    <span class="status-badge status-purple">
                                        ✓ Delivered
                                    </span>

                                @elseif($overallStatus === 'in_transit')

                                    <span class="status-badge status-blue">
                                        🚚 In Transit
                                    </span>

                                @elseif($overallStatus === 'collector_assigned')

                                    <span class="status-badge status-sky">
                                        Collector Assigned
                                    </span>

                                @elseif($overallStatus === 'approved')

                                    <span class="status-badge status-green">
                                        ✓ Request Approved
                                    </span>

                                @elseif($overallStatus === 'declined')

                                    <span class="status-badge status-red">
                                        ✕ Request Declined
                                    </span>

                                @else

                                    <span class="status-badge status-yellow">
                                        ○ Request Pending
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SAMPLE INFORMATION --}}
                        {{-- ================================================= --}}

                        <div class="info-grid">


                            {{-- SAMPLE TYPE --}}

                            <div class="info-box">

                                <div class="info-label">
                                    Sample Type
                                </div>

                                <div class="info-value">
                                    {{ $sample->sample_type ?? 'Not specified' }}
                                </div>

                            </div>


                            {{-- COLLECTOR --}}

                            <div class="info-box">

                                <div class="info-label">
                                    Collector
                                </div>

                                <div class="info-value">
                                    {{ $collectorName }}
                                </div>

                            </div>


                            {{-- TRANSPORTATION --}}

                            <div class="info-box">

                                <div class="info-label">
                                    Transportation
                                </div>

                                <div class="info-value">

                                    @if($transportationStatus)

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $transportationStatus
                                            )
                                        ) }}

                                    @else

                                        Not assigned

                                    @endif

                                </div>

                            </div>


                            {{-- LABORATORY REVIEW --}}

                            <div class="info-box">

                                <div class="info-label">
                                    Laboratory Review
                                </div>

                                <div class="info-value">

                                    @if($sample->status === 'accepted')

                                        Accepted

                                    @elseif($sample->status === 'rejected')

                                        Rejected

                                    @elseif(
                                        $transportationStatus === 'delivered'
                                    )

                                        Awaiting review

                                    @else

                                        Not reached yet

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- TRANSPORTATION DETAILS --}}
                        {{-- ================================================= --}}

                        @if($transportation)

                            <div class="info-grid">


                                {{-- COLLECTION CENTER --}}

                                <div class="info-box">

                                    <div class="info-label">
                                        Collection Center
                                    </div>

                                    <div class="info-value">
                                        {{
                                            $transportation
                                                ->collectionCenter
                                                ?->name
                                            ??
                                            'Not specified'
                                        }}
                                    </div>

                                </div>


                                {{-- LABORATORY --}}

                                <div class="info-box">

                                    <div class="info-label">
                                        Destination Laboratory
                                    </div>

                                    <div class="info-value">
                                        {{
                                            $transportation
                                                ->laboratory
                                                ?->name
                                            ??
                                            'Not specified'
                                        }}
                                    </div>

                                </div>


                                {{-- DEPARTURE --}}

                                <div class="info-box">

                                    <div class="info-label">
                                        Departure
                                    </div>

                                    <div class="info-value">

                                        @if($transportation->departure_time)

                                            {{
                                                $transportation
                                                    ->departure_time
                                                    ->format(
                                                        'd M Y, h:i A'
                                                    )
                                            }}

                                        @else

                                            Not started

                                        @endif

                                    </div>

                                </div>


                                {{-- ARRIVAL --}}

                                <div class="info-box">

                                    <div class="info-label">
                                        Arrival
                                    </div>

                                    <div class="info-value">

                                        @if($transportation->arrival_time)

                                            {{
                                                $transportation
                                                    ->arrival_time
                                                    ->format(
                                                        'd M Y, h:i A'
                                                    )
                                            }}

                                        @elseif(
                                            $sample->status === 'accepted'
                                            ||
                                            $sample->status === 'rejected'
                                        )

                                            Delivered to laboratory

                                        @else

                                            Not delivered

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- REVIEW INFORMATION --}}
                        {{-- ================================================= --}}

                        @if($sample->reviewer)

                            <div class="mt-4 text-sm text-gray-500">

                                Reviewed by

                                <span class="font-semibold text-gray-700">
                                    {{ $sample->reviewer->name }}
                                </span>

                                @if($sample->reviewed_at)

                                    on

                                    {{
                                        $sample
                                            ->reviewed_at
                                            ->format(
                                                'd M Y, h:i A'
                                            )
                                    }}

                                @endif

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- FINAL LABORATORY RESULT --}}
                        {{-- ================================================= --}}

                        @if($overallStatus === 'accepted')

                            <div class="review-box review-green">

                                <div class="review-title">
                                    ✓ Sample Accepted
                                </div>

                                <div class="review-text">

                                    Your blood sample passed laboratory
                                    quality review and is now available
                                    in the blood inventory.

                                </div>

                            </div>


                        @elseif($overallStatus === 'rejected')

                            <div class="review-box review-red">

                                <div class="review-title">
                                    ✕ Sample Rejected
                                </div>

                                <div class="review-text">

                                    <strong>
                                        Reason:
                                    </strong>

                                    {{
                                        $sample->rejection_reason
                                        ??
                                        'No reason provided.'
                                    }}

                                </div>

                            </div>


                        @elseif($overallStatus === 'in_transit')

                            <div class="review-box review-blue">

                                <div class="review-title">
                                    🚚 Sample In Transit
                                </div>

                                <div class="review-text">

                                    Your blood sample is currently
                                    being transported to the laboratory.

                                    @if($collectorName !== 'Not assigned')

                                        Collector:

                                        <strong>
                                            {{ $collectorName }}
                                        </strong>.

                                    @endif

                                </div>

                            </div>


                        @elseif($overallStatus === 'delivered')

                            <div class="review-box review-purple">

                                <div class="review-title">
                                    ✓ Delivered to Laboratory
                                </div>

                                <div class="review-text">

                                    Your blood sample has reached
                                    the laboratory and is now waiting
                                    for laboratory review.

                                </div>

                            </div>


                        @elseif($overallStatus === 'collector_assigned')

                            <div class="review-box review-sky">

                                <div class="review-title">
                                    Collector Assigned
                                </div>

                                <div class="review-text">

                                    <strong>
                                        {{ $collectorName }}
                                    </strong>

                                    has been assigned to your blood sample.
                                    Transportation has not started yet.

                                </div>

                            </div>


                        @elseif($overallStatus === 'approved')

                            <div class="review-box review-green">

                                <div class="review-title">
                                    ✓ Request Approved
                                </div>

                                <div class="review-text">

                                    Your request has been approved.
                                    A sample collector will be assigned next.

                                </div>

                            </div>


                        @elseif($overallStatus === 'declined')

                            <div class="review-box review-red">

                                <div class="review-title">
                                    ✕ Request Declined
                                </div>

                                <div class="review-text">

                                    This blood sample request was declined
                                    and will not continue to transportation.

                                </div>

                            </div>


                        @else

                            <div class="review-box review-yellow">

                                <div class="review-title">
                                    Request Pending
                                </div>

                                <div class="review-text">

                                    Your blood sample request is waiting
                                    for administrator approval.

                                </div>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- MEDICAL REPORT --}}
                        {{-- ================================================= --}}

                        @if(
                            $sample->sampleReport?->status === 'published'
                        )

                            <div
                                class="review-box review-green"
                                style="margin-top: 16px;"
                            >

                                <div class="review-title">
                                    Medical Report Available
                                </div>

                                <div class="review-text">

                                    Your doctor has reviewed and published
                                    the laboratory report.

                                </div>

                                <a
                                    href="{{ route(
                                        'sample-reports.patient.show',
                                        $sample->sampleReport
                                    ) }}"
                                    class="inline-flex items-center mt-4
                                           px-4 py-2 rounded-lg
                                           bg-green-600 text-white
                                           text-sm font-semibold
                                           hover:bg-green-700"
                                >
                                    View Medical Report
                                </a>

                            </div>


                        @elseif($sample->sampleReport)

                            <div
                                class="review-box review-yellow"
                                style="margin-top: 16px;"
                            >

                                <div class="review-title">
                                    Medical Report Being Prepared
                                </div>

                                <div class="review-text">

                                    Your assigned doctor has not published
                                    the report yet.

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


            @empty

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="empty-card">

                    <div class="text-2xl font-bold text-gray-900">
                        No blood samples yet
                    </div>

                    <p class="mt-2 text-gray-500">
                        You currently have no completed blood sample history.
                    </p>

                    <a
                        href="{{ route('patient.blood-samples.create') }}"
                        class="inline-flex items-center mt-6 px-5 py-2.5
                               bg-blue-600 text-white rounded-xl
                               font-semibold hover:bg-blue-700"
                    >
                        Donate Your First Blood Sample
                    </a>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- QR CODE LIBRARY --}}
    {{-- ================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            document
                .querySelectorAll('.qr-code')
                .forEach(function (element) {

                    const url =
                        element.dataset.url;


                    if (!url) {
                        return;
                    }


                    if (typeof QRCode === 'undefined') {

                        console.error(
                            'QR Code library failed to load.'
                        );

                        return;
                    }


                    new QRCode(
                        element,
                        {
                            text: url,
                            width: 110,
                            height: 110,
                            correctLevel:
                                QRCode.CorrectLevel.M
                        }
                    );

                });

        });

    </script>


</x-app-layout>