<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load(['items.product', 'user']);
    }

    public function build(): self
    {
        return $this->subject('Your Soccery Order Receipt')
            ->view('emails.orders.receipt');
    }
}

