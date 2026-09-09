
<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-gray-900">
                    My Blood Sample Requests
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    Track your requests and schedule home sample collection.
                </p>

            </div>

            {{-- NEW REQUEST --}}
            <a
                href="{{ route('sample-requests.create') }}"
                class="inline-flex items-center justify-center
                       px-5 py-2.5 rounded-lg
                       bg-blue-600 text-white
                       font-semibold
                       shadow-sm
                       hover:bg-blue-700
                       focus:outline-none
                       focus:ring-2
                       focus:ring-blue-500
                       focus:ring-offset-2
                       whitespace-nowrap"
            >
                + New Request
            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- SUCCESS --}}
            @if(session('success'))

                <div
                    class="mb-6 rounded-xl
                           border border-green-200
                           bg-green-50 px-4 py-3
                           text-green-800"
                >
                    {{ session('success') }}
                </div>

            @endif


            {{-- ERROR --}}
            @if(session('error'))

                <div
                    class="mb-6 rounded-xl
                           border border-red-200
                           bg-red-50 px-4 py-3
                           text-red-800"
                >
                    {{ session('error') }}
                </div>

            @endif


            <div
                class="bg-white border border-gray-200
                       rounded-2xl shadow-sm overflow-hidden"
            >


                @if($requests->isEmpty())


                    {{-- EMPTY STATE --}}
                    <div class="py-16 px-6 text-center">

                        <div
                            class="mx-auto mb-5
                                   w-14 h-14
                                   flex items-center justify-center
                                   rounded-full
                                   bg-blue-50
                                   text-blue-600
                                   text-2xl"
                        >
                            +
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            No sample requests yet
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Submit your first blood sample request.
                        </p>

                        {{-- NEW REQUEST BUTTON --}}
                        <div class="mt-6">

                            <a
                                href="{{ route('sample-requests.create') }}"
                                class="inline-flex items-center justify-center
                                       px-6 py-3
                                       rounded-lg
                                       bg-blue-600
                                       text-white
                                       font-semibold
                                       shadow-sm
                                       hover:bg-blue-700
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-blue-500
                                       focus:ring-offset-2"
                            >
                                + New Request
                            </a>

                        </div>

                    </div>


                @else


                    <div class="overflow-x-auto">

                        <table class="min-w-full">


                            <thead
                                class="bg-gray-50
                                       border-b border-gray-200"
                            >

                                <tr>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Requested
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Requested By
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Blood Type
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Request Status
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Code
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Current Progress
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[220px]">
                                        Collection
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Tracking
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">


                                @foreach($requests as $request)


                                    @php

                                        $bloodSample =
                                            $request->bloodSample;

                                        $transportation =
                                            $bloodSample
                                                ?->transportations
                                                ?->sortByDesc('id')
                                                ?->first();

                                        $homeCollection =
                                            $request->homeCollection;


                                        /*
                                        |--------------------------------------------------------------------------
                                        | CURRENT PROGRESS
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            $request->status
                                            ===
                                            'declined'
                                        ) {

                                            $progress =
                                                'Request Declined';

                                        } elseif (
                                            $bloodSample
                                            &&
                                            $bloodSample->status
                                            ===
                                            'accepted'
                                        ) {

                                            $progress =
                                                'Sample Accepted';

                                        } elseif (
                                            $bloodSample
                                            &&
                                            $bloodSample->status
                                            ===
                                            'rejected'
                                        ) {

                                            $progress =
                                                'Sample Rejected';

                                        } elseif (
                                            $transportation
                                            &&
                                            $transportation->status
                                            ===
                                            'delivered'
                                        ) {

                                            $progress =
                                                'Delivered';

                                        } elseif (
                                            $transportation
                                            &&
                                            $transportation->status
                                            ===
                                            'in_transit'
                                        ) {

                                            $progress =
                                                'In Transit';

                                        } elseif (
                                            $homeCollection
                                            &&
                                            $homeCollection->status
                                            ===
                                            'collected'
                                        ) {

                                            $progress =
                                                'Home Sample Collected';

                                        } elseif (
                                            $homeCollection
                                            &&
                                            $homeCollection->status
                                            ===
                                            'arrived'
                                        ) {

                                            $progress =
                                                'Collector Arrived';

                                        } elseif (
                                            $homeCollection
                                            &&
                                            $homeCollection->status
                                            ===
                                            'on_the_way'
                                        ) {

                                            $progress =
                                                'Collector On The Way';

                                        } elseif (
                                            $homeCollection
                                            &&
                                            $homeCollection->status
                                            ===
                                            'assigned'
                                        ) {

                                            $progress =
                                                'Home Collector Assigned';

                                        } elseif (
                                            $homeCollection
                                        ) {

                                            $progress =
                                                'Home Collection Requested';

                                        } elseif (
                                            $transportation
                                        ) {

                                            $progress =
                                                'Collector Assigned';

                                        } elseif (
                                            $request->status
                                            ===
                                            'approved'
                                        ) {

                                            $progress =
                                                'Request Approved';

                                        } else {

                                            $progress =
                                                'Waiting for Approval';

                                        }

                                    @endphp


                                    <tr class="align-top hover:bg-gray-50">


                                        {{-- REQUEST DATE --}}
                                        <td class="px-5 py-5 whitespace-nowrap">

                                            {{ $request->created_at->format('d M Y') }}

                                            <div class="text-sm text-gray-500 mt-1">

                                                {{ $request->created_at->format('h:i A') }}

                                            </div>

                                        </td>


                                        {{-- REQUESTED BY --}}
                                        <td class="px-5 py-5">

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


                                        {{-- SAMPLE TYPE --}}
                                        <td class="px-5 py-5">

                                            <div class="font-medium text-gray-900">
                                                {{ $request->sample_type }}
                                            </div>

                                        </td>


                                        {{-- BLOOD TYPE --}}
                                        <td class="px-5 py-5">

                                            {{ $request->blood_type ?? 'Not specified' }}

                                        </td>


                                        {{-- REQUEST STATUS --}}
                                        <td class="px-5 py-5">

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


                                        {{-- SAMPLE CODE --}}
                                        <td class="px-5 py-5 font-semibold text-gray-900">

                                            {{ $bloodSample->sample_code ?? 'Not created yet' }}

                                        </td>


                                        {{-- CURRENT PROGRESS --}}
                                        <td class="px-5 py-5">

                                            @if($progress === 'Sample Accepted')

                                                <span class="font-semibold text-green-700">
                                                    ✓ Sample Accepted
                                                </span>

                                            @elseif(
                                                $progress === 'Sample Rejected'
                                                ||
                                                $progress === 'Request Declined'
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

                                            @elseif($progress === 'Home Sample Collected')

                                                <span class="font-semibold text-green-700">
                                                    ✓ Home Sample Collected
                                                </span>

                                            @elseif($progress === 'Collector Arrived')

                                                <span class="font-semibold text-purple-700">
                                                    📍 Collector Arrived
                                                </span>

                                            @elseif($progress === 'Collector On The Way')

                                                <span class="font-semibold text-blue-700">
                                                    🚗 Collector On The Way
                                                </span>

                                            @elseif($progress === 'Home Collector Assigned')

                                                <span class="font-semibold text-blue-700">
                                                    Home Collector Assigned
                                                </span>

                                            @elseif($progress === 'Home Collection Requested')

                                                <span class="font-semibold text-purple-700">
                                                    🏠 Home Collection Requested
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


                                        {{-- COLLECTION METHOD --}}
                                        <td class="px-5 py-5">

                                            @if($homeCollection)

                                                <div class="rounded-xl border border-purple-200 bg-purple-50 p-3">

                                                    <div>

                                                        <span class="inline-flex px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-bold">
                                                            🏠 Home Collection
                                                        </span>

                                                    </div>

                                                    <div class="text-sm font-semibold text-gray-900 mt-3">

                                                        {{ $homeCollection->preferred_date->format('d M Y') }}

                                                    </div>

                                                    <div class="text-xs text-gray-600 mt-1">

                                                        {{ $homeCollection->preferred_time }}

                                                    </div>

                                                    <div class="text-xs font-semibold mt-3">

                                                        @if($homeCollection->status === 'pending')

                                                            <span class="text-yellow-700">
                                                                Waiting for Assignment
                                                            </span>

                                                        @elseif($homeCollection->status === 'assigned')

                                                            <span class="text-blue-700">
                                                                Collector Assigned
                                                            </span>

                                                            @if($homeCollection->assignedCollector)

                                                                <div class="text-gray-500 font-normal mt-1">
                                                                    {{ $homeCollection->assignedCollector->name }}
                                                                </div>

                                                            @endif

                                                        @elseif($homeCollection->status === 'on_the_way')

                                                            <span class="text-blue-700">
                                                                🚗 On The Way
                                                            </span>

                                                        @elseif($homeCollection->status === 'arrived')

                                                            <span class="text-purple-700">
                                                                📍 Collector Arrived
                                                            </span>

                                                        @elseif($homeCollection->status === 'collected')

                                                            <span class="text-green-700">
                                                                ✓ Sample Collected
                                                            </span>

                                                        @elseif($homeCollection->status === 'cancelled')

                                                            <span class="text-red-700">
                                                                Cancelled
                                                            </span>

                                                        @endif

                                                    </div>

                                                </div>


                                            @elseif(
                                                $request->status === 'approved'
                                                &&
                                                $bloodSample
                                                &&
                                                !$transportation
                                            )

                                                <a
                                                    href="{{ route(
                                                        'sample-requests.home-collection.create',
                                                        $request
                                                    ) }}"
                                                    class="inline-flex items-center justify-center
                                                           px-4 py-2 rounded-lg
                                                           bg-purple-600 text-white
                                                           text-sm font-semibold
                                                           hover:bg-purple-700"
                                                >
                                                    🏠 Home Collection
                                                </a>

                                                <div class="text-xs text-gray-500 mt-2">
                                                    Schedule collection from your location.
                                                </div>


                                            @elseif($request->status === 'pending')

                                                <span class="text-sm text-gray-400">
                                                    Available after approval
                                                </span>


                                            @elseif($transportation)

                                                <span class="text-sm text-gray-400">
                                                    Normal collection already arranged
                                                </span>


                                            @else

                                                <span class="text-sm text-gray-400">
                                                    Not available
                                                </span>

                                            @endif

                                        </td>


                                        {{-- TRACKING --}}
                                        <td class="px-5 py-5">

                                            @if($bloodSample)

                                                <a
                                                    href="{{ route(
                                                        'sample-requests.tracking',
                                                        $request
                                                    ) }}"
                                                    class="inline-flex items-center
                                                           px-4 py-2 rounded-lg
                                                           bg-blue-600 text-white
                                                           text-sm font-semibold
                                                           hover:bg-blue-700"
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
