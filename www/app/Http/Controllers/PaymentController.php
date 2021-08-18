<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        /**
         * @var User
         */
        $user = Auth::user();

        $payments = Payment::whereHas('userSubscription', function ($query) use ($user) {
            $query->whereHas('plan', function ($query) use ($user) {
                $query->where('user_id', $user['id']);
            });
        })->orderBy('created_at', 'desc')->get();

        $amount = $payments->sum('net_price');
        $payableAmount = $payments->where('created_at', '<', now()->subDays(30))->sum('net_price');
        $profitCurrentPeriod = $payments->where('created_at', '>=', now()->subDays(30))->sum('net_price');

        return view('payments.index', compact('payments', 'amount', 'payableAmount', 'profitCurrentPeriod'));
    }
}
