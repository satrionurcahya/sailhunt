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

class RegistrationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Unit $unit;
    public string $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
        $this->loginUrl = route('login');

        /*
        |--------------------------------------------------------------------------
        | Queue
        |--------------------------------------------------------------------------
        */
        $this->onQueue('emails');

        /*
        |--------------------------------------------------------------------------
        | After Commit
        |--------------------------------------------------------------------------
        |
        | Email diproses setelah transaksi database selesai.
        |
        */
        $this->afterCommit();
    }

    /**
     * Queue middleware.
     *
     * Menggunakan rate limiter "email"
     * yang didefinisikan di AppServiceProvider.
     */
    public function middleware(): array
    {
        return [
            (new RateLimited('email'))
                ->releaseAfter(2),
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pendaftaran Unit PMR – Sail & Hunt Chapter I',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-confirmation',
            with: [
                'unit' => $this->unit,
                'loginUrl' => $this->loginUrl,
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