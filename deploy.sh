#!/bin/bash

# CulturalTranslate Platform Deployment Script
# Version: 2.0 - Deep Learning System Update
# Date: 2025-11-27

set -e  # Exit on error

echo "========================================="
echo "CulturalTranslate Deployment Script"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}➜ $1${NC}"
}

# Check if running as root or with sudo
if [ "$EUID" -ne 0 ]; then 
    print_error "Please run with sudo: sudo bash deploy.sh"
    exit 1
fi

# Get the actual user (not root)
ACTUAL_USER=${SUDO_USER:-$USER}
print_info "Running as user: $ACTUAL_USER"

# Step 1: Pull latest changes from GitHub
print_info "Step 1: Pulling latest changes from GitHub..."
sudo -u $ACTUAL_USER git pull origin main
print_success "Git pull completed"
echo ""

# Step 2: Install/Update Composer dependencies
print_info "Step 2: Updating Composer dependencies..."
sudo -u $ACTUAL_USER composer install --no-dev --optimize-autoloader
print_success "Composer dependencies updated"
echo ""

# Step 3: Run database migrations
print_info "Step 3: Running database migrations..."
sudo -u $ACTUAL_USER php artisan migrate --force
print_success "Migrations completed"
echo ""

# Step 4: Clear all caches
print_info "Step 4: Clearing all caches..."
sudo -u $ACTUAL_USER php artisan route:clear
sudo -u $ACTUAL_USER php artisan config:clear
sudo -u $ACTUAL_USER php artisan view:clear
sudo -u $ACTUAL_USER php artisan cache:clear
sudo -u $ACTUAL_USER php artisan optimize:clear
print_success "All caches cleared"
echo ""

# Step 5: Optimize for production
print_info "Step 5: Optimizing for production..."
sudo -u $ACTUAL_USER php artisan config:cache
sudo -u $ACTUAL_USER php artisan route:cache
sudo -u $ACTUAL_USER php artisan view:cache
sudo -u $ACTUAL_USER composer dump-autoload -o
print_success "Optimization completed"
echo ""

# Step 6: Fix permissions
print_info "Step 6: Fixing file permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
print_success "Permissions fixed"
echo ""

# Step 7: Restart services
print_info "Step 7: Restarting services..."

# Detect PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
print_info "Detected PHP version: $PHP_VERSION"

# Restart PHP-FPM
if systemctl is-active --quiet php${PHP_VERSION}-fpm; then
    systemctl restart php${PHP_VERSION}-fpm
    print_success "PHP-FPM restarted"
else
    print_error "PHP-FPM service not found or not running"
fi

# Restart web server (Nginx or Apache)
if systemctl is-active --quiet nginx; then
    systemctl restart nginx
    print_success "Nginx restarted"
elif systemctl is-active --quiet apache2; then
    systemctl restart apache2
    print_success "Apache restarted"
else
    print_error "No web server found (Nginx/Apache)"
fi
echo ""

# Step 8: Verify deployment
print_info "Step 8: Verifying deployment..."
echo ""
echo "Checking routes:"
sudo -u $ACTUAL_USER php artisan route:list | grep -E "training-data|translate|plans" | head -10
echo ""

# Step 9: Show summary
echo "========================================="
echo -e "${GREEN}Deployment Completed Successfully!${NC}"
echo "========================================="
echo ""
echo "New Features Added:"
echo "  ✓ Deep Learning System"
echo "  ✓ Translation Memory"
echo "  ✓ Training Data Collection"
echo "  ✓ Auto-create Free Trial"
echo "  ✓ Fixed Subscription Plans"
echo ""
echo "Next Steps:"
echo "  1. Test translation feature"
echo "  2. Check Training Data tab"
echo "  3. Verify subscription plans"
echo "  4. Monitor logs: tail -f storage/logs/laravel.log"
echo ""
echo "Documentation: See DEPLOYMENT.md for details"
echo ""
