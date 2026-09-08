<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Services\BloodAnalyzerService; // Added AI Service Import

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Only admin should access Feature 5.
        // If your project already has admin/role middleware,
        // this check can later be handled by that middleware.
        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Main statistics
        |--------------------------------------------------------------------------
        */

        $totalSamples = BloodSample::count();

        $acceptedSamples = BloodSample::where(
            'status',
            'accepted'
        )->count();

        $rejectedSamples = BloodSample::where(
            'status',
            'rejected'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Rates
        |--------------------------------------------------------------------------
        */

        $acceptanceRate = $totalSamples > 0
            ? round(($acceptedSamples / $totalSamples) * 100, 1)
            : 0;

        $rejectionRate = $totalSamples > 0
            ? round(($rejectedSamples / $totalSamples) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Last 7 days
        |--------------------------------------------------------------------------
        */

        $dailyRaw = BloodSample::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate(
                'created_at',
                '>=',
                now()->subDays(6)->toDateString()
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $dailyLabels = [];
        $dailyValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $dateKey = $date->toDateString();

            $dailyLabels[] = $date->format('D');
            $dailyValues[] = (int) ($dailyRaw[$dateKey] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Recent samples
        |--------------------------------------------------------------------------
        */

        $recentSamples = BloodSample::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSamples',
            'acceptedSamples',
            'rejectedSamples',
            'acceptanceRate',
            'rejectionRate',
            'dailyLabels',
            'dailyValues',
            'recentSamples'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | AI Blood Analysis Feature
    |--------------------------------------------------------------------------
    */
    public function runAiAnalysis(BloodAnalyzerService $analyzer)
    {
        $analyzer->analyzeInventory();
        $analyzer->analyzeDonors();
        
        return redirect()->back()->with('success', 'AI Analysis Complete: Expired units and deferred donors are now blacklisted.');
    }
}