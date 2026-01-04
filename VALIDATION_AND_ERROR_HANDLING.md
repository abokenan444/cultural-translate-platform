# Validation & Error Handling Documentation

**Date:** 2026-01-04
**Platform:** CulturalTranslate - AI Translation Platform
**Version:** v2.0 with Deep Learning System

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Form Request Validation](#form-request-validation)
3. [Error Handling](#error-handling)
4. [Logging Strategy](#logging-strategy)
5. [API Error Responses](#api-error-responses)
6. [Best Practices](#best-practices)

---

## 🎯 Overview

This document outlines the input validation and error handling mechanisms implemented across the CulturalTranslate Platform to ensure data integrity, security, and user-friendly error messages.

### Key Features

- ✅ **Form Request Classes** - Centralized validation logic
- ✅ **Comprehensive Error Handling** - Try-catch blocks in all controllers
- ✅ **Standardized Error Responses** - Consistent API error format
- ✅ **Logging** - All errors logged for debugging and monitoring
- ✅ **User-Friendly Messages** - Clear, actionable error messages

---

## 📝 Form Request Validation

All API endpoints use Laravel Form Request classes for input validation. These provide:

- Automatic validation before controller methods execute
- Centralized validation rules
- Custom error messages
- Consistent error response format

### Available Form Requests

#### 1. TranslateRequest

**Endpoint:** `/api/v1/translate`, `/api/v2/translate`

**Validation Rules:**

```php
[
    'text' => 'required|string|max:10000',
    'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
    'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
    'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
    'preserve_formatting' => 'nullable|boolean',
    'context' => 'nullable|string|max:1000',
]
```

**Usage Example:**

```php
use App\Http\Requests\TranslateRequest;

public function translate(TranslateRequest $request)
{
    // Validation already passed
    $validated = $request->validated();

    // Process translation
    return $this->performTranslation($validated);
}
```

**Error Response Example:**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "text": ["The text to translate is required."],
        "target_lang": ["The target language must be different from the source language."]
    }
}
```

---

#### 2. RegisterRequest

**Endpoint:** `/api/register`

**Validation Rules:**

```php
[
    'name' => 'required|string|min:2|max:255',
    'email' => 'required|string|email|max:255|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
    'password_confirmation' => 'required|string|min:8',
]
```

**Custom Messages:**

- "Your name is required."
- "This email is already registered."
- "Password must be at least 8 characters long."
- "Password confirmation does not match."

---

#### 3. LoginRequest

**Endpoint:** `/api/login`

**Validation Rules:**

```php
[
    'email' => 'required|string|email',
    'password' => 'required|string|min:8',
    'remember' => 'nullable|boolean',
]
```

---

#### 4. FeedbackRequest

**Endpoint:** `/api/feedback`

**Validation Rules:**

```php
[
    'translation_id' => 'required|integer|exists:translations,id',
    'rating' => 'required|integer|min:1|max:5',
    'feedback_text' => 'nullable|string|max:1000',
    'suggested_translation' => 'nullable|string|max:5000',
    'issues' => 'nullable|array',
    'issues.*' => 'string|in:accuracy,grammar,tone,cultural,formatting,other',
]
```

---

#### 5. ImageTranslationRequest

**Endpoint:** `/api/v1/visual/image`

**Validation Rules:**

```php
[
    'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
    'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
    'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
    'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
    'extract_only' => 'nullable|boolean',
]
```

**File Restrictions:**

- Maximum size: 10MB
- Allowed formats: JPEG, PNG, JPG, GIF, WEBP

---

#### 6. VoiceTranslationRequest

**Endpoint:** `/api/v1/visual/voice`

**Validation Rules:**

```php
[
    'audio' => 'required|file|mimes:mp3,wav,ogg,m4a,flac|max:51200', // 50MB
    'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
    'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
    'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
    'output_format' => 'nullable|string|in:text,audio,both',
]
```

**File Restrictions:**

- Maximum size: 50MB
- Allowed formats: MP3, WAV, OGG, M4A, FLAC

---

#### 7. TrainingDataRequest

**Endpoint:** `/api/v1/training-data`

**Validation Rules:**

```php
[
    'source_text' => 'required|string|max:10000',
    'translated_text' => 'required|string|max:10000',
    'source_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl',
    'target_lang' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr,nl|different:source_lang',
    'tone' => 'nullable|string|in:formal,casual,technical,friendly,professional',
    'context' => 'nullable|string|max:1000',
    'quality_score' => 'nullable|numeric|min:0|max:100',
    'metadata' => 'nullable|array',
    'translation_id' => 'nullable|integer|exists:translations,id',
]
```

---

## 🛡️ Error Handling

All controllers implement comprehensive error handling with try-catch blocks.

### DashboardApiController

**Error Handling Pattern:**

```php
public function getStats(Request $request): JsonResponse
{
    try {
        $user = $request->user();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        // Check if relationships exist
        if (method_exists($user, 'translations')) {
            try {
                $translationsCount = $user->translations()->count() ?? 0;
            } catch (QueryException $e) {
                Log::warning('Failed to count translations', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Return successful response
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    } catch (Exception $e) {
        Log::error('Error fetching dashboard stats', [
            'user_id' => $request->user()?->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return $this->errorResponse('Failed to fetch dashboard statistics', 500);
    }
}
```

**Features:**

1. **Outer Try-Catch** - Catches all unexpected errors
2. **Inner Try-Catch** - Handles database query failures gracefully
3. **Method Existence Check** - Validates relationships exist before using
4. **Logging** - All errors logged with context
5. **User-Friendly Errors** - Generic messages to users, detailed logs for developers

---

### UserDashboardController

**Error Handling for Web Views:**

```php
public function index()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Safe data fetching with fallbacks
        $recentUsage = collect([]);
        if (method_exists($user, 'tokenUsageLogs')) {
            try {
                $recentUsage = $user->tokenUsageLogs()
                    ->with('userSubscription.subscriptionPlan')
                    ->latest()
                    ->take(10)
                    ->get();
            } catch (QueryException $e) {
                Log::error('Failed to fetch token usage logs', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return view('user-dashboard.index', compact('user', 'recentUsage'));
    } catch (Exception $e) {
        Log::error('Error loading dashboard', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Failed to load dashboard. Please try again.');
    }
}
```

**Features:**

1. **Graceful Degradation** - Returns empty collections on failures
2. **Flash Messages** - User-friendly error messages via session
3. **Redirect on Error** - Sends users back with error message
4. **Complete Logging** - All failures logged for debugging

---

## 📊 Logging Strategy

### Log Levels

We use appropriate log levels based on severity:

- **Log::error()** - Critical errors requiring immediate attention
- **Log::warning()** - Non-critical issues that should be reviewed
- **Log::info()** - Important informational messages
- **Log::debug()** - Detailed debugging information

### Logged Information

All error logs include:

```php
Log::error('Error message', [
    'user_id' => $user->id,          // User context
    'error' => $e->getMessage(),      // Error message
    'trace' => $e->getTraceAsString(), // Stack trace
    'request' => $request->all(),     // Request data (if applicable)
]);
```

### Log Locations

Logs are stored in `storage/logs/`:

- `laravel.log` - General application logs
- `deployment-YYYYMMDD-HHMMSS.log` - Deployment logs

### Viewing Logs

```bash
# View latest logs
tail -f storage/logs/laravel.log

# Search for errors
grep "ERROR" storage/logs/laravel.log

# Filter by user ID
grep "user_id.*123" storage/logs/laravel.log
```

---

## 🔄 API Error Responses

### Standardized Format

All API errors follow this format:

```json
{
    "success": false,
    "message": "User-friendly error message",
    "error": true
}
```

### HTTP Status Codes

- **400** - Bad Request (invalid input)
- **401** - Unauthorized (authentication required)
- **403** - Forbidden (insufficient permissions)
- **404** - Not Found (resource doesn't exist)
- **422** - Unprocessable Entity (validation failed)
- **500** - Internal Server Error (unexpected errors)

### Error Response Helper

In `DashboardApiController`:

```php
private function errorResponse(string $message, int $statusCode = 500): JsonResponse
{
    return response()->json([
        'success' => false,
        'message' => $message,
        'error' => true,
    ], $statusCode);
}
```

**Usage:**

```php
// Not found error
return $this->errorResponse('User not found', 404);

// Server error
return $this->errorResponse('Failed to fetch data', 500);

// Validation error (handled by Form Requests)
return response()->json([
    'success' => false,
    'message' => 'Validation failed',
    'errors' => $validator->errors()
], 422);
```

---

## ✅ Best Practices

### 1. Never Expose Sensitive Information

```php
// ❌ BAD - Exposes internal details
return response()->json([
    'error' => $e->getMessage(), // Could reveal database structure
    'trace' => $e->getTraceAsString() // Security risk
]);

// ✅ GOOD - Generic message to user, detailed log for developers
Log::error('Database error', [
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString()
]);

return $this->errorResponse('An error occurred. Please try again.', 500);
```

### 2. Always Validate Input

```php
// ❌ BAD - No validation
public function translate(Request $request)
{
    $text = $request->input('text');
    // Process without validation
}

// ✅ GOOD - Use Form Request
public function translate(TranslateRequest $request)
{
    $validated = $request->validated();
    // Data is validated and safe
}
```

### 3. Log Errors with Context

```php
// ❌ BAD - No context
Log::error('Translation failed');

// ✅ GOOD - Rich context for debugging
Log::error('Translation failed', [
    'user_id' => $user->id,
    'source_lang' => $request->source_lang,
    'target_lang' => $request->target_lang,
    'text_length' => strlen($request->text),
    'error' => $e->getMessage(),
]);
```

### 4. Handle Edge Cases

```php
// ✅ GOOD - Handles null, zero, and missing data
private function calculateUsagePercentage($subscription): float
{
    if (!$subscription || !$subscription->subscriptionPlan) {
        return 0.0;
    }

    $tokensLimit = $subscription->subscriptionPlan->tokens_limit;

    if (!$tokensLimit || $tokensLimit <= 0) {
        return 0.0; // Prevent division by zero
    }

    return round(($subscription->tokens_used / $tokensLimit) * 100, 1);
}
```

### 5. Provide Fallback Data

```php
// ✅ GOOD - Returns empty collection on failure
$recentUsage = collect([]);
if (method_exists($user, 'tokenUsageLogs')) {
    try {
        $recentUsage = $user->tokenUsageLogs()->latest()->get();
    } catch (QueryException $e) {
        Log::warning('Failed to fetch usage logs', ['error' => $e->getMessage()]);
        // Falls back to empty collection
    }
}
```

---

## 🔧 Monitoring & Debugging

### Error Monitoring

Consider integrating error tracking services:

- **Sentry** - Real-time error tracking
- **Bugsnag** - Error monitoring and reporting
- **Rollbar** - Error tracking with deployment tracking

### Health Checks

Implement health check endpoints:

```php
// routes/api.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'database' => DB::connection()->getDatabaseName(),
    ]);
});
```

### Performance Monitoring

Log slow queries and performance issues:

```php
DB::listen(function ($query) {
    if ($query->time > 1000) { // Queries over 1 second
        Log::warning('Slow query detected', [
            'sql' => $query->sql,
            'time' => $query->time,
            'bindings' => $query->bindings,
        ]);
    }
});
```

---

## 📚 Resources

- [Laravel Validation](https://laravel.com/docs/validation)
- [Form Request Validation](https://laravel.com/docs/validation#form-request-validation)
- [Error Handling](https://laravel.com/docs/errors)
- [Logging](https://laravel.com/docs/logging)
- [HTTP Responses](https://laravel.com/docs/responses)

---

**Last Updated:** 2026-01-04
**Status:** ✅ Production Ready
