<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;
    public $productName;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($productName, $status)
    {
        $this->productName = $productName;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your product status is now: " . ucfirst($this->status))
            ->view('emails.order_status_updated');
    }
}
