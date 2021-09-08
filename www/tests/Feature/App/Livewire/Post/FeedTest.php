<?php

namespace Tests\Feature\App\Livewire\Post;

use App\Http\Livewire\Post\Feed;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que le laoding de post appelle bien les méthodes
     */
    public function test_loadMore_with_event()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(Post::factory()->count(10))->create();

        $this->actingAs($user);

        Livewire::test(Feed::class, [
            'user' => $user
        ])
            ->Set('perPage', 0)
            ->call('loadMore')
            ->assertSet('perPage', 5)
            ->assertDispatchedBrowserEvent('newPostsLoaded');
    }

    /**
     * Vérifie que la méthode loadMore ne charge pas plus de données, s'il n'y a plus de données
     */
    public function test_loadMore_without_event()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(Post::factory()->count(5))->create();

        $this->actingAs($user);

        Livewire::test(Feed::class, [
            'user' => $user
        ])
            ->Set('perPage', 5)
            ->call('loadPosts')
            ->assertCount('posts', 5)
            ->call('loadMore')
            ->assertCount('posts', 5);
    }

    /**
     * Vérifie que la fonction loadPosts returne bien des éléments
     */
    public function test_loadPost()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(Post::factory()->count(10))->create();

        $this->actingAs($user);

        Livewire::test(Feed::class, [
            'user' => $user
        ])
            ->Set('perPage', 5)
            ->call('loadPosts')
            ->assertCount('posts', 5);
    }
}
