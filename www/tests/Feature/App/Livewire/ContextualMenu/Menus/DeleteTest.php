<?php

namespace Tests\Feature\App\Livewire\ContextualMenu\Menus;

use App\Http\Livewire\Commons\ContextualMenu\Menus\Delete;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
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

        Livewire::test(Delete::class)
            ->set('model', $user['posts'][0]['comments'][0])
            ->call('delete')
            ->assertEmitted('closeModal');

        $nComments = Comment::count();

        $this->assertEquals($nComments, 0);
    }
}
