<?php

namespace App\Mail;

use App\Models\MiniVendor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MiniVendorAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $affiliate,
        public User $mainVendor,
        public MiniVendor $miniVendor
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! You\'ve Been Assigned as a Mini Vendor',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.mini-vendor.assigned',
            with: [
                'affiliate' => $this->affiliate,
                'mainVendor' => $this->mainVendor,
                'miniVendor' => $this->miniVendor,
                'commissionRate' => $this->miniVendor->commission_rate,
                'dashboardUrl' => route('vendor.dashboard')
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
