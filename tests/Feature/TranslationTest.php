<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /** @test */
    public function authenticated_user_can_translate_text()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/v1/translate', [
                             'text' => 'Hello, world!',
                             'source_language' => 'en',
                             'target_language' => 'ar',
                             'ai_model' => 'gpt-4',
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'translated_text',
                         'source_language',
                         'target_language',
                     ]
                 ]);
    }

    /** @test */
    public function translation_requires_authentication()
    {
        $response = $this->postJson('/api/v1/translate', [
            'text' => 'Hello, world!',
            'source_language' => 'en',
            'target_language' => 'ar',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function translation_validates_required_fields()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/v1/translate', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['text', 'target_language']);
    }

    /** @test */
    public function user_can_get_translation_history()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/v1/translations');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data'
                 ]);
    }

    /** @test */
    public function user_can_translate_batch()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/v1/translate/batch', [
                             'texts' => ['Hello', 'World', 'Test'],
                             'source_language' => 'en',
                             'target_language' => 'ar',
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'translations',
                         'total_count',
                     ]
                 ]);
    }
}
