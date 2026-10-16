<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CaseStudyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_super_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $caseStudy = $this->route('case_study');

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:180', 'unique:case_studies,slug,'.($caseStudy?->id ?? 'NULL')],
            'client_name' => ['nullable', 'string', 'max:150'],
            'industry' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:600'],
            'overview' => ['required', 'string', 'max:20000'],
            'challenge' => ['required', 'string', 'max:20000'],
            'solution' => ['required', 'string', 'max:30000'],
            'implementation' => ['nullable', 'string', 'max:30000'],
            'results' => ['nullable', 'array', 'max:15'],
            'results.*' => ['string', 'max:300'],
            'project_url' => ['nullable', 'url:https', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'screenshots' => ['nullable', 'array', 'max:8'],
            'screenshots.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'technology_ids' => ['nullable', 'array'],
            'technology_ids.*' => ['integer', 'exists:technologies,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'display_order' => ['integer', 'min:0', 'max:10000'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'canonical_url' => ['nullable', 'url:https', 'max:2048'],
            'noindex' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
