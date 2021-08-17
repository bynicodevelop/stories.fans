<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;

class PlanController extends Controller
{
    public function index()
    {
        $options = [];

        return view('plan.index', compact('options'));
    }

    public function show($userId)
    {
        /**
         * @var User $user
         */
        $user = User::find($userId);

        try {
            $plan = $user->activePlan()->first();

            if (is_null($plan)) {
                abort(404);
            }
        } catch (Exception $e) {
            abort(404);
        }

        return view('plan.show', compact('plan', 'user'));
    }
}
