<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'country' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'budget' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'consent' => ['required', 'accepted'],
            'website' => ['nullable', 'size:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => strtolower(trim((string) $this->input('email'))),
            'company' => trim((string) $this->input('company')) ?: null,
            'message' => trim((string) $this->input('message')),
        ]);
    }
}
