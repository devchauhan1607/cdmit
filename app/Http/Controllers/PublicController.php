<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\PageSeo;
use App\Models\Post;
use App\Models\Service;
use App\Models\SlugRedirect;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Public/Home', [
            'services' => Service::published()->where('is_featured', true)->orderBy('display_order')->take(6)->get(),
            'caseStudies' => CaseStudy::published()->with('technologies:id,name,slug')->where('is_featured', true)->orderBy('display_order')->take(3)->get(),
            'posts' => Post::published()->with('category:id,name,slug')->latest('published_at')->take(3)->get(),
            'technologies' => Technology::where('is_active', true)->orderBy('display_order')->get()->groupBy('category'),
            'seo' => $this->seo('home'),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('Public/About', ['seo' => $this->seo('about')]);
    }

    public function services(Request $request): Response
    {
        $services = Service::published()->with('category:id,name,slug')
            ->when($request->string('q')->toString(), fn (Builder $query, string $search) => $query
                ->where(fn (Builder $nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('summary', 'like', "%{$search}%")))
            ->orderBy('display_order')->paginate(12)->withQueryString();

        return Inertia::render('Public/Services', [
            'services' => $services,
            'filters' => $request->only('q'),
            'seo' => $this->seo('services'),
        ]);
    }

    public function service(string $slug): Response|RedirectResponse
    {
        $service = Service::published()->with('category:id,name,slug')->where('slug', $slug)->first();
        if (! $service) {
            $redirect = SlugRedirect::where(['type' => 'service', 'old_slug' => $slug])->first();
            abort_unless($redirect, 404);

            return redirect()->route('services.show', $redirect->new_slug, status: 301);
        }

        return Inertia::render('Public/ServiceShow', [
            'service' => $service,
            'related' => Service::published()->whereKeyNot($service->id)->where('category_id', $service->category_id)->take(3)->get(),
        ]);
    }

    public function portfolio(Request $request): Response
    {
        return Inertia::render('Public/Portfolio', [
            'caseStudies' => CaseStudy::published()->with('technologies:id,name,slug')
                ->when($request->string('q')->toString(), fn (Builder $query, string $search) => $query
                    ->where(fn (Builder $nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('summary', 'like', "%{$search}%")))
                ->latest('published_at')->paginate(9)->withQueryString(),
            'filters' => $request->only('q'),
            'seo' => $this->seo('portfolio'),
        ]);
    }

    public function caseStudy(string $slug): Response
    {
        $caseStudy = CaseStudy::published()->with('technologies:id,name,slug')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/CaseStudyShow', ['caseStudy' => $caseStudy]);
    }

    public function technologies(): Response
    {
        return Inertia::render('Public/Technologies', [
            'technologies' => Technology::where('is_active', true)->orderBy('display_order')->get()->groupBy('category'),
            'seo' => $this->seo('technologies'),
        ]);
    }

    public function blog(Request $request): Response
    {
        $posts = Post::published()->with(['category:id,name,slug', 'author:id,name', 'tags:id,name,slug'])
            ->when($request->string('q')->toString(), fn (Builder $query, string $search) => $query
                ->where(fn (Builder $nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('excerpt', 'like', "%{$search}%")))
            ->when($request->string('category')->toString(), fn (Builder $query, string $category) => $query->whereHas('category', fn (Builder $q) => $q->where('slug', $category)))
            ->when($request->string('tag')->toString(), fn (Builder $query, string $tag) => $query->whereHas('tags', fn (Builder $q) => $q->where('slug', $tag)))
            ->latest('published_at')->paginate(10)->withQueryString();

        return Inertia::render('Public/Blog', [
            'posts' => $posts,
            'filters' => $request->only('q', 'category', 'tag'),
            'seo' => $this->seo('blog'),
        ]);
    }

    public function post(string $slug): Response
    {
        $post = Post::published()->with(['category:id,name,slug', 'author:id,name', 'tags:id,name,slug'])->where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/PostShow', [
            'post' => $post,
            'related' => Post::published()->whereKeyNot($post->id)->where('category_id', $post->category_id)->latest('published_at')->take(3)->get(),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Public/Contact', [
            'services' => Service::published()->orderBy('display_order')->get(['id', 'title']),
            'seo' => $this->seo('contact'),
        ]);
    }

    private function seo(string $key): ?PageSeo
    {
        return PageSeo::where('page_key', $key)->first();
    }
}
