<x-app-layout>

    @php
        $bloodSample = $sampleRequest->bloodSample;

        $requestApproved = $sampleRequest->status === 'approved';
        $requestDeclined = $sampleRequest->status === 'declined';

        $collectorAssigned = $transportation !== null;

        $inTransit = $transportation &&
            in_array($transportation->status, ['in_transit', 'delivered']);

        $delivered = $transportation &&
            $transportation->status === 'delivered';

        $sampleAccepted = $bloodSample &&
            $bloodSample->status === 'accepted';

        $sampleRejected = $bloodSample &&
            $bloodSample->status === 'rejected';

        if ($requestDeclined) {
            $currentStatus = 'Request Declined';
        } elseif ($sampleRejected) {
            $currentStatus = 'Sample Rejected';
        } elseif ($sampleAccepted) {
            $currentStatus = 'Sample Accepted';
        } elseif ($delivered) {
            $currentStatus = 'Delivered';
        } elseif ($inTransit) {
            $currentStatus = 'In Transit';
        } elseif ($collectorAssigned) {
            $currentStatus = 'Collector Assigned';
        } elseif ($requestApproved) {
            $currentStatus = 'Request Approved';
        } else {
            $currentStatus = 'Request Pending';
        }
    @endphp


    <style>
        .tracking-page {
            background: #f3f4f6;
            min-height: 100vh;
            padding: 32px 20px 60px;
        }

        .tracking-container {
            max-width: 1250px;
            margin: 0 auto;
        }

        .tracking-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .tracking-header {
            padding: 30px 34px;
        }

        .back-link {
            color: #2563eb;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header-main {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
        }

        .sample-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .sample-code {
            color: #111827;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 700;
        }

        .status-pill {
            margin-top: 15px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .status-blue {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .status-green {
            color: #15803d;
            background: #dcfce7;
        }

        .status-yellow {
            color: #a16207;
            background: #fef3c7;
        }

        .status-red {
            color: #b91c1c;
            background: #fee2e2;
        }

        .tracking-title {
            text-align: right;
        }

        .tracking-title h1 {
            margin: 0;
            font-size: 27px;
            color: #111827;
            font-weight: 700;
        }

        .tracking-title p {
            margin-top: 5px;
            color: #6b7280;
            font-size: 15px;
        }


        /* PROGRESS */

        .progress-wrapper {
            padding: 15px 34px 30px;
            overflow-x: auto;
        }

        .progress {
            min-width: 850px;
            display: flex;
            position: relative;
            padding-top: 15px;
        }

        .progress-line {
            position: absolute;
            height: 4px;
            background: #e5e7eb;
            top: 37px;
            left: 10%;
            right: 10%;
            z-index: 0;
        }

        .progress-step {
            width: 20%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 46px;
            height: 46px;
            margin: 0 auto;
            border-radius: 50%;
            background: white;
            border: 3px solid #d1d5db;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #9ca3af;
            font-weight: 700;
            font-size: 17px;
        }

        .step-circle.complete {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .step-circle.current {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
            box-shadow: 0 0 0 7px #dbeafe;
        }

        .step-name {
            margin-top: 14px;
            font-weight: 700;
            color: #6b7280;
            font-size: 14px;
        }

        .step-name.complete {
            color: #15803d;
        }

        .step-name.current {
            color: #1d4ed8;
        }

        .step-date {
            margin-top: 8px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
        }


        /* STATUS MESSAGE */

        .current-message {
            margin: 0 34px 30px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 14px;
            font-weight: 500;
        }


        /* BOTTOM */

        .tracking-grid {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 22px;
            margin-top: 22px;
        }

        .history-card,
        .details-card {
            padding: 30px;
        }

        .card-title {
            margin: 0 0 28px;
            color: #111827;
            font-size: 22px;
            font-weight: 700;
        }


        /* VERTICAL TIMELINE */

        .history {
            position: relative;
        }

        .history::before {
            content: "";
            position: absolute;
            left: 17px;
            top: 15px;
            bottom: 15px;
            width: 2px;
            background: #e5e7eb;
        }

        .history-item {
            display: flex;
            position: relative;
            gap: 18px;
            padding-bottom: 32px;
        }

        .history-item:last-child {
            padding-bottom: 0;
        }

        .history-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            z-index: 2;
        }

        .history-icon.done {
            background: #16a34a;
            color: white;
        }

        .history-icon.current {
            background: #2563eb;
            color: white;
        }

        .history-content {
            flex: 1;
            padding-top: 3px;
        }

        .history-title {
            font-size: 16px;
            font-weight: 700;
            color: #374151;
        }

        .history-title.done {
            color: #15803d;
        }

        .history-title.current {
            color: #1d4ed8;
        }

        .history-date {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
        }

        .history-description {
            color: #4b5563;
            font-size: 14px;
            margin-top: 7px;
            line-height: 1.6;
        }

        .current-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 3px 7px;
            border-radius: 5px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 10px;
            font-weight: 700;
        }


        /* COLLECTOR INFO */

        .collector-box {
            margin-top: 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 15px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .collector-label {
            font-size: 11px;
            color: #6b7280;
        }

        .collector-value {
            margin-top: 4px;
            color: #111827;
            font-size: 13px;
            font-weight: 600;
        }


        /* DETAILS */

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-value {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }


        /* NEXT */

        .next-card {
            margin-top: 22px;
            padding: 22px;
            border-radius: 16px;
            background: #fffbeb;
            border: 1px solid #fde68a;
        }

        .next-title {
            color: #92400e;
            font-weight: 700;
            font-size: 16px;
        }

        .next-text {
            margin-top: 8px;
            color: #78350f;
            line-height: 1.6;
            font-size: 14px;
        }


        @media (max-width: 900px) {

            .tracking-grid {
                grid-template-columns: 1fr;
            }

            .header-main {
                flex-direction: column;
            }

            .tracking-title {
                text-align: left;
            }

            .collector-box {
                grid-template-columns: 1fr;
            }

        }
    </style>


    <div class="tracking-page">

        <div class="tracking-container">


            {{-- TOP CARD --}}

            <div class="tracking-card">

                <div class="tracking-header">

                    <a
                        href="{{ route('sample-requests.patient.index') }}"
                        class="back-link"
                    >
                        ← Back to Requests
                    </a>


                    <div class="header-main">

                        <div>

                            <div class="sample-label">
                                Sample Code
                            </div>

                            <div class="sample-code">
                                {{ $bloodSample->sample_code ?? 'Not Available' }}
                            </div>


                            @if($currentStatus === 'In Transit')

                                <div class="status-pill status-blue">
                                    🚚 IN TRANSIT
                                </div>

                            @elseif($currentStatus === 'Delivered')

                                <div class="status-pill status-green">
                                    ✓ DELIVERED
                                </div>

                            @elseif($currentStatus === 'Sample Accepted')

                                <div class="status-pill status-green">
                                    ✓ SAMPLE ACCEPTED
                                </div>

                            @elseif(
                                $currentStatus === 'Request Declined'
                                || $currentStatus === 'Sample Rejected'
                            )

                                <div class="status-pill status-red">
                                    {{ strtoupper($currentStatus) }}
                                </div>

                            @elseif($currentStatus === 'Request Approved')

                                <div class="status-pill status-green">
                                    ✓ REQUEST APPROVED
                                </div>

                            @else

                                <div class="status-pill status-yellow">
                                    {{ strtoupper($currentStatus) }}
                                </div>

                            @endif

                        </div>


                        <div class="tracking-title">

                            <h1>
                                Sample Tracking
                            </h1>

                            <p>
                                Track the progress of your blood sample in real time.
                            </p>

                        </div>

                    </div>

                </div>



                @if(!$requestDeclined)

                    {{-- HORIZONTAL PROGRESS --}}

                    <div class="progress-wrapper">

                        <div class="progress">

                            <div class="progress-line"></div>


                            {{-- Submitted --}}
                            <div class="progress-step">

                                <div class="step-circle complete">
                                    ✓
                                </div>

                                <div class="step-name complete">
                                    Request Submitted
                                </div>

                                <div class="step-date">
                                    {{ $sampleRequest->created_at->format('d M Y') }}
                                    <br>
                                    {{ $sampleRequest->created_at->format('h:i A') }}
                                </div>

                            </div>


                            {{-- Approved --}}
                            <div class="progress-step">

                                <div class="step-circle {{ $requestApproved ? 'complete' : '' }}">
                                    {{ $requestApproved ? '✓' : '2' }}
                                </div>

                                <div class="step-name {{ $requestApproved ? 'complete' : '' }}">
                                    Request Approved
                                </div>

                                <div class="step-date">

                                    @if($requestApproved)

                                        {{ $sampleRequest->approved_at?->format('d M Y') }}
                                        <br>
                                        {{ $sampleRequest->approved_at?->format('h:i A') }}

                                    @else
                                        Waiting
                                    @endif

                                </div>

                            </div>


                            {{-- Collector --}}
                            <div class="progress-step">

                                <div class="step-circle {{ $collectorAssigned ? 'complete' : '' }}">
                                    {{ $collectorAssigned ? '✓' : '3' }}
                                </div>

                                <div class="step-name {{ $collectorAssigned ? 'complete' : '' }}">
                                    Collector Assigned
                                </div>

                                <div class="step-date">

                                    @if($collectorAssigned)

                                        {{ $transportation->created_at?->format('d M Y') }}
                                        <br>
                                        {{ $transportation->created_at?->format('h:i A') }}

                                    @else
                                        Waiting
                                    @endif

                                </div>

                            </div>


                            {{-- Transit --}}
                            <div class="progress-step">

                                <div class="step-circle
                                    {{ $inTransit && !$delivered
                                        ? 'current'
                                        : ($inTransit ? 'complete' : '')
                                    }}"
                                >

                                    @if($inTransit && !$delivered)
                                        🚚
                                    @elseif($inTransit)
                                        ✓
                                    @else
                                        4
                                    @endif

                                </div>

                                <div class="step-name
                                    {{ $inTransit && !$delivered
                                        ? 'current'
                                        : ($inTransit ? 'complete' : '')
                                    }}"
                                >
                                    In Transit
                                </div>

                                <div class="step-date">

                                    @if($inTransit)

                                        {{ $transportation->departure_time?->format('d M Y') }}
                                        <br>
                                        {{ $transportation->departure_time?->format('h:i A') }}

                                    @else
                                        Waiting
                                    @endif

                                </div>

                            </div>


                            {{-- Delivered --}}
                            <div class="progress-step">

                                <div class="step-circle {{ $delivered ? 'complete' : '' }}">
                                    {{ $delivered ? '✓' : '5' }}
                                </div>

                                <div class="step-name {{ $delivered ? 'complete' : '' }}">
                                    Delivered
                                </div>

                                <div class="step-date">

                                    @if($delivered)

                                        {{ $transportation->arrival_time?->format('d M Y') }}
                                        <br>
                                        {{ $transportation->arrival_time?->format('h:i A') }}

                                    @else
                                        Waiting for delivery
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endif



                {{-- STATUS MESSAGE --}}

                <div class="current-message">

                    @if($currentStatus === 'In Transit')

                        🚚 Your blood sample is currently on the way to the laboratory.

                    @elseif($currentStatus === 'Collector Assigned')

                        A collector has been assigned and transportation will begin soon.

                    @elseif($currentStatus === 'Delivered')

                        ✓ Your blood sample has arrived at the laboratory.

                    @elseif($currentStatus === 'Request Approved')

                        ✓ Your request was approved and is waiting for collector assignment.

                    @elseif($currentStatus === 'Request Pending')

                        Your request is waiting for administrator approval.

                    @elseif($currentStatus === 'Sample Accepted')

                        ✓ Your blood sample has been accepted.

                    @elseif($currentStatus === 'Sample Rejected')

                        Your blood sample was rejected during review.

                    @else

                        This request was declined.

                    @endif

                </div>

            </div>



            {{-- LOWER SECTION --}}

            <div class="tracking-grid">


                {{-- HISTORY --}}

                <div class="tracking-card history-card">

                    <h2 class="card-title">
                        Tracking History
                    </h2>


                    <div class="history">


                        {{-- SUBMITTED --}}

                        <div class="history-item">

                            <div class="history-icon done">
                                ✓
                            </div>

                            <div class="history-content">

                                <div class="history-title done">
                                    Request Submitted
                                </div>

                                <div class="history-date">
                                    {{ $sampleRequest->created_at->format('d M Y • h:i A') }}
                                </div>

                                <div class="history-description">
                                    Your blood sample request was successfully submitted.
                                </div>

                            </div>

                        </div>



                        {{-- APPROVED --}}

                        <div class="history-item">

                            <div class="history-icon {{ $requestApproved ? 'done' : '' }}">
                                {{ $requestApproved ? '✓' : '2' }}
                            </div>

                            <div class="history-content">

                                <div class="history-title {{ $requestApproved ? 'done' : '' }}">
                                    Request Approved
                                </div>


                                @if($requestApproved)

                                    <div class="history-date">
                                        {{ $sampleRequest->approved_at?->format('d M Y • h:i A') }}
                                    </div>

                                    <div class="history-description">
                                        Your request was approved by the administrator.
                                    </div>

                                @else

                                    <div class="history-description">
                                        Waiting for administrator approval.
                                    </div>

                                @endif

                            </div>

                        </div>



                        {{-- COLLECTOR --}}

                        <div class="history-item">

                            <div class="history-icon {{ $collectorAssigned ? 'done' : '' }}">
                                {{ $collectorAssigned ? '✓' : '3' }}
                            </div>

                            <div class="history-content">

                                <div class="history-title {{ $collectorAssigned ? 'done' : '' }}">
                                    Collector Assigned
                                </div>


                                @if($collectorAssigned)

                                    <div class="history-date">
                                        {{ $transportation->created_at?->format('d M Y • h:i A') }}
                                    </div>


                                    <div class="collector-box">

                                        <div>

                                            <div class="collector-label">
                                                Collector
                                            </div>

                                            <div class="collector-value">
                                                {{ $transportation->transporter->name ?? 'Assigned' }}
                                            </div>

                                        </div>


                                        <div>

                                            <div class="collector-label">
                                                Collection Center
                                            </div>

                                            <div class="collector-value">
                                                {{ $transportation->collectionCenter->name ?? '—' }}
                                            </div>

                                        </div>


                                        <div>

                                            <div class="collector-label">
                                                Destination Laboratory
                                            </div>

                                            <div class="collector-value">
                                                {{ $transportation->laboratory->name ?? '—' }}
                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div class="history-description">
                                        Waiting for collector assignment.
                                    </div>

                                @endif

                            </div>

                        </div>



                        {{-- TRANSIT --}}

                        <div class="history-item">

                            <div class="history-icon
                                {{ $inTransit && !$delivered
                                    ? 'current'
                                    : ($inTransit ? 'done' : '')
                                }}"
                            >

                                @if($inTransit && !$delivered)
                                    🚚
                                @elseif($inTransit)
                                    ✓
                                @else
                                    4
                                @endif

                            </div>

                            <div class="history-content">

                                <div class="history-title
                                    {{ $inTransit && !$delivered
                                        ? 'current'
                                        : ($inTransit ? 'done' : '')
                                    }}"
                                >

                                    In Transit

                                    @if($inTransit && !$delivered)

                                        <span class="current-badge">
                                            CURRENT STEP
                                        </span>

                                    @endif

                                </div>


                                @if($inTransit)

                                    <div class="history-date">
                                        {{ $transportation->departure_time?->format('d M Y • h:i A') }}
                                    </div>

                                    <div class="history-description">
                                        Your blood sample is being transported to the laboratory.
                                    </div>

                                @else

                                    <div class="history-description">
                                        Transportation has not started.
                                    </div>

                                @endif

                            </div>

                        </div>



                        {{-- DELIVERED --}}

                        <div class="history-item">

                            <div class="history-icon {{ $delivered ? 'done' : '' }}">
                                {{ $delivered ? '✓' : '5' }}
                            </div>

                            <div class="history-content">

                                <div class="history-title {{ $delivered ? 'done' : '' }}">
                                    Delivered to Laboratory
                                </div>


                                @if($delivered)

                                    <div class="history-date">
                                        {{ $transportation->arrival_time?->format('d M Y • h:i A') }}
                                    </div>

                                    <div class="history-description">
                                        Your blood sample was successfully delivered to the laboratory.
                                    </div>

                                @else

                                    <div class="history-description">
                                        Waiting for delivery.
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>



                {{-- RIGHT SIDE --}}

                <div>


                    <div class="tracking-card details-card">

                        <h2 class="card-title">
                            Sample Details
                        </h2>


                        <div class="detail-row">

                            <span class="detail-label">
                                Sample Type
                            </span>

                            <span class="detail-value">
                                {{ $sampleRequest->sample_type }}
                            </span>

                        </div>


                        <div class="detail-row">

                            <span class="detail-label">
                                Blood Type
                            </span>

                            <span class="detail-value">
                                {{ $sampleRequest->blood_type ?? 'Not specified' }}
                            </span>

                        </div>


                        <div class="detail-row">

                            <span class="detail-label">
                                Requested On
                            </span>

                            <span class="detail-value">
                                {{ $sampleRequest->created_at->format('d M Y, h:i A') }}
                            </span>

                        </div>


                        <div class="detail-row">

                            <span class="detail-label">
                                Sample Code
                            </span>

                            <span class="detail-value">
                                {{ $bloodSample->sample_code ?? '—' }}
                            </span>

                        </div>


                        <div class="detail-row">

                            <span class="detail-label">
                                Current Status
                            </span>

                            <span class="detail-value">
                                {{ $currentStatus }}
                            </span>

                        </div>


                        @if($transportation)

                            <div class="detail-row">

                                <span class="detail-label">
                                    Last Updated
                                </span>

                                <span class="detail-value">
                                    {{ $transportation->updated_at?->format('d M Y, h:i A') }}
                                </span>

                            </div>

                        @endif

                    </div>



                    {{-- WHAT'S NEXT --}}

                    <div class="next-card">

                        <div class="next-title">
                            💡 What's Next?
                        </div>


                        <div class="next-text">

                            @if($currentStatus === 'In Transit')

                                Your sample is currently travelling to the laboratory.
                                Once the collector confirms delivery, this page will
                                automatically show it as delivered.

                            @elseif($currentStatus === 'Collector Assigned')

                                Your collector has been assigned. The next step is
                                for the collector to begin transportation.

                            @elseif($currentStatus === 'Delivered')

                                Your sample has reached the laboratory. Lab staff
                                can now review the sample.

                            @elseif($currentStatus === 'Request Approved')

                                Your request has been approved. An administrator
                                will assign a collector next.

                            @elseif($currentStatus === 'Request Pending')

                                Your request is waiting for administrator approval.

                            @elseif($currentStatus === 'Sample Accepted')

                                Your sample has completed the process successfully.

                            @elseif($currentStatus === 'Sample Rejected')

                                Your sample was rejected during laboratory review.

                            @else

                                This request was declined and will not continue
                                through transportation.

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>