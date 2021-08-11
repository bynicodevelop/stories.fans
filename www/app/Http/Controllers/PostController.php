<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;

class PostController extends Controller
{
    public function index($postId)
    {
        try {
            $post = Post::where('id', $postId)->with('user')->first();

            if (is_null($post)) {
                abort(404);
            }
        } catch (Exception $e) {
            abort(404);
        }

        return view('post.index', compact('post'));
    }
}
