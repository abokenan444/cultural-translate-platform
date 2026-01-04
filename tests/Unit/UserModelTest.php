<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that user has HasApiTokens trait.
     */
    public function test_user_has_api_tokens_trait(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(
            method_exists($user, 'createToken'),
            'User model should have HasApiTokens trait'
        );
    }

    /**
     * Test that free subscription is auto-created on user registration.
     */
    public function test_free_subscription_auto_created_on_user_registration(): void
    {
        // Create a free plan first
        $freePlan = SubscriptionPlan::create([
            'name' => 'Free Trial',
            'slug' => 'free',
            'description' => 'Free trial plan',
            'price' => 0,
            'billing_period' => 'monthly',
            'tokens_limit' => 100000,
            'is_active' => true,
        ]);

        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Check that subscription was auto-created
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $user->id,
            'subscription_plan_id' => $freePlan->id,
            'status' => 'active',
        ]);

        // Check that user has active subscription
        $this->assertTrue($user->hasActiveSubscription());
    }

    /**
     * Test subscription accessor returns correct subscription.
     */
    public function test_subscription_accessor_returns_active_subscription(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'tokens_used' => 500,
            'tokens_remaining' => 9500,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $userSubscription = $user->subscription;

        $this->assertNotNull($userSubscription);
        $this->assertEquals($subscription->id, $userSubscription->id);
        $this->assertEquals('active', $userSubscription->status);
    }

    /**
     * Test hasTokens method returns correct boolean.
     */
    public function test_has_tokens_method_returns_correct_boolean(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create(['tokens_limit' => 10000]);

        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'tokens_used' => 500,
            'tokens_remaining' => 9500,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        // User has 9500 tokens remaining
        $this->assertTrue($user->hasTokens(100));
        $this->assertTrue($user->hasTokens(9500));
        $this->assertFalse($user->hasTokens(10000));
    }

    /**
     * Test hasTokens returns false when no subscription.
     */
    public function test_has_tokens_returns_false_when_no_subscription(): void
    {
        $user = User::factory()->create();

        // Delete auto-created subscription
        $user->subscriptions()->delete();

        $this->assertFalse($user->hasTokens(1));
    }

    /**
     * Test user relationships.
     */
    public function test_user_has_correct_relationships(): void
    {
        $user = User::factory()->create();

        // Test subscriptions relationship
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $user->subscriptions
        );

        // Test tokenUsageLogs relationship
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $user->tokenUsageLogs
        );

        // Test payments relationship
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $user->payments
        );
    }

    /**
     * Test hasActiveSubscription method.
     */
    public function test_has_active_subscription_method(): void
    {
        $user = User::factory()->create();

        // Delete auto-created subscription
        $user->subscriptions()->delete();

        // Should return false when no subscription
        $this->assertFalse($user->hasActiveSubscription());

        // Create active subscription
        $plan = SubscriptionPlan::factory()->create();
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'tokens_used' => 0,
            'tokens_remaining' => 10000,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertTrue($user->hasActiveSubscription());
    }

    /**
     * Test user password is hashed.
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'plain-text-password',
        ]);

        $this->assertNotEquals('plain-text-password', $user->password);
        $this->assertTrue(\Hash::check('plain-text-password', $user->password));
    }

    /**
     * Test user can create API token.
     */
    public function test_user_can_create_api_token(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token');

        $this->assertNotNull($token);
        $this->assertInstanceOf(\Laravel\Sanctum\NewAccessToken::class, $token);
        $this->assertEquals('test-token', $token->accessToken->name);
    }

    /**
     * Test user token usage logs relationship.
     */
    public function test_user_token_usage_logs_relationship(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->tokenUsageLogs()
        );
    }
}
