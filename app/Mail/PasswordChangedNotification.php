<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $changeTime;
    public $ipAddress;
    public $userAgent;
    public $newPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $ipAddress = null, $userAgent = null, $newPassword = null)
    {
        $this->user = $user;
        $this->changeTime = now();
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->newPassword = $newPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->newPassword 
            ? 'Password Changed - New Password Included - ' . config('app.name')
            : 'Password Changed Successfully - ' . config('app.name');
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-changed',
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
