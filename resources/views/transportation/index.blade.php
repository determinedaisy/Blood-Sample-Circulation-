<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Transportation Management
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Track blood samples moving from collection centers to laboratories.
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Messages --}}
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

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- SUMMARY CARDS --}}
            {{-- These are buttons now, so they DO NOT refresh the page --}}
            {{-- ========================================================= --}}

            <div
                style="
                    display:grid;
                    grid-template-columns:repeat(3, minmax(0, 1fr));
                    gap:20px;
                    margin-bottom:24px;
                "
            >

                {{-- Pending --}}
                <button
                    type="button"
                    class="summary-filter-btn"
                    data-filter="pending"
                    style="
                        display:block;
                        width:100%;
                        text-align:left;
                        background:#ffffff;
                        border:1px solid #e5e7eb;
                        border-radius:16px;
                        padding:22px;
                        cursor:pointer;
                        box-shadow:0 1px 3px rgba(0,0,0,0.06);
                    "
                >
                    <div style="display:flex;align-items:center;gap:16px;">

                        <div style="
                            width:56px;
                            height:56px;
                            border-radius:14px;
                            background:#fff7df;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:25px;
                            flex-shrink:0;
                        ">
                            🚚
                        </div>

                        <div>
                            <p style="
                                margin:0;
                                font-size:15px;
                                font-weight:600;
                                color:#374151;
                            ">
                                Pending
                            </p>

                            <p style="
                                margin:3px 0 0;
                                font-size:30px;
                                line-height:1;
                                font-weight:700;
                                color:#e5a000;
                            ">
                                {{ $pendingCount }}
                            </p>

                            <p style="
                                margin:9px 0 0;
                                font-size:14px;
                                color:#6b7280;
                            ">
                                Waiting to be picked up
                            </p>
                        </div>

                    </div>
                </button>


                {{-- In Transit --}}
                <button
                    type="button"
                    class="summary-filter-btn"
                    data-filter="in_transit"
                    style="
                        display:block;
                        width:100%;
                        text-align:left;
                        background:#ffffff;
                        border:1px solid #e5e7eb;
                        border-radius:16px;
                        padding:22px;
                        cursor:pointer;
                        box-shadow:0 1px 3px rgba(0,0,0,0.06);
                    "
                >
                    <div style="display:flex;align-items:center;gap:16px;">

                        <div style="
                            width:56px;
                            height:56px;
                            border-radius:14px;
                            background:#eaf3ff;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:25px;
                            flex-shrink:0;
                        ">
                            🚚
                        </div>

                        <div>
                            <p style="
                                margin:0;
                                font-size:15px;
                                font-weight:600;
                                color:#374151;
                            ">
                                In Transit
                            </p>

                            <p style="
                                margin:3px 0 0;
                                font-size:30px;
                                line-height:1;
                                font-weight:700;
                                color:#2563eb;
                            ">
                                {{ $inTransitCount }}
                            </p>

                            <p style="
                                margin:9px 0 0;
                                font-size:14px;
                                color:#6b7280;
                            ">
                                Currently on the way
                            </p>
                        </div>

                    </div>
                </button>


                {{-- Delivered --}}
                <button
                    type="button"
                    class="summary-filter-btn"
                    data-filter="delivered"
                    style="
                        display:block;
                        width:100%;
                        text-align:left;
                        background:#ffffff;
                        border:1px solid #e5e7eb;
                        border-radius:16px;
                        padding:22px;
                        cursor:pointer;
                        box-shadow:0 1px 3px rgba(0,0,0,0.06);
                    "
                >
                    <div style="display:flex;align-items:center;gap:16px;">

                        <div style="
                            width:56px;
                            height:56px;
                            border-radius:14px;
                            background:#eaf9ef;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:27px;
                            font-weight:700;
                            color:#16a34a;
                            flex-shrink:0;
                        ">
                            ✓
                        </div>

                        <div>
                            <p style="
                                margin:0;
                                font-size:15px;
                                font-weight:600;
                                color:#374151;
                            ">
                                Delivered
                            </p>

                            <p style="
                                margin:3px 0 0;
                                font-size:30px;
                                line-height:1;
                                font-weight:700;
                                color:#16a34a;
                            ">
                                {{ $deliveredCount }}
                            </p>

                            <p style="
                                margin:9px 0 0;
                                font-size:14px;
                                color:#6b7280;
                            ">
                                Successfully delivered
                            </p>
                        </div>

                    </div>
                </button>

            </div>


            {{-- ========================================================= --}}
            {{-- CREATE TRANSPORTATION --}}
            {{-- ========================================================= --}}

            @if(in_array(auth()->user()->role, ['admin', 'lab_staff']))

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">

                    <div class="p-6">

                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">
                                Create Transportation
                            </h3>

                            <p class="text-sm text-gray-600 mt-1">
                                Assign a blood sample to a collector for transportation.
                            </p>
                        </div>


                        <form
                            method="POST"
                            action="{{ route('transportation.store') }}"
                        >
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">

                                {{-- Blood Sample --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Blood Sample <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        name="blood_sample_id"
                                        required
                                        class="w-full rounded-xl border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Select blood sample
                                        </option>

                                        @foreach($bloodSamples as $sample)
                                            <option
                                                value="{{ $sample->id }}"
                                                {{ old('blood_sample_id') == $sample->id ? 'selected' : '' }}
                                            >
                                                {{ $sample->sample_code }}
                                                -
                                                {{ $sample->blood_type ?? 'Unknown' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                {{-- Collector --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Sample Collector <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        name="transported_by"
                                        required
                                        class="w-full rounded-xl border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Select collector
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
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        From Collection Center <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        name="collection_center_id"
                                        required
                                        class="w-full rounded-xl border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Select collection center
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
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Destination Laboratory <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        name="laboratory_id"
                                        required
                                        class="w-full rounded-xl border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Select laboratory
                                        </option>

                                        @foreach($laboratories as $laboratory)
                                            <option
                                                value="{{ $laboratory->id }}"
                                                {{ old('laboratory_id') == $laboratory->id ? 'selected' : '' }}
                                            >
                                                {{ $laboratory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                {{-- Notes --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Notes
                                        <span class="text-gray-500 font-normal">
                                            (Optional)
                                        </span>
                                    </label>

                                    <textarea
                                        name="notes"
                                        rows="2"
                                        maxlength="1000"
                                        class="w-full rounded-xl border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Optional transportation notes..."
                                    >{{ old('notes') }}</textarea>
                                </div>

                            </div>


                            <div class="mt-6">

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-3 rounded-lg font-semibold shadow-sm"
                                    style="background:#2563eb;color:#ffffff;"
                                >
                                    <span class="text-lg">＋</span>
                                    Create Transportation
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- TRANSPORTATION RECORDS --}}
            {{-- ========================================================= --}}

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">

                <div class="p-6 border-b border-gray-200">

                    <h3 class="text-xl font-bold text-gray-900">
                        Transportation Records
                    </h3>

                    <p class="text-sm text-gray-600 mt-1">
                        Filter transportation records by their current status.
                    </p>


                    {{-- Instant Filter Buttons --}}
                    <div
                        class="flex flex-wrap gap-3 mt-5"
                        id="transportFilters"
                    >

                        <button
                            type="button"
                            data-filter="all"
                            class="transport-filter-btn px-5 py-2 rounded-lg text-sm font-semibold border"
                            style="background:#2563eb;color:#ffffff;border-color:#2563eb;"
                        >
                            All
                        </button>


                        <button
                            type="button"
                            data-filter="pending"
                            class="transport-filter-btn px-5 py-2 rounded-lg text-sm font-semibold border"
                            style="background:#ffffff;color:#374151;border-color:#d1d5db;"
                        >
                            🚚 Pending
                        </button>


                        <button
                            type="button"
                            data-filter="in_transit"
                            class="transport-filter-btn px-5 py-2 rounded-lg text-sm font-semibold border"
                            style="background:#ffffff;color:#374151;border-color:#d1d5db;"
                        >
                            🚚 In Transit
                        </button>


                        <button
                            type="button"
                            data-filter="delivered"
                            class="transport-filter-btn px-5 py-2 rounded-lg text-sm font-semibold border"
                            style="background:#ffffff;color:#374151;border-color:#d1d5db;"
                        >
                            ✓ Delivered
                        </button>

                    </div>

                </div>


                {{-- Empty state shown by JavaScript when a filter has no rows --}}
                <div
                    id="transportEmptyState"
                    class="py-16 text-center"
                    style="display:none;"
                >
                    <h4 class="text-lg font-semibold text-gray-900">
                        No transportation records found
                    </h4>

                    <p class="text-gray-500 mt-2">
                        There are currently no records for this filter.
                    </p>
                </div>


                {{-- Table --}}
                @if($transportations->isEmpty())

                    <div class="py-16 text-center">

                        <h4 class="text-lg font-semibold text-gray-900">
                            No transportation records found
                        </h4>

                        <p class="text-gray-500 mt-2">
                            There are currently no transportation records.
                        </p>

                    </div>

                @else

                    <div
                        class="overflow-x-auto"
                        id="transportTableContainer"
                    >

                        <table class="min-w-full">

                            <thead class="bg-gray-50 border-b border-gray-200">

                                <tr>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Sample
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        From
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        To
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Collector
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Departure
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Arrival
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @foreach($transportations as $transportation)

                                    <tr
                                        class="transport-row hover:bg-gray-50"
                                        data-status="{{ $transportation->status }}"
                                    >

                                        {{-- Sample --}}
                                        <td class="px-6 py-5">

                                            <p class="font-bold text-gray-900">
                                                {{ $transportation->bloodSample->sample_code ?? 'Unknown' }}
                                            </p>

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $transportation->bloodSample->blood_type ?? 'Unknown' }}
                                            </p>

                                        </td>


                                        {{-- From --}}
                                        <td class="px-6 py-5">

                                            <p class="font-medium text-gray-900">
                                                {{ $transportation->collectionCenter->name ?? 'Unknown' }}
                                            </p>

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $transportation->collectionCenter->address ?? '' }}
                                            </p>

                                        </td>


                                        {{-- To --}}
                                        <td class="px-6 py-5">

                                            <p class="font-medium text-gray-900">
                                                {{ $transportation->laboratory->name ?? 'Unknown' }}
                                            </p>

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $transportation->laboratory->address ?? '' }}
                                            </p>

                                        </td>


                                        {{-- Collector --}}
                                        <td class="px-6 py-5 text-gray-900 font-medium">
                                            {{ $transportation->transporter->name ?? 'Not assigned' }}
                                        </td>


                                        {{-- Departure --}}
                                        <td class="px-6 py-5 text-gray-900">

                                            {{ $transportation->departure_time
                                                ? $transportation->departure_time->format('d M Y, h:i A')
                                                : 'Not started'
                                            }}

                                        </td>


                                        {{-- Arrival --}}
                                        <td class="px-6 py-5 text-gray-900">

                                            {{ $transportation->arrival_time
                                                ? $transportation->arrival_time->format('d M Y, h:i A')
                                                : 'Not delivered'
                                            }}

                                        </td>


                                        {{-- Status --}}
                                        <td class="px-6 py-5">

                                            @if($transportation->status === 'pending')

                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-semibold"
                                                    style="background:#fef3c7;color:#92400e;"
                                                >
                                                    Pending
                                                </span>

                                            @elseif($transportation->status === 'in_transit')

                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-semibold"
                                                    style="background:#dbeafe;color:#1d4ed8;"
                                                >
                                                    In Transit
                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-semibold"
                                                    style="background:#dcfce7;color:#15803d;"
                                                >
                                                    Delivered
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td class="px-6 py-5">

                                            {{-- Assigned collector can start --}}
                                            @if(
                                                $transportation->status === 'pending'
                                                && auth()->user()->role === 'sample_collector'
                                                && (int) $transportation->transported_by === (int) auth()->id()
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route('transportation.start', $transportation) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-4 py-2 rounded-lg text-sm font-semibold"
                                                        style="background:#2563eb;color:#ffffff;"
                                                    >
                                                        Start Transport
                                                    </button>
                                                </form>


                                            {{-- Assigned collector can deliver --}}
                                            @elseif(
                                                $transportation->status === 'in_transit'
                                                && auth()->user()->role === 'sample_collector'
                                                && (int) $transportation->transported_by === (int) auth()->id()
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route('transportation.deliver', $transportation) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-4 py-2 rounded-lg text-sm font-semibold"
                                                        style="background:#16a34a;color:#ffffff;"
                                                        onclick="return confirm('Confirm that this sample has been delivered?')"
                                                    >
                                                        ✓ Mark Delivered
                                                    </button>
                                                </form>


                                            {{-- Admin / Lab Staff --}}
                                            @elseif(in_array(auth()->user()->role, ['admin', 'lab_staff']))

                                                <span class="text-sm text-gray-500">
                                                    View status only
                                                </span>


                                            {{-- Completed --}}
                                            @elseif($transportation->status === 'delivered')

                                                <span style="color:#15803d;font-weight:600;">
                                                    ✓ Completed
                                                </span>


                                            {{-- No action --}}
                                            @else

                                                <span class="text-sm text-gray-500">
                                                    No action
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


    {{-- ========================================================= --}}
    {{-- INSTANT FILTERING - NO PAGE REFRESH --}}
    {{-- ========================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const filterButtons = document.querySelectorAll(
                '.transport-filter-btn'
            );

            const summaryButtons = document.querySelectorAll(
                '.summary-filter-btn'
            );

            const rows = document.querySelectorAll(
                '.transport-row'
            );

            const emptyState = document.getElementById(
                'transportEmptyState'
            );

            const tableContainer = document.getElementById(
                'transportTableContainer'
            );


            function applyFilter(selectedFilter) {

                let visibleRows = 0;


                // Show/hide table rows
                rows.forEach(function (row) {

                    const rowStatus = row.dataset.status;

                    const shouldShow =
                        selectedFilter === 'all'
                        || rowStatus === selectedFilter;


                    if (shouldShow) {

                        row.style.display = '';
                        visibleRows++;

                    } else {

                        row.style.display = 'none';

                    }

                });


                // Reset all record filter buttons
                filterButtons.forEach(function (button) {

                    button.style.background = '#ffffff';
                    button.style.color = '#374151';
                    button.style.borderColor = '#d1d5db';

                });


                // Highlight selected record filter button
                filterButtons.forEach(function (button) {

                    if (button.dataset.filter === selectedFilter) {

                        if (selectedFilter === 'pending') {

                            button.style.background = '#fef3c7';
                            button.style.color = '#92400e';
                            button.style.borderColor = '#f59e0b';

                        } else if (selectedFilter === 'in_transit') {

                            button.style.background = '#dbeafe';
                            button.style.color = '#1d4ed8';
                            button.style.borderColor = '#3b82f6';

                        } else if (selectedFilter === 'delivered') {

                            button.style.background = '#dcfce7';
                            button.style.color = '#15803d';
                            button.style.borderColor = '#22c55e';

                        } else {

                            button.style.background = '#2563eb';
                            button.style.color = '#ffffff';
                            button.style.borderColor = '#2563eb';

                        }

                    }

                });


                // Show empty message when selected filter has no records
                if (rows.length > 0) {

                    if (visibleRows === 0) {

                        if (tableContainer) {
                            tableContainer.style.display = 'none';
                        }

                        if (emptyState) {
                            emptyState.style.display = 'block';
                        }

                    } else {

                        if (tableContainer) {
                            tableContainer.style.display = 'block';
                        }

                        if (emptyState) {
                            emptyState.style.display = 'none';
                        }

                    }

                }

            }


            // Four Transportation Records buttons
            filterButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    applyFilter(this.dataset.filter);

                });

            });


            // Three summary cards
            summaryButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    applyFilter(this.dataset.filter);


                    // Scroll to Transportation Records
                    const recordsSection =
                        document.getElementById('transportFilters');

                    if (recordsSection) {

                        recordsSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                    }

                });

            });


            // Always start by showing all records
            applyFilter('all');

        });
    </script>

</x-app-layout>