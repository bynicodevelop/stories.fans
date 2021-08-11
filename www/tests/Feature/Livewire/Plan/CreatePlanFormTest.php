<?php

namespace Tests\Feature\Livewire\Plan;

use App\Http\Livewire\Plan\CreatePlanForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePlanFormTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(CreatePlanForm::class);

        $component->assertStatus(200);
    }
}
