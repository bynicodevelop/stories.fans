<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PriceHelper;
use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Fee;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::debug($data);

        if ($data["type"] != "invoice.payment_succeeded") {
            return response()->json([
                "status" => true
            ]);
        }

        $fee = Fee::latestFee();

        $userSubscription = UserSubscription::where("stripe_id", $data["data"]["object"]["subscription"])->with(['plan' => function ($query) {
            $query->with('user');
        }], 'user')->first();

        // Log::debug($userSubscription);

        $payment = $userSubscription->payments()->create([
            "plan_id" => $userSubscription["plan_id"],
            "fee_id" => $fee["id"],
            "net_price" => PriceHelper::netPrice($userSubscription['plan'][$userSubscription['price_period']], $fee['fee']),
            "hosted_invoice_url" => $data["data"]["object"]["hosted_invoice_url"],
        ]);

        $user = $userSubscription['user'];
        $author = $userSubscription['plan']['user'];

        Mail::to($user)
            ->queue((new InvoiceMail($user, $author, $payment))->onQueue('email'));

        return response()->json([
            "created" => true
        ]);
    }
}
