<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Category;
use App\Models\Service;
use App\Services\ContentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/ContentIndex', [
            'resource' => 'services',
            'title' => 'Services',
            'items' => Service::with('category:id,name')->when($request->string('q')->toString(), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
                ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
                ->orderBy('display_order')->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only('q', 'status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/ServiceForm', ['service' => null, 'categories' => $this->categories()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request, ContentManager $content)
    {
        $service = DB::transaction(function () use ($request, $content) {
            $service = Service::create($content->prepare($request->validated(), 'services'));
            $content->audit('service.created', $service);

            return $service;
        });

        return redirect()->route('admin.services.edit', $service)->with('success', 'Service created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->noindex = true;

        return Inertia::render('Public/ServiceShow', ['service' => $service->load('category'), 'related' => []]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): Response
    {
        return Inertia::render('Admin/ServiceForm', ['service' => $service, 'categories' => $this->categories()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service, ContentManager $content)
    {
        DB::transaction(function () use ($request, $service, $content) {
            $oldSlug = $service->slug;
            $service->update($content->prepare($request->validated(), 'services'));
            $content->syncSlug($service, $oldSlug, 'service');
            $content->audit('service.updated', $service);
        });

        return back()->with('success', 'Service updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service, ContentManager $content)
    {
        $service->update(['status' => 'archived']);
        $content->audit('service.archived', $service);

        return redirect()->route('admin.services.index')->with('success', 'Service archived.');
    }

    private function categories()
    {
        return Category::where('type', 'service')->where('is_active', true)->orderBy('display_order')->get(['id', 'name']);
    }
}
