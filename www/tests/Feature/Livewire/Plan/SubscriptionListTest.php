<?php

namespace Tests\Feature\Livewire\Plan;

use App\Http\Livewire\Plan\SubscriptionList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionListTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(SubscriptionList::class);

        $component->assertStatus(200);
    }
}
