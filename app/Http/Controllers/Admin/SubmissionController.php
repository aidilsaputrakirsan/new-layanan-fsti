<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubmissionController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the submissions.
     */
    public function index(Request $request): Response
    {
        $query = Submission::query()
            ->with(['form:id,title,slug', 'form.category:id,name'])
            ->withCount('files');

        // Filter by form
        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by tracking number or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();
        $forms = Form::select('id', 'title')->get();

        return Inertia::render('Admin/Submissions/Index', [
            'submissions' => $submissions,
            'forms' => $forms,
            'filters' => $request->only(['form_id', 'status', 'date_from', 'date_to', 'search']),
        ]);
    }

    /**
     * Display the specified submission.
     */
    public function show(Submission $submission): Response
    {
        $submission->load([
            'form:id,title,slug,schema',
            'form.category:id,name',
            'files',
            'histories.changedBy:id,name',
        ]);

        return Inertia::render('Admin/Submissions/Show', [
            'submission' => $submission,
        ]);
    }

    /**
     * Update the status of the submission.
     */
    public function updateStatus(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_review,needs_revision,approved,rejected,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $submission->status;
        
        $submission->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['notes'],
        ]);

        // Create history record
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'changed_by' => auth()->id(),
            'created_at' => now(),
        ]);

        // Send status update email if status changed
        if ($oldStatus !== $validated['status']) {
            $this->notificationService->sendStatusUpdate(
                $submission,
                $oldStatus,
                $validated['notes']
            );
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Export submissions to CSV.
     */
    public function export(Request $request)
    {
        $query = Submission::query()->with('form:id,title');

        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $submissions = $query->get();

        $filename = 'submissions_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Tracking Number', 'Form', 'Email', 'Status', 'Tanggal']);
            
            // Data
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->tracking_number,
                    $submission->form->title ?? '-',
                    $submission->email,
                    $submission->status_label,
                    $submission->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
