<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
        $service = $this->route('service');

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:180', 'unique:services,slug,'.($service?->id ?? 'NULL')],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:500'],
            'overview' => ['required', 'string', 'max:20000'],
            'benefits' => ['nullable', 'array', 'max:12'],
            'benefits.*' => ['string', 'max:250'],
            'deliverables' => ['nullable', 'array', 'max:20'],
            'deliverables.*' => ['string', 'max:250'],
            'process' => ['nullable', 'array', 'max:12'],
            'process.*' => ['string', 'max:500'],
            'technologies' => ['nullable', 'array', 'max:30'],
            'technologies.*' => ['string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:80'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
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
