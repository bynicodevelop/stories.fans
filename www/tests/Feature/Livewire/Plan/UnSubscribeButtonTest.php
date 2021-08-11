<?php

namespace Tests\Feature\Livewire\Plan;

use App\Http\Livewire\Plan\UnSubscribeButton;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class UnSubscribeButtonTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(UnSubscribeButton::class);

        $component->assertStatus(200);
    }
}
