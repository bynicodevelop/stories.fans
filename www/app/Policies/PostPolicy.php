<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        return $user['id'] == $post['user_id'];
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can see premium button.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function seePremiumButton(?User $user, Post $post)
    {
        if ($post[Post::IS_PREMIUM] == 1) {
            return false;
        }

        return true;
    }

    public function seePost(?User $user, Post $post)
    {
        if ($post[Post::IS_PREMIUM] == 0) {
            return true;
        }

        if (is_null($user) && $post[Post::IS_PREMIUM] == 1) {
            return false;
        }

        if ($post['user_id'] == $user['id']) {
            return true;
        }

        $result = $user->subscriptions()->with(['plan' => function ($query) use ($post) {
            $query->where('user_id', $post['user']['id'])->first();
        }])
            ->whereDate('ends_at', '>', now())
            ->get()
            ->filter(function ($r) {
                return !empty($r['plan']);
            })
            ->first();

        if (!empty($result)) {
            return true;
        }

        return false;
    }
}
