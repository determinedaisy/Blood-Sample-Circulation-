
<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-2xl font-bold text-gray-900">
                Home Collections
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Review patient home collection appointments and assign sample collectors.
            </p>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            @if(session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>

            @endif


            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                @if($homeCollections->isEmpty())

                    <div class="py-16 text-center">

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

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Address
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        GPS
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Status
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[240px]">
                                        Collector
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @foreach($homeCollections as $homeCollection)

                                    <tr class="align-top hover:bg-gray-50">

                                        <td class="px-5 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $homeCollection->patient->email ?? '' }}
                                            </div>

                                        </td>


                                        <td class="px-5 py-5">

                                            <div class="font-bold text-blue-700">
                                                {{ $homeCollection->sampleRequest?->bloodSample?->sample_code ?? '—' }}
                                            </div>

                                            <div class="text-sm text-gray-700 mt-1">
                                                {{ $homeCollection->sampleRequest?->sample_type ?? '—' }}
                                            </div>

                                        </td>


                                        <td class="px-5 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $homeCollection->preferred_date->format('d M Y') }}
                                            </div>

                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $homeCollection->preferred_time }}
                                            </div>

                                        </td>


                                        <td class="px-5 py-5">

                                            <div class="text-sm text-gray-800">
                                                {{ $homeCollection->address }}
                                            </div>

                                            @if($homeCollection->instructions)

                                                <div class="text-xs text-gray-500 mt-2">
                                                    Note:
                                                    {{ $homeCollection->instructions }}
                                                </div>

                                            @endif

                                        </td>


                                        <td class="px-5 py-5">

                                            @if(
                                                $homeCollection->latitude
                                                &&
                                                $homeCollection->longitude
                                            )

                                                <div class="text-xs text-gray-700">
                                                    {{ $homeCollection->latitude }}
                                                </div>

                                                <div class="text-xs text-gray-700">
                                                    {{ $homeCollection->longitude }}
                                                </div>

                                                <div class="text-xs text-green-700 font-semibold mt-1">
                                                    GPS Available
                                                </div>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    No GPS
                                                </span>

                                            @endif

                                        </td>


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
                                                    On The Way
                                                </span>

                                            @elseif($homeCollection->status === 'arrived')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-semibold">
                                                    Arrived
                                                </span>

                                            @elseif($homeCollection->status === 'collected')

                                                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                                                    Collected
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                                                    {{ ucfirst($homeCollection->status) }}
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-5 py-5">

                                            @if($homeCollection->assignedCollector)

                                                <div class="rounded-xl border border-green-200 bg-green-50 p-3">

                                                    <div class="font-semibold text-green-800">
                                                        ✓ Collector Assigned
                                                    </div>

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

                                                <form
                                                    method="POST"
                                                    action="{{ route('home-collections.assign', $homeCollection) }}"
                                                    class="space-y-3"
                                                >

                                                    @csrf

                                                    <select
                                                        name="assigned_collector_id"
                                                        required
                                                        class="w-full rounded-lg border-gray-300 text-sm"
                                                    >

                                                        <option value="">
                                                            Select Collector
                                                        </option>

                                                        @foreach($collectors as $collector)

                                                            <option value="{{ $collector->id }}">
                                                                {{ $collector->name }}
                                                            </option>

                                                        @endforeach

                                                    </select>


                                                    <button
                                                        type="submit"
                                                        class="w-full px-4 py-2 rounded-lg bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700"
                                                    >
                                                        Assign Home Collector
                                                    </button>

                                                </form>

                                            @else

                                                <span class="text-sm text-gray-500">
                                                    No collector assigned
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