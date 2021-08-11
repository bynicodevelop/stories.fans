<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\Feed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FeedTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Feed::class);

        $component->assertStatus(200);
    }
}
