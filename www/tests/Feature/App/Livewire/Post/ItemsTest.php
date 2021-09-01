<?php

namespace Tests\Feature\App\Livewire\Post;

use App\Http\Livewire\Post\Items;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Vérifie que le laoding de post appelle bien les méthodes
     */
    public function test_loadMore()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(Items::class)
            ->Set('perPage', 0)
            ->call('loadMore')
            ->assertSet('perPage', 5)
            ->assertDispatchedBrowserEvent('newPostsLoaded');
    }
}
