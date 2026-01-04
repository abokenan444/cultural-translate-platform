<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImageTranslationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
            'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
            'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
            'extract_only' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Image file is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be in JPEG, PNG, JPG, GIF, or WEBP format.',
            'image.max' => 'The image size cannot exceed 10MB.',
            'source_lang.required' => 'Source language is required.',
            'source_lang.in' => 'Invalid source language.',
            'target_lang.required' => 'Target language is required.',
            'target_lang.in' => 'Invalid target language.',
            'target_lang.different' => 'Target language must be different from source language.',
            'tone.in' => 'The tone must be one of: formal, casual, technical, friendly, professional.',
        ];
    }

    /**
     * Handle a failed validation attempt for API requests.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
