<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OwnerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $owner)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun Owner Anda Telah Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.owner-approved',
        );
    }
}
