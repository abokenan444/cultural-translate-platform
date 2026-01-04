# 🚀 Quick Deployment Guide

**CulturalTranslate Platform - Fast Deployment Steps**

This guide provides the fastest way to deploy updates to your production server.

---

## ⚡ Method 1: Automated Script (Recommended)

Use the automated deployment script for zero-downtime deployment:

```bash
# 1. Connect to server via SSH
ssh user@culturaltranslate.com

# 2. Navigate to project directory
cd /var/www/culturaltranslate
# Or your custom path:
# cd /path/to/your/project

# 3. Run deployment script
sudo bash deploy.sh
```

### What the script does automatically:
- ✅ **Pull** latest code from GitHub
- ✅ **Install/Update** Composer dependencies
- ✅ **Run** database migrations
- ✅ **Clear** all caches (route, config, view, application)
- ✅ **Optimize** for production
- ✅ **Fix** file permissions
- ✅ **Restart** PHP-FPM and web server services

**Duration:** ~2-3 minutes

---

## 📝 Prerequisites

Before running deployment:
- ✅ Git repository is configured
- ✅ Composer is installed
- ✅ `.env` file is properly configured
- ✅ Database credentials are correct
- ✅ You have sudo access (for service restart)

## 🔧 Method 2: Manual Deployment (Step by Step)

For more control or troubleshooting, deploy manually:

### Step 1: Update Code
```bash
# Pull latest changes from repository
git pull origin main
```

### Step 2: Update Dependencies
```bash
# Install/update Composer packages (production mode)
composer install --no-dev --optimize-autoloader
```

### Step 3: Database Migration
```bash
# Run new migrations (force flag for production)
php artisan migrate --force
```

### Step 4: Clear All Caches
```bash
# Clear application caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### Step 5: Optimize for Production
```bash
# Cache configurations for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload -o
```

### Step 6: Fix File Permissions
```bash
# Set correct ownership and permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 7: Restart Services
```bash
# Restart PHP-FPM (adjust version if needed)
sudo systemctl restart php8.2-fpm

# Restart web server
sudo systemctl restart nginx
# OR for Apache:
# sudo systemctl restart apache2
```

**Duration:** ~5-10 minutes

---

## ✅ Post-Deployment Verification

After deployment completes, verify everything works:

### 1. Check Routes
```bash
# Verify new routes are registered
php artisan route:list | grep training-data
php artisan route:list | grep translate
```

### 2. Check Application Logs
```bash
# Review recent logs for errors
tail -100 storage/logs/laravel.log
```

### 3. Test Website Access
```bash
# Open in browser
https://culturaltranslate.com
https://culturaltranslate.com/dashboard
https://culturaltranslate.com/admin
```

### 4. Check Services Status
```bash
# Verify services are running
sudo systemctl status php8.2-fpm
sudo systemctl status nginx
```

---

## 🧪 Feature Testing

Test core functionality after deployment:

### 1. Translation System
1. Navigate to **Dashboard → Translate**
2. Enter text in source language
3. Select target language and tone
4. Click **Translate**
5. ✅ Translation should appear within 2-3 seconds

### 2. Training Data Collection
1. Navigate to **Dashboard → Training Data**
2. ✅ Statistics should display (total, rated, approved)
3. Recent translations should be listed
4. Try rating a translation (1-5 stars)
5. ✅ Rating should save successfully

### 3. Subscription Management
1. Navigate to **Dashboard → Subscription**
2. ✅ Current plan should display
3. ✅ Available plans should load from database
4. ✅ Usage statistics should show

### 4. Admin Panel (Admins only)
1. Navigate to **/admin**
2. ✅ Dashboard should load
3. Try accessing **Training Data** resource
4. ✅ All 35+ resources should be accessible

---

## 🐛 Troubleshooting

### Problem: "Class not found" error
```bash
# Solution: Rebuild autoloader
composer dump-autoload -o
php artisan optimize:clear
```

### Problem: "Route not found" or 404 errors
```bash
# Solution: Clear and rebuild route cache
php artisan route:clear
php artisan route:cache
php artisan route:list  # Verify routes exist
```

### Problem: "500 Internal Server Error"
```bash
# Step 1: Check application logs
tail -100 storage/logs/laravel.log

# Step 2: Check web server logs
sudo tail -50 /var/log/nginx/error.log

# Step 3: Verify file permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Problem: Database connection errors
```bash
# Check database credentials in .env
cat .env | grep DB_

# Test database connection
php artisan migrate:status
```

### Problem: Frontend assets not loading
```bash
# Rebuild frontend assets
npm install
npm run build

# Clear browser cache and try again
```

---

## 📞 Support Resources

If you encounter issues:

1. **Application Logs:** `storage/logs/laravel.log`
2. **Web Server Logs:** `/var/log/nginx/error.log`
3. **Browser Console:** Check for JavaScript errors (F12)
4. **Network Tab:** Inspect failed API requests
5. **Comprehensive Guide:** See [DEPLOYMENT.md](DEPLOYMENT.md)

---

## 🎉 Success Checklist

After successful deployment, you should have:

- ✅ **Translation Engine** - OpenAI-powered translations working
- ✅ **Deep Learning System** - Training data collection active
- ✅ **Auto Subscriptions** - Free 14-day trials created automatically
- ✅ **Multi-Language** - All 14 languages working
- ✅ **Rating Interface** - Users can rate translations
- ✅ **Data Export** - Export training data in JSONL/CSV
- ✅ **Admin Panel** - Full access to 35+ resources

---

## 🎯 Next Steps

**Start collecting translation data to build your proprietary AI model:**

1. **Generate Translations** - Use the platform actively
2. **Rate Quality** - Have users rate translations
3. **Export Data** - Periodically export training data
4. **Analyze Patterns** - Review translation patterns and quality
5. **Train Model** - Once you have 10,000+ quality pairs

**Current Focus:** Data Collection Phase (Phase 1)

---

**Happy Deploying! 🚀**

*For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md)*
