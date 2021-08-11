<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;

class ProfileController extends Controller
{
    public function index($slug)
    {
        try {
            $user = User::where("slug", $slug)->first();

            if (is_null($user)) {
                abort(404);
            }
        } catch (Exception $e) {
            abort(404);
        }

        return view("profile.index", ["user" => $user]);
    }

    public function all()
    {
        return view("profile.all");
    }
}
