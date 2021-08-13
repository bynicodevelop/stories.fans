<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Models\Post;
use Exception;

class PostController extends Controller
{
    use SEOToolsTrait;

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

        $this->seo()->setTitle('Post ' . $post['user']['name'] . ' @' . $post['user']['slug']);
        $this->seo()->setDescription($post['content']);
        $this->seo()->opengraph()->setUrl(route('post', ['postId' => $post['id']]));
        $this->seo()->opengraph()->addProperty('type', 'article');

        return view('post.index', compact('post'));
    }
}
