<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFollowerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $follower;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $follower)
    {
        $this->user = $user;

        $this->follower = $follower;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-follower.mail', [
            'name' => $this->user['name'],
            'url' => route('profiles-slug', ['slug' => $this->follower['slug']]),
            'followerName' => $this->follower['name'],
        ])->subject(__('new-follower-mail.subject'));
    }
}
