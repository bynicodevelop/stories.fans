<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view("home", ["user" => Auth::user()]);
        }

        return view("index");
    }
}
