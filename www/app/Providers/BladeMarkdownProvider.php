<?php

namespace App\Providers;

use App\MarkdownElements\FencedCodeRenderer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Paragraph;

class BladeMarkdownProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('md', function ($expression) {
            return '<?php echo \App\Providers\BladeMarkdownProvider::md()->convertToHtml(' . $expression . '); ?>';
        });
    }

    public static function md()
    {
        static $md;

        if (!isset($md)) {
            $config = [
                'default_attributes' => [
                    Heading::class => [
                        'class' => static function (Heading $node) {
                            switch ($node->getLevel()) {
                                case 1:
                                    return 'text-lg font-bold';
                                    break;
                                case 2:
                                    return 'text-lg font-semibold';
                                    break;
                                default:
                                    return null;
                            }
                        },
                    ],
                    Paragraph::class => [
                        'class' => ['text-base'],
                    ],
                    // Link::class => [
                    //     'class' => 'btn btn-link',
                    // ],
                ],
                'external_link' => [
                    'internal_hosts' => str_replace(['http://', 'https://'], '', config('app.url')),
                    'open_in_new_window' => true,
                    'html_class' => 'external-link',
                    'nofollow' => '',
                    'noopener' => 'external',
                    'noreferrer' => 'external',
                ],
            ];

            $environment = new Environment($config);

            $environment->addExtension(new CommonMarkCoreExtension());

            $environment->addExtension(new DefaultAttributesExtension());
            $environment->addExtension(new GithubFlavoredMarkdownExtension());
            $environment->addExtension(new ExternalLinkExtension());

            // $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());

            $md = new MarkdownConverter($environment);
        }

        return $md;
    }
}
