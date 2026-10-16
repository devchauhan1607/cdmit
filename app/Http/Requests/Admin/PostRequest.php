<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $post = $this->route('post');

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:180', 'unique:posts,slug,'.($post?->id ?? 'NULL')],
            'excerpt' => ['required', 'string', 'max:700'],
            'content' => ['required', 'string', 'max:100000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'tags' => ['nullable', 'array', 'max:20'],
            'tags.*' => ['string', 'max:60'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'canonical_url' => ['nullable', 'url:https', 'max:2048'],
            'social_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'noindex' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
