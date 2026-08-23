<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Reception Assistance
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Track requests that you sent to the reception team.
                </p>
            </div>

           <a
    href="{{ route('reception-requests.create') }}"
    style="
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:12px 20px;
        background:#4f46e5;
        color:#ffffff !important;
        font-weight:700;
        font-size:15px;
        border-radius:12px;
        text-decoration:none;
        box-shadow:0 6px 18px rgba(79,70,229,.22);
    "
>
    + Contact Receptionist
</a>

        </div>
    </x-slot>


    <style>
        .reception-page {
            background: #f6f7fb;
            min-height: 100vh;
            padding: 34px 20px 60px;
        }

        .reception-container {
            max-width: 1150px;
            margin: 0 auto;
        }

        .request-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 7px 25px rgba(15, 23, 42, .05);
        }

        .request-top {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
        }

        .request-number {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .request-title {
            font-size: 21px;
            font-weight: 800;
            color: #111827;
            margin-top: 5px;
        }

        .status {
            padding: 7px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-handled {
            background: #dcfce7;
            color: #166534;
        }

        .request-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 22px;
        }

        .info {
            background: #f8fafc;
            border-radius: 13px;
            padding: 14px;
        }

        .info-label {
            color: #6b7280;
            font-size: 11px;
            font-weight: 600;
        }

        .info-value {
            color: #111827;
            font-size: 14px;
            font-weight: 700;
            margin-top: 4px;
        }

        .message-box {
            margin-top: 17px;
            background: #eef2ff;
            border: 1px solid #e0e7ff;
            border-radius: 13px;
            padding: 15px;
            color: #4338ca;
            font-size: 14px;
        }

        .processed-box {
            margin-top: 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 13px;
            padding: 15px;
            color: #166534;
        }

        @media(max-width:700px) {
            .request-grid {
                grid-template-columns: 1fr;
            }

            .request-top {
                flex-direction: column;
            }
        }
    </style>


    <div class="reception-page">

        <div class="reception-container">

            @if(session('success'))

                <div class="mb-6 bg-green-50 border border-green-200
                            text-green-800 rounded-xl px-5 py-4">
                    {{ session('success') }}
                </div>

            @endif


            @forelse($requests as $request)

                <div class="request-card">

                    <div class="request-top">

                        <div>

                            <div class="request-number">
                                Reception Request #{{ $request->id }}
                            </div>

                            <div class="request-title">
                                {{ $request->sample_type }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                Sent {{ $request->created_at->format('d M Y, h:i A') }}
                            </div>

                        </div>


                        @if($request->status === 'handled')

                            <span class="status status-handled">
                                ✓ Processed
                            </span>

                        @else

                            <span class="status status-pending">
                                ● Waiting for Reception
                            </span>

                        @endif

                    </div>


                    <div class="request-grid">

                        <div class="info">

                            <div class="info-label">
                                Sample Type
                            </div>

                            <div class="info-value">
                                {{ $request->sample_type }}
                            </div>

                        </div>


                        <div class="info">

                            <div class="info-label">
                                Blood Type
                            </div>

                            <div class="info-value">
                                {{ $request->blood_type ?? 'Not specified' }}
                            </div>

                        </div>


                        <div class="info">

                            <div class="info-label">
                                Receptionist
                            </div>

                            <div class="info-value">
                                {{ $request->handler?->name ?? 'Not assigned yet' }}
                            </div>

                        </div>

                    </div>


                    @if($request->notes)

                        <div class="message-box">
                            <strong>Your message:</strong>
                            {{ $request->notes }}
                        </div>

                    @endif


                    @if($request->status === 'handled')

                        <div class="processed-box">

                            <strong>
                                ✓ Reception processed your request
                            </strong>

                            <div class="mt-1 text-sm">

                                @if($request->handled_at)

                                    Processed on
                                    {{ $request->handled_at->format('d M Y, h:i A') }}.

                                @endif

                                Your request has now entered the normal sample
                                request approval workflow.

                            </div>


                            @if($request->sampleRequest)

                                <a
                                    href="{{ route('sample-requests.patient.index') }}"
                                    class="inline-block mt-3 font-semibold underline"
                                >
                                    View Sample Request →
                                </a>

                            @endif

                        </div>

                    @else

                        <div class="mt-5 text-sm text-gray-500">
                            Reception has not processed this request yet.
                        </div>

                    @endif

                </div>

            @empty

                <div class="bg-white border border-gray-200 rounded-2xl
                            shadow-sm text-center p-12">

                    <div class="text-2xl font-bold text-gray-900">
                        No reception requests
                    </div>

                    <p class="text-gray-500 mt-2">
                        You haven't contacted reception yet.
                    </p>

                    <a
                        href="{{ route('reception-requests.create') }}"
                        class="inline-flex mt-6 px-5 py-2.5
                               bg-indigo-600 text-white rounded-xl font-semibold"
                    >
                        Contact Receptionist
                    </a>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>