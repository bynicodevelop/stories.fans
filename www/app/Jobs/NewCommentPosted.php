<?php

namespace App\Jobs;

use App\Mail\NewCommentPostedMail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewCommentPosted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var Post
     */
    private $post;

    /**
     *
     * @var User
     */
    private $authorComment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authorPost = $this->post->user;

        $comments = $this->post->comments()->select(['id', 'user_id'])->where('user_id', '!=', $authorPost['id'])->with('user:id,email,name')->distinct('user_id')->get();

        $comments->each(function ($comment) {
            Mail::to($comment['user'])->queue((new NewCommentPostedMail($this->post, $comment['user'], NewCommentPostedMail::TO_OTHER))->onQueue("email"));
        });

        Mail::to($authorPost)->queue((new NewCommentPostedMail($this->post, $authorPost, NewCommentPostedMail::AUTHOR))->onQueue("email"));
    }
}
