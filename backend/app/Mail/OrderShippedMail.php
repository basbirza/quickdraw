<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShippedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $order->load('items');
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Your Order Has Shipped! #' . $this->order->order_number . ' - Quickdraw Pressing Co.')
                    ->view('emails.order-shipped');
    }
}
