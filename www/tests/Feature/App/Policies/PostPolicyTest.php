<?php

namespace Tests\Feature\App\Policies;

use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use App\Models\UserSubscription;
use App\Policies\PostPolicy;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    private $postPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postPolicy = new PostPolicy();
    }

    /**
     * Valide le fait que le post soit accessible
     * non premium
     */
    public function test_seePremiumButton_true()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                Post::IS_PREMIUM => false
            ];
        }))->create();

        $result = $this->postPolicy->seePremiumButton($user, $user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Valide le fait que le post ne soit pas accessible
     * premium
     */
    public function test_seePremiumButton_false()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $result = $this->postPolicy->seePremiumButton($user, $user['posts'][0]);

        $this->assertFalse($result);
    }

    /**
     * Valide le fait que le post soit accessible
     * non premium et non connecté
     */
    public function test_seePremiumButton_unautentucated_true()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                Post::IS_PREMIUM => false
            ];
        }))->create();

        $result = $this->postPolicy->seePremiumButton(null, $user['posts'][0]);

        $this->assertTrue($result);
    }


    /**
     * Valide le fait que le post ne soit pas accessible
     * premium et non connecté
     */
    public function test_seePremiumButton_unauthenticated_false()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {

            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $result = $this->postPolicy->seePremiumButton(null, $user['posts'][0]);

        $this->assertFalse($result);
    }

    /**
     * Valide le fait que le post ne soit pas accéssible par un utilisateur non connecté
     * non premium et non connecté
     */
    public function test_seePost_no_premium_unautenticated()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            return [
                Post::IS_PREMIUM => false
            ];
        }))->create();

        $result = $this->postPolicy->seePost(null, $user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Valide le fait que le post ne soit pas accéssible par un utilisateur non connecté
     * non premium et non connecté
     */
    public function test_seePost_no_premium_autenticated()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            return [
                Post::IS_PREMIUM => false
            ];
        }))->create();

        $authenticatedUser = User::factory()->create();

        $result = $this->postPolicy->seePost($authenticatedUser, $user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Valide le fait que le post ne soit pas accéssible par un utilisateur non connecté
     * premium et non connecté
     */
    public function test_seePost_premium_unautenticated()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $result = $this->postPolicy->seePost(null, $user['posts'][0]);

        $this->assertFalse($result);
    }

    /**
     * Valide le fait que le post ne soit pas accéssible par un utilisateur connecté non abonné
     * premium, connecté et non abonné
     */
    public function test_seePost_premium_autenticated()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $authenticatedUser = User::factory()->create();

        $result = $this->postPolicy->seePost($authenticatedUser, $user['posts'][0]);

        $this->assertFalse($result);
    }

    /**
     * Valide le fait que le post soit accéssible par un utilisateur connecté avec un mauvais abonné
     * premium, connecté, abonné et date du plan invalide
     */
    public function test_seePost_premium_autenticated_invalid_plan()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            Plan::factory()->count(1)->create(
                [
                    'user_id' => $user['id']
                ]
            );

            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $authenticatedUser = User::factory()->create();

        $subscriptionData = [
            'user_id' => $authenticatedUser['id'],
            'plan_id' => 1,
            'price_period' => Plan::PRICE_MONTHLY,
            'stripe_id' => "id_stripe",
            'ends_at' =>  now()->subDays(30)->timestamp,
            'trial_ends_at' => null,
        ];

        UserSubscription::create($subscriptionData);

        $result = $this->postPolicy->seePost($authenticatedUser, $user['posts'][0]);

        $this->assertFalse($result);
    }

    /**
     * Valide le fait que le post soit accéssible par un utilisateur connecté et abonné
     * premium, connecté et abonné
     */
    public function test_seePost_premium_autenticated_plan()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            Plan::factory()->count(1)->create(
                [
                    'user_id' => $user['id']
                ]
            );

            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $authenticatedUser = User::factory()->create();

        $subscriptionData = [
            'user_id' => $authenticatedUser['id'],
            'plan_id' => 1,
            'price_period' => Plan::PRICE_MONTHLY,
            'stripe_id' => "id_stripe",
            'ends_at' =>  now()->addDays(30),
            'trial_ends_at' => null,
        ];

        UserSubscription::create($subscriptionData);

        $result = $this->postPolicy->seePost($authenticatedUser, $user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Valide le fait que le post soit accéssible par un utilisateur connecté et dont c'est son post
     * premium, connecté et abonné
     */
    public function test_seePost_is_owner_post()
    {
        $user = User::factory()->has(Post::factory()->count(1)->state(function (array $attributes, User $user) {
            Plan::factory()->count(1)->create(
                [
                    'user_id' => $user['id']
                ]
            );

            return [
                Post::IS_PREMIUM => true
            ];
        }))->create();

        $result = $this->postPolicy->seePost($user, $user['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Verifie que tout le monde de connecté peut créer du contenu
     */
    public function test_create_with_authenticated_user()
    {
        $authenticatedUser = User::factory()->create();

        $result = $this->postPolicy->create($authenticatedUser);

        $this->assertTrue($result);
    }

    /**
     * Vérifie qu'un utilisateur peut supprimer son contenu
     */
    public function test_delete_with_post_owner_allow()
    {
        $owner = User::factory()->has(Post::factory()->count(1))->create();

        $result = $this->postPolicy->delete($owner, $owner['posts'][0]);

        $this->assertTrue($result);
    }

    /**
     * Vérifier qu'un utilisateur ne peut pas supprimer le contenu de quelqu'un d'autre
     */
    public function test_delete_with_post_owner_deny()
    {
        $owner = User::factory()->has(Post::factory()->count(1))->create();

        $user = User::factory()->create();

        $result = $this->postPolicy->delete($user, $owner['posts'][0]);

        $this->assertFalse($result);
    }
}
