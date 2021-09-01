<?php

namespace App\Traits;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait PremiumHelper
{
    /**
     * Permet de vérifier si un contenu est protégé
     * Si c'est le cas vérifie si l'utilisateur connecté à un abonnement (plan) valide
     *
     * @param Post $post
     * @return boolean
     */
    public function isPremium(Post $post): bool
    {
        if ($post["is_premium"]) {
            return true;
        }

        return false;
        // if ($post['is_premium'] == true) {
        //     if (Auth::check() == true) {
        //         /**
        //          * @var User $user
        //          */
        //         $user = Auth::user();

        //         DB::enableQueryLog();

        //         /**
        //          * Recherche des souscriptions dont le plan a pour user_id le user_id
        //          * du post a valider
        //          */
        //         $result = $user->subscriptions()->with(['plan' => function ($query) use ($post) {
        //             $query->where('user_id', $post['user']['id'])->first();
        //         }])
        //             ->where('ends_at', '>', now()->timestamp)
        //             ->get()
        //             ->filter(function ($r) {
        //                 return !empty($r['plan']);
        //             })
        //             ->first();

        //         // dd(DB::getQueryLog());

        //         if (!empty($result)) {
        //             return true;
        //         }
        //     }

        //     return false;
        // }

        // return true;
    }
}
