<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleTransportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_sample_id',
        'collection_center_id',
        'laboratory_id',
        'scheduled_test_date',
        'transported_by',
        'status',
        'departure_time',
        'arrival_time',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
            'scheduled_test_date' => 'date',
        ];
    }

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    public function collectionCenter()
    {
        return $this->belongsTo(CollectionCenter::class)
            ->withDefault(function ($collectionCenter, $transportation): void {
                $homeCollection = $transportation
                    ->bloodSample
                    ?->sampleRequest
                    ?->homeCollection;

                $collectionCenter->name = 'Patient Home';

                if (
                    $homeCollection?->address
                    && $homeCollection?->latitude
                    && $homeCollection?->longitude
                ) {
                    $collectionCenter->address = $homeCollection->address
                        .' · GPS: '
                        .$homeCollection->latitude
                        .', '
                        .$homeCollection->longitude;
                } elseif ($homeCollection?->address) {
                    $collectionCenter->address = $homeCollection->address;
                } elseif ($homeCollection?->latitude && $homeCollection?->longitude) {
                    $collectionCenter->address = 'GPS: '
                        .$homeCollection->latitude
                        .', '
                        .$homeCollection->longitude;
                } else {
                    $collectionCenter->address = 'Home collection location';
                }
            });
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function transporter()
    {
        return $this->belongsTo(User::class, 'transported_by');
    }
}
