<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $site_name;
    public $login_url;
    public $support_email;
    public $password;
    public $include_password;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password = null)
    {
        $this->user = $user;
        $this->site_name = config('app.name', 'OSMart BD');
        $this->login_url = route('login');
        $this->support_email = config('mail.from.address', 'support@osmartbd.com');
        $this->password = $password;
        $this->include_password = env('INCLUDE_PASSWORD_IN_EMAIL', false) && !empty($password);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Welcome to ' . $this->site_name . ' - Your Account is Ready!';
        if ($this->include_password) {
            $subject = 'Welcome to ' . $this->site_name . ' - Account Created with Login Details';
        }
        
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
            view: 'emails.user-welcome',
            with: [
                'user' => $this->user,
                'site_name' => $this->site_name,
                'login_url' => $this->login_url,
                'support_email' => $this->support_email,
                'username' => $this->user->username,
                'full_name' => trim($this->user->firstname . ' ' . $this->user->lastname) ?: $this->user->username,
                'sponsor_name' => $this->user->sponsor ? 
                    trim($this->user->sponsor->firstname . ' ' . $this->user->sponsor->lastname) : 
                    'Direct Registration',
                'referral_code' => $this->user->referral_code,
                'current_year' => date('Y'),
                'password' => $this->password,
                'include_password' => $this->include_password
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
