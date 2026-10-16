<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_services_are_listed(): void
    {
        Service::factory()->create(['title' => 'Published Service', 'status' => 'published', 'published_at' => now()]);
        Service::factory()->create(['title' => 'Draft Service', 'status' => 'draft', 'published_at' => null]);

        $this->get('/services')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Services')
            ->has('services.data', 1)
            ->where('services.data.0.title', 'Published Service'));
    }

    public function test_unpublished_content_is_not_publicly_accessible(): void
    {
        $service = Service::factory()->create(['status' => 'draft', 'published_at' => null]);
        $post = Post::factory()->create(['status' => 'draft', 'published_at' => null]);

        $this->get(route('services.show', $service->slug))->assertNotFound();
        $this->get(route('blog.show', $post->slug))->assertNotFound();
    }

    public function test_sitemap_contains_only_published_records(): void
    {
        $published = Service::factory()->create(['status' => 'published', 'published_at' => now()]);
        $draft = Service::factory()->create(['status' => 'draft', 'published_at' => null]);

        $this->get('/sitemap.xml')->assertOk()
            ->assertSee(route('services.show', $published->slug), false)
            ->assertDontSee(route('services.show', $draft->slug), false);
    }
}
