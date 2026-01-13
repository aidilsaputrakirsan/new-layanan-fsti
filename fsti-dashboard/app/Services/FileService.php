<?php

namespace App\Services;

use App\Models\SubmissionFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FileService
{
    /**
     * Allowed file extensions by type.
     */
    protected array $allowedExtensions = [
        'pdf' => ['pdf'],
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'doc' => ['doc', 'docx'],
        'excel' => ['xls', 'xlsx'],
    ];

    /**
     * Allowed MIME types by extension.
     */
    protected array $mimeTypes = [
        'pdf' => ['application/pdf'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
        'doc' => ['application/msword'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'xls' => ['application/vnd.ms-excel'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    ];

    /**
     * Default max file size in KB.
     */
    protected int $defaultMaxSize = 5120; // 5MB

    /**
     * Upload a file with unique filename.
     */
    public function upload(UploadedFile $file, string $directory = 'submissions'): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs($directory . '/' . date('Y/m'), $filename, 'public');

        return [
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }

    /**
     * Validate file type and size.
     */
    public function validate(UploadedFile $file, array $allowedTypes = ['pdf', 'image'], ?int $maxSizeKb = null): bool
    {
        $maxSize = $maxSizeKb ?? $this->defaultMaxSize;
        $errors = [];

        // Check file size
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "Ukuran file maksimal {$maxSize}KB.";
        }

        // Get allowed extensions
        $allowedExtensions = [];
        foreach ($allowedTypes as $type) {
            $allowedExtensions = array_merge($allowedExtensions, $this->allowedExtensions[$type] ?? []);
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            $allowedStr = implode(', ', $allowedExtensions);
            $errors[] = "Tipe file tidak diizinkan. Tipe yang diizinkan: {$allowedStr}.";
        }

        // Check MIME type matches extension
        $mimeType = $file->getMimeType();
        $expectedMimes = $this->mimeTypes[$extension] ?? [];
        if (!empty($expectedMimes) && !in_array($mimeType, $expectedMimes)) {
            $errors[] = "Tipe file tidak valid.";
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['file' => $errors]);
        }

        return true;
    }

    /**
     * Delete a file from storage.
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return true;
    }

    /**
     * Delete multiple files from storage.
     */
    public function deleteMultiple(array $paths): int
    {
        $deleted = 0;
        foreach ($paths as $path) {
            if ($this->delete($path)) {
                $deleted++;
            }
        }
        return $deleted;
    }

    /**
     * Delete all files for a submission.
     */
    public function deleteSubmissionFiles(int $submissionId): int
    {
        $files = SubmissionFile::where('submission_id', $submissionId)->get();
        $deleted = 0;

        foreach ($files as $file) {
            if ($this->delete($file->path)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Check if file exists.
     */
    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get file contents.
     */
    public function get(string $path): ?string
    {
        if ($this->exists($path)) {
            return Storage::disk('public')->get($path);
        }
        return null;
    }

    /**
     * Get file path for download.
     */
    public function getPath(string $path): ?string
    {
        if ($this->exists($path)) {
            return Storage::disk('public')->path($path);
        }
        return null;
    }

    /**
     * Get allowed extensions for given types.
     */
    public function getAllowedExtensions(array $types = ['pdf', 'image']): array
    {
        $extensions = [];
        foreach ($types as $type) {
            $extensions = array_merge($extensions, $this->allowedExtensions[$type] ?? []);
        }
        return array_unique($extensions);
    }

    /**
     * Check if file is previewable (image or PDF).
     */
    public function isPreviewable(string $mimeType): bool
    {
        $previewableMimes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ];

        return in_array($mimeType, $previewableMimes);
    }
}
