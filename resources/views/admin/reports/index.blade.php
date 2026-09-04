<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Administrative Reports
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Generate database-backed operational reports with an AI narrative.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Generate a report</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Only aggregate operational data is sent to the AI service.
                            </p>
                        </div>

                        @if (config('ai-reports.gemini.api_key'))
                            <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                AI service configured
                            </span>
                        @else
                            <span class="inline-flex w-fit items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                AI key not configured
                            </span>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.reports.store') }}" class="grid gap-5 p-6 md:grid-cols-4">
                    @csrf

                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Report type</label>
                        <select
                            id="report_type"
                            name="report_type"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="all" @selected(old('report_type', 'all') === 'all')>Complete report</option>
                            <option value="collection" @selected(old('report_type') === 'collection')>Sample collection</option>
                            <option value="transportation" @selected(old('report_type') === 'transportation')>Transportation</option>
                            <option value="inventory" @selected(old('report_type') === 'inventory')>Inventory</option>
                            <option value="laboratory" @selected(old('report_type') === 'laboratory')>Laboratory activity</option>
                            <option value="bottleneck" @selected(old('report_type') === 'bottleneck')>Bottleneck &amp; anomaly</option>
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start date</label>
                        <input
                            id="start_date"
                            name="start_date"
                            type="date"
                            value="{{ old('start_date', now()->subDays(29)->toDateString()) }}"
                            max="{{ now()->toDateString() }}"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End date</label>
                        <input
                            id="end_date"
                            name="end_date"
                            type="date"
                            value="{{ old('end_date', now()->toDateString()) }}"
                            max="{{ now()->toDateString() }}"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Generate report
                        </button>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Report history</h3>
                    <p class="mt-1 text-sm text-gray-500">Previously generated administrative reports.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Report</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Generated by</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">AI status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($reports as $report)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $report->report_type === 'all' ? 'Complete report' : ucfirst($report->report_type).' report' }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $report->created_at->format('d M Y, h:i A') }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $report->start_date->format('d M Y') }} - {{ $report->end_date->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $report->generatedBy?->name ?? 'Deleted administrator' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($report->ai_generated)
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">AI generated</span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Metrics only</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a
                                            href="{{ route('admin.reports.show', $report) }}"
                                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                                        >
                                            View report
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No reports have been generated yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reports->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $reports->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
