<?php

namespace App\Services;

use App\Mail\StatusUpdateMail;
use App\Mail\SubmissionConfirmationMail;
use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Maximum retry attempts for sending emails.
     */
    protected int $maxRetries = 3;

    /**
     * Send submission confirmation email.
     */
    public function sendSubmissionConfirmation(Submission $submission): bool
    {
        return $this->sendWithRetry(
            $submission->email,
            new SubmissionConfirmationMail($submission),
            'submission_confirmation',
            $submission->tracking_number
        );
    }

    /**
     * Send status update email.
     */
    public function sendStatusUpdate(Submission $submission, string $oldStatus, ?string $notes = null): bool
    {
        return $this->sendWithRetry(
            $submission->email,
            new StatusUpdateMail($submission, $oldStatus, $notes),
            'status_update',
            $submission->tracking_number
        );
    }

    /**
     * Send email with retry logic.
     */
    protected function sendWithRetry(string $to, $mailable, string $type, string $reference): bool
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            $attempts++;

            try {
                Mail::to($to)->send($mailable);

                Log::info("Email sent successfully", [
                    'type' => $type,
                    'to' => $to,
                    'reference' => $reference,
                    'attempt' => $attempts,
                ]);

                return true;
            } catch (\Exception $e) {
                $lastException = $e;

                Log::warning("Email send attempt failed", [
                    'type' => $type,
                    'to' => $to,
                    'reference' => $reference,
                    'attempt' => $attempts,
                    'error' => $e->getMessage(),
                ]);

                // Wait before retrying (exponential backoff)
                if ($attempts < $this->maxRetries) {
                    sleep(pow(2, $attempts - 1));
                }
            }
        }

        Log::error("Email send permanently failed after {$this->maxRetries} attempts", [
            'type' => $type,
            'to' => $to,
            'reference' => $reference,
            'error' => $lastException?->getMessage(),
        ]);

        return false;
    }

    /**
     * Get the maximum retry attempts.
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * Set the maximum retry attempts.
     */
    public function setMaxRetries(int $maxRetries): self
    {
        $this->maxRetries = $maxRetries;
        return $this;
    }
}
