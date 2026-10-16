<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $statusCounts = Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'totalLeads' => Lead::count(),
                'newLeads' => Lead::where('status', LeadStatus::New)->count(),
                'awaitingFollowUp' => Lead::whereIn('status', [LeadStatus::New, LeadStatus::Qualified])->count(),
                'publishedServices' => Service::published()->count(),
                'publishedCaseStudies' => CaseStudy::published()->count(),
                'publishedPosts' => Post::published()->count(),
            ],
            'leadStatuses' => collect(LeadStatus::cases())->map(fn (LeadStatus $status) => [
                'status' => $status->value,
                'label' => $status->label(),
                'total' => (int) ($statusCounts[$status->value] ?? 0),
            ]),
            'recentLeads' => Lead::with('service:id,title')->latest()->take(8)->get(),
        ]);
    }
}
