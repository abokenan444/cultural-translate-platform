<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VoiceTranslationRequest extends FormRequest
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
            'audio' => 'required|file|mimes:mp3,wav,ogg,m4a,flac|max:51200', // 50MB max
            'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
            'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
            'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
            'output_format' => 'nullable|string|in:text,audio,both',
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
            'audio.required' => 'Audio file is required.',
            'audio.file' => 'The uploaded file must be a valid audio file.',
            'audio.mimes' => 'The audio must be in MP3, WAV, OGG, M4A, or FLAC format.',
            'audio.max' => 'The audio file size cannot exceed 50MB.',
            'source_lang.required' => 'Source language is required.',
            'source_lang.in' => 'Invalid source language.',
            'target_lang.required' => 'Target language is required.',
            'target_lang.in' => 'Invalid target language.',
            'target_lang.different' => 'Target language must be different from source language.',
            'tone.in' => 'The tone must be one of: formal, casual, technical, friendly, professional.',
            'output_format.in' => 'Output format must be: text, audio, or both.',
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
