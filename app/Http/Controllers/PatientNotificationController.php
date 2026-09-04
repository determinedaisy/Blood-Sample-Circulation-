<?php

namespace App\Http\Controllers;

use App\Models\SampleReport;
use App\Models\SampleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientNotificationController extends Controller
{
    public function index(): View
    {
        $this->authorizePatient();

        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = Auth::user()
            ->unreadNotifications()
            ->count();

        return view('patient.notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    public function read(DatabaseNotification $notification): RedirectResponse
    {
        $this->authorizePatient();
        $this->authorizeNotification($notification);

        $data = $notification->data;
        $notification->markAsRead();

        $sampleRequestId = $data['sample_request_id'] ?? null;
        $kind = $data['kind'] ?? null;

        if ($kind === 'medical_report_published' && !empty($data['sample_report_id'])) {
            $sampleReport = SampleReport::query()
                ->whereKey($data['sample_report_id'])
                ->where('status', 'published')
                ->whereHas('bloodSample', function ($query) {
                    $query->where('patient_id', Auth::id());
                })
                ->first();

            if ($sampleReport) {
                return redirect()->route(
                    'sample-reports.patient.show',
                    $sampleReport
                );
            }
        }

        if ($kind === 'sample_request_declined') {
            return redirect()->route('sample-requests.patient.index');
        }

        if (
            $sampleRequestId
            && SampleRequest::whereKey($sampleRequestId)
                ->where('patient_id', Auth::id())
                ->exists()
        ) {
            return redirect()->route(
                'sample-requests.tracking',
                $sampleRequestId
            );
        }

        return redirect()->route('patient.notifications.index');
    }

    public function readAll(): RedirectResponse
    {
        $this->authorizePatient();

        Auth::user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    private function authorizePatient(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'patient',
            403
        );
    }

    private function authorizeNotification(DatabaseNotification $notification): void
    {
        abort_unless(
            (int) $notification->notifiable_id === (int) Auth::id()
            && $notification->notifiable_type === get_class(Auth::user()),
            403
        );
    }
}
