<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    protected SubmissionService $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    /**
     * Display the public form.
     */
    public function show(string $slug): Response
    {
        $form = Form::where('slug', $slug)
            ->with('category')
            ->first();

        if (!$form) {
            abort(404, 'Form tidak ditemukan.');
        }

        if (!$form->is_active) {
            return Inertia::render('Public/FormUnavailable', [
                'message' => 'Form ini sedang tidak tersedia.',
            ]);
        }

        return Inertia::render('Public/FormView', [
            'form' => $form,
        ]);
    }

    /**
     * Process form submission.
     */
    public function submit(Request $request, Form $form)
    {
        if (!$form->is_active) {
            return back()->with('error', 'Form ini sedang tidak tersedia.');
        }

        // Validate email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Validate form data based on schema
        $validatedData = $this->submissionService->validateSubmission($form, $request->all());

        // Handle file uploads
        $files = $this->submissionService->handleFileUploads($form, $request);

        // Create submission
        $submission = Submission::create([
            'form_id' => $form->id,
            'email' => $request->email,
            'data' => $validatedData,
        ]);

        // Save file records
        foreach ($files as $file) {
            $submission->files()->create($file);
        }

        return Inertia::render('Public/FormSuccess', [
            'trackingNumber' => $submission->tracking_number,
            'email' => $submission->email,
        ]);
    }
}
