<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeReport;
use App\Services\AdministrativeReportService;
use App\Services\AiReportService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdministrativeReportController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $reports = AdministrativeReport::query()
            ->with('generatedBy:id,name')
            ->latest()
            ->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    public function store(
        Request $request,
        AdministrativeReportService $reportService,
        AiReportService $aiReportService
    ): RedirectResponse {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'report_type' => [
                'required',
                Rule::in([
                    'all',
                    'collection',
                    'transportation',
                    'inventory',
                    'laboratory',
                    'bottleneck',
                ]),
            ],
            'start_date' => [
                'required',
                'date',
                'before_or_equal:end_date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                'before_or_equal:today',
            ],
        ]);

        $start = CarbonImmutable::parse($validated['start_date'])
            ->startOfDay();
        $end = CarbonImmutable::parse($validated['end_date'])
            ->endOfDay();

        if ($start->diffInDays($end) > 365) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => 'A report can cover a maximum of 366 days.',
                ]);
        }

        $metrics = $reportService->build(
            $validated['report_type'],
            $start,
            $end
        );

        $aiResult = $aiReportService->generate(
            $validated['report_type'],
            $start,
            $end,
            $metrics
        );

        $report = AdministrativeReport::create([
            'generated_by' => Auth::id(),
            'report_type' => $validated['report_type'],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'metrics' => $metrics,
            'ai_summary' => $aiResult['summary'],
            'ai_generated' => $aiResult['generated'],
            'ai_error' => $aiResult['error'],
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Administrative report generated successfully.');
    }

    public function show(AdministrativeReport $administrativeReport): View
    {
        $this->authorizeAdmin();

        $administrativeReport->load('generatedBy:id,name');

        return view('admin.reports.show', [
            'report' => $administrativeReport,
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403
        );
    }
}
