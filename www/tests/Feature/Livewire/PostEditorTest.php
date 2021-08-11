<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PostEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PostEditorTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(PostEditor::class);

        $component->assertStatus(200);
    }
}
