<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🚨 Emergency Blood Requests
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Patients who urgently need your blood donation
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- SUCCESS MESSAGE --}}

            @if(session('success'))

                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800 shadow-sm">
                    {{ session('success') }}
                </div>

            @endif


            {{-- ERROR MESSAGE --}}

            @if(session('error'))

                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4 text-red-800 shadow-sm">
                    {{ session('error') }}
                </div>

            @endif


            {{-- =========================================================
                 HEADER
            ========================================================== --}}

            <div class="mb-8">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>

                        <h1 class="text-3xl font-bold text-red-600 dark:text-red-400">
                            🚨 Emergency Blood Requests
                        </h1>

                        <p class="mt-2 text-[#0033CC] dark:text-[#66B3FF] font-bold">
                            These patients have specifically requested you for an emergency blood donation.
                        </p>

                    </div>


                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold"
                    >
                        🩸 {{ $requests->count() }} Pending Request{{ $requests->count() !== 1 ? 's' : '' }}
                    </div>

                </div>

            </div>


            {{-- =========================================================
                 NOTIFICATION AREA
            ========================================================== --}}

            @if(isset($notifications) && $notifications->count() > 0)

                <div class="mb-8">

                    <div
                        class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-2xl p-6"
                    >

                        <div class="flex items-start gap-4">

                            <div
                                class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center flex-shrink-0"
                            >
                                <span class="text-2xl">🔔</span>
                            </div>

                            <div class="flex-1">

                                <h2 class="text-lg font-bold text-red-800 dark:text-red-300">
                                    New Emergency Blood Request
                                </h2>

                                <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                    You have received {{ $notifications->count() }}
                                    new emergency blood request{{ $notifications->count() !== 1 ? 's' : '' }}.
                                </p>

                                <p class="mt-3 text-sm text-red-700 dark:text-red-400">
                                    Please review the request below and respond as soon as possible.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- =========================================================
                 REQUESTS
            ========================================================== --}}

            @if($requests->count() > 0)

                <div class="space-y-6">

                    @foreach($requests as $donorRequest)

                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-red-200 dark:border-red-900 overflow-hidden"
                        >

                            {{-- CARD HEADER --}}

                            <div
                                class="p-6 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-950/20"
                            >

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                    <div class="flex items-center gap-4">

                                        <div
                                            class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center"
                                        >
                                            <span class="text-2xl">🚨</span>
                                        </div>

                                        <div>

                                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                                Emergency Blood Request
                                            </h2>

                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                Request #{{ $donorRequest->id }}
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-bold"
                                    >
                                        ⏳ Pending
                                    </span>

                                </div>

                            </div>


                            {{-- REQUEST INFORMATION --}}

                            <div class="p-6">

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">


                                    {{-- Patient --}}

                                    <div
                                        class="rounded-xl bg-gray-50 dark:bg-gray-900 p-4"
                                    >

                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            Patient
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $donorRequest->patient->user->name ?? 'Emergency Patient' }}
                                        </p>

                                    </div>


                                    {{-- Blood Group --}}

                                    <div
                                        class="rounded-xl bg-red-50 dark:bg-red-950/30 p-4"
                                    >

                                        <p class="text-xs uppercase tracking-wide text-red-600 dark:text-red-400">
                                            Blood Group
                                        </p>

                                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                                            {{ $donorRequest->emergencyRequest->blood_group ?? 'N/A' }}
                                        </p>

                                    </div>


                                    {{-- Requested At --}}

                                    <div
                                        class="rounded-xl bg-gray-50 dark:bg-gray-900 p-4"
                                    >

                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            Requested
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                                            {{ $donorRequest->created_at?->format('d M Y, h:i A') }}
                                        </p>

                                    </div>


                                    {{-- Distance --}}

                                    <div
                                        class="rounded-xl bg-blue-50 dark:bg-blue-950/30 p-4"
                                    >

                                        <p class="text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400">
                                            Patient Location
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $donorRequest->patient_latitude }},
                                            {{ $donorRequest->patient_longitude }}
                                        </p>

                                    </div>

                                </div>


                                {{-- EMERGENCY LOCATION --}}

                                <div
                                    class="mt-6 rounded-xl border border-gray-200 dark:border-gray-700 p-5"
                                >

                                    <h3 class="font-bold text-gray-900 dark:text-white">
                                        📍 Emergency Location
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        Latitude:
                                        <strong>
                                            {{ $donorRequest->patient_latitude }}
                                        </strong>
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        Longitude:
                                        <strong>
                                            {{ $donorRequest->patient_longitude }}
                                        </strong>
                                    </p>

                                </div>


                                {{-- ACTIONS --}}

                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">


                                    {{-- ACCEPT --}}

                                    <form
                                        method="POST"
                                        action="{{ route('donor.request.accept', $donorRequest->id) }}"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-sm"
                                        >
                                            🩸 Accept & Go Donate Blood
                                        </button>

                                    </form>


                                    {{-- DECLINE --}}

                                    <form
                                        method="POST"
                                        action="{{ route('donor.request.decline', $donorRequest->id) }}"
                                        onsubmit="return confirm('Are you sure you want to decline this emergency blood request?');"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-4 px-6 rounded-xl transition"
                                        >
                                            ✕ Decline Request
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- NO REQUESTS --}}

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center"
                >

                    <div class="text-6xl mb-5">
                        🩸
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        No Emergency Requests
                    </h2>

                    <p class="mt-3 max-w-xl mx-auto text-gray-600 dark:text-gray-300">
                        You currently have no pending emergency blood donation requests.
                        When a nearby patient requests your blood, the request will appear here.
                    </p>

                    <div
                        class="mt-6 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 font-semibold"
                    >
                        ✓ You are ready to help
                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>