<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecurringViewableMail extends Mailable /* implements ShouldQueue */
{
    use Queueable, SerializesModels;

    public $invoice;
    public $customer;
    public $client;
    public $src;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $customer, $invoice, $src = 'service')
    {
        $this->invoice  = $invoice;
        $this->customer = $customer;
        $this->client   = $client;
        $this->src      = $src;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("🧾 Recurring Sales ". invoice($this->invoice)." from {$this->client->fullname}")
        // ->view('mail.invoice-viewable-mail');
        ->markdown('mail.recurring-viewable-mail-markdown');
    }
}
