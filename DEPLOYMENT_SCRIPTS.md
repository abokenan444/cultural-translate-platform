# 🚀 Deployment Scripts Documentation

**CulturalTranslate Platform - Professional Deployment Scripts**

This document explains all deployment scripts and their usage for the CulturalTranslate Platform.

---

## 📋 Overview

The platform includes professional deployment scripts with enterprise-grade features:

- ✅ **Automated Deployment** - Zero-downtime deployment
- ✅ **Automatic Backup** - Complete backup before each deployment
- ✅ **Rollback Support** - Quick rollback to previous version
- ✅ **Health Checks** - Automatic verification after deployment
- ✅ **Comprehensive Logging** - Detailed logs for every deployment
- ✅ **Error Handling** - Graceful error recovery
- ✅ **Multi-Branch Support** - Deploy from any Git branch

---

## 📁 Available Scripts

### 1. `deploy.sh` ⭐ (Main Deployment Script)

**Purpose:** Professional production deployment with backup and rollback capabilities

**Version:** 3.0 - Professional Production Deployment

**Features:**
- Automatic backup before deployment
- Database backup (MySQL/MariaDB)
- Source code backup
- Branch selection support
- Maintenance mode (zero-downtime)
- Dependency updates (Composer, NPM)
- Database migrations
- Cache optimization
- Permission fixes
- Service restart (PHP-FPM, Nginx/Apache)
- Health checks and verification
- Rollback script generation
- Comprehensive logging
- Error handling and recovery

---

### 2. `deploy-rollback.sh` (Auto-generated)

**Purpose:** Rollback to previous version from backup

**Auto-generated:** Created automatically during deployment

**Features:**
- Restore source code from backup
- Restore database from backup
- Clear all caches
- Restart services

---

### 3. `create_missing_pages.sh` (Utility)

**Purpose:** Create Filament resource pages for admin panel

**Usage:** For development only, not for production deployment

---

## 🔧 Usage Guide

### Basic Deployment

Deploy from the default `main` branch:

```bash
# Navigate to project directory
cd /var/www/culturaltranslate

# Run deployment script
sudo bash deploy.sh
```

### Deploy from Specific Branch

```bash
# Deploy from 'develop' branch
sudo bash deploy.sh develop

# Deploy from 'feature-branch'
sudo bash deploy.sh feature-branch

# Deploy from 'staging'
sudo bash deploy.sh staging
```

---

## 📊 Deployment Process Flow

### Phase 1: Pre-Flight Checks
1. ✅ Check sudo permissions
2. ✅ Verify project directory
3. ✅ Initialize logging
4. ✅ Check system requirements (git, php, composer, npm, mysql)
5. ✅ Validate Git repository and branch

### Phase 2: Backup Creation
1. ✅ Create timestamped backup directory
2. ✅ Backup source code (excluding node_modules, vendor)
3. ✅ Backup database (MySQL dump)
4. ✅ Save Git commit hash
5. ✅ Save PHP and Composer versions
6. ✅ Generate rollback script
7. ✅ Clean old backups (7+ days)

### Phase 3: Maintenance Mode
1. ✅ Enable Laravel maintenance mode
2. ✅ Display custom 503 page

### Phase 4: Code Update
1. ✅ Fetch latest changes from Git
2. ✅ Checkout target branch
3. ✅ Pull latest code
4. ✅ Show current commit hash

### Phase 5: Dependencies
1. ✅ Update Composer packages (production mode)
2. ✅ Update NPM packages (if package.json exists)
3. ✅ Build frontend assets (npm run build)

### Phase 6: Database
1. ✅ Run database migrations (with --force flag)

### Phase 7: Optimization
1. ✅ Clear all caches (route, config, view, cache, optimize)
2. ✅ Cache configurations (config, route, view)
3. ✅ Optimize autoloader
4. ✅ Optimize Filament

### Phase 8: Permissions
1. ✅ Fix storage and bootstrap/cache permissions
2. ✅ Fix .env file permissions
3. ✅ Set correct ownership (www-data)

### Phase 9: Services
1. ✅ Detect PHP version
2. ✅ Restart PHP-FPM
3. ✅ Restart Nginx or Apache
4. ✅ Restart queue workers (if Supervisor installed)

### Phase 10: Disable Maintenance
1. ✅ Disable Laravel maintenance mode
2. ✅ Application back online

### Phase 11: Verification
1. ✅ Verify database connection
2. ✅ Verify routes are registered
3. ✅ Check Laravel can boot
4. ✅ Check storage is writable
5. ✅ Verify model loading

### Phase 12: Summary
1. ✅ Display deployment details
2. ✅ Show active features
3. ✅ Provide next steps
4. ✅ Show rollback command

---

## 📝 Logging

### Log Location

All deployment logs are stored in:
```
storage/logs/deployment-YYYYMMDD-HHMMSS.log
```

Example:
```
storage/logs/deployment-20260104-143025.log
```

### Log Format

Each log entry includes:
- Timestamp
- Step description
- Success/Error/Warning status
- Command output

### Viewing Logs

```bash
# View latest deployment log
ls -lt storage/logs/deployment-*.log | head -1 | xargs cat

# Tail deployment log in real-time
tail -f storage/logs/deployment-*.log

# Search for errors in logs
grep ERROR storage/logs/deployment-*.log
```

---

## 💾 Backup System

### Backup Location

Backups are stored in:
```
backups/backup-YYYYMMDD-HHMMSS/
```

### Backup Contents

Each backup includes:
```
backups/backup-20260104-143025/
├── code/               # Complete source code backup
├── database.sql        # MySQL database dump
├── git-commit.txt      # Git commit hash
├── php-version.txt     # PHP version
└── composer-version.txt # Composer version
```

### Backup Retention

- Default retention: **7 days**
- Automatic cleanup of old backups
- Manual cleanup: Delete from `backups/` directory

### Backup Size

Typical backup sizes:
- Source code: ~50-200 MB (without node_modules, vendor)
- Database: ~10-100 MB (depends on data)
- Total: ~60-300 MB per backup

---

## 🔄 Rollback Procedure

### When to Rollback

Rollback if:
- ❌ Deployment failed mid-process
- ❌ Application errors after deployment
- ❌ Database migration issues
- ❌ Critical bugs introduced

### How to Rollback

#### Step 1: Identify Backup

```bash
# List available backups
ls -lt backups/

# Example output:
# backups/backup-20260104-143025/
# backups/backup-20260104-120000/
# backups/backup-20260103-180000/
```

#### Step 2: Run Rollback

```bash
# Rollback to specific backup
sudo bash deploy-rollback.sh backups/backup-20260104-143025

# Or use the command shown in deployment summary
sudo bash deploy-rollback.sh /full/path/to/backup
```

#### Step 3: Verify Rollback

```bash
# Check application status
php artisan --version

# Check current commit
git log -1 --oneline

# Test application
curl -I https://culturaltranslate.com
```

### Rollback Process

The rollback script performs:
1. ✅ Restore source code from backup
2. ✅ Restore database from SQL dump
3. ✅ Clear all caches
4. ✅ Restart PHP-FPM
5. ✅ Restart web server (Nginx/Apache)

**Duration:** ~2-5 minutes

---

## ⚠️ Error Handling

### Common Errors and Solutions

#### Error: "This script must be run with sudo"

**Solution:**
```bash
sudo bash deploy.sh
```

#### Error: "Not in Laravel project directory"

**Solution:**
```bash
cd /var/www/culturaltranslate
sudo bash deploy.sh
```

#### Error: "Branch 'xyz' does not exist"

**Solution:**
```bash
# Check available branches
git branch -a

# Deploy from correct branch
sudo bash deploy.sh correct-branch-name
```

#### Error: "Database connection failed"

**Solution:**
```bash
# Check .env file
cat .env | grep DB_

# Test MySQL connection
mysql -u username -p -h localhost database_name
```

#### Error: "PHP-FPM service not found"

**Solution:**
```bash
# Check PHP version
php -v

# Check available PHP services
systemctl list-units --type=service | grep php

# Restart correct PHP-FPM version
sudo systemctl restart php8.2-fpm
```

### Deployment Failures

If deployment fails:

1. **Check the log file**
   ```bash
   tail -100 storage/logs/deployment-*.log
   ```

2. **Review error messages**
   - Look for ERROR or FAILED messages
   - Identify which step failed

3. **Rollback if necessary**
   ```bash
   sudo bash deploy-rollback.sh backups/backup-YYYYMMDD-HHMMSS
   ```

4. **Fix the issue**
   - Resolve the error
   - Test locally if possible

5. **Re-deploy**
   ```bash
   sudo bash deploy.sh
   ```

---

## 🎯 Best Practices

### Before Deployment

1. ✅ **Test Locally** - Test all changes in local/staging environment
2. ✅ **Review Changes** - Review Git diff before deploying
3. ✅ **Backup Verification** - Ensure backup system is working
4. ✅ **Downtime Window** - Plan deployment during low-traffic hours
5. ✅ **Team Notification** - Notify team about deployment

### During Deployment

1. ✅ **Monitor Logs** - Watch deployment logs in real-time
2. ✅ **Stay Available** - Be available for quick response
3. ✅ **Don't Interrupt** - Let script complete, don't terminate mid-process

### After Deployment

1. ✅ **Verify Functionality** - Test key features
2. ✅ **Check Logs** - Review application logs for errors
3. ✅ **Monitor Performance** - Watch server metrics
4. ✅ **User Feedback** - Monitor user reports
5. ✅ **Keep Backup** - Retain backup for at least 24 hours

---

## 🔒 Security Considerations

### File Permissions

The script sets secure permissions:
- `storage/`: 775 (www-data:www-data)
- `bootstrap/cache/`: 775 (www-data:www-data)
- `.env`: 640 (user:www-data)

### Database Credentials

- Database backups are stored locally
- Backup directory should be excluded from web access
- Consider encrypting sensitive backups

### Access Control

- Only authorized users should have sudo access
- Limit SSH access to deployment servers
- Use SSH keys instead of passwords

---

## 📊 Performance Optimization

### Deployment Speed

Typical deployment times:
- Small changes (code only): **2-3 minutes**
- With migrations: **3-5 minutes**
- With heavy dependencies: **5-10 minutes**

### Reducing Deployment Time

1. **Composer cache**: Pre-download packages
2. **NPM cache**: Use npm ci instead of npm install
3. **Parallel tasks**: Run independent tasks concurrently
4. **Incremental backups**: Only backup changed files

---

## 📞 Support & Troubleshooting

### Get Help

1. **Check Logs**
   ```bash
   tail -100 storage/logs/deployment-*.log
   tail -100 storage/logs/laravel.log
   tail -50 /var/log/nginx/error.log
   ```

2. **Review Documentation**
   - [DEPLOYMENT.md](DEPLOYMENT.md) - Comprehensive deployment guide
   - [QUICK_DEPLOY.md](QUICK_DEPLOY.md) - Quick deployment steps
   - [README.md](README.md) - Platform overview

3. **Check Platform Status**
   ```bash
   # Laravel status
   php artisan --version

   # Services status
   sudo systemctl status php8.2-fpm
   sudo systemctl status nginx

   # Routes
   php artisan route:list | grep -E "training-data|translate|plans"
   ```

---

## 🎉 Deployment Checklist

Use this checklist for every deployment:

### Pre-Deployment ✅

- [ ] All changes committed to Git
- [ ] Changes pushed to repository
- [ ] Tested in local/staging environment
- [ ] Database migrations tested
- [ ] Team notified about deployment
- [ ] Backup system verified
- [ ] Low-traffic time selected

### During Deployment ✅

- [ ] SSH connected to production server
- [ ] Navigated to project directory
- [ ] Ran deployment script with correct branch
- [ ] Monitored deployment logs
- [ ] No errors occurred

### Post-Deployment ✅

- [ ] Application is accessible
- [ ] Homepage loads correctly
- [ ] Translation feature works
- [ ] Training Data system works
- [ ] Subscription system works
- [ ] Admin panel accessible
- [ ] No errors in logs
- [ ] Performance is normal
- [ ] Backup created successfully

---

## 📚 Advanced Topics

### Customizing Deployment

Edit `deploy.sh` configuration section:

```bash
# Backup retention (days)
BACKUP_RETENTION_DAYS=7

# Health check timeout
HEALTH_CHECK_TIMEOUT=30

# Default branch
DEFAULT_BRANCH="main"
```

### Running Specific Steps

For debugging, you can run individual functions:

```bash
# Source the script
source deploy.sh

# Run specific function
create_backup
verify_routes
check_application_health
```

### Notifications (Future Enhancement)

Add notification hooks:
- Slack notifications
- Email alerts
- Discord webhooks
- SMS for critical failures

---

## 🏆 Deployment Success

After successful deployment, you should have:

- ✅ **Updated Code** - Latest code from Git repository
- ✅ **Database Migrated** - All migrations applied
- ✅ **Dependencies Updated** - Latest Composer and NPM packages
- ✅ **Caches Optimized** - All caches cleared and rebuilt
- ✅ **Services Restarted** - PHP-FPM and web server restarted
- ✅ **Health Verified** - All health checks passed
- ✅ **Backup Created** - Full backup available for rollback
- ✅ **Platform Online** - Application accessible and working

---

## 🌟 Platform Features After Deployment

The deployment ensures all these features are active:

- ✅ **Translation Engine** - OpenAI, Google, DeepL integration
- ✅ **Deep Learning System** - Training data collection
- ✅ **Translation Memory** - Reuse previous translations
- ✅ **14 Languages** - Full multi-language support
- ✅ **Smart Subscriptions** - Automatic 14-day free trials
- ✅ **Filament Admin** - 35+ resources management
- ✅ **Rating System** - User feedback on translations
- ✅ **Data Export** - Export training data for ML

---

## 📖 Related Documentation

- [README.md](README.md) - Platform overview and features
- [VISION.md](VISION.md) - **Vision, strategy, and roadmap**
- [DEPLOYMENT.md](DEPLOYMENT.md) - Comprehensive deployment guide
- [QUICK_DEPLOY.md](QUICK_DEPLOY.md) - Quick deployment steps

---

**Version:** 3.0
**Last Updated:** 2026-01-04
**Maintained by:** CulturalTranslate Development Team

---

**Happy Deploying! 🚀**

*Building the Future of Culturally-Aware Translation*
