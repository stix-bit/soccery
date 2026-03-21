<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public array $items;
    public float $total;

    private string $pdfPath;
    private string $pdfFileName;

    public function __construct(Order $order, array $items, float $total, string $pdfPath, string $pdfFileName)
    {
        $this->order = $order->load(['items.product', 'user']);
        $this->items = $items;
        $this->total = $total;
        $this->pdfPath = $pdfPath;
        $this->pdfFileName = $pdfFileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Soccery Order Receipt'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.receipt'
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as($this->pdfFileName)
                ->withMime('application/pdf'),
        ];
    }
}

