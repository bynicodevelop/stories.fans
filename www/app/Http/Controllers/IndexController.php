<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function index()
    {
        return view("welcome");
    }

    // public function email()
    // {
    //     /**
    //      * @var User $user
    //      */
    //     $user = Auth::user();

    //     // Mail::to($user)
    //     //     ->queue(new WelcomeMail($user));

    //     // return response()->json([
    //     //     'name' => 'Cool',
    //     // ]);

    //     return new WelcomeMail($user);
    // }
}
