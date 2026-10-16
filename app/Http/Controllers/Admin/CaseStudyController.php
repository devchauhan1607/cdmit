<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CaseStudyRequest;
use App\Models\CaseStudy;
use App\Models\Technology;
use App\Services\ContentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CaseStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/ContentIndex', [
            'resource' => 'case-studies', 'title' => 'Case studies',
            'items' => CaseStudy::with('technologies:id,name')->when($request->string('q')->toString(), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
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
        return Inertia::render('Admin/CaseStudyForm', ['caseStudy' => null, 'technologies' => $this->technologies()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CaseStudyRequest $request, ContentManager $content)
    {
        $caseStudy = DB::transaction(function () use ($request, $content) {
            $data = $this->prepare($request, $content);
            $technologyIds = $data['technology_ids'] ?? [];
            unset($data['technology_ids']);
            $caseStudy = CaseStudy::create($data);
            $caseStudy->technologies()->sync($technologyIds);
            $content->audit('case-study.created', $caseStudy);

            return $caseStudy;
        });

        return redirect()->route('admin.case-studies.edit', $caseStudy)->with('success', 'Case study created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CaseStudy $caseStudy)
    {
        $caseStudy->noindex = true;

        return Inertia::render('Public/CaseStudyShow', ['caseStudy' => $caseStudy->load('technologies')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaseStudy $caseStudy): Response
    {
        return Inertia::render('Admin/CaseStudyForm', ['caseStudy' => $caseStudy->load('technologies:id,name'), 'technologies' => $this->technologies()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CaseStudyRequest $request, CaseStudy $caseStudy, ContentManager $content)
    {
        DB::transaction(function () use ($request, $caseStudy, $content) {
            $oldSlug = $caseStudy->slug;
            $data = $this->prepare($request, $content);
            $technologyIds = $data['technology_ids'] ?? [];
            unset($data['technology_ids']);
            $caseStudy->update($data);
            $caseStudy->technologies()->sync($technologyIds);
            $content->syncSlug($caseStudy, $oldSlug, 'case-study');
            $content->audit('case-study.updated', $caseStudy);
        });

        return back()->with('success', 'Case study updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaseStudy $caseStudy, ContentManager $content)
    {
        $caseStudy->update(['status' => 'archived']);
        $content->audit('case-study.archived', $caseStudy);

        return redirect()->route('admin.case-studies.index')->with('success', 'Case study archived.');
    }

    private function technologies()
    {
        return Technology::where('is_active', true)->orderBy('category')->orderBy('display_order')->get(['id', 'name', 'category']);
    }

    private function prepare(CaseStudyRequest $request, ContentManager $content): array
    {
        $data = $content->prepare($request->validated(), 'case-studies');
        if ($request->hasFile('screenshots')) {
            $data['screenshots'] = collect($request->file('screenshots'))->map->store('case-studies/screenshots', 'public')->all();
        } else {
            unset($data['screenshots']);
        }

        return $data;
    }
}
