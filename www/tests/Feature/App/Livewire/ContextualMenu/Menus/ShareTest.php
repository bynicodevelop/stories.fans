<?php

namespace Tests\Feature\App\Livewire\ContextualMenu\Menus;

use App\Http\Livewire\Commons\ContextualMenu\Menus\Share;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_share()
    {
        $user = User::factory()->has(
            Post::factory()->count(1)
        )->create();

        $this->actingAs($user);

        Livewire::test(Share::class, [
            'model' => $user['posts'][0]
        ])
            ->call('copied')
            ->assertEmitted('showMessage', [
                "message" => "contextual-menu.copied"
            ])
            ->assertEmitted('closeModal');
    }
}
