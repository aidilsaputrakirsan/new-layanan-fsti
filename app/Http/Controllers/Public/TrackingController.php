<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrackingController extends Controller
{
    /**
     * Display the tracking page.
     */
    public function index(): Response
    {
        return Inertia::render('Public/Tracking');
    }

    /**
     * Show submission by tracking number.
     */
    public function show(Request $request): Response
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $submission = Submission::where('tracking_number', $request->tracking_number)
            ->with([
                'form:id,title,slug',
                'histories' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
            ])
            ->first();

        if (!$submission) {
            return Inertia::render('Public/Tracking', [
                'error' => 'Nomor tracking tidak ditemukan.',
                'searchedNumber' => $request->tracking_number,
            ]);
        }

        return Inertia::render('Public/TrackingResult', [
            'submission' => [
                'tracking_number' => $submission->tracking_number,
                'form_title' => $submission->form->title ?? '-',
                'email' => $this->maskEmail($submission->email),
                'status' => $submission->status,
                'status_label' => $submission->status_label,
                'admin_notes' => $submission->admin_notes,
                'created_at' => $submission->created_at,
                'updated_at' => $submission->updated_at,
                'histories' => $submission->histories->map(function ($history) {
                    return [
                        'status' => $history->status,
                        'status_label' => $history->status_label,
                        'notes' => $history->notes,
                        'created_at' => $history->created_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Mask email for privacy.
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 2) {
            $maskedName = $name[0] . '***';
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', strlen($name) - 2);
        }

        return $maskedName . '@' . $domain;
    }
}
