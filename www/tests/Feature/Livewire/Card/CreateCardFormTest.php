<?php

namespace Tests\Feature\Livewire\Card;

use App\Http\Livewire\Card\CreateCardForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateCardFormTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(CreateCardForm::class);

        $component->assertStatus(200);
    }
}
