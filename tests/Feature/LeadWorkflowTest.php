<?php

namespace Tests\Feature;

use App\Jobs\SendLeadNotification;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LeadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_inquiry_is_stored_and_notification_is_queued(): void
    {
        Queue::fake();

        $response = $this->post('/contact', [
            'name' => 'Project Owner', 'email' => 'owner@example.com', 'company' => 'Example Company',
            'message' => 'We need help planning and building a secure customer application.', 'consent' => true,
            'website' => '',
        ]);

        $response->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertDatabaseHas('leads', ['email' => 'owner@example.com', 'status' => 'new']);
        Queue::assertPushed(SendLeadNotification::class);
    }

    public function test_inquiry_validation_and_honeypot_are_enforced(): void
    {
        $this->post('/contact', ['name' => 'A', 'email' => 'bad', 'message' => 'short', 'consent' => false, 'website' => 'spam'])
            ->assertSessionHasErrors(['name', 'email', 'message', 'consent', 'website']);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_duplicate_inquiry_does_not_create_another_lead(): void
    {
        Queue::fake();
        $data = ['name' => 'Project Owner', 'email' => 'owner@example.com', 'message' => 'This is a sufficiently detailed project inquiry for validation.', 'consent' => true, 'website' => ''];
        $this->post('/contact', $data);
        $this->post('/contact', $data)->assertSessionHas('success');
        $this->assertSame(1, Lead::count());
    }
}
