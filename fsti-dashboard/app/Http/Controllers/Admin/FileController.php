<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionFile;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Download a submission file.
     */
    public function download(SubmissionFile $file): StreamedResponse
    {
        // Authorization check - user must be authenticated (handled by middleware)
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if file exists
        if (!$this->fileService->exists($file->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $file->path,
            $file->original_name,
            ['Content-Type' => $file->mime_type]
        );
    }

    /**
     * Preview a file (for supported formats).
     */
    public function preview(SubmissionFile $file)
    {
        // Authorization check - user must be authenticated (handled by middleware)
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if file exists
        if (!$this->fileService->exists($file->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if file is previewable
        if (!$this->fileService->isPreviewable($file->mime_type)) {
            abort(400, 'File tidak dapat di-preview. Silakan download file.');
        }

        $path = $this->fileService->getPath($file->path);

        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]);
    }

    /**
     * Delete a submission file.
     */
    public function destroy(SubmissionFile $file)
    {
        // Authorization check - user must be authenticated (handled by middleware)
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        // Delete file from storage
        $this->fileService->delete($file->path);

        // Delete database record
        $file->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }
}
