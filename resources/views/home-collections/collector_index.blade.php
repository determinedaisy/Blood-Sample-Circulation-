

<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                My Home Collections
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Home collection appointments assigned to you.
            </p>
        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

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


            @if($homeCollections->isEmpty())

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">

                    <div class="text-4xl mb-4">
                        🏠
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        No home collections assigned
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Home visits assigned by the administrator will appear here.
                    </p>

                </div>

            @else

                <div class="space-y-6">

                    @foreach($homeCollections as $homeCollection)

                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                            <div class="px-6 py-5 border-b border-gray-200 flex items-start justify-between gap-4">

                                <div>

                                    <div class="text-xs font-semibold uppercase text-gray-500">
                                        Sample
                                    </div>

                                    <div class="text-xl font-bold text-blue-700 mt-1">
                                        {{ $homeCollection->sampleRequest?->bloodSample?->sample_code ?? '—' }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $homeCollection->sampleRequest?->sample_type ?? '—' }}
                                    </div>

                                </div>


                                <div>

                                    @if($homeCollection->status === 'assigned')

                                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                            Assigned
                                        </span>

                                    @elseif($homeCollection->status === 'on_the_way')

                                        <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
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

                                    @endif

                                </div>

                            </div>


                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>

                                    <div class="text-xs font-semibold uppercase text-gray-500">
                                        Patient
                                    </div>

                                    <div class="mt-1 font-semibold text-gray-900">
                                        {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $homeCollection->patient->email ?? '' }}
                                    </div>

                                </div>


                                <div>

                                    <div class="text-xs font-semibold uppercase text-gray-500">
                                        Appointment
                                    </div>

                                    <div class="mt-1 font-semibold text-gray-900">
                                        {{ $homeCollection->preferred_date->format('d M Y') }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $homeCollection->preferred_time }}
                                    </div>

                                </div>


                                <div class="md:col-span-2">

                                    <div class="text-xs font-semibold uppercase text-gray-500">
                                        Address
                                    </div>

                                    <div class="mt-1 text-gray-900">
                                        {{ $homeCollection->address }}
                                    </div>

                                </div>


                                @if($homeCollection->instructions)

                                    <div class="md:col-span-2">

                                        <div class="text-xs font-semibold uppercase text-gray-500">
                                            Instructions
                                        </div>

                                        <div class="mt-1 rounded-xl bg-gray-50 border border-gray-200 p-3 text-gray-800">
                                            {{ $homeCollection->instructions }}
                                        </div>

                                    </div>

                                @endif


                                @if(
                                    $homeCollection->latitude
                                    &&
                                    $homeCollection->longitude
                                )

                                    <div class="md:col-span-2">

                                        <div class="text-xs font-semibold uppercase text-gray-500">
                                            GPS
                                        </div>

                                        <div class="mt-1 text-sm text-gray-700">
                                            {{ $homeCollection->latitude }},
                                            {{ $homeCollection->longitude }}
                                        </div>

                                    </div>

                                @endif

                            </div>


                            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200">

                                @if($homeCollection->status === 'assigned')

                                    <form
                                        method="POST"
                                        action="{{ route('home-collections.collector.start', $homeCollection) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700"
                                        >
                                            🚗 Start Trip
                                        </button>

                                    </form>


                                @elseif($homeCollection->status === 'on_the_way')

                                    <form
                                        method="POST"
                                        action="{{ route('home-collections.collector.arrive', $homeCollection) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-5 py-2.5 rounded-lg bg-purple-600 text-white font-semibold hover:bg-purple-700"
                                        >
                                            📍 Mark Arrived
                                        </button>

                                    </form>


                                @elseif($homeCollection->status === 'arrived')

                                    <form
                                        method="POST"
                                        action="{{ route('home-collections.collector.collect', $homeCollection) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-5 py-2.5 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700"
                                        >
                                            ✓ Confirm Sample Collected
                                        </button>

                                    </form>


                                @elseif($homeCollection->status === 'collected')

                                    <div class="font-semibold text-green-700">
                                        ✓ Home sample collection completed
                                    </div>

                                    @if($homeCollection->collected_at)

                                        <div class="text-sm text-gray-500 mt-1">
                                            Collected
                                            {{ $homeCollection->collected_at->format('d M Y, h:i A') }}
                                        </div>

                                    @endif

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-app-layout>