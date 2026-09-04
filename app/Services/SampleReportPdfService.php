<?php

namespace App\Services;

use App\Models\SampleReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SampleReportPdfService
{
    public function generateAndStore(SampleReport $report): void
    {
        $report->load([
            'doctor.doctorProfile',
            'labSubmitter',
            'results',
            'bloodSample.patient',
            'bloodSample.sampleRequest.assignedDoctor',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ]);

        $transportation = $report->bloodSample?->transportations
            ?->sortByDesc('id')
            ?->first();

        $pdf = Pdf::loadView('sample-reports.pdf', [
            'sampleReport' => $report,
            'transportation' => $transportation,
        ])->setPaper('a4');

        $safeCode = preg_replace('/[^A-Za-z0-9_-]/', '-', $report->bloodSample->sample_code);
        $filename = 'laboratory-report-'.$safeCode.'.pdf';
        $path = 'sample-reports/generated/'.$report->id.'-'.$filename;
        $oldPath = $report->attachment_path;

        Storage::disk('local')->put($path, $pdf->output());

        $report->forceFill([
            'attachment_path' => $path,
            'attachment_original_name' => $filename,
        ])->save();

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('local')->delete($oldPath);
        }
    }
}
