<?php

namespace App\Mail;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public Unit $unit;
    public string $resetUrl;

    public function __construct(Unit $unit, string $token)
    {
        $this->unit = $unit;
        $this->resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $unit->email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔑 Reset Password – Sail & Hunt Chapter I',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'unit' => $this->unit,
                'resetUrl' => $this->resetUrl,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}