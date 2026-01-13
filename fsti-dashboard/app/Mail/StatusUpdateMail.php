<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Submission $submission,
        public string $oldStatus,
        public ?string $notes = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Pengajuan - ' . $this->submission->tracking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status-update',
            with: [
                'submission' => $this->submission,
                'form' => $this->submission->form,
                'trackingNumber' => $this->submission->tracking_number,
                'oldStatus' => $this->getStatusLabel($this->oldStatus),
                'newStatus' => $this->submission->status_label,
                'notes' => $this->notes,
                'trackingUrl' => url('/tracking?number=' . $this->submission->tracking_number),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get status label from status value.
     */
    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            Submission::STATUS_PENDING => 'Menunggu',
            Submission::STATUS_IN_REVIEW => 'Sedang Ditinjau',
            Submission::STATUS_NEEDS_REVISION => 'Perlu Revisi',
            Submission::STATUS_APPROVED => 'Disetujui',
            Submission::STATUS_REJECTED => 'Ditolak',
            Submission::STATUS_COMPLETED => 'Selesai',
            default => $status,
        };
    }
}
