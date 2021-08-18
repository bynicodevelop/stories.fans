<?php

namespace Tests\Feature;

use App\Mail\InvoiceMail;
use App\Mail\NewSubscriberMail;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StripeControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Fee::factory()->create();

        User::factory()->has(Plan::factory()->count(1))->create([
            "email" => "jane@domain.tld"
        ]);

        User::factory()->has(UserSubscription::factory()->count(1)->state([
            "plan_id" => Plan::first()['id'],
            "stripe_id" => "sub_K3W6iYAK6X536t"
        ]), "subscriptions")->create([
            "email" => "john.doe@domain.tld"
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_create_new_payment()
    {
        Mail::fake();

        $response = $this->post('/api/v1/stripe/webhook', [
            "type" => "invoice.payment_succeeded",
            "data" => [
                "object" => [
                    "subscription" => "sub_K3W6iYAK6X536t",
                    "hosted_invoice_url" => "url"
                ]
            ]
        ]);

        $response->assertStatus(200)->assertJson([
            'created' => true,
        ]);

        Mail::assertQueued(function (InvoiceMail $mail) {
            return $mail->user->id == "2"
                && $mail->author->id == "1"
                && $mail->payment->id == "1"
                && $mail->hasTo("john.doe@domain.tld");
        });

        Mail::assertQueued(function (NewSubscriberMail $mail) {
            return $mail->user->id == "1"
                && $mail->subscriber->id == "2"
                && $mail->hasTo("jane@domain.tld");
        });

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
