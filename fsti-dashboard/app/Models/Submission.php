<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Submission extends Model
{
    protected $fillable = [
        'form_id',
        'tracking_number',
        'email',
        'data',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_NEEDS_REVISION = 'needs_revision';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($submission) {
            if (empty($submission->tracking_number)) {
                $submission->tracking_number = static::generateTrackingNumber();
            }
        });
    }

    /**
     * Generate a unique tracking number.
     */
    public static function generateTrackingNumber(): string
    {
        do {
            $number = 'FSTI-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (static::where('tracking_number', $number)->exists());

        return $number;
    }

    /**
     * Get the form that owns the submission.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the files for the submission.
     */
    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    /**
     * Get the history for the submission.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(SubmissionHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_IN_REVIEW => 'Sedang Ditinjau',
            self::STATUS_NEEDS_REVISION => 'Perlu Revisi',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_COMPLETED => 'Selesai',
            default => $this->status,
        };
    }

    /**
     * Scope by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Find by tracking number.
     */
    public static function findByTrackingNumber(string $trackingNumber): ?self
    {
        return static::where('tracking_number', $trackingNumber)->first();
    }
}
