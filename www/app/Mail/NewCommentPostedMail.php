<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentPostedMail extends Mailable
{
    use Queueable, SerializesModels;

    const AUTHOR = "author";

    const TO_OTHER = "to_other";

    /**
     * @var Post
     */
    private $post;

    /**
     * @var User
     */
    private $user;

    /**
     * @var [type]
     */
    private $recipient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user, string $recipient = self::AUTHOR)
    {
        $this->post = $post;

        $this->user = $user;

        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->recipient == self::AUTHOR) {
            return $this->markdown('emails.new-comment-posted.new-comment-author-mail', [
                'name' => $this->user['name'],
                'url' => route('post', ['postId' => $this->post['id']]),
            ])->subject(__('new-comment-author-mail.subject'));
        }

        return $this->markdown('emails.new-comment-posted.new-comment-to-other-mail', [
            'name' => $this->user['name'],
            'url' => route('post', ['postId' => $this->post['id']]),
        ])->subject(__('new-comment-to-other-mail.subject'));
    }
}
