<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\PageSeo;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Settings', [
            'settings' => SiteSetting::pluck('value', 'key'),
            'seoPages' => PageSeo::orderBy('page_key')->get(),
        ]);
    }

    public function update(SettingsRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated() as $key => $value) {
                SiteSetting::updateOrCreate(['key' => $key], [
                    'value' => $value, 'type' => 'string',
                    'group' => str_contains($key, 'meta') ? 'seo' : 'general',
                    'is_public' => $key !== 'lead_notification_email',
                ]);
            }
        });

        return back()->with('success', 'Site settings saved.');
    }

    public function updateSeo(Request $request, string $pageKey)
    {
        abort_unless(in_array($pageKey, ['home', 'about', 'services', 'portfolio', 'technologies', 'blog', 'contact'], true), 404);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:70'],
            'description' => ['required', 'string', 'max:170'],
            'canonical_url' => ['nullable', 'url:https', 'max:2048'],
            'social_title' => ['nullable', 'string', 'max:70'],
            'social_description' => ['nullable', 'string', 'max:170'],
            'noindex' => ['boolean'],
        ]);
        PageSeo::updateOrCreate(['page_key' => $pageKey], $data);

        return back()->with('success', 'Page SEO updated.');
    }
}
