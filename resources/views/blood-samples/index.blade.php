<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Blood Sample Review
        </h2>
    </x-slot>

    <div class="py-12">
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

                <div class="bg-white shadow rounded-lg p-6 mb-6">

                    <h3 class="text-lg font-bold mb-3">
                        Sample:
                        {{ $sample->sample_code ?? 'Not Assigned' }}
                    </h3>

                    <p>
                        <strong>Type:</strong>
                        {{ $sample->sample_type ?? 'Not specified' }}
                    </p>

                    <p>
                        <strong>Status:</strong>
                        {{ ucfirst($sample->status) }}
                    </p>

                    <p>
                        <strong>Patient:</strong>
                        {{ $sample->patient?->name ?? 'Not assigned' }}
                    </p>

                    <p>
                        <strong>Collected By:</strong>
                        {{ $sample->collector?->name ?? 'Not assigned' }}
                    </p>

                    @if(
                        $sample->status !== 'accepted'
                        && $sample->status !== 'rejected'
                    )

                        <form
                            method="POST"
                            action="{{ route('blood-samples.review', $sample) }}"
                            class="mt-5"
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

                    @elseif($sample->status === 'accepted')

                        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                            ✓ Sample Accepted
                        </div>

                    @else

                        <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">

                            <strong>Sample Rejected</strong>

                            <br>

                            Reason:
                            {{ $sample->rejection_reason }}

                        </div>

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