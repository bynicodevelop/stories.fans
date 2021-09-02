<?php

namespace Tests\Feature\App\Livewire\Comment;

use App\Http\Livewire\Post\PostCommentMenuContextual;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PostCommentMenuContextualTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifie que le commentaire soit supprimé
     * Et que les commentaires soit refraichi
     */
    public function test_delete_comment()
    {
        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )->count(1)
        )->create();

        $this->actingAs($user);

        Livewire::test(PostCommentMenuContextual::class)
            ->set('comment', $user['posts'][0]['comments'][0])
            ->call('delete')
            ->assertEmitted('$refresh')
            ->assertSet('openModal', false);

        $nComments = Comment::count();

        $this->assertEquals($nComments, 0);
    }
}
