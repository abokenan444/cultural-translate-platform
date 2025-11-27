# 🚀 Deployment Guide - CulturalTranslate Platform

**تاريخ:** 27 نوفمبر 2025  
**الإصدار:** v2.0 - Deep Learning System Update

---

## 📋 نظرة عامة

تم إجراء تحديثات شاملة على المنصة تشمل:
- ✅ إصلاح نظام الترجمة والاشتراكات
- ✅ إضافة نظام التعلم العميق الكامل (Translation Memory & Training Data Collection)
- ✅ إصلاح Frontend API URLs
- ✅ تحسينات في الأداء والأمان

---

## 🔧 الأوامر المطلوبة (يجب تشغيلها على السيرفر)

### 1. الدخول إلى مجلد المشروع
```bash
cd /var/www/culturaltranslate
# أو المسار الصحيح للمشروع على السيرفر
```

### 2. تشغيل Migrations الجديدة
```bash
php artisan migrate
```

**الـ Migrations الجديدة:**
- `2025_11_27_100000_add_deep_learning_fields_to_translations.php` - إضافة حقول التعلم العميق
- `2025_11_27_110000_create_training_data_table.php` - جدول بيانات التدريب

### 3. مسح جميع أنواع الـ Cache
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### 4. إعادة تحميل Autoloader
```bash
composer dump-autoload -o
```

### 5. إعادة تشغيل خدمات السيرفر
```bash
# PHP-FPM (تأكد من الإصدار الصحيح)
sudo systemctl restart php8.2-fpm

# Nginx
sudo systemctl restart nginx

# أو Apache (إذا كنت تستخدمه)
sudo systemctl restart apache2
```

### 6. التحقق من الحالة
```bash
php artisan route:list | grep training-data
php artisan route:list | grep translate
```

---

## 📁 الملفات الجديدة المضافة

### Backend Files

#### Services
- `app/Services/TranslationService.php` - خدمة الترجمة الجديدة مع نظام حفظ البيانات

#### Models
- `app/Models/TrainingData.php` - موديل بيانات التدريب

#### Controllers
- `app/Http/Controllers/Api/V1/TrainingDataController.php` - API endpoints للتدريب

#### Migrations
- `database/migrations/2025_11_27_100000_add_deep_learning_fields_to_translations.php`
- `database/migrations/2025_11_27_110000_create_training_data_table.php`

### Frontend Files

#### Views
- `resources/views/dashboard/tabs/training-data.blade.php` - واجهة Training Data

#### JavaScript
- تحديثات على `public/js/api-client.js` - إضافة Training Data methods

---

## 🔄 الملفات المحدثة

### Backend
1. `app/Http/Controllers/Api/V1/TranslationController.php`
   - استخدام TranslationService الجديد
   - Auto-create free trial subscription
   - حفظ بيانات الترجمة للتدريب

2. `app/Models/Translation.php`
   - إضافة حقول جديدة للتعلم العميق

3. `app/Models/User.php`
   - إضافة subscription accessor
   - Auto-create subscription عند التسجيل

4. `routes/api.php`
   - إضافة Training Data routes
   - تحديث middleware

5. `app/Http/Kernel.php`
   - إضافة Session middleware إلى API

### Frontend
1. `resources/views/dashboard/tabs/subscription.blade.php`
   - تحميل Plans من API بدلاً من hardcoded

2. `public/js/api-client.js`
   - إضافة Training Data methods
   - إضافة getPlans method

---

## 🎯 الميزات الجديدة

### 1. نظام التعلم العميق (Deep Learning System)

**الوصف:** نظام متكامل لجمع وتقييم بيانات الترجمة لبناء نموذج AI خاص مستقبلاً.

**المكونات:**
- ✅ حفظ تلقائي لجميع الترجمات مع metadata
- ✅ كشف تلقائي للبيانات الحساسة
- ✅ نظام تقييم الجودة (Quality Scoring)
- ✅ واجهة لتقييم الترجمات (Rating Interface)
- ✅ إحصائيات شاملة
- ✅ تصدير البيانات (JSONL/CSV)

**API Endpoints:**
```
GET  /api/v1/training-data/recent          - الحصول على ترجمات حديثة للتقييم
POST /api/v1/training-data/{id}/rate       - تقييم ترجمة
GET  /api/v1/training-data/statistics      - إحصائيات بيانات التدريب
GET  /api/v1/training-data/export          - تصدير بيانات التدريب
POST /api/v1/training-data/bulk-approve    - الموافقة الجماعية
```

### 2. TranslationService الجديد

**الميزات:**
- ✅ اتصال حقيقي بـ OpenAI API
- ✅ حساب تلقائي للـ tokens والتكلفة
- ✅ حفظ تلقائي للترجمات
- ✅ كشف البيانات الحساسة
- ✅ تقييم جودة أولي

### 3. Auto-Create Free Trial

**الوصف:** إنشاء اشتراك تجريبي مجاني تلقائياً عند:
- التسجيل الجديد
- أول محاولة ترجمة بدون اشتراك

**المواصفات:**
- المدة: 14 يوم
- Tokens: 100,000
- مجاني تماماً

---

## 🔐 متغيرات البيئة المطلوبة

تأكد من وجود هذه المتغيرات في `.env`:

```env
# OpenAI API
OPENAI_API_KEY=sk-...

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=culturaltranslate
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Stripe (للدفع)
STRIPE_KEY=pk_...
STRIPE_SECRET=sk_...
```

---

## 📊 Database Schema Changes

### جدول `translations` - حقول جديدة:
```sql
source_text              TEXT
translated_text          TEXT
tone                     VARCHAR(50)
quality_score           DECIMAL(3,2)
user_rating             TINYINT
user_feedback           TEXT
is_approved_for_training BOOLEAN
is_in_translation_memory BOOLEAN
ml_metadata             JSON
```

### جدول `training_data` - جديد:
```sql
id                      BIGINT PRIMARY KEY
user_id                 BIGINT
project_id              BIGINT (nullable)
source_text             TEXT
source_language         VARCHAR(10)
target_language         VARCHAR(10)
translated_text         TEXT
tone                    VARCHAR(50)
context                 TEXT
industry                VARCHAR(100)
model_used              VARCHAR(50)
user_rating             TINYINT
user_feedback           TEXT
is_approved             BOOLEAN
word_count              INTEGER
tokens_used             INTEGER
is_suitable_for_training BOOLEAN
contains_sensitive_data  BOOLEAN
data_quality            ENUM('pending','good','excellent','poor')
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

---

## ✅ اختبار التحديثات

### 1. اختبار الترجمة
```bash
# من المتصفح
1. افتح https://culturaltranslate.com/dashboard
2. اذهب إلى Translate
3. أدخل نص وترجمه
4. تحقق من ظهور الترجمة
```

### 2. اختبار Training Data
```bash
# من المتصفح
1. اذهب إلى Training Data tab
2. تحقق من ظهور الإحصائيات
3. قيّم ترجمة
4. تحقق من التحديث
```

### 3. اختبار Subscription
```bash
# من المتصفح
1. اذهب إلى Subscription
2. تحقق من ظهور Available Plans
3. تحقق من Current Plan
```

### 4. اختبار API
```bash
# من Terminal
curl -X GET https://culturaltranslate.com/api/v1/plans \
  -H "Accept: application/json"

curl -X GET https://culturaltranslate.com/api/v1/training-data/statistics \
  -H "Accept: application/json" \
  -H "Cookie: laravel_session=..."
```

---

## 🐛 Troubleshooting

### المشكلة: "Class not found"
```bash
composer dump-autoload -o
php artisan optimize:clear
```

### المشكلة: "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### المشكلة: "Migration already exists"
```bash
# تحقق من الجداول الموجودة
php artisan migrate:status

# إذا كانت موجودة، تخطى
# إذا لم تكن موجودة، شغل migrate
```

### المشكلة: "500 Internal Server Error"
```bash
# تحقق من logs
tail -100 storage/logs/laravel.log

# تحقق من permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 📞 الدعم

إذا واجهت أي مشاكل:
1. تحقق من `storage/logs/laravel.log`
2. تحقق من Browser Console
3. تحقق من Network tab في DevTools

---

## 🎉 الخلاصة

بعد تشغيل جميع الأوامر أعلاه، ستكون المنصة جاهزة مع:
- ✅ نظام ترجمة محسّن
- ✅ نظام تعلم عميق كامل
- ✅ اشتراكات تلقائية
- ✅ واجهات محدثة

**الخطوة التالية:** البدء في جمع بيانات الترجمة لبناء نموذج AI خاص!
