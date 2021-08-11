<?php

namespace App\Http\Controllers;

use App\Models\User;

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

        $plan = $user->activePlan()->first();

        return view('plan.show', compact('plan', 'user'));
    }
}
