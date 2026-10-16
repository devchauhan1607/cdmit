<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
        return [
            'company_name' => ['required', 'string', 'max:120'],
            'tagline' => ['required', 'string', 'max:180'],
            'contact_email' => ['nullable', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:500'],
            'service_areas' => ['nullable', 'string', 'max:500'],
            'linkedin_url' => ['nullable', 'url:https', 'max:2048'],
            'github_url' => ['nullable', 'url:https', 'max:2048'],
            'lead_notification_email' => ['nullable', 'email', 'max:190'],
            'default_meta_title' => ['nullable', 'string', 'max:70'],
            'default_meta_description' => ['nullable', 'string', 'max:170'],
        ];
    }
}
