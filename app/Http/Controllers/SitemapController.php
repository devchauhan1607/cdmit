<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $static = ['/', '/about', '/services', '/portfolio', '/technologies', '/blog', '/contact'];
        $urls = collect($static)->map(fn ($path) => ['loc' => url($path), 'lastmod' => null])
            ->concat(Service::published()->get()->map(fn ($item) => ['loc' => route('services.show', $item->slug), 'lastmod' => $item->updated_at]))
            ->concat(CaseStudy::published()->get()->map(fn ($item) => ['loc' => route('case-studies.show', $item->slug), 'lastmod' => $item->updated_at]))
            ->concat(Post::published()->get()->map(fn ($item) => ['loc' => route('blog.show', $item->slug), 'lastmod' => $item->updated_at]));

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }
}
