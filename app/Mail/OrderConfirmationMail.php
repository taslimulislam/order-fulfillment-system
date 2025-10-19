<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐20

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Order Confirmation')
        ->view('emails.order_confirmation')
        ->with([
            'order' => $this->order,
            'buyer' => $this->order->buyer,
            'items' => $this->order->items,
        ]);
    }
}
