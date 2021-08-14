<?php

namespace App\Http\Middleware;

use App\Jobs\CreateInvitedHash;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitedHash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $result = $user->invitationLinks()->count();

        // TODO: Peut être que ce n'est pas nécessaire car ce hash est créé dans un job à la création du compte
        if ($result == 0) {
            $this->user->invitationLinks()->create([
                'hash' => Str::random(6)
            ]);
        }

        return $next($request);
    }
}
