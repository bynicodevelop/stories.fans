<?php

namespace Tests\Unit\App\Traits;

use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use App\Models\UserSubscription;
use App\Traits\PremiumHelper;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PremierHelperTest extends TestCase
{
    use DatabaseMigrations;
    use PremiumHelper;

    /**
     * Valide le fait que le post soit accessible
     * non premium
     */
    public function test_isPremium_premium_true()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                'is_premium' => true
            ];
        }))->create();

        $result = $this->isPremium($user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Valide le fait que le post ne soit pas accessible
     * non premium
     */
    public function test_isPremium_premium_false()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                'is_premium' => false
            ];
        }))->create();

        $result = $this->isPremium($user['posts'][0]);

        $this->assertFalse($result);
    }

    // /**
    //  * Valide qu'il ne soit pas possible d'accéder au contenu
    //  * Premium et non connecté
    //  */
    // public function test_isPremium_premiun_unauthenticated_false()
    // {
    //     $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

    //         return [
    //             'is_premium' => true
    //         ];
    //     }))->create();

    //     $result = $this->isPremium($user['posts'][0]);

    //     $this->assertFalse($result);
    // }

    // /**
    //  * Valide que le contenu est accéssible que si l'utilisateur est connecté
    //  * non premium et connecté
    //  */
    // public function test_isPremium_no_premiun_authenticated_true()
    // {
    //     $this->actingAs(User::factory()->create());

    //     $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

    //         return [
    //             'is_premium' => false
    //         ];
    //     }))->create();

    //     $result = $this->isPremium($user['posts'][0]);

    //     $this->assertTrue($result);
    // }

    // /**
    //  * Valide que le contenu ne soit pas accessible même si l'utilisateur est connecté
    //  * premium et connecté
    //  */
    // public function test_isPremium_premiun_authenticated_false()
    // {
    //     $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

    //         return [
    //             'is_premium' => true
    //         ];
    //     }))->create();

    //     $this->actingAs(User::factory()->create());

    //     $result = $this->isPremium($user['posts'][0]);

    //     $this->assertFalse($result);
    // }

    // /**
    //  * Valide que le contenu soit accessible si l'utilisateur est connecté et a un abonnement
    //  * premium et connecté et plan
    //  */
    // public function test_isPremium_premium_authenticated_plan_true()
    // {
    //     $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
    //         Plan::factory()->count(1)->create(
    //             [
    //                 'user_id' => $user['id']
    //             ]
    //         );

    //         return [
    //             'is_premium' => true
    //         ];
    //     }))->create();

    //     $authenticatedUser = User::factory()->create();

    //     $subscriptionData = [
    //         'user_id' => $authenticatedUser['id'],
    //         'plan_id' => 1,
    //         'price_period' => Plan::PRICE_MONTHLY,
    //         'stripe_id' => "id_stripe",
    //         'ends_at' =>  now()->addDays(30)->timestamp,
    //         'trial_ends_at' => null,
    //     ];

    //     UserSubscription::create($subscriptionData);

    //     $this->actingAs($authenticatedUser);

    //     $result = $this->isPremium($user['posts'][0]);

    //     $this->assertTrue($result);
    // }

    // /**
    //  * Valide que le contenu soit soit pas accessible si l'utilisateur est connecté, a un abonnement dont la date n'est pas valide
    //  * premium et connecté et plan invalide
    //  */
    // public function test_isPremium_premium_authenticated_invalid_plan_true()
    // {
    //     $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
    //         Plan::factory()->count(1)->create(
    //             [
    //                 'user_id' => $user['id']
    //             ]
    //         );

    //         return [
    //             'is_premium' => true
    //         ];
    //     }))->create();

    //     $authenticatedUser = User::factory()->create();

    //     $subscriptionData = [
    //         'user_id' => $authenticatedUser['id'],
    //         'plan_id' => 1,
    //         'price_period' => Plan::PRICE_MONTHLY,
    //         'stripe_id' => "id_stripe",
    //         'ends_at' =>  now()->subDays(30)->timestamp,
    //         'trial_ends_at' => null,
    //     ];

    //     UserSubscription::create($subscriptionData);

    //     $this->actingAs($authenticatedUser);

    //     $result = $this->isPremium($user['posts'][0]);

    //     $this->assertFalse($result);
    // }
}
