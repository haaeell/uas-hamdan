<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OwnerStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $owner,
        public string $subjectLine,
        public string $headline,
        public string $statusLabel,
        public string $messageLine,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.owner-status',
        );
    }
}
