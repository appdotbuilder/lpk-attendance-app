<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePicketReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isCpmi();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report' => 'required|string|min:50|max:2000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'issues' => 'nullable|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'report.required' => 'Daily report is required.',
            'report.min' => 'Report must be at least 50 characters long.',
            'report.max' => 'Report cannot exceed 2000 characters.',
            'photos.max' => 'You can upload maximum 5 photos.',
            'photos.*.image' => 'All uploaded files must be images.',
            'photos.*.mimes' => 'Photos must be in JPEG, PNG, or JPG format.',
            'photos.*.max' => 'Each photo cannot exceed 2MB.',
        ];
    }
}