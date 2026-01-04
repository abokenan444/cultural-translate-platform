# Testing Documentation

**Date:** 2026-01-04
**Platform:** CulturalTranslate - AI Translation Platform
**Version:** v2.0 with Deep Learning System

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Test Structure](#test-structure)
3. [Unit Tests](#unit-tests)
4. [Feature Tests](#feature-tests)
5. [Running Tests](#running-tests)
6. [Code Coverage](#code-coverage)
7. [Test Database](#test-database)

---

## 🎯 Overview

This document outlines the testing strategy and implementation for the CulturalTranslate Platform. We use **PHPUnit** with Laravel's testing framework to ensure code quality and prevent regressions.

### Testing Goals

- ✅ Ensure all critical functionality works correctly
- ✅ Prevent regressions when adding new features
- ✅ Validate business logic and data integrity
- ✅ Test API endpoints and authentication
- ✅ Verify error handling and edge cases

---

## 📂 Test Structure

```
tests/
├── Unit/                    # Unit tests for models, services, utilities
│   ├── UserModelTest.php
│   └── ...
├── Feature/                 # Feature tests for controllers, API endpoints
│   ├── DashboardApiTest.php
│   └── ...
└── TestCase.php            # Base test class
```

---

## 🧪 Unit Tests

Unit tests focus on testing individual components in isolation.

### UserModelTest.php

Tests for the User model functionality.

**Test Cases:**

1. **test_user_has_api_tokens_trait()**
   - Verifies that User model has HasApiTokens trait for Sanctum authentication

2. **test_free_subscription_auto_created_on_user_registration()**
   - Ensures new users automatically get a 14-day free trial subscription

3. **test_subscription_accessor_returns_active_subscription()**
   - Tests that `$user->subscription` accessor returns the active subscription

4. **test_has_tokens_method_returns_correct_boolean()**
   - Validates token checking logic with various scenarios

5. **test_has_tokens_returns_false_when_no_subscription()**
   - Edge case: User without subscription should not have tokens

6. **test_user_has_correct_relationships()**
   - Verifies all Eloquent relationships are properly defined

7. **test_has_active_subscription_method()**
   - Tests subscription status checking

8. **test_user_password_is_hashed()**
   - Security: Ensures passwords are properly hashed

9. **test_user_can_create_api_token()**
   - Tests Sanctum token generation

10. **test_user_token_usage_logs_relationship()**
    - Validates relationship type for token usage logs

**Example Test:**

```php
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

    $this->assertTrue($user->hasTokens(100));
    $this->assertTrue($user->hasTokens(9500));
    $this->assertFalse($user->hasTokens(10000));
}
```

---

## 🎨 Feature Tests

Feature tests validate complete user flows and API endpoints.

### DashboardApiTest.php

Tests for Dashboard API endpoints (`/api/dashboard/*`).

**Test Cases:**

1. **test_get_user_requires_authentication()**
   - Ensures unauthenticated requests return 401

2. **test_get_user_returns_user_data()**
   - Validates user data retrieval for authenticated users

3. **test_get_stats_returns_statistics()**
   - Tests dashboard statistics calculation

4. **test_get_subscription_returns_subscription_data()**
   - Verifies subscription data endpoint

5. **test_get_subscription_returns_404_when_no_subscription()**
   - Edge case: Missing subscription should return 404

6. **test_usage_percentage_calculation_is_correct()**
   - Validates percentage calculation: `(tokens_used / tokens_limit) * 100`

7. **test_usage_percentage_handles_zero_tokens_limit()**
   - Edge case: Division by zero protection

8. **test_get_usage_data_returns_usage_logs()**
   - Tests usage logs endpoint

9. **test_get_languages_data_returns_language_stats()**
   - Validates language statistics

10. **test_get_history_returns_translation_history()**
    - Tests translation history endpoint

11. **test_get_projects_returns_projects()**
    - Tests projects endpoint (placeholder)

**Example Test:**

```php
public function test_usage_percentage_handles_zero_tokens_limit(): void
{
    // Create plan with zero tokens
    $zeroPlan = SubscriptionPlan::create([
        'name' => 'Zero Plan',
        'slug' => 'zero',
        'tokens_limit' => 0,
        'is_active' => true,
    ]);

    $userWithZero = User::factory()->create();
    UserSubscription::create([
        'user_id' => $userWithZero->id,
        'subscription_plan_id' => $zeroPlan->id,
        'status' => 'active',
        'tokens_used' => 0,
        'tokens_remaining' => 0,
    ]);

    Sanctum::actingAs($userWithZero);
    $response = $this->getJson('/api/dashboard/stats');

    // Should return 0.0 without crashing
    $response->assertStatus(200)
        ->assertJsonPath('data.usage_percentage', 0.0);
}
```

---

## 🚀 Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test tests/Unit/UserModelTest.php
php artisan test tests/Feature/DashboardApiTest.php
```

### Run Specific Test Method

```bash
php artisan test --filter=test_user_has_api_tokens_trait
```

### Run Tests with Coverage

```bash
php artisan test --coverage
```

### Run Tests in Parallel

```bash
php artisan test --parallel
```

---

## 📊 Code Coverage

We aim for **80%+ code coverage** on critical components.

### Checking Coverage

```bash
php artisan test --coverage --min=80
```

### Coverage Report (HTML)

```bash
php artisan test --coverage-html coverage-report
```

Open `coverage-report/index.html` in your browser.

---

## 🗄️ Test Database

Tests use a separate SQLite database to avoid affecting production data.

### Configuration

In `phpunit.xml`:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

### Database Refresh

All tests use `RefreshDatabase` trait:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    // Tests run with a fresh database each time
}
```

---

## ✅ Testing Best Practices

### 1. Use Descriptive Test Names

```php
// ✅ GOOD
public function test_user_with_expired_subscription_cannot_translate()

// ❌ BAD
public function test_subscription()
```

### 2. Arrange-Act-Assert Pattern

```php
public function test_example()
{
    // Arrange: Set up test data
    $user = User::factory()->create();

    // Act: Perform the action
    $result = $user->hasTokens(100);

    // Assert: Verify the result
    $this->assertTrue($result);
}
```

### 3. Test Edge Cases

- Empty data
- Null values
- Division by zero
- Missing relationships
- Expired subscriptions
- Deleted records

### 4. Use Factories

```php
// ✅ Use factories for test data
$user = User::factory()->create();
$plan = SubscriptionPlan::factory()->create();

// ❌ Avoid manual creation
$user = new User();
$user->name = 'Test';
// ...
```

### 5. Test Both Success and Failure

```php
public function test_translation_succeeds_with_valid_data()
{
    // Test successful translation
}

public function test_translation_fails_with_insufficient_tokens()
{
    // Test failure case
}
```

---

## 🔧 Continuous Integration

### GitHub Actions

Add to `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v2

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'

      - name: Install Dependencies
        run: composer install

      - name: Run Tests
        run: php artisan test --coverage --min=80
```

---

## 📝 Next Steps

1. **Add More Unit Tests**
   - SubscriptionPlan model
   - Translation model
   - Payment model
   - Company model

2. **Add More Feature Tests**
   - Translation API endpoints
   - Authentication endpoints
   - Subscription management
   - Payment processing

3. **Add Integration Tests**
   - OpenAI API integration
   - File storage
   - Email sending

4. **Add Browser Tests**
   - Laravel Dusk for E2E testing
   - User registration flow
   - Translation workflow
   - Dashboard interactions

---

## 🎓 Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Factories](https://laravel.com/docs/database-testing#defining-model-factories)
- [Laravel Sanctum Testing](https://laravel.com/docs/sanctum#testing)

---

**Last Updated:** 2026-01-04
**Status:** ✅ Active Testing Infrastructure
