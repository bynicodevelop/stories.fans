<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ListPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ListPostTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(ListPost::class);

        $component->assertStatus(200);
    }
}
