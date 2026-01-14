<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Cache key prefix.
     */
    protected string $cachePrefix = 'settings_';

    /**
     * Cache TTL in seconds (1 hour).
     */
    protected int $cacheTtl = 3600;

    /**
     * Default settings values.
     */
    protected array $defaults = [
        // Application settings
        'app_name' => 'FSTI Dashboard',
        'app_description' => 'Layanan Administrasi Fakultas Sains dan Teknologi Informasi',
        'contact_email' => null,
        'contact_phone' => null,
        'contact_address' => null,
        
        // Email settings
        'mail_from_name' => 'FSTI Admin',
        'mail_from_address' => 'noreply@fsti.ac.id',
        
        // File upload settings
        'max_file_size' => 5120, // 5MB in KB
        'allowed_file_types' => 'pdf,image,doc,excel',
    ];

    /**
     * Get a setting value with caching.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = $this->cachePrefix . $key;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($key, $default) {
            $value = Setting::getValue($key);
            return $value ?? $this->defaults[$key] ?? $default;
        });
    }

    /**
     * Set a setting value and clear cache.
     */
    public function set(string $key, mixed $value): void
    {
        Setting::setValue($key, $value);
        $this->clearCache($key);
    }

    /**
     * Set multiple settings at once.
     */
    public function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Get multiple settings.
     */
    public function getMultiple(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }
        return $result;
    }

    /**
     * Get all settings grouped by category.
     */
    public function getAllGrouped(): array
    {
        return [
            'application' => [
                'app_name' => $this->get('app_name'),
                'app_description' => $this->get('app_description'),
                'contact_email' => $this->get('contact_email'),
                'contact_phone' => $this->get('contact_phone'),
                'contact_address' => $this->get('contact_address'),
            ],
            'email' => [
                'mail_from_name' => $this->get('mail_from_name'),
                'mail_from_address' => $this->get('mail_from_address'),
            ],
            'file_upload' => [
                'max_file_size' => (int) $this->get('max_file_size'),
                'allowed_file_types' => $this->get('allowed_file_types'),
            ],
        ];
    }

    /**
     * Clear cache for a specific key.
     */
    public function clearCache(string $key): void
    {
        Cache::forget($this->cachePrefix . $key);
    }

    /**
     * Clear all settings cache.
     */
    public function clearAllCache(): void
    {
        foreach (array_keys($this->defaults) as $key) {
            $this->clearCache($key);
        }
    }

    /**
     * Get default value for a key.
     */
    public function getDefault(string $key): mixed
    {
        return $this->defaults[$key] ?? null;
    }

    /**
     * Reset a setting to its default value.
     */
    public function resetToDefault(string $key): void
    {
        $default = $this->getDefault($key);
        if ($default !== null) {
            $this->set($key, $default);
        } else {
            Setting::deleteByKey($key);
            $this->clearCache($key);
        }
    }
}
