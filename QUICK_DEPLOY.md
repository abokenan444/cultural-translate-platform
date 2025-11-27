# 🚀 Quick Deployment Guide

## للنشر السريع على السيرفر

### الطريقة الأولى: استخدام السكريبت التلقائي (موصى به)

```bash
# 1. اتصل بالسيرفر عبر SSH
ssh user@culturaltranslate.com

# 2. اذهب إلى مجلد المشروع
cd /var/www/culturaltranslate
# أو
cd /path/to/your/project

# 3. شغل السكريبت التلقائي
sudo bash deploy.sh
```

**هذا السكريبت سيقوم بكل شيء تلقائياً:**
- ✅ Pull من GitHub
- ✅ Update Composer
- ✅ Run Migrations
- ✅ Clear Caches
- ✅ Optimize
- ✅ Fix Permissions
- ✅ Restart Services

---

### الطريقة الثانية: يدوياً (خطوة بخطوة)

```bash
# 1. Pull من GitHub
git pull origin main

# 2. Update Composer
composer install --no-dev --optimize-autoloader

# 3. Run Migrations
php artisan migrate --force

# 4. Clear Caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# 5. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload -o

# 6. Fix Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 7. Restart Services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## ✅ التحقق من النجاح

بعد النشر، تحقق من:

```bash
# 1. تحقق من الـ routes
php artisan route:list | grep training-data

# 2. تحقق من الـ logs
tail -50 storage/logs/laravel.log

# 3. افتح الموقع في المتصفح
https://culturaltranslate.com/dashboard
```

---

## 🧪 اختبار الميزات الجديدة

### 1. اختبار الترجمة
1. اذهب إلى Dashboard → Translate
2. أدخل نص وترجمه
3. يجب أن تظهر الترجمة

### 2. اختبار Training Data
1. اذهب إلى Dashboard → Training Data
2. يجب أن تظهر الإحصائيات
3. قيّم ترجمة

### 3. اختبار Subscription
1. اذهب إلى Dashboard → Subscription
2. يجب أن تظهر Available Plans
3. يجب أن يظهر Current Plan

---

## 🐛 حل المشاكل

### إذا ظهر "Class not found"
```bash
composer dump-autoload -o
php artisan optimize:clear
```

### إذا ظهر "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### إذا ظهر "500 Error"
```bash
tail -100 storage/logs/laravel.log
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 📞 الدعم

إذا واجهت أي مشاكل، راجع:
- `DEPLOYMENT.md` - الدليل الشامل
- `storage/logs/laravel.log` - سجل الأخطاء
- Browser Console - أخطاء JavaScript

---

## 🎉 الخلاصة

بعد النشر الناجح، ستكون لديك:
- ✅ نظام ترجمة محسّن مع OpenAI
- ✅ نظام تعلم عميق كامل
- ✅ اشتراكات تلقائية (14 يوم مجاني)
- ✅ واجهة تقييم الترجمات
- ✅ تصدير بيانات التدريب

**ابدأ الآن في جمع بيانات الترجمة لبناء نموذج AI خاص!**
