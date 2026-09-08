<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Blood Sample Review
        </h2>
    </x-slot>


    <div class="py-12 min-h-screen bg-gray-50">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif


            @if(session('warning'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('warning') }}
                </div>
            @endif


            @if($errors->any())

                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            @forelse($bloodSamples as $sample)

                @php
                    $overallStatus = $sample->overallStatus();

                    $transportation = $sample
                        ->transportations
                        ->sortByDesc('id')
                        ->first();
                @endphp


                <div class="bg-white shadow rounded-lg p-6 mb-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h3 class="text-lg font-bold mb-3">
                                Sample:
                                {{ $sample->sample_code ?? 'Not Assigned' }}
                            </h3>


                            <p class="mb-2">
                                <strong>Type:</strong>
                                {{ $sample->sample_type ?? 'Not specified' }}
                            </p>


                            <p class="mb-2">

                                <strong>Overall Status:</strong>

                                @if($overallStatus === 'accepted')

                                    <span class="text-green-600 font-semibold">
                                        Accepted
                                    </span>

                                @elseif($overallStatus === 'rejected')

                                    <span class="text-red-600 font-semibold">
                                        Rejected
                                    </span>

                                @elseif($overallStatus === 'delivered')

                                    <span class="text-purple-600 font-semibold">
                                        Delivered / Awaiting Lab Review
                                    </span>

                                @elseif($overallStatus === 'in_transit')

                                    <span class="text-blue-600 font-semibold">
                                        In Transit
                                    </span>

                                @elseif($overallStatus === 'collector_assigned')

                                    <span class="text-yellow-600 font-semibold">
                                        Collector Assigned
                                    </span>

                                @elseif($overallStatus === 'approved')

                                    <span class="text-green-600 font-semibold">
                                        Request Approved
                                    </span>

                                @elseif($overallStatus === 'declined')

                                    <span class="text-red-600 font-semibold">
                                        Request Declined
                                    </span>

                                @else

                                    <span class="text-yellow-600 font-semibold">
                                        Request Pending
                                    </span>

                                @endif

                            </p>


                            <p class="mb-2">
                                <strong>Patient:</strong>
                                {{ $sample->patient?->name ?? 'Not assigned' }}
                            </p>


                            <p class="mb-2">
                                <strong>Collector:</strong>

                                @if($sample->collector)

                                    {{ $sample->collector->name }}

                                @elseif($transportation?->transporter)

                                    {{ $transportation->transporter->name }}

                                @else

                                    Not assigned

                                @endif
                            </p>


                            @if($transportation)

                                <p class="mb-2">
                                    <strong>Transportation Status:</strong>
                                    {{ ucwords(str_replace('_', ' ', $transportation->status)) }}
                                </p>

                            @endif


                            @if($sample->collected_at)

                                <p class="mb-2">
                                    <strong>Collected At:</strong>
                                    {{ $sample->collected_at->format('d M Y, h:i A') }}
                                </p>

                            @endif


                            @if(
                                auth()->user()->role === 'admin'
                                && $sample->reviewer
                            )

                                <p class="mb-2">
                                    <strong>Reviewed By:</strong>
                                    {{ $sample->reviewer->name }}
                                </p>


                                <p class="mb-2">
                                    <strong>Lab Staff ID:</strong>
                                    {{ $sample->reviewer->id }}
                                </p>


                                @if($sample->reviewed_at)

                                    <p class="mb-2">
                                        <strong>Reviewed At:</strong>
                                        {{ $sample->reviewed_at->format('d M Y, h:i A') }}
                                    </p>

                                @endif

                            @endif

                        </div>


                        {{-- Status badge --}}
                        <div>

                            @if($overallStatus === 'accepted')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                                    Accepted
                                </span>

                            @elseif($overallStatus === 'rejected')

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">
                                    Rejected
                                </span>

                            @elseif($overallStatus === 'in_transit')

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                    In Transit
                                </span>

                            @elseif($overallStatus === 'delivered')

                                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 font-semibold">
                                    Delivered
                                </span>

                            @elseif($overallStatus === 'declined')

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">
                                    Declined
                                </span>

                            @elseif($overallStatus === 'approved')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                                    Approved
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $overallStatus)) }}
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- LAB STAFF EXAMINATION --}}
                    {{-- ================================================= --}}

                    @if(auth()->user()->role === 'lab_staff')

                        @if(
                            $overallStatus !== 'declined'
                            && $sample->status !== 'accepted'
                            && $sample->status !== 'rejected'
                        )

                            <form
                                method="POST"
                                action="{{ route('blood-samples.review', $sample) }}"
                                class="mt-6 border-t pt-5"
                            >

                                @csrf
                                @method('PATCH')


                                <h4 class="font-semibold mb-3">
                                    Quality Criteria
                                </h4>


                                <div class="mb-2">

                                    <input
                                        type="hidden"
                                        name="quality_checks[correct_labeling]"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="quality_checks[correct_labeling]"
                                        value="1"
                                    >

                                    Correct Labeling

                                </div>


                                <div class="mb-2">

                                    <input
                                        type="hidden"
                                        name="quality_checks[sufficient_volume]"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="quality_checks[sufficient_volume]"
                                        value="1"
                                    >

                                    Sufficient Blood Volume

                                </div>


                                <div class="mb-2">

                                    <input
                                        type="hidden"
                                        name="quality_checks[no_leakage]"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="quality_checks[no_leakage]"
                                        value="1"
                                    >

                                    No Leakage

                                </div>


                                <div class="mb-2">

                                    <input
                                        type="hidden"
                                        name="quality_checks[proper_container]"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="quality_checks[proper_container]"
                                        value="1"
                                    >

                                    Proper Container

                                </div>


                                <div class="mt-4">

                                    <label class="font-semibold">
                                        Rejection Reason
                                    </label>

                                    <textarea
                                        name="rejection_reason"
                                        rows="3"
                                        class="w-full border rounded mt-2"
                                        placeholder="Required when rejecting a sample"
                                    ></textarea>

                                </div>


                                <div class="mt-4 flex gap-3">

                                    <button
                                        type="submit"
                                        name="decision"
                                        value="accepted"
                                        class="px-4 py-2 bg-green-600 text-white rounded"
                                    >
                                        Accept Sample
                                    </button>


                                    <button
                                        type="submit"
                                        name="decision"
                                        value="rejected"
                                        class="px-4 py-2 bg-red-600 text-white rounded"
                                    >
                                        Reject Sample
                                    </button>

                                </div>

                            </form>


                        @elseif($overallStatus === 'declined')

                            <div class="mt-5 p-4 bg-red-100 text-red-800 rounded">

                                <strong>
                                    Request Declined
                                </strong>

                                <p class="mt-1">
                                    This sample request was declined by the administrator.
                                    Laboratory examination cannot proceed.
                                </p>

                            </div>


                        @elseif($sample->status === 'accepted')

                            <div class="mt-5 p-4 bg-green-100 text-green-800 rounded">

                                <strong>
                                    ✓ Sample Accepted
                                </strong>

                                <p class="mt-1">
                                    This sample passed the laboratory quality review.
                                </p>

                            </div>


                        @elseif($sample->status === 'rejected')

                            <div class="mt-5 p-4 bg-red-100 text-red-800 rounded">

                                <strong>
                                    Sample Rejected
                                </strong>

                                <p class="mt-1">
                                    Reason:
                                    {{ $sample->rejection_reason ?? 'No reason provided.' }}
                                </p>

                            </div>

                        @endif

                    @elseif(auth()->user()->role === 'admin')

                        {{-- ================================================= --}}
                        {{-- ADMIN IS VIEW-ONLY --}}
                        {{-- ================================================= --}}

                        @if($overallStatus === 'declined')

                            <div class="mt-5 p-4 bg-red-100 text-red-800 rounded">

                                <strong>
                                    Request Declined
                                </strong>

                                <p class="mt-1">
                                    This sample request was declined by the administrator.
                                    The sample cannot proceed to collection or laboratory examination.
                                </p>

                            </div>




                        @elseif($sample->status === 'accepted')

                            <div class="mt-5 p-4 bg-green-100 text-green-800 rounded">

                                <strong>
                                    ✓ Sample Accepted
                                </strong>

                                <p class="mt-1">
                                    This sample passed the laboratory quality review.
                                </p>

                            </div>


                        @elseif($sample->status === 'rejected')

                            <div class="mt-5 p-4 bg-red-100 text-red-800 rounded">

                                <strong>
                                    Sample Rejected
                                </strong>

                                <p class="mt-1">
                                    Reason:
                                    {{ $sample->rejection_reason ?? 'No reason provided.' }}
                                </p>

                            </div>


                        @else

                            <div class="mt-5 p-4 bg-gray-100 text-gray-700 rounded">

                                <strong>
                                    View Only
                                </strong>

                                <p class="mt-1">
                                    Laboratory examination is performed by assigned Lab Staff.
                                </p>

                            </div>

                        @endif

                    @endif

                </div>

            @empty

                <div class="bg-white shadow rounded p-6">
                    No blood samples are currently available.
                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>