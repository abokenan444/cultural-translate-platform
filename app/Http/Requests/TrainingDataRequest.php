<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrainingDataRequest extends FormRequest
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
            'source_text' => 'required|string|max:10000',
            'translated_text' => 'required|string|max:10000',
            'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
            'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
            'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
            'context' => 'nullable|string|max:1000',
            'quality_score' => 'nullable|numeric|min:0|max:100',
            'metadata' => 'nullable|array',
            'translation_id' => 'nullable|integer|exists:translations,id',
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
            'source_text.required' => 'Source text is required.',
            'source_text.max' => 'Source text cannot exceed 10,000 characters.',
            'translated_text.required' => 'Translated text is required.',
            'translated_text.max' => 'Translated text cannot exceed 10,000 characters.',
            'source_lang.required' => 'Source language is required.',
            'source_lang.in' => 'Invalid source language.',
            'target_lang.required' => 'Target language is required.',
            'target_lang.in' => 'Invalid target language.',
            'target_lang.different' => 'Target language must be different from source language.',
            'tone.in' => 'The tone must be one of: formal, casual, technical, friendly, professional.',
            'context.max' => 'Context cannot exceed 1,000 characters.',
            'quality_score.numeric' => 'Quality score must be a number.',
            'quality_score.min' => 'Quality score cannot be less than 0.',
            'quality_score.max' => 'Quality score cannot exceed 100.',
            'translation_id.exists' => 'The specified translation does not exist.',
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
