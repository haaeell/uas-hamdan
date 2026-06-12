<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OwnerOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $owner,
        public string $otpCode,
        public ?string $verifyUrl = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Verifikasi Email Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.owner-otp',
        );
    }
}
