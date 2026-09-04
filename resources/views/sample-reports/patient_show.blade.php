<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 print:hidden">
            <div>
                <p class="text-sm font-medium text-indigo-600">Published Medical Report</p>
                <h2 class="text-2xl font-bold text-gray-900">{{ $sampleReport->title }}</h2>
            </div>
            <a href="{{ route('patient.blood-samples.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← My Blood Samples
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <article class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">
                <div class="flex flex-wrap justify-between gap-6 border-b border-gray-200 pb-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Sample code</p>
                        <p class="mt-1 text-xl font-bold text-indigo-700">{{ $sampleReport->bloodSample->sample_code }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $sampleReport->bloodSample->sample_type }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Published</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $sampleReport->published_at?->format('d M Y, h:i A') }}</p>
                        <p class="mt-1 text-sm text-gray-600">Dr. {{ $sampleReport->doctor?->name }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Patient</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $sampleReport->bloodSample->patient?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Report status</p>
                        <span class="mt-1 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Doctor Published</span>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Laboratory results</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                            <tr><th class="px-5 py-3">Test</th><th class="px-5 py-3">Result</th><th class="px-5 py-3">Reference</th><th class="px-5 py-3">Flag</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sampleReport->results as $result)
                                <tr>
                                    <td class="px-5 py-4"><p class="font-semibold text-gray-900">{{ $result->test_name }}</p>@if($result->notes)<p class="mt-1 text-xs text-gray-500">{{ $result->notes }}</p>@endif</td>
                                    <td class="px-5 py-4 text-gray-900">{{ $result->result_value }} {{ $result->unit }}</td>
                                    <td class="px-5 py-4 text-gray-600">{{ $result->reference_range ?: 'Not supplied' }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $result->flag === 'normal' ? 'bg-green-100 text-green-800' : ($result->flag === 'critical' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ ucfirst($result->flag) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-7 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-lg font-semibold text-indigo-950">Explanation for you</h3>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-indigo-700">
                        {{ $sampleReport->ai_generated ? 'AI-assisted · doctor approved' : 'Doctor prepared' }}
                    </span>
                </div>
                <div class="mt-5 text-sm leading-7 text-gray-800">
                    {!! \Illuminate\Support\Str::markdown($sampleReport->patient_explanation, [
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ]) !!}
                </div>
                <p class="mt-5 text-xs text-indigo-700">This explanation is informational and does not replace consultation with your doctor.</p>
            </div>

            @if($sampleReport->doctor_notes)
                <div class="rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Doctor notes</h3>
                    <div class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-700">{{ $sampleReport->doctor_notes }}</div>
                </div>
            @endif

            <div class="flex flex-wrap gap-3 print:hidden">
                <a href="{{ route('sample-reports.download', $sampleReport) }}"
                   class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Download Official Report PDF</a>
                <button type="button" onclick="window.print()"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Print This Report</button>
            </div>
        </article>
    </div>
</x-app-layout>
