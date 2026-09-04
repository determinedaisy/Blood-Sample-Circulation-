<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Laboratory Workload</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $laboratory->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $selectedDate ? 'Samples scheduled for '.\Carbon\Carbon::parse($selectedDate)->format('d M Y') : 'Samples assigned across all scheduled dates' }}
                </p>
            </div>
            <a href="{{ route('laboratory-capacity.index', ['date' => $selectedDate ?? now()->toDateString()]) }}"
               class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← All Laboratories
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('laboratory-capacity.workload', $laboratory) }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Testing date</label>
                        <input id="date" name="date" type="date" value="{{ $selectedDate ?? now()->toDateString() }}" class="mt-1 rounded-lg border-gray-300">
                    </div>
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Apply Date</button>
                    @if($selectedDate)
                        <a href="{{ route('laboratory-capacity.workload', ['laboratory' => $laboratory, 'status' => $status]) }}"
                           class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            View All Dates
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    'all' => ['All scheduled', 'bg-gray-100 text-gray-800'],
                    'pending' => ['Awaiting transport', 'bg-amber-100 text-amber-800'],
                    'in_transit' => ['In transit', 'bg-blue-100 text-blue-800'],
                    'delivered' => ['Delivered', 'bg-green-100 text-green-800'],
                ] as $filter => [$label, $colors])
                    <a href="{{ route('laboratory-capacity.workload', array_filter(['laboratory' => $laboratory, 'date' => $selectedDate, 'status' => $filter])) }}"
                       class="rounded-2xl border p-5 shadow-sm {{ $status === $filter ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200' }} bg-white">
                        <p class="text-sm text-gray-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $statusCounts[$filter] }}</p>
                        <span class="mt-3 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $colors }}">View</span>
                    </a>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Assigned samples</h3>
                    <p class="mt-1 text-sm text-gray-500">Capacity is reserved on each sample's scheduled testing date.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Sample</th>
                                <th class="px-5 py-3">Patient</th>
                                <th class="px-5 py-3">Source</th>
                                <th class="px-5 py-3">Testing date</th>
                                <th class="px-5 py-3">Collector</th>
                                <th class="px-5 py-3">Transport</th>
                                <th class="px-5 py-3">Report</th>
                                <th class="px-5 py-3">Doctor</th>
                                <th class="px-5 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transportations as $transportation)
                                @php
                                    $sample = $transportation->bloodSample;
                                    $report = $sample?->sampleReport;
                                    $reportLabel = $report
                                        ? ucfirst(str_replace('_', ' ', $report->status))
                                        : 'Not entered';
                                @endphp
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-blue-700">{{ $sample?->sample_code ?? 'Missing sample' }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $sample?->sample_type }} · {{ $sample?->blood_type }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-900">{{ $sample?->patient?->name ?? 'Not recorded' }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $sample?->patient?->email }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-gray-700">{{ $transportation->collectionCenter?->name ?? 'Home collection / not recorded' }}</td>
                                    <td class="px-5 py-4 text-gray-700">{{ $transportation->scheduled_test_date?->format('d M Y') ?? 'Not scheduled' }}</td>
                                    <td class="px-5 py-4 text-gray-700">{{ $transportation->transporter?->name ?? 'Not assigned' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $transportation->status === 'delivered' ? 'bg-green-100 text-green-800' : ($transportation->status === 'in_transit' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $transportation->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-gray-700">{{ $reportLabel }}</td>
                                    <td class="px-5 py-4 text-gray-700">{{ $sample?->sampleRequest?->assignedDoctor?->name ?? 'Not assigned' }}</td>
                                    <td class="px-5 py-4">
                                        @if($transportation->status === 'delivered' && $sample && $report?->status !== 'published')
                                            <a href="{{ route('laboratory-capacity.report.edit', ['laboratory' => $laboratory, 'sampleTransportation' => $transportation]) }}"
                                               class="inline-flex rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                                {{ $report ? 'Edit Lab Results' : 'Enter Lab Results' }}
                                            </a>
                                        @elseif($report?->status === 'published')
                                            <span class="text-xs font-semibold text-green-700">Doctor published</span>
                                        @else
                                            <span class="text-xs text-gray-500">Awaiting delivery</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            <tr><td colspan="9" class="px-6 py-12 text-center text-gray-500">No samples match this laboratory, date and status selection.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
