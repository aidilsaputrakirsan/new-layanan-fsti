<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FormBuilderService
{
    /**
     * Supported field types.
     */
    public const FIELD_TYPES = [
        'text',
        'textarea',
        'email',
        'number',
        'date',
        'select',
        'radio',
        'checkbox',
        'file',
    ];

    /**
     * Validate form schema structure.
     */
    public function validateSchema(array $schema): bool
    {
        if (!isset($schema['fields']) || !is_array($schema['fields'])) {
            throw ValidationException::withMessages([
                'schema' => ['Schema harus memiliki array fields.'],
            ]);
        }

        if (count($schema['fields']) === 0) {
            throw ValidationException::withMessages([
                'schema' => ['Form harus memiliki minimal satu field.'],
            ]);
        }

        foreach ($schema['fields'] as $index => $field) {
            $this->validateField($field, $index);
        }

        return true;
    }

    /**
     * Validate individual field structure.
     */
    protected function validateField(array $field, int $index): void
    {
        $prefix = "schema.fields.{$index}";

        // Required properties
        if (!isset($field['id']) || empty($field['id'])) {
            throw ValidationException::withMessages([
                $prefix => ["Field #{$index} harus memiliki id."],
            ]);
        }

        if (!isset($field['type']) || !in_array($field['type'], self::FIELD_TYPES)) {
            throw ValidationException::withMessages([
                $prefix => ["Field #{$index} memiliki tipe yang tidak valid."],
            ]);
        }

        if (!isset($field['label']) || empty($field['label'])) {
            throw ValidationException::withMessages([
                $prefix => ["Field #{$index} harus memiliki label."],
            ]);
        }

        // Validate options for select/radio fields
        if (in_array($field['type'], ['select', 'radio'])) {
            if (!isset($field['options']) || !is_array($field['options']) || count($field['options']) === 0) {
                throw ValidationException::withMessages([
                    $prefix => ["Field #{$index} (select/radio) harus memiliki options."],
                ]);
            }
        }

        // Validate file field
        if ($field['type'] === 'file') {
            if (isset($field['validation']['maxSize']) && $field['validation']['maxSize'] > 10240) {
                throw ValidationException::withMessages([
                    $prefix => ["Field #{$index} (file) maksimal ukuran 10MB."],
                ]);
            }
        }
    }

    /**
     * Generate unique slug from title.
     */
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Form::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
            
            $query = Form::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Generate a unique field ID.
     */
    public function generateFieldId(): string
    {
        return 'field_' . Str::random(8);
    }

    /**
     * Get default schema structure.
     */
    public function getDefaultSchema(): array
    {
        return [
            'fields' => [],
            'settings' => [
                'submitButtonText' => 'Kirim',
                'successMessage' => 'Pengajuan berhasil dikirim.',
            ],
        ];
    }

    /**
     * Get field type configuration.
     */
    public function getFieldTypeConfig(string $type): array
    {
        $configs = [
            'text' => [
                'label' => 'Text Input',
                'icon' => 'TextOutline',
                'hasPlaceholder' => true,
                'hasValidation' => ['required', 'minLength', 'maxLength', 'pattern'],
            ],
            'textarea' => [
                'label' => 'Text Area',
                'icon' => 'DocumentTextOutline',
                'hasPlaceholder' => true,
                'hasValidation' => ['required', 'minLength', 'maxLength'],
            ],
            'email' => [
                'label' => 'Email',
                'icon' => 'MailOutline',
                'hasPlaceholder' => true,
                'hasValidation' => ['required'],
            ],
            'number' => [
                'label' => 'Number',
                'icon' => 'CalculatorOutline',
                'hasPlaceholder' => true,
                'hasValidation' => ['required', 'min', 'max'],
            ],
            'date' => [
                'label' => 'Date',
                'icon' => 'CalendarOutline',
                'hasPlaceholder' => false,
                'hasValidation' => ['required', 'minDate', 'maxDate'],
            ],
            'select' => [
                'label' => 'Dropdown',
                'icon' => 'ChevronDownOutline',
                'hasPlaceholder' => true,
                'hasOptions' => true,
                'hasValidation' => ['required'],
            ],
            'radio' => [
                'label' => 'Radio Button',
                'icon' => 'RadioButtonOnOutline',
                'hasPlaceholder' => false,
                'hasOptions' => true,
                'hasValidation' => ['required'],
            ],
            'checkbox' => [
                'label' => 'Checkbox',
                'icon' => 'CheckboxOutline',
                'hasPlaceholder' => false,
                'hasValidation' => ['required'],
            ],
            'file' => [
                'label' => 'File Upload',
                'icon' => 'CloudUploadOutline',
                'hasPlaceholder' => false,
                'hasValidation' => ['required', 'maxSize', 'allowedTypes'],
            ],
        ];

        return $configs[$type] ?? [];
    }
}
