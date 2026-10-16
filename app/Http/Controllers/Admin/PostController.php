<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ContentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/ContentIndex', [
            'resource' => 'posts', 'title' => 'Blog posts',
            'items' => Post::with('category:id,name')->when($request->string('q')->toString(), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
                ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
                ->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only('q', 'status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/PostForm', ['post' => null, 'categories' => $this->categories()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request, ContentManager $content)
    {
        $post = DB::transaction(function () use ($request, $content) {
            $data = $content->prepare($request->validated(), 'posts');
            $tags = $data['tags'] ?? [];
            unset($data['tags']);
            $data['author_id'] = $request->user()->id;
            $post = Post::create($data);
            $this->syncTags($post, $tags);
            $content->audit('post.created', $post);

            return $post;
        });

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->noindex = true;

        return Inertia::render('Public/PostShow', ['post' => $post->load('category', 'author', 'tags'), 'related' => []]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): Response
    {
        return Inertia::render('Admin/PostForm', ['post' => $post->load('tags:id,name'), 'categories' => $this->categories()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post, ContentManager $content)
    {
        DB::transaction(function () use ($request, $post, $content) {
            $oldSlug = $post->slug;
            $data = $content->prepare($request->validated(), 'posts');
            $tags = $data['tags'] ?? [];
            unset($data['tags']);
            $post->update($data);
            $this->syncTags($post, $tags);
            $content->syncSlug($post, $oldSlug, 'post');
            $content->audit('post.updated', $post);
        });

        return back()->with('success', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, ContentManager $content)
    {
        $post->update(['status' => 'archived']);
        $content->audit('post.archived', $post);

        return redirect()->route('admin.posts.index')->with('success', 'Post archived.');
    }

    private function categories()
    {
        return Category::where('type', 'post')->where('is_active', true)->orderBy('display_order')->get(['id', 'name']);
    }

    private function syncTags(Post $post, array $tags): void
    {
        $ids = collect($tags)->filter()->map(fn ($name) => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => trim($name)])->id);
        $post->tags()->sync($ids);
    }
}
