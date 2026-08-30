<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Home Collections
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Manage home collection appointments, assign collectors,
                and send collected samples to laboratories.
            </p>
        </div>
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ERROR --}}
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif


            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">

                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">


                @if($homeCollections->isEmpty())

                    <div class="py-16 text-center">

                        <div class="text-4xl mb-4">
                            🏠
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            No home collection requests
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            Patient home collection requests will appear here.
                        </p>

                    </div>


                @else


                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-gray-50 border-b border-gray-200">

                                <tr>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Patient
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Appointment
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[220px]">
                                        Address
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        GPS
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Home Status
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[220px]">
                                        Request Approval
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[220px]">
                                        Collector
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[260px]">
                                        Laboratory Transport
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @foreach($homeCollections as $homeCollection)

                                    @php
                                        $bloodSample = $homeCollection->sampleRequest?->bloodSample;

                                        $transportation = $bloodSample
                                            ?->transportations
                                            ?->sortByDesc('id')
                                            ?->first();
                                    @endphp


                                    <tr class="align-top hover:bg-gray-50">


                                        {{-- PATIENT --}}
                                        <td class="px-5 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $homeCollection->patient->email ?? '' }}
                                            </div>

                                        </td>


                                        {{-- SAMPLE --}}
                                        <td class="px-5 py-5">

                                            <div class="font-bold text-blue-700">
                                                {{ $bloodSample?->sample_code ?? '—' }}
                                            </div>

                                            <div class="text-sm text-gray-700 mt-1">
                                                {{ $homeCollection->sampleRequest?->sample_type ?? '—' }}
                                            </div>

                                            @if($homeCollection->sampleRequest?->blood_type)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Blood:
                                                    {{ $homeCollection->sampleRequest->blood_type }}
                                                </div>
                                            @endif

                                        </td>


                                        {{-- APPOINTMENT --}}
                                        <td class="px-5 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $homeCollection->preferred_date->format('d M Y') }}
                                            </div>

                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $homeCollection->preferred_time }}
                                            </div>

                                        </td>


                                        {{-- ADDRESS --}}
                                        <td class="px-5 py-5">

                                            <div class="text-sm text-gray-800">
                                                {{ $homeCollection->address }}
                                            </div>

                                            @if($homeCollection->instructions)

                                                <div class="mt-3 rounded-lg bg-gray-50 border border-gray-200 p-2.5">

                                                    <div class="text-[11px] uppercase font-semibold text-gray-500">
                                                        Instructions
                                                    </div>

                                                    <div class="text-xs text-gray-700 mt-1">
                                                        {{ $homeCollection->instructions }}
                                                    </div>

                                                </div>

                                            @endif

                                        </td>


                                        {{-- GPS --}}
                                        <td class="px-5 py-5">

                                            @if($homeCollection->latitude && $homeCollection->longitude)

                                                <div class="rounded-lg bg-green-50 border border-green-200 p-3">

                                                    <div class="text-xs text-green-700 font-semibold">
                                                        ✓ GPS Available
                                                    </div>

                                                    <div class="text-[11px] text-gray-600 mt-2">
                                                        {{ $homeCollection->latitude }}
                                                    </div>

                                                    <div class="text-[11px] text-gray-600">
                                                        {{ $homeCollection->longitude }}
                                                    </div>

                                                </div>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    No GPS
                                                </span>

                                            @endif

                                        </td>


                                        {{-- HOME STATUS --}}
                                        <td class="px-5 py-5">

                                            @if($homeCollection->status === 'pending')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                                                    Pending
                                                </span>

                                            @elseif($homeCollection->status === 'assigned')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                                    Assigned
                                                </span>

                                            @elseif($homeCollection->status === 'on_the_way')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                                    🚗 On The Way
                                                </span>

                                            @elseif($homeCollection->status === 'arrived')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-semibold">
                                                    📍 Arrived
                                                </span>

                                            @elseif($homeCollection->status === 'collected')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                                                    ✓ Collected
                                                </span>

                                                @if($homeCollection->collected_at)

                                                    <div class="text-xs text-gray-500 mt-2">
                                                        {{ $homeCollection->collected_at->format('d M Y, h:i A') }}
                                                    </div>

                                                @endif

                                            @elseif($homeCollection->status === 'cancelled')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">
                                                    Cancelled
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                                                    {{ ucfirst(str_replace('_', ' ', $homeCollection->status)) }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- REQUEST APPROVAL --}}
                                        <td class="px-5 py-5">

                                            @php
                                                $sampleRequest = $homeCollection->sampleRequest;
                                            @endphp

                                            @if(!$sampleRequest)
                                                <span class="text-sm text-red-500">No linked sample request</span>

                                            @elseif($sampleRequest->status === 'pending')

                                                <div class="space-y-3">
                                                    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-3">
                                                        <div class="font-semibold text-yellow-800">Pending Approval</div>
                                                        <div class="text-xs text-yellow-700 mt-1">
                                                            Approve this request before assigning a home collector.
                                                        </div>
                                                    </div>

                                                    <form method="POST" action="{{ route('sample-requests.approve', $sampleRequest) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="w-full px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700">
                                                            Approve Request
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('sample-requests.decline', $sampleRequest) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="w-full px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                                            Decline Request
                                                        </button>
                                                    </form>
                                                </div>

                                            @elseif($sampleRequest->status === 'approved')

                                                <div class="rounded-xl border border-green-200 bg-green-50 p-3">
                                                    <div class="font-semibold text-green-800">✓ Approved</div>
                                                    @if($sampleRequest->approved_at)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ $sampleRequest->approved_at->format('d M Y, h:i A') }}
                                                        </div>
                                                    @endif
                                                </div>

                                            @elseif($sampleRequest->status === 'declined')

                                                <div class="rounded-xl border border-red-200 bg-red-50 p-3">
                                                    <div class="font-semibold text-red-800">Declined</div>
                                                </div>

                                            @else
                                                <span class="text-sm text-gray-500">
                                                    {{ ucfirst(str_replace('_', ' ', $sampleRequest->status)) }}
                                                </span>
                                            @endif

                                        </td>


                                        {{-- COLLECTOR --}}
                                        <td class="px-5 py-5">

                                            @if(!$homeCollection->sampleRequest || $homeCollection->sampleRequest->status !== 'approved')

                                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                                    <div class="text-sm text-gray-500">
                                                        Waiting for request approval
                                                    </div>
                                                </div>

                                            @elseif($homeCollection->assignedCollector)

                                                <div class="rounded-xl border border-green-200 bg-green-50 p-3">
                                                    <div class="font-semibold text-green-800">✓ Collector Assigned</div>

                                                    <div class="text-sm text-gray-800 mt-1">
                                                        {{ $homeCollection->assignedCollector->name }}
                                                    </div>

                                                    @if($homeCollection->assigned_at)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ $homeCollection->assigned_at->format('d M Y, h:i A') }}
                                                        </div>
                                                    @endif
                                                </div>

                                            @elseif($homeCollection->status === 'pending')

                                                <form method="POST"
                                                    action="{{ route('home-collections.assign', $homeCollection) }}"
                                                    class="space-y-3">

                                                    @csrf

                                                    <select name="assigned_collector_id" required
                                                        class="w-full rounded-lg border-gray-300 text-sm">

                                                        <option value="">Select Collector</option>

                                                        @foreach($collectors as $collector)
                                                            <option value="{{ $collector->id }}">
                                                                {{ $collector->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                    <button type="submit"
                                                        class="w-full px-4 py-2 rounded-lg bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700">
                                                        Assign Home Collector
                                                    </button>

                                                </form>

                                            @else
                                                <span class="text-sm text-gray-500">No collector assigned</span>
                                            @endif

                                        </td>


                                        {{-- LABORATORY TRANSPORT --}}
                                        <td class="px-5 py-5">

                                            @if($transportation)


                                                @if($transportation->status === 'delivered')

                                                    <div class="rounded-xl border border-green-200 bg-green-50 p-4">

                                                        <div class="font-semibold text-green-800">
                                                            ✓ Delivered to Laboratory
                                                        </div>

                                                        <div class="text-sm text-gray-800 mt-2">
                                                            {{ $transportation->laboratory->name ?? 'Laboratory' }}
                                                        </div>

                                                        @if($transportation->arrival_time)

                                                            <div class="text-xs text-gray-500 mt-2">
                                                                {{ $transportation->arrival_time->format('d M Y, h:i A') }}
                                                            </div>

                                                        @endif

                                                    </div>


                                                @elseif($transportation->status === 'in_transit')

                                                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                                                        <div class="font-semibold text-blue-800">
                                                            🚚 In Transit
                                                        </div>

                                                        <div class="text-sm text-gray-700 mt-2">
                                                            Destination:
                                                        </div>

                                                        <div class="font-semibold text-gray-900">
                                                            {{ $transportation->laboratory->name ?? 'Laboratory' }}
                                                        </div>

                                                        @if($transportation->transporter)

                                                            <div class="text-xs text-gray-500 mt-2">
                                                                Transporter:
                                                                {{ $transportation->transporter->name }}
                                                            </div>

                                                        @endif

                                                    </div>


                                                @else

                                                    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4">

                                                        <div class="font-semibold text-yellow-800">
                                                            Transportation Ready
                                                        </div>

                                                        <div class="text-sm text-gray-700 mt-2">
                                                            Destination:
                                                        </div>

                                                        <div class="font-semibold text-gray-900">
                                                            {{ $transportation->laboratory->name ?? 'Laboratory' }}
                                                        </div>

                                                        <div class="text-xs text-gray-500 mt-2">
                                                            Waiting for transportation to begin.
                                                        </div>

                                                    </div>

                                                @endif


                                            @elseif($homeCollection->status === 'collected')

                                                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                                                    <div class="font-semibold text-blue-900">
                                                        Ready for Laboratory
                                                    </div>

                                                    <p class="text-xs text-blue-700 mt-1 mb-3">
                                                        Select where this collected sample should be delivered.
                                                    </p>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('home-collections.send-to-laboratory', $homeCollection) }}"
                                                        class="space-y-3"
                                                    >

                                                        @csrf

                                                        <select
                                                            name="laboratory_id"
                                                            required
                                                            class="w-full rounded-lg border-gray-300 text-sm"
                                                        >

                                                            <option value="">
                                                                Select Laboratory
                                                            </option>

                                                            @foreach($laboratories as $laboratory)

                                                                <option value="{{ $laboratory->id }}">
                                                                    {{ $laboratory->name }}
                                                                </option>

                                                            @endforeach

                                                        </select>

                                                        <button
                                                            type="submit"
                                                            class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700"
                                                        >
                                                            Send to Laboratory
                                                        </button>

                                                    </form>

                                                </div>


                                            @elseif($homeCollection->status === 'cancelled')

                                                <span class="text-sm text-red-500">
                                                    Collection cancelled
                                                </span>


                                            @else

                                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">

                                                    <div class="text-sm text-gray-500">
                                                        Waiting for sample collection
                                                    </div>

                                                </div>

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