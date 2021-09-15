<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostReport extends Mailable
{
    use Queueable, SerializesModels;


    private $user;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;

        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.post-report.mail', [
            'name' => $this->user['name'],
            'data' => $this->data,
        ])->subject(__('post-report-mail.subject'));
    }
}
