<?php

namespace Tests\Feature\App\Jobs;

use App\Events\RefreshCommentsEvent;
use App\Events\RefreshPostsEvent;
use App\Jobs\DeleteContent;
use App\Jobs\DeleteMedia;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DeleteContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_comment()
    {
        Queue::fake();
        Event::fake();

        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )->count(1)
        )->create();

        $deleteContent = new DeleteContent($user['posts'][0]['comments'][0]);

        $deleteContent->handle();

        $comment = $user['posts'][0]['comments'][0];

        Queue::assertNotPushed(DeleteMedia::class);
        Event::assertNotDispatched(RefreshPostsEvent::class);

        Event::assertDispatched(RefreshCommentsEvent::class, function ($event) use ($comment) {
            return $event->comment["id"] === $comment["id"];
        });
    }

    public function test_delete_post_without_media()
    {
        Queue::fake();
        Event::fake();

        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )->count(1)
        )->create();

        $deleteContent = new DeleteContent($user['posts'][0]);

        $deleteContent->handle();

        $post = $user['posts'][0];

        Queue::assertNotPushed(DeleteMedia::class);
        Event::assertNotDispatched(RefreshCommentsEvent::class);

        Event::assertDispatched(RefreshPostsEvent::class, function ($event) use ($post) {
            return $event->post["id"] === $post["id"];
        });
    }

    public function test_delete_post_with_media()
    {
        Queue::fake();
        Event::fake();

        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )
                ->hasMedia(1)
                ->count(1)
        )->create();

        $deleteContent = new DeleteContent($user['posts'][0]);

        $deleteContent->handle();

        $post = $user['posts'][0];

        Event::assertNotDispatched(RefreshCommentsEvent::class);

        Queue::assertPushedOn('media', DeleteMedia::class);
        Event::assertDispatched(RefreshPostsEvent::class, function ($event) use ($post) {
            return $event->post["id"] === $post["id"];
        });
    }
}
