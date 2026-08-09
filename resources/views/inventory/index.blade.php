<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Blood Inventory
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Manage accepted blood samples and collection status.
            </p>

        </div>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- Success Message --}}

            @if(session('success'))

                <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            {{-- Error Message --}}

            @if(session('error'))

                <div class="mb-6 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Summary Cards --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">


                {{-- Total --}}

                <a
                    href="{{ route('inventory.index', ['filter' => 'all']) }}"
                    class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg
                           hover:shadow-md transition
                           {{ $filter === 'all' ? 'ring-2 ring-blue-500' : '' }}"
                >

                    <div class="p-6">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Total Accepted Samples
                        </p>

                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ $totalSamples }}
                        </p>

                    </div>

                </a>


                {{-- Available --}}

                <a
                    href="{{ route('inventory.index', ['filter' => 'available']) }}"
                    class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg
                           hover:shadow-md transition
                           {{ $filter === 'available' ? 'ring-2 ring-yellow-500' : '' }}"
                >

                    <div class="p-6">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Available
                        </p>

                        <p class="text-3xl font-bold text-yellow-600 mt-2">
                            {{ $availableSamples }}
                        </p>

                    </div>

                </a>


                {{-- Collected --}}

                <a
                    href="{{ route('inventory.index', ['filter' => 'collected']) }}"
                    class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg
                           hover:shadow-md transition
                           {{ $filter === 'collected' ? 'ring-2 ring-green-500' : '' }}"
                >

                    <div class="p-6">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Collected
                        </p>

                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $collectedSamples }}
                        </p>

                    </div>

                </a>

            </div>


            {{-- Filter Toggle --}}

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-8">

                <div class="p-6">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Inventory View
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Toggle between available, collected, and all samples.
                            </p>

                        </div>


                        <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">


                            {{-- Available Toggle --}}

                            <a
                                href="{{ route('inventory.index', ['filter' => 'available']) }}"
                                class="
                                    px-4 py-2 text-sm font-semibold
                                    {{ $filter === 'available'
                                        ? 'bg-yellow-500 text-white'
                                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                                    }}
                                    hover:bg-yellow-500 hover:text-white
                                "
                            >
                                Available
                            </a>


                            {{-- Collected Toggle --}}

                            <a
                                href="{{ route('inventory.index', ['filter' => 'collected']) }}"
                                class="
                                    px-4 py-2 text-sm font-semibold
                                    border-l border-gray-300 dark:border-gray-600
                                    {{ $filter === 'collected'
                                        ? 'bg-green-600 text-white'
                                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                                    }}
                                    hover:bg-green-600 hover:text-white
                                "
                            >
                                Collected
                            </a>


                            {{-- All Toggle --}}

                            <a
                                href="{{ route('inventory.index', ['filter' => 'all']) }}"
                                class="
                                    px-4 py-2 text-sm font-semibold
                                    border-l border-gray-300 dark:border-gray-600
                                    {{ $filter === 'all'
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                                    }}
                                    hover:bg-blue-600 hover:text-white
                                "
                            >
                                All
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Inventory Table --}}

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-6">

                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">

                            @if($filter === 'available')

                                Available Blood Samples

                            @elseif($filter === 'collected')

                                Collected Blood Samples

                            @else

                                All Blood Samples

                            @endif

                        </h3>

                    </div>


                    @if($bloodSamples->isEmpty())

                        <div class="text-center py-12">

                            <div class="text-5xl mb-4">
                                🩸
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                No samples found
                            </h3>

                            <p class="text-gray-500 dark:text-gray-400 mt-2">

                                @if($filter === 'available')

                                    There are currently no blood samples waiting for collection.

                                @elseif($filter === 'collected')

                                    No blood samples have been collected yet.

                                @else

                                    There are currently no accepted blood samples.

                                @endif

                            </p>

                        </div>

                    @else

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                                <thead>

                                    <tr>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Sample Code
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Sample Type
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Patient
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Reviewed By
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Collected By
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Status
                                        </th>

                                        @if(auth()->user()->role === 'sample_collector')

                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Action
                                            </th>

                                        @endif

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                    @foreach($bloodSamples as $sample)

                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">


                                            {{-- Sample Code --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $sample->sample_code ?? 'Not Assigned' }}
                                                </span>

                                            </td>


                                            {{-- Sample Type --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                {{ $sample->sample_type ?? 'Not specified' }}

                                            </td>


                                            {{-- Patient --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                @if($sample->patient)

                                                    <p class="font-medium">
                                                        {{ $sample->patient->name }}
                                                    </p>

                                                    <p class="text-sm text-gray-500">
                                                        {{ $sample->patient->email }}
                                                    </p>

                                                @else

                                                    Unknown patient

                                                @endif

                                            </td>


                                            {{-- Reviewer --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                @if($sample->reviewer)

                                                    {{ $sample->reviewer->name }}

                                                @else

                                                    Not available

                                                @endif

                                            </td>


                                            {{-- Collector --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                @if($sample->collector)

                                                    <p class="font-semibold text-green-700">
                                                        {{ $sample->collector->name }}
                                                    </p>

                                                    <p class="text-sm text-gray-500">
                                                        Collector ID:
                                                        {{ $sample->collector->id }}
                                                    </p>

                                                @else

                                                    <span class="text-yellow-600 font-semibold">
                                                        Not collected
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Status --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                @if($sample->collector)

                                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                        Collected
                                                    </span>

                                                @else

                                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                                        Available
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Action --}}

                                            @if(auth()->user()->role === 'sample_collector')

                                                <td class="px-4 py-4 whitespace-nowrap">

                                                    @if(!$sample->collector)

                                                        <form
                                                            method="POST"
                                                            action="{{ route('inventory.collect', $sample) }}"
                                                        >

                                                            @csrf

                                                            @method('PATCH')

                                                            <button
                                                                type="submit"
                                                                class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700"
                                                                onclick="return confirm('Are you sure you want to collect this blood sample?')"
                                                            >
                                                                Collect Sample
                                                            </button>

                                                        </form>

                                                    @else

                                                        <span class="text-sm text-gray-500">
                                                            Already collected
                                                        </span>

                                                    @endif

                                                </td>

                                            @endif

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>