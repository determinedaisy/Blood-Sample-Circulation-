<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $sampleReport->title }}</title>
    <style>
        @page { margin: 28px 36px 42px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #24324a; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.45; }
        .header { width: 100%; border-collapse: collapse; color: #fff; background: #14213d; }
        .header td { padding: 16px; vertical-align: middle; }
        .logo { width: 70px; text-align: center; border-right: 1px solid #41506c; font-size: 21px; font-weight: bold; }
        .lab-name { margin: 0 0 3px; font-size: 18px; font-weight: bold; }
        .lab-address { color: #dce7f8; font-size: 8px; }
        .report-id { width: 130px; border-left: 1px solid #41506c; }
        .report-id small { display: block; color: #dce7f8; font-size: 7px; font-weight: bold; }
        .report-id strong { display: block; margin-top: 3px; font-size: 9px; }
        .title { margin: 18px 0 3px; color: #14213d; text-align: center; font-size: 17px; }
        .subtitle { margin: 0 0 16px; color: #64748b; text-align: center; font-size: 9px; }
        .status { margin-bottom: 14px; padding: 7px 10px; border: 1px solid #bfdbfe; color: #1d4ed8; background: #eff6ff; text-align: center; font-weight: bold; }
        .status.published { border-color: #bbf7d0; color: #15803d; background: #f0fdf4; }
        .section-title { margin: 16px 0 7px; color: #14213d; font-size: 10px; font-weight: bold; }
        .details, .results, .signatures { width: 100%; border-collapse: collapse; }
        .details td { width: 50%; padding: 8px 10px; border: 1px solid #e2e8f0; background: #f8fafc; vertical-align: top; }
        .label { display: block; margin-bottom: 2px; color: #64748b; font-size: 7px; font-weight: bold; text-transform: uppercase; }
        .value { color: #14213d; font-size: 9px; font-weight: bold; }
        .results th { padding: 8px 7px; color: #fff; background: #2563eb; text-align: left; font-size: 7px; text-transform: uppercase; }
        .results td { padding: 8px 7px; border: 1px solid #e2e8f0; vertical-align: top; }
        .results tr:nth-child(even) td { background: #f8fafc; }
        .test { color: #14213d; font-weight: bold; }
        .normal { color: #15803d; font-weight: bold; }
        .low, .high { color: #b45309; font-weight: bold; }
        .critical { color: #b91c1c; font-weight: bold; }
        .box { margin-top: 14px; padding: 11px 12px; border: 1px solid #bfdbfe; background: #eff6ff; }
        .box h3 { margin: 0 0 6px; color: #14213d; font-size: 10px; }
        .explanation h1, .explanation h2, .explanation h3 { margin: 9px 0 4px; color: #14213d; font-size: 10px; }
        .explanation p { margin: 0 0 6px; }
        .explanation ul { margin: 4px 0 6px 18px; padding: 0; }
        .signatures { margin-top: 25px; }
        .signatures td { width: 50%; padding-right: 25px; vertical-align: top; }
        .line { padding-top: 5px; border-top: 1px solid #64748b; }
        .small { color: #64748b; font-size: 7px; }
        .footer { position: fixed; right: 0; bottom: -28px; left: 0; padding-top: 7px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 7px; }
        .footer-right { float: right; color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>
    @php
        $sample = $sampleReport->bloodSample;
        $patient = $sample?->patient;
        $doctor = $sampleReport->doctor ?? $sample?->sampleRequest?->assignedDoctor;
        $laboratory = $transportation?->laboratory;
        $source = $transportation?->collectionCenter?->name ?? 'Home collection / not recorded';
    @endphp

    <table class="header">
        <tr>
            <td class="logo">BSC</td>
            <td>
                <div class="lab-name">{{ $laboratory?->name ?? 'Diagnostic Laboratory' }}</div>
                <div class="lab-address">{{ $laboratory?->address ?? 'Laboratory address not recorded' }}</div>
            </td>
            <td class="report-id">
                <small>REPORT ID</small>
                <strong>LAB-{{ $sample?->sample_code }}</strong>
            </td>
        </tr>
    </table>

    <h1 class="title">{{ $sampleReport->title }}</h1>
    <p class="subtitle">System-generated laboratory and doctor-reviewed report</p>

    <div class="status {{ $sampleReport->status === 'published' ? 'published' : '' }}">
        {{ $sampleReport->status === 'published'
            ? 'PUBLISHED AND APPROVED BY THE ASSIGNED DOCTOR'
            : 'LABORATORY RESULTS SUBMITTED - AWAITING DOCTOR APPROVAL' }}
    </div>

    <div class="section-title">PATIENT AND SAMPLE INFORMATION</div>
    <table class="details">
        <tr>
            <td><span class="label">Patient</span><span class="value">{{ $patient?->name ?? 'Not recorded' }}</span></td>
            <td><span class="label">Patient ID</span><span class="value">#{{ $patient?->id ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Sample code</span><span class="value">{{ $sample?->sample_code }}</span></td>
            <td><span class="label">Sample type / blood group</span><span class="value">{{ $sample?->sample_type }} / {{ $sample?->blood_type ?? 'Not recorded' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Collection source</span><span class="value">{{ $source }}</span></td>
            <td><span class="label">Scheduled testing date</span><span class="value">{{ $transportation?->scheduled_test_date?->format('d M Y') ?? 'Not recorded' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Laboratory submission</span><span class="value">{{ $sampleReport->lab_submitted_at?->format('d M Y, h:i A') ?? 'Not recorded' }}</span></td>
            <td><span class="label">Assigned doctor</span><span class="value">{{ $doctor?->name ? 'Dr. '.$doctor->name : 'Not assigned' }}</span></td>
        </tr>
    </table>

    <div class="section-title">LABORATORY TEST RESULTS</div>
    <table class="results">
        <thead>
            <tr>
                <th style="width: 27%">Test</th>
                <th style="width: 15%">Result</th>
                <th style="width: 14%">Unit</th>
                <th style="width: 25%">Reference range</th>
                <th style="width: 19%">Flag</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sampleReport->results as $result)
                <tr>
                    <td><span class="test">{{ $result->test_name }}</span>@if($result->notes)<br><span class="small">{{ $result->notes }}</span>@endif</td>
                    <td><strong>{{ $result->result_value }}</strong></td>
                    <td>{{ $result->unit ?: '-' }}</td>
                    <td>{{ $result->reference_range ?: 'Not supplied' }}</td>
                    <td class="{{ $result->flag }}">{{ strtoupper($result->flag) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($sampleReport->patient_explanation)
        <div class="box explanation">
            <h3>PATIENT-FRIENDLY EXPLANATION</h3>
            {!! \Illuminate\Support\Str::markdown($sampleReport->patient_explanation, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]) !!}
        </div>
    @endif

    @if($sampleReport->doctor_notes)
        <div class="box">
            <h3>DOCTOR NOTES</h3>
            <div>{!! nl2br(e($sampleReport->doctor_notes)) !!}</div>
        </div>
    @endif

    <table class="signatures">
        <tr>
            <td><div class="line"><strong>{{ $sampleReport->labSubmitter?->name ?? 'Laboratory Staff' }}</strong><br><span class="small">Laboratory result entry</span></div></td>
            <td><div class="line"><strong>{{ $sampleReport->status === 'published' ? ($doctor?->name ? 'Dr. '.$doctor->name : 'Assigned Doctor') : 'Doctor approval pending' }}</strong><br><span class="small">Medical review and publication</span></div></td>
        </tr>
    </table>

    <div class="footer">
        Generated by Blood Sample Circulation on {{ now()->format('d M Y, h:i A') }}
        <span class="footer-right">CONFIDENTIAL MEDICAL REPORT</span>
    </div>
</body>
</html>
