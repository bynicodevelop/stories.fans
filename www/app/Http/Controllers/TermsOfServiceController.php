<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        $environment = new Environment();

        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return view('terms.index', [
            'terms' => (new CommonMarkConverter([], $environment))->convertToHtml(file_get_contents($termsFile)),
        ]);
    }
}
