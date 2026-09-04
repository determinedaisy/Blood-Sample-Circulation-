<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Blood Sample Requests
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Review requests, coordinate sample collection, and assign doctors after delivery.
            </p>
        </div>
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="mx-auto w-full max-w-[1800px] px-3 sm:px-5 lg:px-6">


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


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">


                @if($requests->isEmpty())


                    {{-- No Requests --}}
                    <div class="py-16 text-center">

                        <h3 class="text-lg font-semibold text-gray-900">
                            No sample requests found
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            New patient and receptionist requests will appear here.
                        </p>

                    </div>


                @else


                    <div class="overflow-x-auto">

                        <table class="min-w-[1380px] w-full">


                            {{-- Table Header --}}
                            <thead class="bg-gray-50 border-b border-gray-200">

                                <tr>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Patient
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
                                        Sample Code
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Request Status
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700">
                                        Date
                                    </th>

                                    <th class="px-5 py-4 text-left text-sm font-semibold text-gray-700 min-w-[300px]">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            {{-- Table Body --}}
                            <tbody class="divide-y divide-gray-200">


                                @foreach($requests as $request)


                                    @php

                                        /*
                                         * Find the most recent transportation
                                         * record connected to this request's
                                         * blood sample.
                                         */

                                        $transportation = $request
                                            ->bloodSample
                                            ?->transportations
                                            ?->sortByDesc('id')
                                            ?->first();

                                    @endphp


                                    <tr class="align-top hover:bg-gray-50">


                                        {{-- Patient --}}
                                        <td class="px-5 py-5">

                                            <div class="font-semibold text-gray-900">
                                                {{ $request->patient->name ?? 'Unknown Patient' }}
                                            </div>

                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $request->patient->email ?? '' }}
                                            </div>

                                        </td>


                                        {{-- Requested By --}}
                                        <td class="px-5 py-5">

                                            <div class="font-medium text-gray-900">
                                                {{ $request->requester->name ?? 'Unknown' }}
                                            </div>


                                            @if(
                                                (int) $request->requested_by
                                                ===
                                                (int) $request->patient_id
                                            )

                                                <div class="text-xs text-gray-500 mt-1">
                                                    Patient
                                                </div>

                                            @else

                                                <div class="text-xs text-gray-500 mt-1">
                                                    Receptionist
                                                </div>

                                            @endif

                                        </td>


                                        {{-- Sample Type --}}
                                        <td class="px-5 py-5 text-gray-900">

                                            {{ $request->sample_type }}

                                        </td>


                                        {{-- Blood Type --}}
                                        <td class="px-5 py-5 text-gray-900">

                                            {{ $request->blood_type ?? '—' }}

                                        </td>


                                        {{-- Sample Code --}}
                                        <td class="px-5 py-5 font-semibold text-gray-900">

                                            {{ $request->bloodSample->sample_code ?? '—' }}

                                        </td>


                                        {{-- Request Status --}}
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


                                        {{-- Date --}}
                                        <td class="px-5 py-5 whitespace-nowrap text-gray-900">

                                            {{ $request->created_at->format('d M Y') }}

                                            <div class="text-sm text-gray-500 mt-1">

                                                {{ $request->created_at->format('h:i A') }}

                                            </div>

                                        </td>


                                        {{-- Action --}}
                                        <td class="px-5 py-5">


                                            {{-- =====================================================
                                                1. PENDING REQUEST
                                            ====================================================== --}}

                                            @if($request->status === 'pending')


                                                <div class="flex flex-wrap gap-2">


                                                    {{-- Approve --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('sample-requests.approve', $request) }}"
                                                    >

                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700"
                                                        >
                                                            Approve
                                                        </button>

                                                    </form>


                                                    {{-- Decline --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('sample-requests.decline', $request) }}"
                                                    >

                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700"
                                                            onclick="return confirm('Decline this sample request?')"
                                                        >
                                                            Decline
                                                        </button>

                                                    </form>


                                                </div>



                                            {{-- =====================================================
                                                2. APPROVED REQUEST
                                            ====================================================== --}}

                                            @elseif($request->status === 'approved')



                                                {{-- =================================================
                                                    NO COLLECTOR ASSIGNED YET
                                                ================================================== --}}

                                                @if(!$transportation)


                                                    <div class="mb-3">

                                                        <div class="font-semibold text-blue-700">
                                                            Assign Collector
                                                        </div>

                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Choose the collector and transportation details.
                                                        </div>

                                                    </div>


                                                    <form
                                                        method="POST"
                                                        action="{{ route('sample-requests.assign-collector', $request) }}"
                                                        class="space-y-3"
                                                    >

                                                        @csrf


                                                        {{-- Collector --}}
                                                        <div>

                                                            <select
                                                                name="transported_by"
                                                                required
                                                                class="w-full rounded-lg border-gray-300 text-sm"
                                                            >

                                                                <option value="">
                                                                    Select Collector
                                                                </option>


                                                                @foreach($collectors as $collector)

                                                                    <option
                                                                        value="{{ $collector->id }}"
                                                                        {{ old('transported_by') == $collector->id ? 'selected' : '' }}
                                                                    >
                                                                        {{ $collector->name }}
                                                                    </option>

                                                                @endforeach


                                                            </select>

                                                        </div>


                                                        {{-- Collection Center --}}
                                                        <div>

                                                            <select
                                                                name="collection_center_id"
                                                                required
                                                                class="w-full rounded-lg border-gray-300 text-sm"
                                                            >

                                                                <option value="">
                                                                    Select Collection Center
                                                                </option>


                                                                @foreach($collectionCenters as $center)

                                                                    <option
                                                                        value="{{ $center->id }}"
                                                                        {{ old('collection_center_id') == $center->id ? 'selected' : '' }}
                                                                    >
                                                                        {{ $center->name }}
                                                                    </option>

                                                                @endforeach


                                                            </select>

                                                        </div>


                                                        {{-- Laboratory --}}
                                                        <div>

                                                            <select
                                                                name="laboratory_id"
                                                                required
                                                                class="w-full rounded-lg border-gray-300 text-sm"
                                                            >

                                                                <option value="">
                                                                    Select Laboratory
                                                                </option>


                                                                @foreach($laboratories as $laboratory)

                                                                    <option
                                                                        value="{{ $laboratory->id }}"
                                                                        {{ old('laboratory_id') == $laboratory->id ? 'selected' : '' }}
                                                                    >
                                                                        {{ $laboratory->name }}
                                                                        (Daily capacity: {{ $laboratory->daily_capacity ?? 20 }})
                                                                    </option>

                                                                @endforeach


                                                            </select>

                                                        </div>

                                                        {{-- Scheduled laboratory testing date --}}
                                                        <div>
                                                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                                                Scheduled Testing Date
                                                            </label>

                                                            <input
                                                                type="date"
                                                                name="scheduled_test_date"
                                                                value="{{ old('scheduled_test_date', now()->toDateString()) }}"
                                                                min="{{ now()->toDateString() }}"
                                                                required
                                                                class="w-full rounded-lg border-gray-300 text-sm"
                                                            >

                                                            @error('scheduled_test_date')
                                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                            @enderror

                                                            @error('laboratory_id')
                                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>


                                                        <button
                                                            type="submit"
                                                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700"
                                                        >
                                                            Assign Collector
                                                        </button>


                                                    </form>



                                                {{-- =================================================
                                                    COLLECTOR HAS BEEN ASSIGNED
                                                ================================================== --}}

                                                @else


                                                    {{-- Collector Information --}}
                                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 mb-3">


                                                        <div class="font-semibold text-green-700">
                                                            ✓ Collector Assigned
                                                        </div>


                                                        <div class="text-sm text-gray-800 mt-1">

                                                            {{ $transportation->transporter->name ?? 'Unknown Collector' }}

                                                        </div>


                                                        <div class="text-xs text-gray-500 mt-1">

                                                            Transportation:

                                                            <span class="font-semibold">

                                                                {{ ucwords(
                                                                    str_replace(
                                                                        '_',
                                                                        ' ',
                                                                        $transportation->status
                                                                    )
                                                                ) }}

                                                            </span>

                                                        </div>

                                                        @if($transportation->scheduled_test_date)
                                                            <div class="text-xs text-blue-700 mt-2">
                                                                Scheduled test: {{ $transportation->scheduled_test_date->format('d M Y') }}
                                                            </div>
                                                        @endif


                                                    </div>



                                                    {{-- =============================================
                                                        SAMPLE NOT DELIVERED YET
                                                    ============================================== --}}

                                                    @if($transportation->status !== 'delivered')


                                                        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-3">

                                                            <div class="text-sm font-semibold text-yellow-800">
                                                                Waiting for Sample Delivery
                                                            </div>

                                                            <div class="text-xs text-yellow-700 mt-1">
                                                                A doctor can be assigned after the sample reaches the laboratory.
                                                            </div>

                                                        </div>



                                                    {{-- =============================================
                                                        SAMPLE DELIVERED
                                                    ============================================== --}}

                                                    @else


                                                        {{-- Doctor already assigned --}}
                                                        @if($request->assignedDoctor)


                                                            <div class="rounded-xl border border-green-200 bg-green-50 p-3">


                                                                <div class="font-semibold text-green-800">
                                                                    ✓ Doctor Assigned
                                                                </div>


                                                                <div class="text-sm text-gray-800 mt-1">

                                                                    {{ $request->assignedDoctor->name }}

                                                                </div>


                                                                @if(
                                                                    $request
                                                                        ->assignedDoctor
                                                                        ?->doctorProfile
                                                                        ?->specialization
                                                                )

                                                                    <div class="text-xs text-gray-500 mt-1">

                                                                        {{ $request
                                                                            ->assignedDoctor
                                                                            ->doctorProfile
                                                                            ->specialization }}

                                                                    </div>

                                                                @endif


                                                            </div>



                                                        {{-- Doctor not assigned yet --}}
                                                        @else


                                                            <div class="rounded-xl border border-purple-200 bg-purple-50 p-3">


                                                                <div class="font-semibold text-purple-800">
                                                                    Sample Delivered
                                                                </div>


                                                                <div class="text-xs text-purple-700 mt-1 mb-3">
                                                                    The sample has reached the laboratory. You can now assign a doctor.
                                                                </div>


                                                                <form
                                                                    method="POST"
                                                                    action="{{ route('sample-requests.assign-doctor', $request) }}"
                                                                    class="space-y-3"
                                                                >

                                                                    @csrf


                                                                    <select
                                                                        name="assigned_doctor_id"
                                                                        required
                                                                        class="w-full rounded-lg border-gray-300 text-sm"
                                                                    >

                                                                        <option value="">
                                                                            Select Doctor
                                                                        </option>


                                                                        @foreach($availableDoctors as $doctor)

                                                                            <option
                                                                                value="{{ $doctor->id }}"
                                                                            >

                                                                                {{ $doctor->name }}

                                                                                @if(
                                                                                    $doctor
                                                                                        ->doctorProfile
                                                                                        ?->specialization
                                                                                )
                                                                                    -
                                                                                    {{ $doctor
                                                                                        ->doctorProfile
                                                                                        ->specialization }}
                                                                                @endif

                                                                            </option>

                                                                        @endforeach


                                                                    </select>


                                                                    <button
                                                                        type="submit"
                                                                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700"
                                                                    >
                                                                        Assign Doctor
                                                                    </button>


                                                                </form>


                                                            </div>


                                                        @endif


                                                    @endif


                                                @endif



                                            {{-- =====================================================
                                                3. DECLINED
                                            ====================================================== --}}

                                            @elseif($request->status === 'declined')


                                                <span class="text-sm font-semibold text-red-600">
                                                    Request Declined
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
