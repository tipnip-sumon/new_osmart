<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfContent;
    public $customMessage;

    public function __construct($order, $pdfContent, $customMessage = null)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $invoiceNumber = 'INV-' . str_pad($this->order->id, 6, '0', STR_PAD_LEFT);
        return new Envelope(
            subject: 'Invoice ' . $invoiceNumber . ' - ' . config('app.name', 'MultiVendor Marketplace'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'order' => $this->order,
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $invoiceNumber = 'INV-' . str_pad($this->order->id, 6, '0', STR_PAD_LEFT);
        return [
            Attachment::fromData(fn () => $this->pdfContent, $invoiceNumber . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
