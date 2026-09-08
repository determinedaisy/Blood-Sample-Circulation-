<?php

namespace App\Services;

use App\Models\BloodSample;
use App\Models\User;
use Carbon\Carbon;

class BloodAnalyzerService
{
    public function analyzeInventory()
    {
        $expiredSamples = BloodSample::where('is_blacklisted', false)
            ->where('created_at', '<', Carbon::now()->subDays(42))
            ->get();

        foreach ($expiredSamples as $sample) {
            $sample->update([
                'is_blacklisted' => true,
                'rejection_reason' => 'AI System: Unit Expired'
            ]);
        }
    }

    public function analyzeDonors()
    {
        $donors = User::where('role', 'donor')->where('is_blacklisted', false)->get();
        $deferralKeywords = ['tattoo', 'travel', 'medication', 'surgery', 'antibiotics'];

        foreach ($donors as $donor) {
            $medicalHistory = strtolower($donor->medical_history ?? '');
            
            foreach ($deferralKeywords as $keyword) {
                if (str_contains($medicalHistory, $keyword)) {
                    $donor->update([
                        'is_blacklisted' => true,
                        'deferral_reason' => 'AI System: Detected deferral criteria (' . $keyword . ')'
                    ]);
                    break;
                }
            }
        }
    }
}