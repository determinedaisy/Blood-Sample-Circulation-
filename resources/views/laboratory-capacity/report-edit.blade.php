<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Laboratory Result Entry</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $sampleTransportation->bloodSample->sample_code }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $laboratory->name }}</p>
            </div>
            <a href="{{ route('laboratory-capacity.workload', ['laboratory' => $laboratory, 'date' => $sampleTransportation->scheduled_test_date?->toDateString()]) }}"
               class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Back to Workload
            </a>
        </div>
    </x-slot>

    @php
        $rows = old('results');
        if ($rows === null) {
            $rows = $report?->results?->map(fn ($result) => [
                'test_name' => $result->test_name,
                'result_value' => $result->result_value,
                'unit' => $result->unit,
                'reference_range' => $result->reference_range,
                'flag' => $result->flag,
                'notes' => $result->notes,
            ])->values()->all() ?: [[
                'test_name' => '', 'result_value' => '', 'unit' => '',
                'reference_range' => '', 'flag' => 'normal', 'notes' => '',
            ]];
        }
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-semibold">Please correct the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6 shadow-sm">
                <h3 class="font-semibold text-blue-950">Delivered sample ready for laboratory results</h3>
                <div class="mt-4 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                    <div><p class="text-xs font-semibold uppercase text-blue-700">Patient</p><p class="mt-1 font-medium text-gray-900">{{ $sampleTransportation->bloodSample->patient?->name ?? 'Not recorded' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-blue-700">Sample type</p><p class="mt-1 font-medium text-gray-900">{{ $sampleTransportation->bloodSample->sample_type }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-blue-700">Source</p><p class="mt-1 font-medium text-gray-900">{{ $sampleTransportation->collectionCenter?->name ?? 'Home collection / not recorded' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-blue-700">Assigned doctor</p><p class="mt-1 font-medium text-gray-900">{{ $sampleTransportation->bloodSample->sampleRequest?->assignedDoctor?->name ?? 'Not assigned yet' }}</p></div>
                </div>
                <p class="mt-5 text-sm text-blue-800">Submitting saves the values, generates the formatted PDF and sends the report into the doctor’s existing case. The patient cannot see it until the doctor reviews and publishes.</p>
            </div>

            <form method="POST"
                  action="{{ route('laboratory-capacity.report.update', ['laboratory' => $laboratory, 'sampleTransportation' => $sampleTransportation]) }}"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Laboratory values</h3>
                            <p class="mt-1 text-sm text-gray-500">Enter the verified values exactly as produced by the laboratory.</p>
                        </div>
                        <button id="add-result" type="button" class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">+ Add Test</button>
                    </div>

                    <div id="result-rows" class="mt-5 space-y-4">
                        @foreach($rows as $index => $row)
                            <div class="result-row rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="grid gap-4 md:grid-cols-6">
                                    <div class="md:col-span-2"><label class="text-xs font-semibold uppercase text-gray-600">Test name</label><input name="results[{{ $index }}][test_name]" required value="{{ $row['test_name'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                                    <div><label class="text-xs font-semibold uppercase text-gray-600">Value</label><input name="results[{{ $index }}][result_value]" required value="{{ $row['result_value'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                                    <div><label class="text-xs font-semibold uppercase text-gray-600">Unit</label><input name="results[{{ $index }}][unit]" value="{{ $row['unit'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                                    <div><label class="text-xs font-semibold uppercase text-gray-600">Reference</label><input name="results[{{ $index }}][reference_range]" value="{{ $row['reference_range'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-600">Flag</label>
                                        <select name="results[{{ $index }}][flag]" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                            @foreach(['normal' => 'Normal', 'low' => 'Low', 'high' => 'High', 'critical' => 'Critical'] as $value => $label)
                                                <option value="{{ $value }}" @selected(($row['flag'] ?? 'normal') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-end gap-3">
                                    <div class="flex-1"><label class="text-xs font-semibold uppercase text-gray-600">Result note (optional)</label><input name="results[{{ $index }}][notes]" value="{{ $row['notes'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                                    <button type="button" class="remove-result rounded-lg px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-green-900">Automatic formatted PDF</h3>
                    <p class="mt-2 text-sm text-green-800">The system will automatically create the PDF using this patient, sample, laboratory, doctor and test-result information. No file upload is needed.</p>
                    @if($report?->attachment_path)
                        <a href="{{ route('sample-reports.download', $report) }}" class="mt-4 inline-flex text-sm font-semibold text-green-700 hover:text-green-900">
                            Download currently generated PDF
                        </a>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Submit Results and Generate PDF</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('result-rows');
            let nextIndex = container.querySelectorAll('.result-row').length;

            document.getElementById('add-result').addEventListener('click', () => {
                const index = nextIndex++;
                const wrapper = document.createElement('div');
                wrapper.className = 'result-row rounded-xl border border-gray-200 bg-gray-50 p-4';
                wrapper.innerHTML = `
                    <div class="grid gap-4 md:grid-cols-6">
                        <div class="md:col-span-2"><label class="text-xs font-semibold uppercase text-gray-600">Test name</label><input name="results[${index}][test_name]" required class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                        <div><label class="text-xs font-semibold uppercase text-gray-600">Value</label><input name="results[${index}][result_value]" required class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                        <div><label class="text-xs font-semibold uppercase text-gray-600">Unit</label><input name="results[${index}][unit]" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                        <div><label class="text-xs font-semibold uppercase text-gray-600">Reference</label><input name="results[${index}][reference_range]" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div>
                        <div><label class="text-xs font-semibold uppercase text-gray-600">Flag</label><select name="results[${index}][flag]" class="mt-1 w-full rounded-lg border-gray-300 text-sm"><option value="normal">Normal</option><option value="low">Low</option><option value="high">High</option><option value="critical">Critical</option></select></div>
                    </div>
                    <div class="mt-3 flex items-end gap-3"><div class="flex-1"><label class="text-xs font-semibold uppercase text-gray-600">Result note (optional)</label><input name="results[${index}][notes]" class="mt-1 w-full rounded-lg border-gray-300 text-sm"></div><button type="button" class="remove-result rounded-lg px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Remove</button></div>`;
                container.appendChild(wrapper);
            });

            container.addEventListener('click', event => {
                const button = event.target.closest('.remove-result');
                if (!button || container.querySelectorAll('.result-row').length === 1) return;
                button.closest('.result-row').remove();
            });
        });
    </script>
</x-app-layout>
