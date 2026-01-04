# CulturalTranslate Platform

**AI-Powered Cultural Translation Platform with Deep Learning System**

A comprehensive Laravel-based translation platform powered by AI, featuring a full Filament v3 admin panel with 35+ resources for managing all aspects of the platform. Our vision is to build a proprietary AI translation model trained on culturally-aware, high-quality translations.

---

## 🎯 Vision & Mission

**Mission:** Provide culturally-aware, AI-powered translations that preserve meaning, context, and cultural nuances across 14 languages.

**Long-term Vision:** Build a proprietary AI translation model trained on high-quality, culturally-sensitive translation data collected from real-world usage, user feedback, and expert evaluations.

---

## Features

### Core Translation Features
- **Multi-Language Support** (14 languages: EN, AR, ES, FR, DE, IT, PT, RU, ZH, JA, KO, HI, TR, NL)
- **AI-Powered Translation** (OpenAI GPT-4, Google Translate, DeepL)
- **Cultural Context Preservation** - Tone and cultural adaptation
- **Translation Memory** - Reuse previous translations
- **Glossary Management** - Maintain consistent terminology
- **File Upload & Processing** - Translate documents
- **Real-time Analytics** - Track usage and quality

### 🧠 Deep Learning System (Unique Feature)
- **Training Data Collection** - Automatic collection of all translations with metadata
- **Quality Scoring** - AI-powered initial quality assessment
- **User Rating System** - Collect feedback on translation quality
- **Sensitive Data Detection** - Automatic privacy protection
- **Translation Memory Integration** - Build searchable translation database
- **Data Export** - Export training data in JSONL/CSV for ML training
- **Statistics Dashboard** - Track data collection progress

**Goal:** Collect high-quality translation pairs to train our own cultural translation AI model

### Admin Panel (Filament v3)
- 35+ Resources with full CRUD
- Custom Dark Theme
- AI Chat Interface
- API Management
- Comprehensive Logging
- Training Data Management

### Business Features
- **Smart Subscriptions** - Automatic 14-day free trial
- Payment Processing (Stripe)
- Invoice Generation
- Usage Tracking (tokens, words, characters)
- Coupon System
- API Key Management

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

## API Examples

### Translation API
```bash
curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, world!",
    "source_language": "en",
    "target_language": "ar",
    "tone": "formal"
  }'
```

### Training Data API
```bash
# Get recent translations for rating
curl -X GET https://api.culturaltranslate.com/v1/training-data/recent \
  -H "Authorization: Bearer YOUR_API_KEY"

# Rate a translation
curl -X POST https://api.culturaltranslate.com/v1/training-data/123/rate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "rating": 5,
    "feedback": "Excellent cultural adaptation"
  }'

# Get statistics
curl -X GET https://api.culturaltranslate.com/v1/training-data/statistics \
  -H "Authorization: Bearer YOUR_API_KEY"

# Export training data
curl -X GET https://api.culturaltranslate.com/v1/training-data/export?format=jsonl \
  -H "Authorization: Bearer YOUR_API_KEY"
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

## 🗺️ Roadmap

### Phase 1: Data Collection (Current) ✅
- ✅ Implement Translation API with OpenAI integration
- ✅ Build Training Data collection system
- ✅ Create Quality Scoring mechanism
- ✅ Add User Rating interface
- ✅ Implement Data Export functionality

### Phase 2: Data Refinement (In Progress) 🔄
- 🔄 Collect 10,000+ high-quality translation pairs
- 🔄 Expert review and validation
- 🔄 Build comprehensive glossaries per industry
- ⏳ Implement active learning for data labeling

### Phase 3: Model Training (Future) 🔮
- ⏳ Train initial cultural translation model
- ⏳ Fine-tune on specialized domains (legal, medical, technical)
- ⏳ A/B testing against commercial APIs
- ⏳ Continuous improvement loop

### Phase 4: Production Model (Future) 🚀
- ⏳ Deploy proprietary AI model
- ⏳ Reduce dependency on third-party APIs
- ⏳ Cost optimization
- ⏳ Superior cultural awareness

**Current Focus:** Collecting diverse, high-quality translation data across all 14 supported languages

---

## 📚 Documentation

- [README.md](README.md) - Main documentation (this file)
- [VISION.md](VISION.md) - **Vision, strategy, and roadmap** ⭐
- [DEPLOYMENT.md](DEPLOYMENT.md) - Comprehensive deployment guide
- [QUICK_DEPLOY.md](QUICK_DEPLOY.md) - Quick deployment steps
- [README.laravel.md](README.laravel.md) - Laravel framework documentation
- [LANGUAGE_SWITCHER_FIX_REPORT.md](LANGUAGE_SWITCHER_FIX_REPORT.md) - Technical report on multi-language support
- [DEPRECATED_FILES.md](DEPRECATED_FILES.md) - List of deprecated components

---

## License

Proprietary Software. All rights reserved.

---

**Built with ❤️ by CulturalTranslate Team**

*Empowering global communication through culturally-aware AI translation*
