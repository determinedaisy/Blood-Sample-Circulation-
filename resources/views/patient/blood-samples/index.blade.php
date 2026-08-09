<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Blood Samples
            </h2>

            <a
                href="{{ route('patient.blood-samples.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
            >
                Donate Blood Sample
            </a>

        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))

                <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif

            @forelse($bloodSamples as $sample)

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-xl font-bold mb-4">
                            Sample: {{ $sample->sample_code ?? 'Not Assigned' }}
                        </h3>

                        <p class="mb-2">
                            <strong>Sample Type:</strong>
                            {{ $sample->sample_type ?? 'Not specified' }}
                        </p>

                        <p class="mb-2">
                            <strong>Status:</strong>

                            @if($sample->status === 'accepted')

                                <span class="text-green-600 font-semibold">
                                    Accepted
                                </span>

                            @elseif($sample->status === 'rejected')

                                <span class="text-red-600 font-semibold">
                                    Rejected
                                </span>

                            @else

                                <span class="text-yellow-600 font-semibold">
                                    {{ ucfirst($sample->status) }}
                                </span>

                            @endif

                        </p>

                        <p class="mb-2">
                            <strong>Collected By:</strong>
                            {{ $sample->collector?->name ?? 'Patient' }}
                        </p>

                        <p class="mb-2">
                            <strong>Collected At:</strong>

                            @if($sample->collected_at)

                                {{ $sample->collected_at->format('d M Y, h:i A') }}

                            @else

                                Not available

                            @endif

                        </p>

                        @if($sample->reviewer)

                            <p class="mb-2">
                                <strong>Reviewed By:</strong>
                                {{ $sample->reviewer->name }}
                            </p>

                        @endif

                        @if($sample->reviewed_at)

                            <p class="mb-2">
                                <strong>Reviewed At:</strong>
                                {{ $sample->reviewed_at->format('d M Y, h:i A') }}
                            </p>

                        @endif

                        @if($sample->status === 'accepted')

                            <div class="mt-5 p-4 bg-green-100 text-green-800 rounded-lg">

                                <strong>Sample Accepted</strong>

                                <p class="mt-1">
                                    Your blood sample passed the laboratory quality review
                                    and is now available in the blood inventory.
                                </p>

                            </div>

                        @elseif($sample->status === 'rejected')

                            <div class="mt-5 p-4 bg-red-100 text-red-800 rounded-lg">

                                <strong>Sample Rejected</strong>

                                <p class="mt-2">

                                    <strong>Reason:</strong>

                                    {{ $sample->rejection_reason ?? 'No reason provided.' }}

                                </p>

                            </div>

                        @else

                            <div class="mt-5 p-4 bg-yellow-100 text-yellow-800 rounded-lg">

                                <strong>Pending Laboratory Review</strong>

                                <p class="mt-1">
                                    Your blood sample has been submitted and is waiting
                                    for laboratory staff to review it.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            @empty

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <p class="mb-4">
                            You currently have no blood samples.
                        </p>

                        <a
                            href="{{ route('patient.blood-samples.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
                        >
                            Donate Your First Blood Sample
                        </a>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>