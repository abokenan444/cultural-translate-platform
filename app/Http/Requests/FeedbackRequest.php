<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FeedbackRequest extends FormRequest
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
            'translation_id' => 'required|integer|exists:translations,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:1000',
            'suggested_translation' => 'nullable|string|max:5000',
            'issues' => 'nullable|array',
            'issues.*' => 'string|in:accuracy,grammar,tone,cultural,formatting,other',
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
            'translation_id.required' => 'Translation ID is required.',
            'translation_id.exists' => 'The specified translation does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot exceed 5.',
            'feedback_text.max' => 'Feedback text cannot exceed 1,000 characters.',
            'suggested_translation.max' => 'Suggested translation cannot exceed 5,000 characters.',
            'issues.*.in' => 'Invalid issue type. Must be one of: accuracy, grammar, tone, cultural, formatting, other.',
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
