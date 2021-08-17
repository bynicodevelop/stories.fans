<?php

namespace Tests\Feature;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StripeControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Fee::factory()->create();

        User::factory()->has(Plan::factory()->count(1))->create();

        User::factory()->has(UserSubscription::factory()->count(1)->state([
            "plan_id" => Plan::first()['id'],
            "stripe_id" => "sub_K3W6iYAK6X536t"
        ]), "subscriptions")->create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_create_new_payment()
    {
        $response = $this->post('/api/v1/stripe/webhook', [
            "type" => "invoice.payment_succeeded",
            "data" => [
                "object" => [
                    "subscription" => "sub_K3W6iYAK6X536t"
                ]
            ]
        ]);

        $response->assertStatus(200)->assertJson([
            'created' => true,
        ]);

        $payment = Payment::first();

        $this->assertEquals($payment["plan_id"], "1");
        $this->assertEquals($payment["user_subscription_id"], "1");
        $this->assertEquals($payment["fee_id"], "1");
        $this->assertEquals($payment["net_price"], "700");
    }

    public function test_return_standart_status()
    {
        $response = $this->post('/api/v1/stripe/webhook', [
            "type" => "charge.succeeded",
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $this->assertEquals(Payment::count(), 0);
    }
}
