<?php

namespace App\Http\Controllers;

use App\Models\InvitationLink;
use Exception;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index()
    {
        return view('invitation.index');
    }

    public function redirect(Request $request, $linkId)
    {
        $referer = $request->headers->get('referer');

        $data = [
            'referer' => $referer,
        ];

        $value = $request->cookie('invited');

        try {
            $link = InvitationLink::where('hash', $linkId)->with('user')->first();

            if (is_null($link)) {
                abort(404);
            }
        } catch (Exception $e) {
            abort(404);
        }

        if (is_null($value)) {
            $invationsStats = $link->invitationStats()->create([
                'stats' => json_encode($data),
            ]);

            return redirect(route('register', ['slug' => $link['user']['slug']]))->cookie('invited', $invationsStats['id'], 60 * 24);
        }

        return redirect(route('register', ['slug' => $link['user']['slug']]));
    }
}


# https://l.instagram.com/?u=http%3A%2F%2Flocalhost:8080/i/ETYbq4%2F&e=ATMxKzYMOt8Wiw0IP1AyfSKq6wqYHa9gUfE--3K-q_XHyY3UNLbwUfDpGmgwxBbn_w0RQyWpUvk6IRvKlGvk5kgLPbM-TWUkCfX_3rk&s=1