<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     *
     * @var User
     */
    public $user;

    /**
     *
     * @var User
     */
    public $author;

    /**
     *
     * @var Payment
     */
    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $author, Payment $payment)
    {
        $this->user = $user;

        $this->author = $author;

        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoice.mail', [
            'name' => $this->user['name'],
            'link' => route('profiles-slug', ['slug' => $this->author['slug']]),
            'authorName' => $this->author['name'],
            'url' => $this->payment['hosted_invoice_url'],
        ])
            ->subject(__('invoice-mail.subject', [
                'author' => Str::ucfirst($this->author['name'])
            ]));
    }
}
