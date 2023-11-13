<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteViewableMail extends Mailable
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
    public function __construct($src, $invoice, $customer, $client)
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
        return $this->subject("🧾 Quotation ". invoice($this->invoice->inv_no)." from {$this->client->fullname}")
        // ->view('mail.invoice-viewable-mail');
        ->markdown('mail.quotation-viewable-mail-markdown');
    }
}
