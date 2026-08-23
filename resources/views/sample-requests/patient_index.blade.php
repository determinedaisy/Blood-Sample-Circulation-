<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    My Blood Sample Requests
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    Track your blood sample request history.
                </p>
            </div>

            <a
                href="{{ route('sample-requests.create') }}"
                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold"
            >
                + New Request
            </a>

        </div>
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>

            @endif


            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                @if($requests->isEmpty())

                    <div class="py-16 text-center">

                        <h3 class="text-lg font-semibold text-gray-900">
                            No sample requests yet
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Submit your first blood sample request.
                        </p>

                    </div>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-gray-50 border-b border-gray-200">

                                <tr>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Requested
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Requested By
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Type
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Blood Type
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Request Status
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Code
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Current Progress
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Tracking
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @foreach($requests as $request)

                                    @php

                                        $bloodSample = $request->bloodSample;

                                        $transportation = $bloodSample
                                            ?->transportations
                                            ?->first();


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Work out the patient's current progress
                                        |--------------------------------------------------------------------------
                                        */

                                        if ($request->status === 'declined') {

                                            $progress = 'Request Declined';

                                        } elseif (
                                            $bloodSample &&
                                            $bloodSample->status === 'accepted'
                                        ) {

                                            $progress = 'Sample Accepted';

                                        } elseif (
                                            $bloodSample &&
                                            $bloodSample->status === 'rejected'
                                        ) {

                                            $progress = 'Sample Rejected';

                                        } elseif (
                                            $transportation &&
                                            $transportation->status === 'delivered'
                                        ) {

                                            $progress = 'Delivered';

                                        } elseif (
                                            $transportation &&
                                            $transportation->status === 'in_transit'
                                        ) {

                                            $progress = 'In Transit';

                                        } elseif ($transportation) {

                                            $progress = 'Collector Assigned';

                                        } elseif ($request->status === 'approved') {

                                            $progress = 'Request Approved';

                                        } else {

                                            $progress = 'Waiting for Approval';

                                        }

                                    @endphp


                                    <tr>

                                        {{-- Requested date --}}
                                        <td class="px-6 py-5 whitespace-nowrap">

                                            {{ $request->created_at->format('d M Y') }}

                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $request->created_at->format('h:i A') }}
                                            </div>

                                        </td>


                                        {{-- Requested By --}}
                                        <td class="px-6 py-5">

                                            @if(
                                                (int) $request->requested_by
                                                ===
                                                (int) $request->patient_id
                                            )

                                                <span class="font-semibold text-gray-900">
                                                    Self
                                                </span>

                                                <div class="text-xs text-gray-500 mt-1">
                                                    You created this request
                                                </div>

                                            @else

                                                <span class="font-semibold text-gray-900">
                                                    Receptionist
                                                </span>

                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $request->requester->name ?? 'Receptionist' }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- Sample Type --}}
                                        <td class="px-6 py-5">
                                            {{ $request->sample_type }}
                                        </td>


                                        {{-- Blood Type --}}
                                        <td class="px-6 py-5">
                                            {{ $request->blood_type ?? 'Not specified' }}
                                        </td>


                                        {{-- Request Status --}}
                                        <td class="px-6 py-5">

                                            @if($request->status === 'pending')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                                                    Pending
                                                </span>

                                            @elseif($request->status === 'approved')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                                                    Approved
                                                </span>

                                            @elseif($request->status === 'declined')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">
                                                    Declined
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                                                    {{ ucfirst($request->status) }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Sample Code --}}
                                        <td class="px-6 py-5 font-semibold text-gray-900">

                                            {{ $bloodSample->sample_code ?? 'Not created yet' }}

                                        </td>


                                        {{-- Current Progress --}}
                                        <td class="px-6 py-5">

                                            @if($progress === 'Sample Accepted')

                                                <span class="font-semibold text-green-700">
                                                    ✓ Sample Accepted
                                                </span>

                                            @elseif(
                                                $progress === 'Sample Rejected'
                                                || $progress === 'Request Declined'
                                            )

                                                <span class="font-semibold text-red-700">
                                                    {{ $progress }}
                                                </span>

                                            @elseif($progress === 'Delivered')

                                                <span class="font-semibold text-purple-700">
                                                    ✓ Delivered
                                                </span>

                                            @elseif($progress === 'In Transit')

                                                <span class="font-semibold text-blue-700">
                                                    🚚 In Transit
                                                </span>

                                            @elseif($progress === 'Collector Assigned')

                                                <span class="font-semibold text-yellow-700">
                                                    Collector Assigned
                                                </span>

                                                @if($transportation?->transporter)

                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $transportation->transporter->name }}
                                                    </div>

                                                @endif

                                            @elseif($progress === 'Request Approved')

                                                <span class="font-semibold text-green-700">
                                                    Request Approved
                                                </span>

                                            @else

                                                <span class="font-semibold text-gray-600">
                                                    Waiting for Approval
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Tracking --}}
                                        <td class="px-6 py-5">

                                            @if($bloodSample)

                                                <a
                                                    href="{{ route('sample-requests.tracking', $request) }}"
                                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700"
                                                >
                                                    Track
                                                </a>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    Not available
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>