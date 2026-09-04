<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\SampleReport;
use App\Models\SampleRequest;
use App\Models\SampleTransportation;
use App\Notifications\PatientSampleUpdateNotification;
use App\Services\ClinicalReportAiService;
use App\Services\SampleReportPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class SampleReportController extends Controller
{
    public function labEdit(Laboratory $laboratory, SampleTransportation $sampleTransportation)
    {
        $this->authorizeLaboratorySample($laboratory, $sampleTransportation);

        if ($sampleTransportation->status !== 'delivered') {
            return redirect()
                ->route('laboratory-capacity.workload', [
                    'laboratory' => $laboratory,
                    'date' => $sampleTransportation->scheduled_test_date?->toDateString(),
                ])
                ->with('error', 'Laboratory results can only be entered after the sample is delivered.');
        }

        $sampleTransportation->load([
            'bloodSample.patient',
            'bloodSample.sampleRequest.assignedDoctor',
            'bloodSample.sampleReport.results',
            'collectionCenter',
            'transporter',
        ]);

        $report = $sampleTransportation->bloodSample?->sampleReport;

        if ($report?->status === 'published') {
            return redirect()
                ->route('laboratory-capacity.workload', [
                    'laboratory' => $laboratory,
                    'date' => $sampleTransportation->scheduled_test_date?->toDateString(),
                ])
                ->with('error', 'This report has already been published by the doctor and can no longer be changed by laboratory staff.');
        }

        return view('laboratory-capacity.report-edit', compact(
            'laboratory',
            'sampleTransportation',
            'report'
        ));
    }

    public function labUpdate(
        Request $request,
        Laboratory $laboratory,
        SampleTransportation $sampleTransportation,
        SampleReportPdfService $pdfService
    ): RedirectResponse {
        $this->authorizeLaboratorySample($laboratory, $sampleTransportation);
        abort_unless($sampleTransportation->status === 'delivered', 403);

        $sampleTransportation->loadMissing('bloodSample.sampleRequest');
        abort_unless($sampleTransportation->bloodSample, 404);

        $report = SampleReport::firstOrNew([
            'blood_sample_id' => $sampleTransportation->bloodSample->id,
        ]);

        if ($report->exists && $report->status === 'published') {
            return back()->with('error', 'Published reports cannot be changed by laboratory staff.');
        }

        $validated = $request->validate([
            'results' => ['required', 'array', 'min:1'],
            'results.*.test_name' => ['required', 'string', 'max:255'],
            'results.*.result_value' => ['required', 'string', 'max:255'],
            'results.*.unit' => ['nullable', 'string', 'max:100'],
            'results.*.reference_range' => ['nullable', 'string', 'max:255'],
            'results.*.flag' => ['required', Rule::in(['normal', 'low', 'high', 'critical'])],
            'results.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use (
            $validated,
            $report,
            $sampleTransportation
        ) {
                $report->fill([
                    'doctor_id' => $sampleTransportation->bloodSample?->sampleRequest?->assigned_doctor_id,
                    'lab_submitted_by' => Auth::id(),
                    'title' => $report->title ?: 'Laboratory Test Report - '.$sampleTransportation->bloodSample->sample_code,
                    'doctor_notes' => null,
                    'patient_explanation' => null,
                    'status' => 'lab_submitted',
                    'published_at' => null,
                    'lab_submitted_at' => now(),
                    'ai_generated' => false,
                    'ai_error' => null,
                ]);

                $report->save();
                $report->results()->delete();

                foreach ($validated['results'] as $index => $result) {
                    $report->results()->create([
                        'test_name' => $result['test_name'],
                        'result_value' => $result['result_value'],
                        'unit' => $result['unit'] ?? null,
                        'reference_range' => $result['reference_range'] ?? null,
                        'flag' => $result['flag'],
                        'notes' => $result['notes'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
        });

        try {
            $pdfService->generateAndStore($report->fresh());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'The laboratory values were saved, but the PDF could not be generated. Please try submitting again.');
        }

        return redirect()
            ->route('laboratory-capacity.workload', [
                'laboratory' => $laboratory,
                'date' => $sampleTransportation->scheduled_test_date?->toDateString(),
                'status' => 'delivered',
            ])
            ->with('success', 'Laboratory values were submitted and the formatted PDF was generated automatically.');
    }

    public function edit(SampleRequest $sampleRequest): View
    {
        $this->authorizeAssignedDoctor($sampleRequest);

        $sampleRequest->load([
            'patient',
            'bloodSample.sampleReport.results',
            'bloodSample.sampleReport.doctor',
        ]);

        abort_unless($sampleRequest->bloodSample, 404);

        $report = $sampleRequest->bloodSample->sampleReport;

        return view('sample-reports.doctor_edit', compact('sampleRequest', 'report'));
    }

    public function update(
        Request $request,
        SampleRequest $sampleRequest,
        SampleReportPdfService $pdfService
    ): RedirectResponse
    {
        $this->authorizeAssignedDoctor($sampleRequest);
        abort_unless($sampleRequest->bloodSample, 404);

        if ($request->has('results')) {
            return back()->with('error', 'Laboratory values can only be changed by laboratory staff.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'doctor_notes' => ['nullable', 'string', 'max:5000'],
            'patient_explanation' => ['nullable', 'string', 'max:10000'],
        ]);

        $report = $sampleRequest->bloodSample->sampleReport;

        if (! $report || ! $report->lab_submitted_at) {
            return back()->with('error', 'Laboratory staff must submit the test results before doctor review.');
        }

        $report->update([
            'doctor_id' => Auth::id(),
            'title' => $validated['title'],
            'doctor_notes' => $validated['doctor_notes'] ?? null,
            'patient_explanation' => $validated['patient_explanation'] ?? null,
            'status' => 'draft',
            'published_at' => null,
            'ai_generated' => false,
            'ai_error' => null,
        ]);

        try {
            $pdfService->generateAndStore($report->fresh());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'The draft was saved, but its PDF could not be regenerated. Please try saving again.');
        }

        return redirect()
            ->route('sample-reports.doctor.edit', $sampleRequest)
            ->with('success', 'The medical report was saved as a draft.');
    }

    public function generateAi(
        SampleRequest $sampleRequest,
        ClinicalReportAiService $aiService,
        SampleReportPdfService $pdfService
    ): RedirectResponse {
        $this->authorizeAssignedDoctor($sampleRequest);
        abort_unless($sampleRequest->bloodSample, 404);

        $report = $sampleRequest->bloodSample->sampleReport()->with('results')->first();

        if (! $report || $report->results->isEmpty()) {
            return back()->with('error', 'Save at least one test result before generating an AI explanation.');
        }

        $generated = $aiService->generate($report);

        if (! $generated['success']) {
            $report->update(['ai_error' => $generated['error']]);

            return back()->with('error', $generated['error']);
        }

        $report->update([
            'patient_explanation' => $generated['text'],
            'ai_generated' => true,
            'ai_error' => null,
            'status' => 'draft',
            'published_at' => null,
        ]);

        try {
            $pdfService->generateAndStore($report->fresh());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'The AI explanation was saved, but the PDF could not be regenerated.');
        }

        return back()->with('success', 'The AI explanation was generated. Review it carefully before publishing.');
    }

    public function publish(
        SampleRequest $sampleRequest,
        SampleReportPdfService $pdfService
    ): RedirectResponse
    {
        $this->authorizeAssignedDoctor($sampleRequest);
        abort_unless($sampleRequest->bloodSample, 404);

        $report = $sampleRequest->bloodSample->sampleReport()->withCount('results')->first();

        if (! $report || $report->results_count < 1) {
            return back()->with('error', 'Add and save at least one laboratory result first.');
        }

        if (! $report->lab_submitted_at) {
            return back()->with('error', 'Laboratory staff must submit the test results before publication.');
        }

        if (trim((string) $report->patient_explanation) === '') {
            return back()->with('error', 'Add or generate a patient explanation before publishing.');
        }

        $report->fill([
            'doctor_id' => Auth::id(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        try {
            $pdfService->generateAndStore($report);
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'The final PDF could not be generated, so the report was not published.');
        }

        $report->loadMissing('bloodSample.patient');

        $report->bloodSample?->patient?->notify(
            new PatientSampleUpdateNotification(
                kind: 'medical_report_published',
                title: 'Medical report ready',
                message: 'Your doctor reviewed and published the medical report for sample '.$report->bloodSample?->sample_code.'.',
                sampleReportId: $report->id,
                sampleCode: $report->bloodSample?->sample_code,
                actionLabel: 'View Report',
            )
        );

        return back()->with('success', 'The report is published and the patient has been notified.');
    }

    public function patientIndex(): View
    {
        abort_unless(Auth::user()?->role === 'patient', 403);

        $reports = SampleReport::query()
            ->where('status', 'published')
            ->whereHas('bloodSample', function ($query) {
                $query->where('patient_id', Auth::id());
            })
            ->with([
                'doctor.doctorProfile',
                'results',
                'bloodSample.sampleRequest.assignedDoctor',
                'bloodSample.transportations.laboratory',
            ])
            ->latest('published_at')
            ->paginate(10);

        return view('sample-reports.patient_index', compact('reports'));
    }

    public function patientShow(SampleReport $sampleReport): View
    {
        $this->authorizePatient($sampleReport);

        $sampleReport->load([
            'doctor.doctorProfile',
            'results',
            'bloodSample.patient',
            'bloodSample.sampleRequest',
        ]);

        return view('sample-reports.patient_show', compact('sampleReport'));
    }

    public function download(SampleReport $sampleReport)
    {
        $sampleReport->loadMissing('bloodSample.sampleRequest', 'bloodSample.transportations');

        $user = Auth::user();
        $isPatient = $user?->role === 'patient'
            && $sampleReport->status === 'published'
            && (int) $sampleReport->bloodSample?->patient_id === (int) $user->id;
        $isDoctor = $user?->role === 'doctor'
            && (int) $sampleReport->bloodSample?->sampleRequest?->assigned_doctor_id === (int) $user->id;
        $isLaboratoryUser = in_array($user?->role, ['admin', 'lab_staff'], true)
            && $sampleReport->bloodSample?->transportations?->isNotEmpty();

        abort_unless($isPatient || $isDoctor || $isLaboratoryUser, 403);
        abort_unless(
            $sampleReport->attachment_path
            && Storage::disk('local')->exists($sampleReport->attachment_path),
            404
        );

        return Storage::disk('local')->download(
            $sampleReport->attachment_path,
            $sampleReport->attachment_original_name ?: 'laboratory-report.pdf'
        );
    }

    private function authorizeAssignedDoctor(SampleRequest $sampleRequest): void
    {
        abort_unless(
            Auth::check()
            && Auth::user()->role === 'doctor'
            && (int) $sampleRequest->assigned_doctor_id === (int) Auth::id(),
            403
        );

        $sampleRequest->loadMissing('bloodSample');
    }

    private function authorizeLaboratorySample(
        Laboratory $laboratory,
        SampleTransportation $sampleTransportation
    ): void {
        abort_unless(
            Auth::check()
            && in_array(Auth::user()->role, ['admin', 'lab_staff'], true)
            && (int) $sampleTransportation->laboratory_id === (int) $laboratory->id,
            403
        );
    }

    private function authorizePatient(SampleReport $sampleReport): void
    {
        $sampleReport->loadMissing('bloodSample');

        abort_unless(
            Auth::check()
            && Auth::user()->role === 'patient'
            && $sampleReport->status === 'published'
            && (int) $sampleReport->bloodSample?->patient_id === (int) Auth::id(),
            403
        );
    }
}
