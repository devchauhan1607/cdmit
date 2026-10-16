<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_a_super_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $regularUser = User::factory()->create(['is_super_admin' => false]);
        $this->actingAs($regularUser)->get('/admin')->assertForbidden();

        $admin = User::factory()->create(['is_super_admin' => true]);
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_super_admin_can_create_a_service(): void
    {
        $admin = User::factory()->create(['is_super_admin' => true]);
        $payload = [
            'title' => 'Integration Engineering', 'slug' => 'integration-engineering',
            'summary' => 'A valid summary for the service and its business purpose.',
            'overview' => 'A complete overview of the engagement and expected delivery approach.',
            'status' => 'draft', 'is_featured' => false, 'display_order' => 1, 'noindex' => false,
        ];

        $this->actingAs($admin)->post('/admin/services', $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('services', ['slug' => 'integration-engineering']);
    }

    public function test_lead_status_change_creates_a_private_note(): void
    {
        $admin = User::factory()->create(['is_super_admin' => true]);
        $lead = Lead::factory()->create(['status' => 'new']);

        $this->actingAs($admin)->patch("/admin/leads/{$lead->id}", ['status' => 'contacted', 'note' => 'Initial discovery call scheduled.'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'status' => 'contacted']);
        $this->assertDatabaseHas('lead_notes', ['lead_id' => $lead->id, 'user_id' => $admin->id]);
    }

    public function test_invalid_service_upload_is_rejected(): void
    {
        $admin = User::factory()->create(['is_super_admin' => true]);
        $this->actingAs($admin)->post('/admin/services', [
            'title' => 'Unsafe Upload', 'slug' => 'unsafe-upload', 'summary' => 'A valid service summary for testing.',
            'overview' => 'A complete overview for upload validation testing.', 'status' => 'draft',
            'display_order' => 0, 'cover_image' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('cover_image');

        $this->assertDatabaseMissing('services', ['slug' => 'unsafe-upload']);
    }
}
