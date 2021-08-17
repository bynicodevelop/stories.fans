<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpyController extends Controller
{
    public function auth($userId)
    {
        Auth::logout();

        Auth::loginUsingId($userId);

        return back();
    }
}
