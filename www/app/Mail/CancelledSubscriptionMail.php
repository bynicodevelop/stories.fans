<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelledSubscriptionMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subscriber;

    public $author;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $subscriber, User $author)
    {
        $this->subscriber = $subscriber;

        $this->author = $author;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.cancelled-subscription.mail', [
            'name' => $this->subscriber['name'],
            'url' => route('subscriptions'),
            'authorName' => $this->author['name'],
        ])->subject(__('cancelled-subscription-mail.subject'));
    }
}
