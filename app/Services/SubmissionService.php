<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SubmissionService
{
    /**
     * Allowed file extensions by type.
     */
    protected array $allowedExtensions = [
        'pdf' => ['pdf'],
        'image' => ['jpg', 'jpeg', 'png', 'gif'],
        'doc' => ['doc', 'docx'],
        'excel' => ['xls', 'xlsx'],
    ];

    /**
     * Generate unique tracking number.
     */
    public function generateTrackingNumber(): string
    {
        return Submission::generateTrackingNumber();
    }

    /**
     * Validate submission data against form schema.
     */
    public function validateSubmission(Form $form, array $data): array
    {
        $schema = $form->schema;
        $fields = $schema['fields'] ?? [];
        $validatedData = [];
        $errors = [];

        foreach ($fields as $field) {
            $fieldId = $field['id'];
            $value = $data[$fieldId] ?? null;

            // Check required
            if ($field['required'] ?? false) {
                if ($field['type'] === 'file') {
                    // File validation is handled separately
                    continue;
                }
                
                if (empty($value) && $value !== 0 && $value !== '0') {
                    $errors[$fieldId] = ["{$field['label']} wajib diisi."];
                    continue;
                }
            }

            // Skip if empty and not required
            if (empty($value) && $value !== 0 && $value !== '0') {
                continue;
            }

            // Type-specific validation
            $validation = $field['validation'] ?? [];

            switch ($field['type']) {
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$fieldId] = ['Format email tidak valid.'];
                    }
                    break;

                case 'number':
                    if (!is_numeric($value)) {
                        $errors[$fieldId] = ['Harus berupa angka.'];
                    } else {
                        if (isset($validation['min']) && $value < $validation['min']) {
                            $errors[$fieldId] = ["Nilai minimal adalah {$validation['min']}."];
                        }
                        if (isset($validation['max']) && $value > $validation['max']) {
                            $errors[$fieldId] = ["Nilai maksimal adalah {$validation['max']}."];
                        }
                    }
                    break;

                case 'text':
                case 'textarea':
                    if (isset($validation['minLength']) && strlen($value) < $validation['minLength']) {
                        $errors[$fieldId] = ["Minimal {$validation['minLength']} karakter."];
                    }
                    if (isset($validation['maxLength']) && strlen($value) > $validation['maxLength']) {
                        $errors[$fieldId] = ["Maksimal {$validation['maxLength']} karakter."];
                    }
                    break;

                case 'select':
                case 'radio':
                    $options = array_column($field['options'] ?? [], 'value');
                    if (!in_array($value, $options)) {
                        $errors[$fieldId] = ['Pilihan tidak valid.'];
                    }
                    break;
            }

            if (!isset($errors[$fieldId])) {
                $validatedData[$fieldId] = $value;
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $validatedData;
    }

    /**
     * Handle file uploads.
     */
    public function handleFileUploads(Form $form, Request $request): array
    {
        $schema = $form->schema;
        $fields = $schema['fields'] ?? [];
        $uploadedFiles = [];
        $errors = [];

        foreach ($fields as $field) {
            if ($field['type'] !== 'file') {
                continue;
            }

            $fieldId = $field['id'];
            $file = $request->file($fieldId);

            // Check required
            if (($field['required'] ?? false) && !$file) {
                $errors[$fieldId] = ["{$field['label']} wajib diupload."];
                continue;
            }

            if (!$file) {
                continue;
            }

            // Validate file
            $validation = $field['validation'] ?? [];
            
            // Check file size (in KB)
            $maxSize = $validation['maxSize'] ?? 5120; // Default 5MB
            if ($file->getSize() > $maxSize * 1024) {
                $errors[$fieldId] = ["Ukuran file maksimal {$maxSize}KB."];
                continue;
            }

            // Check file type
            $allowedTypes = $validation['allowedTypes'] ?? ['pdf', 'image'];
            $allowedExtensions = [];
            foreach ($allowedTypes as $type) {
                $allowedExtensions = array_merge($allowedExtensions, $this->allowedExtensions[$type] ?? []);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                $errors[$fieldId] = ['Tipe file tidak diizinkan.'];
                continue;
            }

            // Upload file
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs('submissions/' . date('Y/m'), $filename, 'public');

            $uploadedFiles[] = [
                'field_id' => $fieldId,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_at' => now(),
            ];
        }

        if (!empty($errors)) {
            // Clean up uploaded files on error
            foreach ($uploadedFiles as $uploaded) {
                Storage::disk('public')->delete($uploaded['path']);
            }
            throw ValidationException::withMessages($errors);
        }

        return $uploadedFiles;
    }
}
