# CulturalTranslate Platform

**AI-Powered Translation Platform with Complete Admin Panel**

A comprehensive Laravel-based translation platform powered by AI, featuring a full Filament v3 admin panel with 35+ resources for managing all aspects of the platform.

---

## Features

### Core Features
- Multi-Language Support (13 languages)
- AI-Powered Translation (OpenAI, Google, DeepL)
- Translation Memory & Glossary
- File Upload & Processing
- Real-time Analytics

### Admin Panel (Filament v3)
- 35+ Resources with full CRUD
- Custom Dark Theme
- AI Chat Interface
- API Management
- Comprehensive Logging

### Business Features
- Subscription Management
- Payment Processing
- Invoice Generation
- Usage Tracking
- Coupon System

---

## Quick Start

```bash
# Install dependencies
composer install && npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Start server
php artisan serve
```

**Default Admin**: admin@culturaltranslate.com / Admin2024!

---

## Admin Resources (35+)

### Content Management (7)
Languages, Services, Features, Integrations, Blog, Testimonials, FAQs

### User Management (3)
Users, Companies, Company Services

### Subscription Management (6)
Plans, Subscriptions, Payments, Invoices, Plan Features, Coupons

### API Management (4)
API Providers, AI Models, API Keys, Webhooks

### Translation Management (5)
Translations, Translation Memory, Glossaries, File Uploads, AI Chat

### Communication (3)
Notifications, Support Tickets, Email Templates

### Analytics & Logs (4)
Usage Logs, Activity Logs, API Request Logs, Audit Logs

### Website Settings (3)
Platform Settings, Footer Links, Social Links

---

## API Example

```bash
curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, world!",
    "source_language": "en",
    "target_language": "ar"
  }'
```

---

## Technology Stack

- Laravel 11.x
- Filament v3
- Livewire & Alpine.js
- Tailwind CSS
- SQLite/MySQL

---

## Project Structure

```
app/Filament/Resources/     # 35+ Admin Resources
app/Models/                 # Eloquent Models
database/migrations/        # Database Schema
resources/views/            # Blade Templates
```

---

## License

Proprietary Software. All rights reserved.

---

**Built with ❤️ by CulturalTranslate Team**

For Laravel documentation, see [README.laravel.md](README.laravel.md)
