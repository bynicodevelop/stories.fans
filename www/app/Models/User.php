<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'bio',
        'photo_url',
        'email',
        'password',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'snapchat',
        'twitter',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function activePlan()
    {
        return $this->plans()->where('deleted', false);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function feed(): HasMany
    {
        return $this->hasMany(Follower::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(Follower::class, 'followers', 'user_id', 'follow_id');
    }

    public function getFollowers()
    {
        return $this->hasMany(Follower::class);
    }

    public function isFollowed(User $user): bool
    {
        return $this->hasMany(Follower::class)->where('follow_id', $user["id"])->count() > 0;
    }

    public function follow(User $user): void
    {
        $this->followers()->toggle([$user["id"]]);
    }

    public function card()
    {
        return $this->hasMany(Card::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function hasLikedPost($post)
    {
        return $this->hasMany(Like::class)->where('post_id', $post['id'])->exists();
    }

    public function invitationLinks(): HasMany
    {
        return $this->hasMany(InvitationLink::class);
    }
}
