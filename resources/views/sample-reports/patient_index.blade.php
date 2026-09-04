<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Reports</h2>
            <p class="mt-1 text-sm text-gray-500">View medical reports published by your assigned doctors.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <p class="text-sm font-semibold text-indigo-950">
                    {{ $reports->total() }} {{ Str::plural('published report', $reports->total()) }}
                </p>
                <p class="mt-1 text-sm text-indigo-700">Laboratory drafts remain private until an assigned doctor reviews and publishes them.</p>
            </div>

            @if($reports->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-14 text-center shadow-sm">
                    <div class="text-4xl">📄</div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No published reports yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Your reports will appear here after laboratory submission and doctor approval.</p>
                    <a href="{{ route('patient.blood-samples.index') }}"
                       class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        View My Samples
                    </a>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-5 py-4">Sample</th>
                                    <th class="px-5 py-4">Report</th>
                                    <th class="px-5 py-4">Laboratory</th>
                                    <th class="px-5 py-4">Approved by</th>
                                    <th class="px-5 py-4">Published</th>
                                    <th class="px-5 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($reports as $report)
                                    @php
                                        $transportation = $report->bloodSample?->transportations?->sortByDesc('id')->first();
                                        $doctor = $report->doctor ?? $report->bloodSample?->sampleRequest?->assignedDoctor;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-indigo-700">{{ $report->bloodSample?->sample_code }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $report->bloodSample?->sample_type }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-gray-900">{{ $report->title }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $report->results->count() }} {{ Str::plural('test', $report->results->count()) }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $transportation?->laboratory?->name ?? 'Not available' }}
                                        </td>
                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $doctor?->name ?? 'Assigned doctor' }}
                                        </td>
                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $report->published_at?->format('d M Y, h:i A') }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('sample-reports.patient.show', $report) }}"
                                                   class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                                    View
                                                </a>
                                                <a href="{{ route('sample-reports.download', $report) }}"
                                                   class="rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800">
                                                    Download PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
