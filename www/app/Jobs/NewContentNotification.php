<?php

namespace App\Jobs;

use App\Mail\PostReport;
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

class NewContentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::enableQueryLog();

        $users = User::with(['getFollowers' => function ($query) {
            $query->with(['posts' => function ($subQuery) {
                $subQuery->where('created_at', '>', now()->subDays(1));
            }]);
        }])->get();

        $data = $users->map(function ($user) {
            $param['user'] = $user;

            $posts = $user['getFollowers']->map(function ($follower) {
                return $follower['posts'];
            })->filter(function ($post) {
                return !$post->isEmpty();
            });

            $param['posts'] = $posts;

            return $param;
        });

        foreach ($data as $d) {
            $params = $d['posts']->map(function ($post) {
                return [
                    'user' => $post->first()['user'],
                    'nPost' => $post->count(),
                ];
            })->filter(function ($param) use ($d) {
                return $param['user']['id'] != $d['user']['id'];
            });

            Mail::to($d['user'])->queue((new PostReport($d['user'], $params))->onQueue('email'));
        }
    }
}
