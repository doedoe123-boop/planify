<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated through middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:255'],
            'project_description' => ['nullable', 'string'],
            'website_type_id' => ['required', 'exists:website_types,id'],
            'selected_features' => ['required', 'array'],
            'selected_features.*' => ['exists:features,id'],
            'custom_features' => ['array'],
            'custom_features.*.name' => ['required_with:custom_features', 'string', 'max:255'],
            'custom_features.*.hours' => ['required_with:custom_features', 'integer', 'min:1'],
            'hourly_rate' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'website_type_id.exists' => 'The selected website type is invalid.',
            'selected_features.*.exists' => 'One or more selected features are invalid.',
            'custom_features.*.name.required_with' => 'Custom feature name is required.',
            'custom_features.*.hours.required_with' => 'Custom feature hours estimation is required.',
            'custom_features.*.hours.min' => 'Custom feature hours must be at least 1.',
        ];
    }
}
