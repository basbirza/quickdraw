<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // Load order items for email template
        $order->load('items');
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Confirmation #' . $this->order->order_number . ' - Quickdraw Pressing Co.')
                    ->view('emails.order-confirmation');
    }
}
