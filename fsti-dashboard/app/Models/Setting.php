<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get multiple settings by keys.
     */
    public static function getValues(array $keys): array
    {
        $settings = static::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        
        // Fill missing keys with null
        foreach ($keys as $key) {
            if (!isset($settings[$key])) {
                $settings[$key] = null;
            }
        }
        
        return $settings;
    }

    /**
     * Set multiple settings at once.
     */
    public static function setValues(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::setValue($key, $value);
        }
    }

    /**
     * Delete a setting by key.
     */
    public static function deleteByKey(string $key): bool
    {
        return static::where('key', $key)->delete() > 0;
    }

    /**
     * Get all settings as key-value array.
     */
    public static function getAllAsArray(): array
    {
        return static::pluck('value', 'key')->toArray();
    }
}
