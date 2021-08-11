<?php

namespace Tests\Feature\Livewire\Card;

use App\Http\Livewire\Card\Items;
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
