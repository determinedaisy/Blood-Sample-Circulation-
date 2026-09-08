@php
    $routePlannerStops = $homeCollections
        ->filter(fn ($collection) =>
            $collection->assigned_collector_id
            && $collection->latitude !== null
            && $collection->longitude !== null
            && $collection->status !== 'cancelled'
        )
        ->map(fn ($collection) => [
            'id' => $collection->id,
            'collectorId' => (int) $collection->assigned_collector_id,
            'collectorName' => $collection->assignedCollector?->name ?? 'Assigned collector',
            'date' => $collection->preferred_date->format('Y-m-d'),
            'time' => $collection->preferred_time,
            'patient' => $collection->patient?->name ?? 'Patient',
            'address' => $collection->address,
            'lat' => (float) $collection->latitude,
            'lng' => (float) $collection->longitude,
            'routeOrder' => $collection->route_order
                ? (int) $collection->route_order
                : null,
            'status' => $collection->status,
        ])
        ->values();

    $routePlannerDates = $routePlannerStops
        ->pluck('date')
        ->unique()
        ->sort()
        ->values();
@endphp

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<style>
    .route-number-icon {
        align-items: center;
        background: #e11d48;
        border: 3px solid #fff;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
        color: #fff;
        display: flex;
        font-size: 13px;
        font-weight: 800;
        height: 32px;
        justify-content: center;
        width: 32px;
    }
    #route-stop-list {
    min-width: 0;
}

#route-stop-list > * {
    min-width: 0;
}
</style>

<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Home Collections
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Manage each home sample from request approval through
                Sample Collector collection and Lab Staff examination.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-gray-100 py-8">

        <div class="mx-auto w-full max-w-[1800px] px-3 sm:px-5 lg:px-6">

            {{-- ========================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ========================================================= --}}

            @if(session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- ERROR MESSAGE --}}
            {{-- ========================================================= --}}

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ========================================================= --}}

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- DAILY ROUTE PLANNER --}}
            {{-- ========================================================= --}}

            <section class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gradient-to-r from-rose-50 via-white to-indigo-50 px-5 py-5 sm:px-6">

                    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">

                        <div>
                            <h3 class="mt-1 text-xl font-bold text-gray-900">
                                Daily Collection Route Planner
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Choose a date and collector, optimize stops using real roads, then save the order.
                            </p>
                        </div>


                        <div class="grid gap-3 sm:grid-cols-2 xl:min-w-[560px]">

                            <div>
                                <label
                                    for="route-date-filter"
                                    class="text-xs font-bold uppercase tracking-wide text-gray-500"
                                >
                                    Route date
                                </label>

                                <select
                                    id="route-date-filter"
                                    class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                                >
                                    @forelse($routePlannerDates as $date)

                                        <option value="{{ $date }}">
                                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                        </option>

                                    @empty

                                        <option value="">
                                            No GPS appointments
                                        </option>

                                    @endforelse
                                </select>
                            </div>


                            <div>
                                <label
                                    for="route-collector-filter"
                                    class="text-xs font-bold uppercase tracking-wide text-gray-500"
                                >
                                    Collector
                                </label>

                                <select
                                    id="route-collector-filter"
                                    class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                                >
                                    <option value="">
                                        Select a route date first
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="grid xl:grid-cols-[minmax(0,1.65fr)_minmax(420px,1fr)]">

                    <div class="min-w-0 border-b border-gray-200 p-4 sm:p-5 xl:border-b-0 xl:border-r">

                        <div
                            id="admin-route-map"
                            data-save-url="{{ route('home-collections.route-order') }}"
                            class="h-[500px] w-full overflow-hidden rounded-xl bg-slate-100"
                        ></div>

                    </div>


                    <aside class="min-w-0 flex min-h-[500px] flex-col p-5 sm:p-6 xl:p-6">

                        <div class="grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-slate-50 p-3">

                                <div class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                    Stops
                                </div>

                                <div
                                    id="route-stop-count"
                                    class="mt-1 text-xl font-bold text-gray-900"
                                >
                                    0
                                </div>

                            </div>


                            <div class="rounded-xl bg-slate-50 p-3">

                                <div class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                    Road estimate
                                </div>

                                <div
                                    id="route-road-estimate"
                                    class="mt-1 text-sm font-bold text-gray-900"
                                >
                                    —
                                </div>

                            </div>

                        </div>


                        <div
                            id="route-planner-message"
                            class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800"
                        >
                            Select a date and collector to load their appointments.
                        </div>


                        <div
    id="route-stop-list"
    class="mt-4 min-w-0 flex-1 space-y-2 overflow-y-auto pr-1 xl:max-h-[320px]"
></div>


                        <div class="mt-5 grid gap-3 sm:grid-cols-2">

                            <button
                                type="button"
                                id="optimize-route-button"
                                disabled
                                class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                            >
                                Optimize road route
                            </button>


                            <button
                                type="button"
                                id="save-route-button"
                                disabled
                                class="rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                            >
                                Save stop order
                            </button>

                        </div>

                    </aside>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- HOME COLLECTION REQUESTS --}}
            {{-- ========================================================= --}}

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-5 py-4 sm:px-6">

                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <h3 class="font-bold text-gray-900">
                                Home Collection Requests
                            </h3>

                            <p class="text-sm text-gray-500">
                                Manage the complete home collection workflow from one panel.
                            </p>
                        </div>


                        <div class="text-sm font-semibold text-gray-600">
                            {{ $homeCollections->count() }}
                            {{ $homeCollections->count() === 1 ? 'request' : 'requests' }}
                        </div>

                    </div>

                </div>


                @forelse($homeCollections as $homeCollection)

                    @php
                        $sampleRequest = $homeCollection->sampleRequest;
                        $bloodSample = $sampleRequest?->bloodSample;

                        $statusClass = match ($homeCollection->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'assigned', 'on_the_way' => 'bg-blue-100 text-blue-800',
                            'arrived' => 'bg-purple-100 text-purple-800',
                            'collected' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp


                    <article
                        id="home-collection-card-{{ $homeCollection->id }}"
                        class="border-b border-gray-200 last:border-b-0"
                    >

                        {{-- ================================================= --}}
                        {{-- HEADER --}}
                        {{-- ================================================= --}}

                        <header class="bg-white px-5 py-5 sm:px-6">

                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                        </h3>


                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $homeCollection->status)) }}
                                        </span>

                                    </div>


                                    <p class="mt-1 break-all text-sm text-gray-500">
                                        {{ $homeCollection->patient->email ?? 'No patient email' }}
                                    </p>

                                </div>


                                <div class="flex flex-wrap gap-x-8 gap-y-2 rounded-xl bg-indigo-50 px-4 py-3">

                                    <div>

                                        <div class="text-xs font-semibold uppercase tracking-wide text-indigo-600">
                                            Sample code
                                        </div>

                                        <div class="mt-1 font-bold text-indigo-900">
                                            {{ $bloodSample?->sample_code ?? 'Not created' }}
                                        </div>

                                    </div>


                                    <div>

                                        <div class="text-xs font-semibold uppercase tracking-wide text-indigo-600">
                                            Sample
                                        </div>

                                        <div class="mt-1 font-semibold text-indigo-900">

                                            {{ $sampleRequest?->sample_type ?? 'Unavailable' }}

                                            @if($sampleRequest?->blood_type)
                                                · {{ $sampleRequest->blood_type }}
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </header>


                        {{-- ================================================= --}}
                        {{-- WORKFLOW --}}
                        {{-- ================================================= --}}

                        <div class="grid bg-white xl:grid-cols-12">


                            {{-- ================================================= --}}
                            {{-- STEP 1 --}}
                            {{-- ================================================= --}}

                            <section class="border-t border-gray-200 p-5 xl:col-span-2">

                                <div class="flex items-center gap-2">

                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700">
                                        1
                                    </span>

                                    <h4 class="text-sm font-bold text-gray-900">
                                        Request approval
                                    </h4>

                                </div>


                                <div class="mt-4">

                                    @if(!$sampleRequest)

                                        <p class="text-sm text-red-600">
                                            No linked sample request.
                                        </p>


                                    @elseif($sampleRequest->status === 'pending')

                                        <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800">
                                            Approve before assigning a collector.
                                        </div>


                                        <div class="mt-3 grid gap-2">

                                            <form
                                                method="POST"
                                                action="{{ route('home-collections.approve', $homeCollection) }}"
                                                class="js-home-workflow-form"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="w-full rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700"
                                                >
                                                    Approve
                                                </button>

                                            </form>


                                            <div>

                                                <button
                                                    type="button"
                                                    onclick="openDeclineModal({{ $homeCollection->id }})"
                                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700"
                                                >
                                                    Decline
                                                </button>

                                            </div>

                                        </div>


                                    @elseif($sampleRequest->status === 'approved')

                                        <div class="rounded-lg bg-green-50 p-3 text-sm text-green-800">

                                            <div class="font-semibold">
                                                ✓ Approved
                                            </div>


                                            @if($sampleRequest->approved_at)

                                                <div class="mt-1 text-xs">
                                                    {{ $sampleRequest->approved_at->format('d M Y, h:i A') }}
                                                </div>

                                            @endif

                                        </div>


                                    @elseif($sampleRequest->status === 'declined')

                                        <div class="rounded-lg bg-red-50 p-3 text-sm text-red-800">

                                            <div class="font-semibold">
                                                ✕ Declined
                                            </div>


                                            @if($sampleRequest->decline_reason)

                                                <div class="mt-2 text-xs text-red-700">

                                                    <span class="font-semibold">
                                                        Reason:
                                                    </span>

                                                    {{ $sampleRequest->decline_reason }}

                                                </div>

                                            @endif

                                        </div>


                                    @else

                                        <p class="text-sm text-gray-600">
                                            {{ ucfirst(str_replace('_', ' ', $sampleRequest->status)) }}
                                        </p>

                                    @endif

                                </div>

                            </section>


                            {{-- ================================================= --}}
                            {{-- STEP 2: SAMPLE COLLECTOR --}}
                            {{-- ================================================= --}}

                            <section class="border-t border-gray-200 p-5 xl:col-span-3 xl:border-l">

                                <div class="flex items-center gap-2">

                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-xs font-bold text-purple-700">
                                        2
                                    </span>

                                    <h4 class="text-sm font-bold text-gray-900">
                                        Sample Collector
                                    </h4>

                                </div>


                                <div class="mt-4">

                                    @if(
                                        !$sampleRequest
                                        || $sampleRequest->status !== 'approved'
                                    )

                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                                            Waiting for approval.
                                        </div>


                                    @elseif($homeCollection->assignedCollector)

                                        <div class="rounded-lg bg-green-50 p-3 text-sm text-green-800">

                                            <div class="font-semibold">
                                                ✓ Collector assigned
                                            </div>


                                            <div class="mt-1 font-medium text-gray-900">
                                                {{ $homeCollection->assignedCollector->name }}
                                            </div>


                                            @if($homeCollection->assigned_at)

                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $homeCollection->assigned_at->format('d M Y, h:i A') }}
                                                </div>

                                            @endif

                                        </div>


                                    @elseif(
                                        in_array(
                                            $homeCollection->status,
                                            ['pending', 'approved', 'assigned'],
                                            true
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route('home-collections.assign', $homeCollection) }}"
                                            class="js-home-workflow-form space-y-3"
                                        >

                                            @csrf


                                            <select
                                                name="assigned_collector_id"
                                                required
                                                class="w-full rounded-lg border-gray-300 text-sm"
                                            >

                                                <option value="">
                                                    Select Collector
                                                </option>


                                                @foreach($collectors as $collector)

                                                    <option
                                                        value="{{ $collector->id }}"
                                                        @selected(
                                                            (int) $homeCollection->assigned_collector_id
                                                            ===
                                                            (int) $collector->id
                                                        )
                                                    >
                                                        {{ $collector->name }}
                                                    </option>

                                                @endforeach

                                            </select>


                                            <button
                                                type="submit"
                                                class="w-full rounded-lg bg-purple-600 px-3 py-2 text-sm font-semibold text-white hover:bg-purple-700"
                                            >
                                                Assign Collector
                                            </button>

                                        </form>


                                    @else

                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                                            No collector assigned.
                                        </div>

                                    @endif

                                </div>

                            </section>


                            {{-- ================================================= --}}
                            {{-- STEP 3: LAB STAFF --}}
                            {{-- ================================================= --}}

                            <section class="border-t border-gray-200 p-5 xl:col-span-3 xl:border-l">

                                <div class="flex items-center gap-2">

                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                        3
                                    </span>

                                    <h4 class="text-sm font-bold text-gray-900">
                                        Lab Staff Examination
                                    </h4>

                                </div>


                                <div class="mt-4">

                                    @if(!$bloodSample)

                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                                            Waiting for the blood sample to be created.
                                        </div>


                                    @elseif(in_array($bloodSample->status, ['accepted', 'rejected'], true))

                                        <div
                                            class="rounded-lg
                                            {{ $bloodSample->status === 'accepted'
                                                ? 'bg-green-50 text-green-800'
                                                : 'bg-red-50 text-red-800' }}
                                            p-3 text-sm"
                                        >

                                            <div class="font-semibold">

                                                {{ $bloodSample->status === 'accepted'
                                                    ? '✓ Sample Accepted'
                                                    : '✕ Sample Rejected' }}

                                            </div>


                                            @if($bloodSample->assignedLabStaff)

                                                <div class="mt-1 font-medium text-gray-900">
                                                    Examined by:
                                                    {{ $bloodSample->assignedLabStaff->name }}
                                                </div>

                                            @endif


                                            @if($bloodSample->reviewed_at)

                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $bloodSample->reviewed_at->format('d M Y, h:i A') }}
                                                </div>

                                            @endif


                                            @if($bloodSample->rejection_reason)

                                                <div class="mt-2 text-xs">

                                                    <span class="font-semibold">
                                                        Reason:
                                                    </span>

                                                    {{ $bloodSample->rejection_reason }}

                                                </div>

                                            @endif

                                        </div>


                                    @elseif($bloodSample->assignedLabStaff)

                                        <div class="rounded-lg bg-blue-50 p-3 text-sm text-blue-800">

                                            <div class="font-semibold">
                                                ✓ Lab Staff Assigned
                                            </div>


                                            <div class="mt-1 font-medium text-gray-900">
                                                {{ $bloodSample->assignedLabStaff->name }}
                                            </div>


                                            <div class="mt-1 text-xs text-gray-500">
                                                Waiting for examination.
                                            </div>

                                        </div>


                                    @elseif($homeCollection->status === 'collected')

                                        @if($labStaff->isEmpty())

                                            <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800">
                                                No Lab Staff accounts are available.
                                            </div>

                                        @else

                                            <div class="mb-3 rounded-lg bg-green-50 p-3 text-sm text-green-800">

                                                <div class="font-semibold">
                                                    ✓ Sample collection complete
                                                </div>

                                                <div class="mt-1 text-xs">
                                                    Assign a Lab Staff member to examine this sample.
                                                </div>

                                            </div>


                                            <form
                                                method="POST"
                                                action="{{ route('home-collections.assign-lab-staff', $homeCollection) }}"
                                                class="js-home-workflow-form space-y-3"
                                            >

                                                @csrf


                                                <select
                                                    name="assigned_lab_staff_id"
                                                    required
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                >

                                                    <option value="">
                                                        Select Lab Staff
                                                    </option>


                                                    @foreach($labStaff as $staff)

                                                        <option value="{{ $staff->id }}">
                                                            {{ $staff->name }}
                                                        </option>

                                                    @endforeach

                                                </select>


                                                <button
                                                    type="submit"
                                                    class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                                >
                                                    Assign Lab Staff
                                                </button>

                                            </form>

                                        @endif


                                    @elseif($homeCollection->status === 'cancelled')

                                        <div class="rounded-lg bg-red-50 p-3 text-sm text-red-700">
                                            Collection cancelled.
                                        </div>


                                    @else

                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                                            Waiting for sample collection to be completed.
                                        </div>

                                    @endif

                                </div>

                            </section>

                        </div>

                    </article>


                @empty

                    <div class="px-6 py-16 text-center">

                        <div class="text-4xl">
                            🏠
                        </div>


                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            No home collection requests
                        </h3>


                        <p class="mt-2 text-sm text-gray-500">
                            Patient home collection requests will appear here.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECLINE REQUEST MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="decline-request-modal"
        class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/50 px-4"
    >

        <div
            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="decline-modal-title"
        >

            <div class="flex items-start justify-between gap-4">

                <div>

                    <h3
                        id="decline-modal-title"
                        class="text-lg font-bold text-gray-900"
                    >
                        Decline Sample Request
                    </h3>


                    <p class="mt-1 text-sm text-gray-500">
                        Please provide a reason for declining this request.
                        The patient will receive this reason in their notification.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeDeclineModal()"
                    class="text-2xl leading-none text-gray-400 hover:text-gray-600"
                    aria-label="Close"
                >
                    &times;
                </button>

            </div>


            <form
                id="decline-request-form"
                method="POST"
                class="mt-5 space-y-4"
            >

                @csrf
                @method('PATCH')


                <div>

                    <label
                        for="decline-reason-input"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Reason for decline
                    </label>


                    <textarea
                        id="decline-reason-input"
                        name="decline_reason"
                        rows="5"
                        required
                        minlength="3"
                        maxlength="1000"
                        placeholder="Write the reason for declining this sample request..."
                        class="mt-2 w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-red-500 focus:ring-red-500"
                    ></textarea>


                    <p class="mt-1 text-xs text-gray-500">
                        This reason will be shown to the patient.
                    </p>

                </div>


                <div class="flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="closeDeclineModal()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Confirm Decline
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ROUTE PLANNER --}}
    {{-- ========================================================= --}}

    <script type="application/json" id="admin-route-data">
        @json($routePlannerStops)
    </script>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="{{ asset('js/home-route-planner-admin.js') }}"></script>


    {{-- ========================================================= --}}
    {{-- DECLINE MODAL SCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        function openDeclineModal(homeCollectionId) {

            const modal =
                document.getElementById('decline-request-modal');

            const form =
                document.getElementById('decline-request-form');

            const reasonInput =
                document.getElementById('decline-reason-input');


            const routeTemplate =
                @json(route('home-collections.decline', ['homeCollection' => '__HOME_COLLECTION_ID__']));


            form.action =
                routeTemplate.replace(
                    '__HOME_COLLECTION_ID__',
                    homeCollectionId
                );


            reasonInput.value = '';


            modal.classList.remove('hidden');

            modal.classList.add('flex');


            window.setTimeout(function () {

                reasonInput.focus();

            }, 100);

        }


        function closeDeclineModal() {

            const modal =
                document.getElementById('decline-request-modal');

            const form =
                document.getElementById('decline-request-form');


            modal.classList.add('hidden');

            modal.classList.remove('flex');


            form.reset();

        }


        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                const modal =
                    document.getElementById('decline-request-modal');

                if (
                    modal
                    && !modal.classList.contains('hidden')
                ) {

                    closeDeclineModal();

                }

            }

        });


        document.addEventListener('click', function (event) {

            const modal =
                document.getElementById('decline-request-modal');

            if (
                modal
                && event.target === modal
            ) {

                closeDeclineModal();

            }

        });

    </script>


    {{-- ========================================================= --}}
    {{-- AJAX WORKFLOW --}}
    {{-- ========================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            function showWorkflowMessage(text, isError) {

                const existing =
                    document.getElementById('home-workflow-toast');


                if (existing) {
                    existing.remove();
                }


                const toast =
                    document.createElement('div');


                toast.id =
                    'home-workflow-toast';


                toast.className =
                    'fixed right-5 top-20 z-[1000] max-w-sm rounded-xl px-5 py-4 text-sm font-semibold text-white shadow-xl '
                    +
                    (
                        isError
                            ? 'bg-red-600'
                            : 'bg-green-600'
                    );


                toast.textContent =
                    text;


                document.body.appendChild(toast);


                window.setTimeout(
                    () => toast.remove(),
                    4000
                );

            }


            document.addEventListener(
                'submit',
                async function (event) {

                    const form =
                        event.target.closest(
                            '.js-home-workflow-form'
                        );


                    /*
                     * Decline modal does not use this AJAX handler.
                     */
                    if (!form) {
                        return;
                    }


                    event.preventDefault();


                    const button =
                        form.querySelector(
                            'button[type="submit"]'
                        );


                    const originalText =
                        button?.textContent;


                    if (button) {

                        button.disabled =
                            true;

                        button.textContent =
                            'Saving…';

                    }


                    try {

                        const response =
                            await fetch(
                                form.action,
                                {
                                    method: 'POST',

                                    headers: {
                                        'X-Requested-With':
                                            'XMLHttpRequest',

                                        'Accept':
                                            'application/json'
                                    },

                                    body:
                                        new FormData(form)
                                }
                            );


                        /*
                         * IMPORTANT:
                         *
                         * The controller returns JSON for AJAX requests.
                         * Previously this code tried to parse that JSON
                         * as HTML, which caused the false
                         * "action could not be completed" message.
                         */

                        const contentType =
                            response.headers.get(
                                'content-type'
                            ) || '';


                        if (
                            contentType.includes(
                                'application/json'
                            )
                        ) {

                            const data =
                                await response.json();


                            if (!response.ok) {

                                let message =
                                    data.message
                                    ||
                                    'The action could not be completed.';


                                if (
                                    data.errors
                                ) {

                                    const firstError =
                                        Object.values(
                                            data.errors
                                        )
                                        .flat()[0];

                                    if (firstError) {
                                        message =
                                            firstError;
                                    }

                                }


                                throw new Error(
                                    message
                                );
                            }


                            showWorkflowMessage(
                                data.message
                                ||
                                'Updated successfully.',
                                false
                            );


                            /*
                             * Reload after a successful workflow action.
                             *
                             * This ensures:
                             * - assigned collector appears
                             * - home collection status updates
                             * - route planner gets fresh data
                             * - Lab Staff section gets fresh data
                             */

                            window.setTimeout(
                                function () {
                                    window.location.reload();
                                },
                                600
                            );


                            return;
                        }


                        /*
                         * Fallback for normal HTML responses.
                         */

                        const html =
                            await response.text();


                        const parsed =
                            new DOMParser()
                                .parseFromString(
                                    html,
                                    'text/html'
                                );


                        const article =
                            form.closest(
                                'article[id]'
                            );


                        const replacement =
                            article
                                ? parsed.getElementById(
                                    article.id
                                )
                                : null;


                        if (
                            !response.ok
                            || !replacement
                        ) {

                            const pageError =
                                parsed
                                    .querySelector(
                                        '.border-red-200'
                                    )
                                    ?.textContent
                                    .trim();


                            throw new Error(
                                pageError
                                ||
                                'The action could not be completed.'
                            );

                        }


                        article.replaceWith(
                            replacement
                        );


                        const success =
                            parsed
                                .querySelector(
                                    '.mb-5.border-green-200'
                                )
                                ?.textContent
                                .trim();


                        showWorkflowMessage(
                            success
                            ||
                            'Updated successfully without refreshing the page.',
                            false
                        );


                    } catch (error) {

                        if (button) {

                            button.disabled =
                                false;

                            button.textContent =
                                originalText;

                        }


                        showWorkflowMessage(
                            error.message
                            ||
                            'The action could not be completed.',
                            true
                        );

                    }

                }
            );

        });

    </script>


</x-app-layout>