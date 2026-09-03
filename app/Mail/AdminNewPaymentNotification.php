<?php

namespace App\Mail;

use App\Models\Upload;
use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Upload $upload;
    public Unit $unit;
    public string $adminUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
        $this->unit = $upload->unit;
        $this->adminUrl = route('admin.payments.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📢 Bukti Pembayaran Baru – Sail & Hunt Chapter I',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-payment-notification',
            with: [
                'upload' => $this->upload,
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