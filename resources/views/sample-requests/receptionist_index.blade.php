<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Patient Sample Requests
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    View blood sample requests you created for registered patients.
                </p>
            </div>

            <a
                href="{{ route('sample-requests.receptionist.create') }}"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-semibold"
            >
                + Create Request
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


            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                @if($requests->isEmpty())

                    <div class="py-16 text-center">

                        <h3 class="text-lg font-semibold text-gray-900">
                            No requests created yet
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Create a blood sample request for a registered patient.
                        </p>

                    </div>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-gray-50 border-b">

                                <tr>
                                    <th class="px-5 py-4 text-left">Patient</th>
                                    <th class="px-5 py-4 text-left">Sample</th>
                                    <th class="px-5 py-4 text-left">Blood Type</th>
                                    <th class="px-5 py-4 text-left">Sample Code</th>
                                    <th class="px-5 py-4 text-left">Request</th>
                                    <th class="px-5 py-4 text-left">Current Progress</th>
                                    <th class="px-5 py-4 text-left">Collector</th>
                                    <th class="px-5 py-4 text-left">Date</th>
                                </tr>

                            </thead>


                            <tbody class="divide-y">

                                @foreach($requests as $request)

                                    @php
                                        $bloodSample = $request->bloodSample;

                                        $transportation = $bloodSample
                                            ?->transportations
                                            ?->first();

                                        if ($request->status === 'declined') {
                                            $progress = 'Request Declined';
                                        }
                                        elseif ($bloodSample && $bloodSample->status === 'accepted') {
                                            $progress = 'Sample Accepted';
                                        }
                                        elseif ($bloodSample && $bloodSample->status === 'rejected') {
                                            $progress = 'Sample Rejected';
                                        }
                                        elseif ($transportation && $transportation->status === 'delivered') {
                                            $progress = 'Delivered';
                                        }
                                        elseif ($transportation && $transportation->status === 'in_transit') {
                                            $progress = 'In Transit';
                                        }
                                        elseif ($transportation) {
                                            $progress = 'Collector Assigned';
                                        }
                                        elseif ($request->status === 'approved') {
                                            $progress = 'Approved';
                                        }
                                        else {
                                            $progress = 'Waiting for Approval';
                                        }
                                    @endphp


                                    <tr>

                                        <td class="px-5 py-5">

                                            <div class="font-semibold">
                                                {{ $request->patient->name ?? 'Unknown' }}
                                            </div>

                                            <div class="text-sm text-gray-500">
                                                {{ $request->patient->email ?? '' }}
                                            </div>

                                        </td>


                                        <td class="px-5 py-5">
                                            {{ $request->sample_type }}
                                        </td>


                                        <td class="px-5 py-5">
                                            {{ $request->blood_type ?? '—' }}
                                        </td>


                                        <td class="px-5 py-5 font-semibold">
                                            {{ $bloodSample->sample_code ?? '—' }}
                                        </td>


                                        <td class="px-5 py-5">
                                            {{ ucfirst($request->status) }}
                                        </td>


                                        <td class="px-5 py-5">

                                            @if($progress === 'Sample Accepted')

                                                <span class="text-green-700 font-semibold">
                                                    ✓ Sample Accepted
                                                </span>

                                            @elseif(
                                                $progress === 'Sample Rejected'
                                                || $progress === 'Request Declined'
                                            )

                                                <span class="text-red-700 font-semibold">
                                                    {{ $progress }}
                                                </span>

                                            @elseif($progress === 'In Transit')

                                                <span class="text-blue-700 font-semibold">
                                                    🚚 In Transit
                                                </span>

                                            @else

                                                <span class="font-semibold text-gray-700">
                                                    {{ $progress }}
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-5 py-5">

                                            @if($transportation)

                                                {{ $transportation->transporter->name ?? 'Assigned' }}

                                            @else

                                                <span class="text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-5 py-5 whitespace-nowrap">

                                            {{ $request->created_at->format('d M Y') }}

                                            <div class="text-sm text-gray-500">
                                                {{ $request->created_at->format('h:i A') }}
                                            </div>

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