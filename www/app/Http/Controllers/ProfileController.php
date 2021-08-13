<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Models\User;
use Exception;

class ProfileController extends Controller
{
    use SEOToolsTrait;

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

        $this->seo()->setTitle($user['name'] . ' @' . $user['slug']);
        $this->seo()->setDescription($user['bio']);
        $this->seo()->opengraph()->setUrl(route('profiles-slug', ['slug' => $user['slug']]));
        $this->seo()->opengraph()->addProperty('type', 'profile:username');

        return view("profile.index", ["user" => $user]);
    }

    public function all()
    {
        return view("profile.all");
    }
}
