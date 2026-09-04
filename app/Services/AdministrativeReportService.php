<?php

namespace App\Services;

use App\Models\BloodSample;
use App\Models\HomeCollectionRequest;
use App\Models\Inventory;
use App\Models\Laboratory;
use App\Models\SampleRequest;
use App\Models\SampleTransportation;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class AdministrativeReportService
{
    private const APPROVED_COLLECTION_HOURS = 24;

    private const IN_TRANSIT_HOURS = 2;

    private const DELIVERED_REVIEW_HOURS = 24;

    public function build(
        string $reportType,
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $metrics = [
            'period' => [
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'days' => (int) $start->diffInDays($end) + 1,
            ],
        ];

        if (in_array($reportType, ['all', 'collection'], true)) {
            $metrics['collection'] = $this->collectionMetrics($start, $end);
        }

        if (in_array($reportType, ['all', 'transportation'], true)) {
            $metrics['transportation'] = $this->transportationMetrics($start, $end);
        }

        if (in_array($reportType, ['all', 'inventory'], true)) {
            $metrics['inventory'] = $this->inventoryMetrics($start, $end);
        }

        if (in_array($reportType, ['all', 'laboratory'], true)) {
            $metrics['laboratory'] = $this->laboratoryMetrics($start, $end);
        }

        if (in_array($reportType, ['all', 'bottleneck'], true)) {
            $metrics['bottleneck'] = $this->bottleneckMetrics($start, $end);
        }

        return $metrics;
    }

    private function bottleneckMetrics(
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $approvedAwaitingCollection = SampleRequest::query()
            ->where('status', 'approved')
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$start, $end])
            ->where('approved_at', '<=', now()->subHours(self::APPROVED_COLLECTION_HOURS))
            ->where(function ($query) {
                $query->whereDoesntHave('bloodSample')
                    ->orWhereHas('bloodSample', fn ($sampleQuery) =>
                        $sampleQuery->whereNull('collected_at')
                    );
            })
            ->count();

        $collectedAwaitingTransportation = BloodSample::query()
            ->whereNotNull('collected_at')
            ->whereBetween('collected_at', [$start, $end])
            ->whereDoesntHave('transportations')
            ->count();

        $transportOverdue = SampleTransportation::query()
            ->where('status', 'in_transit')
            ->whereNotNull('departure_time')
            ->whereBetween('departure_time', [$start, $end])
            ->where('departure_time', '<=', now()->subHours(self::IN_TRANSIT_HOURS))
            ->count();

        $deliveredAwaitingReview = SampleTransportation::query()
            ->where('status', 'delivered')
            ->whereNotNull('arrival_time')
            ->whereBetween('arrival_time', [$start, $end])
            ->where('arrival_time', '<=', now()->subHours(self::DELIVERED_REVIEW_HOURS))
            ->whereHas('bloodSample', fn ($query) =>
                $query->whereNotIn('status', ['accepted', 'rejected'])
            )
            ->count();

        $acceptedAwaitingInventory = BloodSample::query()
            ->where('status', 'accepted')
            ->whereNotNull('reviewed_at')
            ->whereBetween('reviewed_at', [$start, $end])
            ->whereNotIn(
                'id',
                Inventory::query()->select('blood_sample_id')
            )
            ->count();

        $duplicateInventorySamples = Inventory::query()
            ->select('blood_sample_id')
            ->selectRaw('COUNT(*) as record_count')
            ->groupBy('blood_sample_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $incompleteTransportationRecords = SampleTransportation::query()
            ->whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->where(function ($missingDeparture) {
                    $missingDeparture
                        ->whereIn('status', ['in_transit', 'delivered'])
                        ->whereNull('departure_time');
                })->orWhere(function ($missingArrival) {
                    $missingArrival
                        ->where('status', 'delivered')
                        ->whereNull('arrival_time');
                });
            })
            ->count();

        $overdueHomeCollections = HomeCollectionRequest::query()
            ->whereBetween('preferred_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->whereDate('preferred_date', '<', now()->toDateString())
            ->whereNotIn('status', ['collected'])
            ->count();

        $overbookedLaboratoryDays = $this->overbookedLaboratoryDays($start, $end);

        $flags = [
            'approved_awaiting_collection' => $approvedAwaitingCollection,
            'collected_awaiting_transportation' => $collectedAwaitingTransportation,
            'transport_overdue' => $transportOverdue,
            'delivered_awaiting_review' => $deliveredAwaitingReview,
            'accepted_awaiting_inventory' => $acceptedAwaitingInventory,
            'duplicate_inventory_samples' => $duplicateInventorySamples,
            'incomplete_transportation_records' => $incompleteTransportationRecords,
            'overdue_home_collections' => $overdueHomeCollections,
            'overbooked_laboratory_days' => $overbookedLaboratoryDays,
        ];

        $totalFlags = array_sum($flags);

        return [
            'risk_level' => match (true) {
                $totalFlags >= 10 => 'high',
                $totalFlags >= 4 => 'medium',
                $totalFlags >= 1 => 'low',
                default => 'clear',
            },
            'total_flags' => $totalFlags,
            'thresholds' => [
                'approved_to_collection_hours' => self::APPROVED_COLLECTION_HOURS,
                'maximum_in_transit_hours' => self::IN_TRANSIT_HOURS,
                'delivered_to_review_hours' => self::DELIVERED_REVIEW_HOURS,
            ],
            'flags' => $flags,
        ];
    }

    private function overbookedLaboratoryDays(
        CarbonInterface $start,
        CarbonInterface $end
    ): int {
        $laboratories = Laboratory::query()
            ->get()
            ->keyBy('id');

        return SampleTransportation::query()
            ->whereNotNull('scheduled_test_date')
            ->whereBetween('scheduled_test_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get(['laboratory_id', 'scheduled_test_date'])
            ->groupBy(fn ($transportation) =>
                $transportation->laboratory_id.'|'.$transportation->scheduled_test_date->toDateString()
            )
            ->filter(function ($scheduledForDay) use ($laboratories) {
                $laboratoryId = $scheduledForDay->first()->laboratory_id;
                $laboratory = $laboratories->get($laboratoryId);

                return $laboratory
                    && $scheduledForDay->count() > (int) $laboratory->daily_capacity;
            })
            ->count();
    }

    private function collectionMetrics(
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $requestQuery = SampleRequest::query()
            ->whereBetween('created_at', [$start, $end]);

        $sampleQuery = BloodSample::query()
            ->whereBetween('created_at', [$start, $end]);

        $homeQuery = HomeCollectionRequest::query()
            ->whereBetween('created_at', [$start, $end]);

        return [
            'requests_total' => (clone $requestQuery)->count(),
            'requests_by_status' => $this->statusCounts(clone $requestQuery),
            'samples_created' => (clone $sampleQuery)->count(),
            'samples_by_status' => $this->statusCounts(clone $sampleQuery),
            'samples_collected' => BloodSample::query()
                ->whereNotNull('collected_at')
                ->whereBetween('collected_at', [$start, $end])
                ->count(),
            'samples_reviewed' => BloodSample::query()
                ->whereNotNull('reviewed_at')
                ->whereBetween('reviewed_at', [$start, $end])
                ->count(),
            'home_collection_requests' => (clone $homeQuery)->count(),
            'home_collections_by_status' => $this->statusCounts(clone $homeQuery),
        ];
    }

    private function transportationMetrics(
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $transportations = SampleTransportation::query()
            ->with('laboratory:id,name')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $durations = $transportations
            ->filter(fn ($item) => $item->departure_time && $item->arrival_time)
            ->map(fn ($item) => $item->departure_time->diffInMinutes(
                $item->arrival_time,
                false
            ))
            ->filter(fn ($minutes) => $minutes >= 0);

        return [
            'records_total' => $transportations->count(),
            'by_status' => $transportations
                ->groupBy('status')
                ->map(fn ($items) => $items->count())
                ->sortKeys()
                ->all(),
            'delivered_count' => $transportations
                ->where('status', 'delivered')
                ->count(),
            'average_transit_minutes' => $durations->isNotEmpty()
                ? round((float) $durations->average(), 1)
                : null,
            'origin_breakdown' => [
                'collection_center' => $transportations
                    ->whereNotNull('collection_center_id')
                    ->count(),
                'patient_home' => $transportations
                    ->whereNull('collection_center_id')
                    ->count(),
            ],
            'by_laboratory' => $transportations
                ->groupBy(fn ($item) => $item->laboratory?->name ?? 'Unknown laboratory')
                ->map(fn ($items) => $items->count())
                ->sortDesc()
                ->all(),
        ];
    }

    private function inventoryMetrics(
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $currentInventory = Inventory::query()->get();

        return [
            'current_stored_samples' => $currentInventory->count(),
            'added_during_period' => Inventory::query()
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'accepted_not_stored' => BloodSample::query()
                ->where('status', 'accepted')
                ->whereNotIn(
                    'id',
                    Inventory::query()->select('blood_sample_id')
                )
                ->count(),
            'by_storage_location' => $currentInventory
                ->groupBy('storage_location')
                ->map(fn ($items) => $items->count())
                ->sortDesc()
                ->all(),
            'by_refrigerator' => $currentInventory
                ->groupBy('refrigerator')
                ->map(fn ($items) => $items->count())
                ->sortDesc()
                ->all(),
        ];
    }

    private function laboratoryMetrics(
        CarbonInterface $start,
        CarbonInterface $end
    ): array {
        $laboratories = Laboratory::query()
            ->orderBy('name')
            ->get();

        $scheduled = SampleTransportation::query()
            ->whereBetween('scheduled_test_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get();

        $days = (int) $start->diffInDays($end) + 1;
        $activeLaboratories = $laboratories->where('is_active', true);
        $totalCapacity = (int) $activeLaboratories
            ->sum(fn ($laboratory) => $laboratory->daily_capacity * $days);
        $scheduledCount = $scheduled->count();

        $byLaboratory = $laboratories->map(function ($laboratory) use ($scheduled, $days) {
            $scheduledForLab = $scheduled
                ->where('laboratory_id', $laboratory->id)
                ->count();
            $periodCapacity = $laboratory->is_active
                ? (int) $laboratory->daily_capacity * $days
                : 0;

            return [
                'name' => $laboratory->name,
                'active' => (bool) $laboratory->is_active,
                'daily_capacity' => (int) $laboratory->daily_capacity,
                'period_capacity' => $periodCapacity,
                'scheduled_tests' => $scheduledForLab,
                'available_slots' => max($periodCapacity - $scheduledForLab, 0),
                'utilization_percent' => $periodCapacity > 0
                    ? round(($scheduledForLab / $periodCapacity) * 100, 1)
                    : 0,
            ];
        })->values()->all();

        return [
            'laboratories_total' => $laboratories->count(),
            'active_laboratories' => $activeLaboratories->count(),
            'total_period_capacity' => $totalCapacity,
            'scheduled_tests' => $scheduledCount,
            'available_slots' => max($totalCapacity - $scheduledCount, 0),
            'utilization_percent' => $totalCapacity > 0
                ? round(($scheduledCount / $totalCapacity) * 100, 1)
                : 0,
            'by_laboratory' => $byLaboratory,
        ];
    }

    private function statusCounts(Builder $query): array
    {
        return $query
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->pluck('total', 'status')
            ->map(fn ($total) => (int) $total)
            ->all();
    }
}
