<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\MenuContextual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class MenuContextualTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(MenuContextual::class);

        $component->assertStatus(200);
    }
}
