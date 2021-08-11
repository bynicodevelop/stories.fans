<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\Items;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Items::class);

        $component->assertStatus(200);
    }
}
