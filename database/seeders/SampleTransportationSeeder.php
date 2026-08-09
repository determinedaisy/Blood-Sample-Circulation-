<?php

namespace Database\Seeders;

use App\Models\BloodSample;
use App\Models\CollectionCenter;
use App\Models\Laboratory;
use App\Models\SampleTransportation;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleTransportationSeeder extends Seeder
{
    public function run(): void
    {
        // Get blood samples that can reasonably be transported.
        $samples = BloodSample::where('status', 'received')
            ->take(3)
            ->get();

        $centers = CollectionCenter::where('is_active', true)
            ->take(3)
            ->get();

        $laboratories = Laboratory::where('is_active', true)
            ->take(3)
            ->get();

        $collectors = User::where('role', 'sample_collector')
            ->take(3)
            ->get();

        // Stop safely if required data is missing.
        if (
            $samples->count() < 3 ||
            $centers->count() < 3 ||
            $laboratories->count() < 3 ||
            $collectors->count() < 3
        ) {
            $this->command->warn(
                'Not enough samples, centers, laboratories, or collectors to seed transportation data.'
            );

            return;
        }

        // Pending transportation
        SampleTransportation::updateOrCreate(
            [
                'blood_sample_id' => $samples[0]->id,
            ],
            [
                'collection_center_id' => $centers[0]->id,
                'laboratory_id' => $laboratories[0]->id,
                'transported_by' => $collectors[0]->id,
                'status' => 'pending',
                'departure_time' => null,
                'arrival_time' => null,
                'notes' => 'Sample waiting for transportation.',
            ]
        );

        // Sample currently being transported
        SampleTransportation::updateOrCreate(
            [
                'blood_sample_id' => $samples[1]->id,
            ],
            [
                'collection_center_id' => $centers[1]->id,
                'laboratory_id' => $laboratories[1]->id,
                'transported_by' => $collectors[1]->id,
                'status' => 'in_transit',
                'departure_time' => now()->subHour(),
                'arrival_time' => null,
                'notes' => 'Sample is currently being transported to the laboratory.',
            ]
        );

        // Completed transportation
        SampleTransportation::updateOrCreate(
            [
                'blood_sample_id' => $samples[2]->id,
            ],
            [
                'collection_center_id' => $centers[2]->id,
                'laboratory_id' => $laboratories[2]->id,
                'transported_by' => $collectors[2]->id,
                'status' => 'delivered',
                'departure_time' => now()->subHours(3),
                'arrival_time' => now()->subHours(2),
                'notes' => 'Sample delivered successfully to the laboratory.',
            ]
        );

        $this->command->info('Sample transportation data seeded successfully.');
    }
}
