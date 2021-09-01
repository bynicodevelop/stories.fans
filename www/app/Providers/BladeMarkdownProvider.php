<?php

namespace App\Providers;

use App\MarkdownElements\FencedCodeRenderer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

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
            $environment = Environment::createCommonMarkEnvironment();
            $environment->addExtension(new GithubFlavoredMarkdownExtension());

            $environment->addBlockRenderer(FencedCode::class, new FencedCodeRenderer());

            $md = (new CommonMarkConverter([], $environment));
        }

        return $md;
    }
}
