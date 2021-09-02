<?php

namespace Tests\Feature\App\Actions\Fortify;

use App\Actions\Fortify\CreateNewUser;
use App\Jobs\CreateInvitedHash;
use App\Mail\WelcomeMail;
use App\Models\InvitationLink;
use App\Models\InvitationStat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Créer un utilisateur
     */
    public function test_create_new_user()
    {
        Queue::fake();
        Mail::fake();

        $owner = User::factory()->create();

        $createNewUser = new CreateNewUser();

        $newUser = $createNewUser->create([
            "name" => "bibi",
            "email" => "bibi@domain.tld",
            "password" => "password",
            "password_confirmation" => "password",
            "parent_id" => $owner['id'],
            "invited_id" => $owner['id'],
            "terms" => "on",
        ]);

        $user = User::where('id', $newUser['id'])->first();

        $this->assertEquals($newUser['email'], $user['email']);

        Queue::assertPushed(CreateInvitedHash::class);

        Mail::assertQueued(WelcomeMail::class);
    }

    /**
     * Vérifie qu'un nouveau slug est généré
     */
    public function test_create_new_user_with_unique_slug()
    {
        $owner = User::factory()->create();

        User::factory()->create([
            'slug' => 'bibi'
        ]);

        $createNewUser = new CreateNewUser();

        $newUser = $createNewUser->create([
            "name" => "bibi",
            "email" => "bibi@domain.tld",
            "password" => "password",
            "password_confirmation" => "password",
            "parent_id" => $owner['id'],
            "invited_id" => $owner['id'],
            "terms" => "on",
        ]);

        $user = User::where('id', $newUser['id'])->first();

        $this->assertEquals($user['slug'], 'bibi-1');

        $follower = $user->getFollowers()
            ->where('user_id', $newUser['id'])
            ->where('follow_id', $newUser['id'])
            ->first();

        $this->assertNotNull($follower);
    }

    /**
     * Vérfie que l'utilisateur follow la personne qui l'a invité
     */
    public function test_parrent_follower()
    {
        $owner = User::factory()->create();

        $createNewUser = new CreateNewUser();

        $newUser = $createNewUser->create([
            "name" => "bibi",
            "email" => "bibi@domain.tld",
            "password" => "password",
            "password_confirmation" => "password",
            "parent_id" => $owner['id'],
            "invited_id" => $owner['id'],
            "terms" => "on",
        ]);

        $follower = $newUser->getFollowers()
            ->where('user_id', $newUser['id'])
            ->where('follow_id', $owner['id'])
            ->first();

        $this->assertNotNull($follower);
    }

    /**
     * Vérifie que quand l'utilisateur est invité, celui-ci renseigne bien les stats.
     */
    public function test_invitation_with_stat()
    {
        $owner = User::factory()->has(
            InvitationLink::factory()->has(
                InvitationStat::factory()->count(1)
            )->count(1)
        )->create();

        $createNewUser = new CreateNewUser();

        $newUser = $createNewUser->create([
            "name" => "bibi",
            "email" => "bibi@domain.tld",
            "password" => "password",
            "password_confirmation" => "password",
            "parent_id" => $owner['id'],
            "invited_id" => $owner['id'],
            "terms" => "on",
        ]);

        $invitationStat = InvitationStat::where('invitation_link_id', $owner['invitationLinks'][0]['id'])->first();

        $this->assertEquals($invitationStat['user_id'], $newUser['id']);
    }
}
