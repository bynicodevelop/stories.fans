<?php

namespace Tests\Feature\Livewire\Profiles;

use App\Http\Livewire\Profiles\CountLike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CountLikeTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(CountLike::class);

        $component->assertStatus(200);
    }
}
