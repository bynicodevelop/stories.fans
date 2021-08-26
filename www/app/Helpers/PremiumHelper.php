<?php

namespace App\Helpers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PremiumHelper
{
    public static function protectedContent(Post $post)
    {
        if (!Auth::check()) {
            return true;
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($post['user_id'] == $user['id']) {
            return false;
        }

        return self::isPremium($post);
    }

    public static function isPremium(Post $post)
    {
        if (!Auth::check()) {
            return true;
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        // DB::enableQueryLog();

        $plan = null;

        if ($post['is_premium']) {
            $result = $user->subscriptions()->with(['plan' => function ($query) use ($post) {
                $query->where('user_id', $post['user']['id'])->first();
            }])->where('ends_at', '>', now())->get();

            $plan = $result->filter(function ($r) {
                return !empty($r['plan']);
            })->first();
        }

        // dd(DB::getQueryLog());

        return $post['is_premium'] && $plan == null;
    }
}
