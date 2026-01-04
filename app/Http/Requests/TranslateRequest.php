<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TranslateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string|max:10000',
            'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
            'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
            'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
            'preserve_formatting' => 'nullable|boolean',
            'context' => 'nullable|string|max:1000',
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
            'text.required' => 'The text to translate is required.',
            'text.max' => 'The text cannot exceed 10,000 characters.',
            'source_lang.required' => 'The source language is required.',
            'source_lang.in' => 'The source language must be one of the supported languages.',
            'target_lang.required' => 'The target language is required.',
            'target_lang.in' => 'The target language must be one of the supported languages.',
            'target_lang.different' => 'The target language must be different from the source language.',
            'tone.in' => 'The tone must be one of: formal, casual, technical, friendly, professional.',
            'context.max' => 'The context cannot exceed 1,000 characters.',
        ];
    }

    /**
     * Handle a failed validation attempt for API requests.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
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

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'text' => 'text to translate',
            'source_lang' => 'source language',
            'target_lang' => 'target language',
            'tone' => 'translation tone',
            'preserve_formatting' => 'preserve formatting',
            'context' => 'translation context',
        ];
    }
}
