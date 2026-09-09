<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Donor & Blood Applications
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Review patient applications and make a medical decision.
            </p>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))

                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>

            @endif

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Patient
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Blood
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Age
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Weight
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y">

                            @forelse ($applications as $application)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">

                                        <div class="font-medium">
                                            {{ $application->patient->user->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $application->phone }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $application->request_type_name }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        {{ $application->blood_group }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $application->age ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $application->weight ? $application->weight . ' kg' : '-' }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if ($application->status === 'pending')

                                            <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>

                                        @elseif ($application->status === 'approved')

                                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                Approved
                                            </span>

                                        @else

                                            <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                                Rejected
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <a
                                            href="{{ route('doctor.donor-applications.show', $application) }}"
                                            class="text-blue-600 font-semibold hover:underline"
                                        >
                                            Review
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        No donor or blood applications have been submitted yet.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>