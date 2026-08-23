<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Reception Inbox
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Review assistance requests sent by patients.
                </p>
            </div>

            <a
                href="{{ route('sample-requests.receptionist.create') }}"
                class="px-5 py-2.5 rounded-xl
                       bg-blue-600 text-white font-semibold"
            >
                + Manual Sample Request
            </a>

        </div>

    </x-slot>


    <style>
        .inbox-page {
            background: #f5f7fb;
            min-height: 100vh;
            padding: 35px 20px 60px;
        }

        .inbox-container {
            max-width: 1200px;
            margin: auto;
        }

        .inbox-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            margin-bottom: 20px;
            padding: 26px;
            box-shadow: 0 8px 30px rgba(15,23,42,.05);
        }

        .inbox-top {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .patient-name {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
        }

        .patient-email {
            color: #6b7280;
            font-size: 13px;
            margin-top: 2px;
        }

        .new-badge,
        .done-badge {
            display: inline-flex;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .new-badge {
            background: #fef3c7;
            color: #92400e;
        }

        .done-badge {
            background: #dcfce7;
            color: #166534;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 14px;
            margin-top: 20px;
        }

        .data-box {
            background: #f8fafc;
            border-radius: 13px;
            padding: 14px;
        }

        .data-label {
            color: #64748b;
            font-size: 11px;
        }

        .data-value {
            font-weight: 700;
            color: #111827;
            margin-top: 4px;
        }

        .patient-message {
            margin-top: 17px;
            background: #eef2ff;
            color: #3730a3;
            border-radius: 13px;
            padding: 15px;
        }

        .action-area {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #eef2f7;
            display: flex;
            justify-content: flex-end;
        }

        .process-button {
            background: linear-gradient(135deg,#4f46e5,#7c3aed);
            border: 0;
            color: white;
            border-radius: 12px;
            padding: 11px 19px;
            font-weight: 700;
            cursor: pointer;
        }

        .handled-info {
            color: #166534;
            font-size: 14px;
            font-weight: 600;
        }

        @media(max-width:700px) {
            .data-grid {
                grid-template-columns: 1fr;
            }

            .inbox-top {
                flex-direction: column;
            }
        }
    </style>


    <div class="inbox-page">

        <div class="inbox-container">


            @if(session('success'))

                <div class="mb-6 px-5 py-4 rounded-xl
                            border border-green-200 bg-green-50
                            text-green-800">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 px-5 py-4 rounded-xl
                            border border-red-200 bg-red-50
                            text-red-800">
                    {{ session('error') }}
                </div>

            @endif


            @forelse($requests as $request)

                <div class="inbox-card">

                    <div class="inbox-top">

                        <div>

                            <div class="patient-name">
                                {{ $request->patient?->name ?? 'Unknown Patient' }}
                            </div>

                            <div class="patient-email">
                                {{ $request->patient?->email ?? '' }}
                            </div>

                            <div class="text-xs text-gray-400 mt-2">
                                Reception Request #{{ $request->id }}
                                •
                                {{ $request->created_at->format('d M Y, h:i A') }}
                            </div>

                        </div>


                        <div>

                            @if($request->status === 'pending')

                                <span class="new-badge">
                                    ● New Request
                                </span>

                            @else

                                <span class="done-badge">
                                    ✓ Processed
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="data-grid">

                        <div class="data-box">

                            <div class="data-label">
                                Sample Type
                            </div>

                            <div class="data-value">
                                {{ $request->sample_type }}
                            </div>

                        </div>


                        <div class="data-box">

                            <div class="data-label">
                                Blood Type
                            </div>

                            <div class="data-value">
                                {{ $request->blood_type ?? 'Not specified' }}
                            </div>

                        </div>


                        <div class="data-box">

                            <div class="data-label">
                                Current Status
                            </div>

                            <div class="data-value">
                                {{ ucfirst($request->status) }}
                            </div>

                        </div>

                    </div>


                    @if($request->notes)

                        <div class="patient-message">

                            <strong>
                                Patient message
                            </strong>

                            <div class="mt-1">
                                {{ $request->notes }}
                            </div>

                        </div>

                    @endif


                    <div class="action-area">

                        @if($request->status === 'pending')

                            <form
                                method="POST"
                                action="{{ route(
                                    'reception-requests.process',
                                    $request
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="process-button"
                                >
                                    ✓ Process & Create Sample Request
                                </button>

                            </form>

                        @else

                            <div class="handled-info">

                                ✓ Processed

                                @if($request->handler)

                                    by {{ $request->handler->name }}

                                @endif

                                @if($request->handled_at)

                                    on
                                    {{ $request->handled_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @endif

                            </div>

                        @endif

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-2xl border
                            border-gray-200 p-12 text-center">

                    <div class="text-2xl font-bold">
                        Reception inbox is empty
                    </div>

                    <p class="text-gray-500 mt-2">
                        No patients have contacted reception yet.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>