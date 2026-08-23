<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                My Assigned Samples
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Delivered blood samples assigned to you by the administrator.
            </p>
        </div>
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif


            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif


            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                @if($requests->isEmpty())

                    {{-- Empty State --}}
                    <div class="py-16 px-6 text-center">

                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                            <span class="text-2xl">🩺</span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            No Assigned Samples
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            Delivered samples assigned to you by an administrator will appear here.
                        </p>

                    </div>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            {{-- TABLE HEADER --}}
                            <thead class="bg-gray-50 border-b border-gray-200">

                                <tr>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Code
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Patient
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Type
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Blood Type
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Laboratory
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Delivery
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Sample Status
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            {{-- TABLE BODY --}}
                            <tbody class="divide-y divide-gray-200">

                                @foreach($requests as $request)

                                    @php

                                        /*
                                         * Get the most recent transportation
                                         * belonging to this blood sample.
                                         */
                                        $transportation = $request
                                            ->bloodSample
                                            ?->transportations
                                            ?->sortByDesc('id')
                                            ?->first();

                                    @endphp


                                    <tr class="hover:bg-gray-50">


                                        {{-- SAMPLE CODE --}}
                                        <td class="px-6 py-5">

                                            <span class="font-bold text-blue-700">
                                                {{ $request->bloodSample->sample_code ?? '—' }}
                                            </span>

                                        </td>


                                        {{-- PATIENT --}}
                                        <td class="px-6 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $request->patient->name ?? 'Unknown Patient' }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $request->patient->email ?? '' }}
                                            </div>

                                        </td>


                                        {{-- SAMPLE TYPE --}}
                                        <td class="px-6 py-5 text-gray-800">

                                            {{ $request->sample_type }}

                                        </td>


                                        {{-- BLOOD TYPE --}}
                                        <td class="px-6 py-5">

                                            @if($request->blood_type)

                                                <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 font-semibold text-sm">
                                                    {{ $request->blood_type }}
                                                </span>

                                            @else

                                                <span class="text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- LABORATORY --}}
                                        <td class="px-6 py-5 text-gray-800">

                                            {{ $transportation?->laboratory?->name ?? '—' }}

                                        </td>


                                        {{-- DELIVERY STATUS --}}
                                        <td class="px-6 py-5">

                                            @if($transportation?->status === 'delivered')

                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">

                                                    ✓ Delivered

                                                </span>

                                            @elseif($transportation)

                                                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">

                                                    {{ ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $transportation->status
                                                        )
                                                    ) }}

                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-semibold">

                                                    No Transportation

                                                </span>

                                            @endif

                                        </td>


                                        {{-- SAMPLE STATUS --}}
                                        <td class="px-6 py-5">

                                            @if($request->bloodSample)

                                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">

                                                    {{ ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $request->bloodSample->status
                                                        )
                                                    ) }}

                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-semibold">

                                                    Unknown

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTION --}}
                                        <td class="px-6 py-5">

                                            <a
                                                href="{{ route('sample-requests.doctor.show', $request) }}"
                                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                                            >

                                                {{-- Eye Icon --}}
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>

                                                View Case

                                            </a>

                                        </td>


                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>


            {{-- Information Card --}}
            @if(!$requests->isEmpty())

                <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <div class="text-xl">
                            ℹ️
                        </div>

                        <div>

                            <div class="font-semibold text-blue-900">
                                Doctor Case Review
                            </div>

                            <div class="text-sm text-blue-700 mt-1">
                                Open a case to review the patient's request information,
                                clinical notes, sample details, transportation information,
                                and available reports.
                            </div>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>