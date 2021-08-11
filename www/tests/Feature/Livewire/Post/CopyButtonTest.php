<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\CopyButton;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CopyButtonTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(CopyButton::class);

        $component->assertStatus(200);
    }
}
