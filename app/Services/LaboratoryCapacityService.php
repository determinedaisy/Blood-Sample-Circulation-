<?php

namespace App\Services;

use App\Models\Laboratory;
use App\Models\SampleTransportation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LaboratoryCapacityService
{
    /**
     * Schedule a sample while holding a database lock on the laboratory.
     * This prevents two simultaneous requests from taking the final slot.
     */
    public function schedule(array $transportationData, string $scheduledDate): SampleTransportation
    {
        return DB::transaction(function () use ($transportationData, $scheduledDate) {
            $laboratory = Laboratory::query()
                ->whereKey($transportationData['laboratory_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$laboratory) {
                throw ValidationException::withMessages([
                    'laboratory_id' => 'The selected laboratory is not active.',
                ]);
            }

            $scheduledCount = SampleTransportation::query()
                ->where('laboratory_id', $laboratory->id)
                ->whereDate('scheduled_test_date', $scheduledDate)
                ->count();

            $capacity = (int) $laboratory->daily_capacity;

            if ($capacity < 1 || $scheduledCount >= $capacity) {
                throw ValidationException::withMessages([
                    'laboratory_id' => "{$laboratory->name} has reached its daily testing capacity for {$scheduledDate}. Select another date or laboratory.",
                ]);
            }

            return SampleTransportation::create([
                ...$transportationData,
                'scheduled_test_date' => $scheduledDate,
            ]);
        });
    }
}