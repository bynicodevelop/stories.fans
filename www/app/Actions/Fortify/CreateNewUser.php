<?php

namespace App\Actions\Fortify;

use App\Jobs\CreateInvitedHash;
use App\Mail\NewFollowerMail;
use App\Mail\WelcomeMail;
use App\Models\InvitationStat;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use App\Traits\CreateUniqueSlug;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use CreateUniqueSlug;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
            'parent_id' => ['required'],
        ])->validate();

        $slug = $this->getUniqueSlug(Str::slug($input['name']));

        /**
         * @var User $user
         */
        $user = User::create([
            'name' => $input['name'],
            'slug' => $slug,
            'email' => $input['email'],
            'parent_id' => $input['parent_id'],
            'password' => Hash::make($input['password']),
        ]);

        $parentUser = User::where('id', $input['parent_id'])->first();

        $user->follow($parentUser);

        $user->follow($user);

        if (!empty($input['invited_id'])) {
            InvitationStat::where('id', $input['invited_id'])->update([
                'user_id' => $user['id']
            ]);
        }

        CreateInvitedHash::dispatch($user);

        Mail::to($user)
            ->queue((new WelcomeMail($user))->onQueue('email'));

        Mail::to($parentUser)
            ->queue((new NewFollowerMail($parentUser, $user))->onQueue('email'));

        return $user;
    }
}
