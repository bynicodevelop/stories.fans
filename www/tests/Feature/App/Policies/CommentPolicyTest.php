<?php

namespace Tests\Feature\App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CommentPolicy
     */
    private $commentPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commentPolicy = new CommentPolicy();
    }

    /**
     * Verifie qu'il soit possible de supprimer un commentaire
     * pour un utilisateur qui l'a écrit
     */
    public function test_delete_current_user()
    {
        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )->count(1)
        )->create();

        $result = $this->commentPolicy->delete($user, $user['posts'][0]['comments'][0]);

        $this->assertTrue($result);
    }

    /**
     * Vérifie qu'il ne soit pas possible de supprimer un commentaire
     * pour un utilisateur lambda
     */
    public function test_delete_lambda_user()
    {
        $user = User::factory()->has(
            Post::factory()->has(
                Comment::factory()->count(1)->state(function (array $attributes, Post $post) {
                    return [
                        'user_id' => $post['user_id']
                    ];
                })
            )->count(1)
        )->create();

        $authenticatedUser = User::factory()->create();

        $result = $this->commentPolicy->delete($authenticatedUser, $user['posts'][0]['comments'][0]);

        $this->assertFalse($result);
    }
}
