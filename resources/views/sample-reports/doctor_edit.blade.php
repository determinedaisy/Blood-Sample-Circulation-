<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Doctor Medical Report</p>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $sampleRequest->bloodSample->sample_code }}
                </h2>
            </div>
            <a href="{{ route('sample-requests.doctor.show', $sampleRequest) }}"
               class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Back to Case
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-semibold">Please correct the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Patient and sample</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $sampleRequest->patient?->name }} · {{ $sampleRequest->bloodSample->sample_type }}
                        </p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $report?->status === 'published' ? 'bg-green-100 text-green-800' : ($report?->status === 'lab_submitted' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $report?->status === 'published' ? 'Published' : ($report?->status === 'lab_submitted' ? 'Laboratory Results Submitted' : 'Draft') }}
                    </span>
                </div>
                @if($report?->status === 'lab_submitted')
                    <p class="mt-4 rounded-lg bg-indigo-50 p-3 text-sm text-indigo-800">
                        Laboratory staff submitted these values and the original PDF {{ $report->lab_submitted_at?->diffForHumans() }}. Verify them before generating an explanation.
                    </p>
                @endif
                @if($report?->status === 'published')
                    <p class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">
                        Saving any change will return this report to draft until you publish it again.
                    </p>
                @endif
            </div>

            <form method="POST"
                  action="{{ route('sample-reports.doctor.update', $sampleRequest) }}"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <label for="title" class="block text-sm font-semibold text-gray-800">Report title</label>
                    <input id="title" name="title" type="text" required
                           value="{{ old('title', $report?->title ?? 'Laboratory Test Report - '.$sampleRequest->bloodSample->sample_code) }}"
                           class="mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Laboratory results</h3>
                        <p class="mt-1 text-sm text-gray-500">Official values submitted by laboratory staff. Doctors can review but cannot change them.</p>
                    </div>

                    <div class="mt-5 overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Test</th>
                                    <th class="px-4 py-3">Value</th>
                                    <th class="px-4 py-3">Unit</th>
                                    <th class="px-4 py-3">Reference</th>
                                    <th class="px-4 py-3">Flag</th>
                                    <th class="px-4 py-3">Lab note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($report?->results ?? [] as $result)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $result->test_name }}</td>
                                        <td class="px-4 py-3 text-gray-900">{{ $result->result_value }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $result->unit ?: '—' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $result->reference_range ?: '—' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $result->flag === 'normal' ? 'bg-green-100 text-green-800' : ($result->flag === 'critical' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ ucfirst($result->flag) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $result->notes ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No laboratory results have been submitted.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-800">System-generated laboratory PDF</h3>
                        <p class="mt-1 text-xs text-gray-500">The PDF is regenerated automatically when laboratory values or doctor-reviewed content changes.</p>
                        @if($report?->attachment_path)
                            <a href="{{ route('sample-reports.download', $report) }}"
                               class="mt-4 inline-flex text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                Download current formatted PDF
                            </a>
                        @else
                            <p class="mt-4 text-sm font-medium text-amber-700">The laboratory has not generated a PDF yet.</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <label for="doctor_notes" class="block text-sm font-semibold text-gray-800">Doctor notes</label>
                        <p class="mt-1 text-xs text-gray-500">These notes will be visible to the patient.</p>
                        <textarea id="doctor_notes" name="doctor_notes" rows="5"
                                  class="mt-3 w-full rounded-lg border-gray-300">{{ old('doctor_notes', $report?->doctor_notes) }}</textarea>
                    </div>
                </div>

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                    <label for="patient_explanation" class="block text-sm font-semibold text-indigo-950">Patient-friendly explanation</label>
                    <p class="mt-1 text-xs text-indigo-700">You may write this yourself or save the values first and use Generate AI. Always review AI text before publishing.</p>
                    <textarea id="patient_explanation" name="patient_explanation" rows="13"
                              class="mt-3 w-full rounded-lg border-indigo-200 bg-white">{{ old('patient_explanation', $report?->patient_explanation) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button class="rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white hover:bg-gray-800">
                        Save Draft
                    </button>
                </div>
            </form>

            @if($report?->exists)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Review and publish</h3>
                    <p class="mt-1 text-sm text-gray-500">AI creates a draft explanation only. The assigned doctor remains responsible for approval.</p>
                    <p class="mt-2 text-sm font-medium text-amber-700">After editing the generated explanation, click Save Draft again so the formatted PDF is updated before publishing.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('sample-reports.doctor.generate-ai', $sampleRequest) }}">
                            @csrf
                            <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                Generate AI Explanation
                            </button>
                        </form>
                        <form method="POST" action="{{ route('sample-reports.doctor.publish', $sampleRequest) }}"
                              onsubmit="return confirm('Publish this report for the patient?');">
                            @csrf
                            <button class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                                Publish to Patient
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
