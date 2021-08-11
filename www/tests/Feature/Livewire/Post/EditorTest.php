<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\Editor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditorTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Editor::class);

        $component->assertStatus(200);
    }
}
