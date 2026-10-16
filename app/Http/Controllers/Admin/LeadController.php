<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadStatusRequest;
use App\Jobs\SendLeadNotification;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Leads', [
            'leads' => Lead::with('service:id,title')
                ->when($request->string('q')->toString(), fn ($q, $search) => $q->where(fn ($nested) => $nested->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('company', 'like', "%{$search}%")))
                ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
                ->when($request->integer('service_id'), fn ($q, $service) => $q->where('service_id', $service))
                ->latest()->paginate(20)->withQueryString(),
            'services' => Service::orderBy('title')->get(['id', 'title']),
            'statuses' => config('cdmit.lead_statuses'),
            'filters' => $request->only('q', 'status', 'service_id'),
        ]);
    }

    public function show(Lead $lead): Response
    {
        return Inertia::render('Admin/LeadShow', [
            'lead' => $lead->load(['service:id,title', 'notes.user:id,name']),
            'statuses' => config('cdmit.lead_statuses'),
        ]);
    }

    public function update(LeadStatusRequest $request, Lead $lead)
    {
        DB::transaction(function () use ($request, $lead) {
            $oldStatus = $lead->status->value;
            $lead->update([
                'status' => $request->validated('status'),
                'last_contacted_at' => $request->validated('status') === 'contacted' ? now() : $lead->last_contacted_at,
            ]);
            if ($request->filled('note')) {
                $lead->notes()->create(['user_id' => $request->user()->id, 'note' => $request->validated('note')]);
            }
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'lead.status-updated',
                'subject_type' => $lead->getMorphClass(),
                'subject_id' => $lead->id,
                'properties' => ['from' => $oldStatus, 'to' => $request->validated('status')],
                'ip_address' => $request->ip(),
            ]);
        });

        return back()->with('success', 'Lead updated.');
    }

    public function retryNotification(Lead $lead)
    {
        $lead->update(['notification_status' => 'pending', 'notification_error' => null]);
        SendLeadNotification::dispatch($lead);

        return back()->with('success', 'Notification retry queued.');
    }
}
