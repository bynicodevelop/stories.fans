<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Models\Post;
use App\Traits\MediaHelper;
use Exception;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class PostController extends Controller
{
    use SEOToolsTrait;
    use MediaHelper;

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

        $environment = new Environment([]);

        $environment->addExtension(new CommonMarkCoreExtension());

        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $md = new MarkdownConverter($environment);

        $user = '@' . $post['user']['slug'];

        $title = 'Post ' . $post['user']['name'] . ' ' . $user;

        if (!empty($post['content'])) {
            $contentExploded = explode("\n", $post['content']);

            if (!empty($contentExploded[0])) {
                $titleWithoutHastag = str_replace("# ", "", $contentExploded[0]);

                $nLettersInUsername = strlen($user);
                $nLettersInTitle = strlen($titleWithoutHastag);

                $nLettresToRemove = ($nLettersInTitle + $nLettersInUsername) - 60;

                $more = " - ";

                if ($nLettresToRemove > 0) {
                    $more = "... ";
                }

                $title = substr($titleWithoutHastag, 0, $nLettersInTitle - ($nLettresToRemove + strlen($more))) . $more . $user;
            }
        }

        $route = route('post', ['postId' => $post['id']]);

        $this->seo()->setTitle($title);

        $this->seo()->setDescription(str_replace("\n", " ", strip_tags($md->convertToHtml(substr($post['content'], 0, 160)))));
        $this->seo()->setCanonical($route);

        $this->seo()->opengraph()->setUrl($route);
        $this->seo()->opengraph()->addProperty('type', 'article');

        if (count($post['media'])) {
            $this->seo()->opengraph()->addImage($this->getPreview($post['media'][0]['name'], false));
            $this->seo()->twitter()->addImage($this->getPreview($post['media'][0]['name'], false));
        }


        return view('post.index', compact('post'));
    }
}
