<?php

namespace Tests\Feature\App\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class MenuContextualTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Vérifie que l'utilisateur peut supprimer son propre post
     */
    public function test_delete_true()
    {
        Queue::fake();

        $user = User::factory()->has(Post::factory()->count(1))->create();

        $this->actingAs($user);

        Livewire::test('post.menu-contextual', [
            'post' => $user['posts'][0],
            'user' => $user,
        ])

            ->call('delete')
            ->assertEmitted('$refresh')
            ->assertSet('confirmingPostDeletion', false);
    }

    /**
     * Vérifie que l'utilisateur ne peut pas supprimer le post d'un autre utilisateur
     */
    public function test_delete_false()
    {
        $user = User::factory()->has(Post::factory()->count(1))->create();

        $authenticatedUser = User::factory()->create();

        $this->actingAs($authenticatedUser);

        Livewire::test('post.menu-contextual', [
            'post' => $user['posts'][0],
            'user' => $authenticatedUser,
        ])
            ->call('delete')
            ->assertForbidden();
    }
}
