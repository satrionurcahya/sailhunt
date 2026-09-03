<?php

namespace App\Mail;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Unit $unit;
    public string $adminUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
        $this->adminUrl = route('admin.units.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📢 Unit PMR Baru Mendaftar – Sail & Hunt Chapter I',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-registration-notification',
            with: [
                'unit' => $this->unit,
                'adminUrl' => $this->adminUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}