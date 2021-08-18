<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewSubscriberMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     *
     * @var User
     */
    public $user;

    /**
     *
     * @var User
     */
    public $subscriber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $subscriber)
    {
        $this->user = $user;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-subscriber.mail', [
            'name' => $this->user['name'],
            'url' => route('profiles-slug', ['slug' => $this->subscriber['slug']]),
            'subscriberName' => $this->subscriber['name'],
        ])->subject(__('new-subscriber-mail.subject'));
    }
}
