<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected SubscriptionPlan $plan;
    protected UserSubscription $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create subscription plan
        $this->plan = SubscriptionPlan::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'description' => 'Professional plan',
            'price' => 99.00,
            'billing_period' => 'monthly',
            'tokens_limit' => 1000000,
            'is_active' => true,
        ]);

        // Create subscription
        $this->subscription = UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->plan->id,
            'status' => 'active',
            'tokens_used' => 100000,
            'tokens_remaining' => 900000,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);
    }

    /**
     * Test get user endpoint requires authentication.
     */
    public function test_get_user_requires_authentication(): void
    {
        $response = $this->getJson('/api/dashboard/user');

        $response->assertStatus(401);
    }

    /**
     * Test get user endpoint returns user data.
     */
    public function test_get_user_returns_user_data(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ]
            ]);
    }

    /**
     * Test get stats endpoint returns statistics.
     */
    public function test_get_stats_returns_statistics(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'tokens_used' => 100000,
                    'tokens_remaining' => 900000,
                    'tokens_limit' => 1000000,
                ]
            ])
            ->assertJsonPath('data.usage_percentage', 10.0);
    }

    /**
     * Test get subscription endpoint returns subscription data.
     */
    public function test_get_subscription_returns_subscription_data(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/subscription');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->subscription->id,
                    'plan_name' => 'Pro Plan',
                    'plan_slug' => 'pro',
                    'status' => 'active',
                    'tokens_used' => 100000,
                    'tokens_remaining' => 900000,
                ]
            ]);
    }

    /**
     * Test get subscription returns 404 when no subscription.
     */
    public function test_get_subscription_returns_404_when_no_subscription(): void
    {
        // Create user without subscription
        $userWithoutSub = User::factory()->create();
        $userWithoutSub->subscriptions()->delete();

        Sanctum::actingAs($userWithoutSub);

        $response = $this->getJson('/api/dashboard/subscription');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No active subscription found'
            ]);
    }

    /**
     * Test usage percentage calculation is correct.
     */
    public function test_usage_percentage_calculation_is_correct(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/stats');

        $data = $response->json('data');

        $expectedPercentage = round((100000 / 1000000) * 100, 1);

        $this->assertEquals($expectedPercentage, $data['usage_percentage']);
    }

    /**
     * Test usage percentage handles division by zero.
     */
    public function test_usage_percentage_handles_zero_tokens_limit(): void
    {
        // Create plan with zero tokens
        $zeroPlan = SubscriptionPlan::create([
            'name' => 'Zero Plan',
            'slug' => 'zero',
            'description' => 'Plan with zero tokens',
            'price' => 0,
            'billing_period' => 'monthly',
            'tokens_limit' => 0,
            'is_active' => true,
        ]);

        $userWithZero = User::factory()->create();
        $userWithZero->subscriptions()->delete();

        UserSubscription::create([
            'user_id' => $userWithZero->id,
            'subscription_plan_id' => $zeroPlan->id,
            'status' => 'active',
            'tokens_used' => 0,
            'tokens_remaining' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        Sanctum::actingAs($userWithZero);

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonPath('data.usage_percentage', 0.0);
    }

    /**
     * Test get usage data endpoint.
     */
    public function test_get_usage_data_returns_usage_logs(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/usage');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /**
     * Test get languages data endpoint.
     */
    public function test_get_languages_data_returns_language_stats(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/languages');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test get history endpoint.
     */
    public function test_get_history_returns_translation_history(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/history');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test get projects endpoint.
     */
    public function test_get_projects_returns_projects(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/dashboard/projects');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Projects feature coming soon'
            ]);
    }
}
