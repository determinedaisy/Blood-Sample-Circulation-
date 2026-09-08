<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Services\BloodAnalyzerService; // Added AI Service Import

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Only admin should access the dashboard.
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
        | Daily sample data
        |--------------------------------------------------------------------------
        | We prepare BOTH 7-day and 30-day datasets.
        | The frontend toggle decides which one to display.
        |--------------------------------------------------------------------------
        */

        /*
        |------------------------------
        | Last 30 days
        |------------------------------
        */

        $dailyRaw30 = BloodSample::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate(
                'created_at',
                '>=',
                now()->subDays(29)->toDateString()
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $dailyLabels30 = [];
        $dailyValues30 = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $dateKey = $date->toDateString();

            $dailyLabels30[] = $date->format('d M');
            $dailyValues30[] = (int) ($dailyRaw30[$dateKey] ?? 0);
        }


        /*
        |------------------------------
        | Last 7 days
        |------------------------------
        */

        $dailyRaw7 = BloodSample::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate(
                'created_at',
                '>=',
                now()->subDays(6)->toDateString()
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $dailyLabels7 = [];
        $dailyValues7 = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $dateKey = $date->toDateString();

            $dailyLabels7[] = $date->format('d M');
            $dailyValues7[] = (int) ($dailyRaw7[$dateKey] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Recent samples
        |--------------------------------------------------------------------------
        */

        $recentSamples = BloodSample::latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Send everything to dashboard
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalSamples',
            'acceptedSamples',
            'rejectedSamples',
            'acceptanceRate',
            'rejectionRate',

            // 7-day graph data
            'dailyLabels7',
            'dailyValues7',

            // 30-day graph data
            'dailyLabels30',
            'dailyValues30',

            // Recent samples
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
