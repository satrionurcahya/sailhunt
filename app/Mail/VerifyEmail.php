<?php

namespace App\Mail;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Unit $unit;

    /**
     * Create a new message instance.
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;

        /*
        |--------------------------------------------------------------------------
        | Queue
        |--------------------------------------------------------------------------
        */

        $this->onQueue('emails');

        /*
        |--------------------------------------------------------------------------
        | Setelah database transaction selesai
        |--------------------------------------------------------------------------
        */

        $this->afterCommit();
    }

    /**
     * Queue middleware.
     */
    public function middleware(): array
    {
        return [
            (new RateLimited('email'))
                ->releaseAfter(2),
        ];
    }

    /**
     * Generate signed URL untuk verifikasi.
     */
    protected function generateVerificationUrl(): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $this->unit->id,
                'hash' => sha1($this->unit->email),
            ]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📧 Verifikasi Email – Sail & Hunt Chapter I',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'unit' => $this->unit,
                'verificationUrl' => $this->generateVerificationUrl(),
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
}