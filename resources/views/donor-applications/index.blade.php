<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                My Donor & Blood Applications
            </h2>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))

                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>

            @endif

            <div class="flex justify-end mb-6">

                <a
                    href="{{ route('donor-applications.create') }}"
                    class="px-5 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700"
                >
                    + New Application
                </a>

            </div>

            @if ($applications->isEmpty())

                <div class="bg-white rounded-xl shadow p-10 text-center">

                    <div class="text-4xl mb-4">
                        🩸
                    </div>

                    <h3 class="text-lg font-semibold">
                        No applications yet
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Submit a donor or blood request application to get started.
                    </p>

                </div>

            @else

                <div class="space-y-4">

                    @foreach ($applications as $application)

                        <a
                            href="{{ route('donor-applications.show', $application) }}"
                            class="block bg-white rounded-xl shadow p-6 hover:shadow-lg transition"
                        >

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                <div>

                                    <h3 class="font-semibold text-lg">
                                        {{ $application->request_type_name }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Blood Group:
                                        <strong>{{ $application->blood_group }}</strong>
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Submitted:
                                        {{ $application->created_at->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                                <div>

                                    @if ($application->status === 'pending')

                                        <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                            Pending

                                        </span>

                                    @elseif ($application->status === 'approved')

                                        <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                            Approved
                                        </span>

                                    @else

                                        <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                                            Rejected
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-app-layout>