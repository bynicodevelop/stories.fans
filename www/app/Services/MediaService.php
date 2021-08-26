<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MediaService
{
    public function blurred($user, $mediaId)
    {
        $subscription = null;

        $media = Media::where('id', $mediaId)->with(['post' => function ($query) {
            $query->with(['user' => function ($query) {
                $query->with('activePlan');
            }]);
        }])->first();

        if (!is_null($user)) {
            if (Auth::check()) {
                if ($media['post']['user_id'] == Auth::id()) {
                    $subscription = true;
                } else {
                    $plan = $media['post']['user']['plans']->first();

                    if (!is_null($plan)) {
                        $subscription = $user->subscriptions()
                            ->where('ends_at', '>', now())
                            ->where('plan_id', $plan['id'])
                            ->first();
                    }
                }
            }
        }


        return [
            "name_preview" => $media["name_preview"],
            "name" => $media["name"],
            "type" => $media["type"],
            "ext" => $media["ext"],
            "isBlurred" => !empty($media['post']['is_premium']) && is_null($subscription)
        ];
    }
}
