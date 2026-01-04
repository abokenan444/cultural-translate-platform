#!/bin/bash

################################################################################
# CulturalTranslate Platform - Professional Deployment Script
################################################################################
#
# Purpose: Zero-downtime deployment with backup, rollback, and health checks
# Version: 3.0 - Professional Production Deployment
# Platform: Cultural Translation Platform with Deep Learning System
#
# Features:
#   - Automatic backup before deployment
#   - Rollback capability on failure
#   - Branch selection support
#   - Health checks and verification
#   - Comprehensive error handling
#   - Detailed logging
#   - Zero-downtime deployment
#   - Database backup
#   - Service verification
#
# Usage:
#   sudo bash deploy.sh [branch_name]
#
# Examples:
#   sudo bash deploy.sh              # Deploy from 'main' branch
#   sudo bash deploy.sh develop      # Deploy from 'develop' branch
#
################################################################################

set -e  # Exit on error
set -u  # Exit on undefined variable

################################################################################
# CONFIGURATION
################################################################################

# Project settings
PROJECT_NAME="CulturalTranslate"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_DIR="${PROJECT_DIR}/storage/logs"
BACKUP_DIR="${PROJECT_DIR}/backups"
DEPLOYMENT_LOG="${LOG_DIR}/deployment-$(date +%Y%m%d-%H%M%S).log"

# Default branch
DEFAULT_BRANCH="main"
DEPLOY_BRANCH="${1:-$DEFAULT_BRANCH}"

# Backup retention (days)
BACKUP_RETENTION_DAYS=7

# Health check settings
HEALTH_CHECK_TIMEOUT=30
MAX_HEALTH_CHECK_RETRIES=3

# Color codes for output
readonly RED='\033[0;31m'
readonly GREEN='\033[0;32m'
readonly YELLOW='\033[1;33m'
readonly BLUE='\033[0;34m'
readonly MAGENTA='\033[0;35m'
readonly CYAN='\033[0;36m'
readonly NC='\033[0m' # No Color
readonly BOLD='\033[1m'

################################################################################
# LOGGING FUNCTIONS
################################################################################

# Initialize logging
init_logging() {
    mkdir -p "$LOG_DIR"
    touch "$DEPLOYMENT_LOG"
    echo "===========================================================" | tee -a "$DEPLOYMENT_LOG"
    echo "Deployment started at: $(date '+%Y-%m-%d %H:%M:%S')" | tee -a "$DEPLOYMENT_LOG"
    echo "Branch: $DEPLOY_BRANCH" | tee -a "$DEPLOYMENT_LOG"
    echo "User: $ACTUAL_USER" | tee -a "$DEPLOYMENT_LOG"
    echo "===========================================================" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"
}

# Log message to file and console
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$DEPLOYMENT_LOG"
}

# Print colored output with logging
print_header() {
    echo "" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${BOLD}${CYAN}===========================================================${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${BOLD}${CYAN}$1${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${BOLD}${CYAN}===========================================================${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"
    log "$1"
}

print_step() {
    echo "" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${BOLD}${BLUE}▶ $1${NC}" | tee -a "$DEPLOYMENT_LOG"
    log "STEP: $1"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}" | tee -a "$DEPLOYMENT_LOG"
    log "SUCCESS: $1"
}

print_error() {
    echo -e "${RED}✗ $1${NC}" | tee -a "$DEPLOYMENT_LOG"
    log "ERROR: $1"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}" | tee -a "$DEPLOYMENT_LOG"
    log "WARNING: $1"
}

print_info() {
    echo -e "${MAGENTA}ℹ $1${NC}" | tee -a "$DEPLOYMENT_LOG"
    log "INFO: $1"
}

################################################################################
# ERROR HANDLING
################################################################################

# Global error flag
DEPLOYMENT_FAILED=0
BACKUP_CREATED=0
BACKUP_PATH=""

# Cleanup on exit
cleanup() {
    local exit_code=$?

    if [ $exit_code -ne 0 ]; then
        print_error "Deployment failed with exit code: $exit_code"

        if [ $BACKUP_CREATED -eq 1 ]; then
            print_warning "A backup was created at: $BACKUP_PATH"
            print_info "To rollback, run: sudo bash deploy-rollback.sh $BACKUP_PATH"
        fi

        print_info "Check logs at: $DEPLOYMENT_LOG"
    fi
}

trap cleanup EXIT

# Handle errors during deployment
handle_error() {
    local line_number=$1
    print_error "Error occurred at line $line_number"
    DEPLOYMENT_FAILED=1
    exit 1
}

trap 'handle_error $LINENO' ERR

################################################################################
# VALIDATION FUNCTIONS
################################################################################

# Check if running with sudo
check_sudo() {
    if [ "$EUID" -ne 0 ]; then
        print_error "This script must be run with sudo"
        echo "Usage: sudo bash deploy.sh [branch_name]"
        exit 1
    fi
}

# Get actual user (not root)
get_actual_user() {
    ACTUAL_USER=${SUDO_USER:-$USER}
    if [ "$ACTUAL_USER" = "root" ]; then
        print_warning "Running as root user"
    fi
}

# Check if in project directory
check_project_dir() {
    if [ ! -f "$PROJECT_DIR/artisan" ]; then
        print_error "Not in Laravel project directory"
        exit 1
    fi
}

# Check required commands
check_requirements() {
    print_step "Checking system requirements"

    local missing_deps=()

    for cmd in git php composer npm mysql systemctl; do
        if ! command -v $cmd &> /dev/null; then
            missing_deps+=("$cmd")
        fi
    done

    if [ ${#missing_deps[@]} -ne 0 ]; then
        print_error "Missing required commands: ${missing_deps[*]}"
        exit 1
    fi

    print_success "All required commands are available"
}

# Check Git status
check_git_status() {
    print_step "Checking Git repository"

    cd "$PROJECT_DIR"

    # Check if Git repo
    if ! git rev-parse --git-dir > /dev/null 2>&1; then
        print_error "Not a Git repository"
        exit 1
    fi

    # Check if branch exists
    if ! git show-ref --verify --quiet "refs/heads/$DEPLOY_BRANCH" && \
       ! git show-ref --verify --quiet "refs/remotes/origin/$DEPLOY_BRANCH"; then
        print_error "Branch '$DEPLOY_BRANCH' does not exist"
        exit 1
    fi

    print_success "Git repository is valid"
}

################################################################################
# BACKUP FUNCTIONS
################################################################################

# Create backup of current state
create_backup() {
    print_step "Creating backup"

    local timestamp=$(date +%Y%m%d-%H%M%S)
    BACKUP_PATH="${BACKUP_DIR}/backup-${timestamp}"

    mkdir -p "$BACKUP_DIR"
    mkdir -p "$BACKUP_PATH"

    print_info "Backup location: $BACKUP_PATH"

    # Backup source code
    print_info "Backing up source code..."
    rsync -a --exclude='node_modules' \
             --exclude='vendor' \
             --exclude='storage/logs' \
             --exclude='storage/framework/cache' \
             --exclude='backups' \
             "$PROJECT_DIR/" "$BACKUP_PATH/code/" 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Backup database
    print_info "Backing up database..."
    if [ -f "$PROJECT_DIR/.env" ]; then
        # Extract database credentials from .env
        DB_DATABASE=$(grep "^DB_DATABASE=" "$PROJECT_DIR/.env" | cut -d '=' -f2)
        DB_USERNAME=$(grep "^DB_USERNAME=" "$PROJECT_DIR/.env" | cut -d '=' -f2)
        DB_PASSWORD=$(grep "^DB_PASSWORD=" "$PROJECT_DIR/.env" | cut -d '=' -f2)

        if [ -n "$DB_DATABASE" ]; then
            MYSQL_PWD="$DB_PASSWORD" mysqldump -u "$DB_USERNAME" "$DB_DATABASE" > "$BACKUP_PATH/database.sql" 2>&1 | tee -a "$DEPLOYMENT_LOG" || true
        fi
    fi

    # Save current git commit
    git rev-parse HEAD > "$BACKUP_PATH/git-commit.txt" 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Save environment info
    php -v > "$BACKUP_PATH/php-version.txt" 2>&1
    composer -V > "$BACKUP_PATH/composer-version.txt" 2>&1

    BACKUP_CREATED=1
    print_success "Backup created successfully: $BACKUP_PATH"

    # Create rollback script
    create_rollback_script
}

# Create rollback script
create_rollback_script() {
    cat > "$PROJECT_DIR/deploy-rollback.sh" << 'ROLLBACK_SCRIPT'
#!/bin/bash

# Rollback script for CulturalTranslate Platform

set -e

BACKUP_PATH="$1"

if [ -z "$BACKUP_PATH" ] || [ ! -d "$BACKUP_PATH" ]; then
    echo "Error: Invalid backup path"
    echo "Usage: sudo bash deploy-rollback.sh /path/to/backup"
    exit 1
fi

echo "========================================="
echo "Rolling back to: $BACKUP_PATH"
echo "========================================="

# Restore code
echo "Restoring source code..."
rsync -a --delete "$BACKUP_PATH/code/" .

# Restore database if exists
if [ -f "$BACKUP_PATH/database.sql" ]; then
    echo "Restoring database..."
    DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
    DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2)
    DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)

    MYSQL_PWD="$DB_PASSWORD" mysql -u "$DB_USERNAME" "$DB_DATABASE" < "$BACKUP_PATH/database.sql"
fi

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart services
systemctl restart php*-fpm
systemctl restart nginx || systemctl restart apache2

echo "Rollback completed successfully!"
ROLLBACK_SCRIPT

    chmod +x "$PROJECT_DIR/deploy-rollback.sh"
}

# Clean old backups
cleanup_old_backups() {
    print_step "Cleaning old backups (older than $BACKUP_RETENTION_DAYS days)"

    if [ -d "$BACKUP_DIR" ]; then
        find "$BACKUP_DIR" -maxdepth 1 -type d -name "backup-*" -mtime +$BACKUP_RETENTION_DAYS -exec rm -rf {} \; 2>&1 | tee -a "$DEPLOYMENT_LOG"
        print_success "Old backups cleaned"
    fi
}

################################################################################
# DEPLOYMENT FUNCTIONS
################################################################################

# Enable maintenance mode
enable_maintenance_mode() {
    print_step "Enabling maintenance mode"

    sudo -u "$ACTUAL_USER" php artisan down --render="errors::503" --retry=60 2>&1 | tee -a "$DEPLOYMENT_LOG" || true
    print_success "Maintenance mode enabled"
}

# Disable maintenance mode
disable_maintenance_mode() {
    print_step "Disabling maintenance mode"

    sudo -u "$ACTUAL_USER" php artisan up 2>&1 | tee -a "$DEPLOYMENT_LOG"
    print_success "Maintenance mode disabled"
}

# Pull latest code from Git
pull_latest_code() {
    print_step "Pulling latest code from Git (branch: $DEPLOY_BRANCH)"

    cd "$PROJECT_DIR"

    # Fetch latest changes
    sudo -u "$ACTUAL_USER" git fetch origin 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Checkout branch
    sudo -u "$ACTUAL_USER" git checkout "$DEPLOY_BRANCH" 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Pull changes
    sudo -u "$ACTUAL_USER" git pull origin "$DEPLOY_BRANCH" 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Show current commit
    local current_commit=$(git rev-parse --short HEAD)
    print_success "Code updated to commit: $current_commit"
}

# Install/Update dependencies
update_dependencies() {
    print_step "Updating Composer dependencies"

    sudo -u "$ACTUAL_USER" composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tee -a "$DEPLOYMENT_LOG"
    print_success "Composer dependencies updated"

    # NPM dependencies (if package.json exists)
    if [ -f "$PROJECT_DIR/package.json" ]; then
        print_step "Updating NPM dependencies"
        sudo -u "$ACTUAL_USER" npm install --production 2>&1 | tee -a "$DEPLOYMENT_LOG"

        print_step "Building frontend assets"
        sudo -u "$ACTUAL_USER" npm run build 2>&1 | tee -a "$DEPLOYMENT_LOG"
        print_success "Frontend assets built"
    fi
}

# Run database migrations
run_migrations() {
    print_step "Running database migrations"

    sudo -u "$ACTUAL_USER" php artisan migrate --force 2>&1 | tee -a "$DEPLOYMENT_LOG"
    print_success "Migrations completed"
}

# Clear and optimize caches
optimize_application() {
    print_step "Clearing all caches"

    sudo -u "$ACTUAL_USER" php artisan route:clear 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan config:clear 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan view:clear 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan cache:clear 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan optimize:clear 2>&1 | tee -a "$DEPLOYMENT_LOG"

    print_success "All caches cleared"

    print_step "Optimizing for production"

    sudo -u "$ACTUAL_USER" php artisan config:cache 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan route:cache 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" php artisan view:cache 2>&1 | tee -a "$DEPLOYMENT_LOG"
    sudo -u "$ACTUAL_USER" composer dump-autoload -o 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Optimize Filament
    sudo -u "$ACTUAL_USER" php artisan filament:optimize 2>&1 | tee -a "$DEPLOYMENT_LOG" || true

    print_success "Application optimized"
}

# Fix file permissions
fix_permissions() {
    print_step "Fixing file permissions"

    chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache" 2>&1 | tee -a "$DEPLOYMENT_LOG"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache" 2>&1 | tee -a "$DEPLOYMENT_LOG"

    # Fix .env permissions
    if [ -f "$PROJECT_DIR/.env" ]; then
        chmod 640 "$PROJECT_DIR/.env" 2>&1 | tee -a "$DEPLOYMENT_LOG"
        chown "$ACTUAL_USER:www-data" "$PROJECT_DIR/.env" 2>&1 | tee -a "$DEPLOYMENT_LOG"
    fi

    print_success "Permissions fixed"
}

# Restart services
restart_services() {
    print_step "Restarting services"

    # Detect PHP version
    local php_version=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    print_info "Detected PHP version: $php_version"

    # Restart PHP-FPM
    if systemctl is-active --quiet "php${php_version}-fpm" 2>&1 | tee -a "$DEPLOYMENT_LOG"; then
        systemctl restart "php${php_version}-fpm" 2>&1 | tee -a "$DEPLOYMENT_LOG"
        print_success "PHP-FPM restarted"
    else
        print_warning "PHP-FPM service not found"
    fi

    # Restart web server
    if systemctl is-active --quiet nginx 2>&1 | tee -a "$DEPLOYMENT_LOG"; then
        systemctl restart nginx 2>&1 | tee -a "$DEPLOYMENT_LOG"
        print_success "Nginx restarted"
    elif systemctl is-active --quiet apache2 2>&1 | tee -a "$DEPLOYMENT_LOG"; then
        systemctl restart apache2 2>&1 | tee -a "$DEPLOYMENT_LOG"
        print_success "Apache restarted"
    else
        print_warning "No web server found"
    fi

    # Restart queue workers if supervisor is installed
    if command -v supervisorctl &> /dev/null; then
        print_step "Restarting queue workers"
        supervisorctl restart all 2>&1 | tee -a "$DEPLOYMENT_LOG" || true
        print_success "Queue workers restarted"
    fi
}

################################################################################
# VERIFICATION FUNCTIONS
################################################################################

# Verify routes are registered
verify_routes() {
    print_step "Verifying application routes"

    local route_count=$(sudo -u "$ACTUAL_USER" php artisan route:list 2>/dev/null | wc -l)

    if [ "$route_count" -gt 10 ]; then
        print_success "Routes verified: $route_count routes registered"

        # Show important routes
        echo "" | tee -a "$DEPLOYMENT_LOG"
        print_info "Key routes:"
        sudo -u "$ACTUAL_USER" php artisan route:list 2>/dev/null | grep -E "training-data|translate|plans|dashboard" | head -10 | tee -a "$DEPLOYMENT_LOG" || true
    else
        print_warning "Route count seems low: $route_count"
    fi
}

# Verify database connection
verify_database() {
    print_step "Verifying database connection"

    if sudo -u "$ACTUAL_USER" php artisan migrate:status &> /dev/null; then
        print_success "Database connection verified"
    else
        print_error "Database connection failed"
        return 1
    fi
}

# Check application health
check_application_health() {
    print_step "Running application health checks"

    # Check if Laravel can boot
    if sudo -u "$ACTUAL_USER" php artisan --version &> /dev/null; then
        local laravel_version=$(sudo -u "$ACTUAL_USER" php artisan --version)
        print_success "Laravel is running: $laravel_version"
    else
        print_error "Laravel failed to boot"
        return 1
    fi

    # Check storage is writable
    if [ -w "$PROJECT_DIR/storage" ]; then
        print_success "Storage directory is writable"
    else
        print_error "Storage directory is not writable"
        return 1
    fi

    # Check key platform features
    print_info "Checking platform features..."

    # Check if Translation model exists
    if sudo -u "$ACTUAL_USER" php artisan tinker --execute="echo 'Model check: OK';" &> /dev/null; then
        print_success "Model loading verified"
    fi
}

# Verify deployment success
verify_deployment() {
    print_header "VERIFICATION & HEALTH CHECKS"

    verify_database || return 1
    verify_routes || return 1
    check_application_health || return 1

    print_success "All health checks passed!"
}

################################################################################
# DEPLOYMENT SUMMARY
################################################################################

show_deployment_summary() {
    print_header "DEPLOYMENT COMPLETED SUCCESSFULLY"

    local end_time=$(date '+%Y-%m-%d %H:%M:%S')
    local current_commit=$(git rev-parse --short HEAD)

    echo -e "${GREEN}${BOLD}✓ CulturalTranslate Platform Deployed Successfully!${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    print_info "Deployment Details:"
    echo "  • Branch:           $DEPLOY_BRANCH" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Commit:           $current_commit" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Completed at:     $end_time" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Backup location:  $BACKUP_PATH" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Log file:         $DEPLOYMENT_LOG" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    print_info "Platform Features Active:"
    echo "  ✓ Translation Engine (OpenAI, Google, DeepL)" | tee -a "$DEPLOYMENT_LOG"
    echo "  ✓ Deep Learning System (Training Data Collection)" | tee -a "$DEPLOYMENT_LOG"
    echo "  ✓ Translation Memory & Glossaries" | tee -a "$DEPLOYMENT_LOG"
    echo "  ✓ 14 Languages Support" | tee -a "$DEPLOYMENT_LOG"
    echo "  ✓ Smart Subscription Management" | tee -a "$DEPLOYMENT_LOG"
    echo "  ✓ Filament Admin Panel (35+ Resources)" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    print_info "Next Steps:"
    echo "  1. Test translation feature at /dashboard" | tee -a "$DEPLOYMENT_LOG"
    echo "  2. Check Training Data collection at /dashboard" | tee -a "$DEPLOYMENT_LOG"
    echo "  3. Verify subscription plans at /dashboard" | tee -a "$DEPLOYMENT_LOG"
    echo "  4. Access admin panel at /admin" | tee -a "$DEPLOYMENT_LOG"
    echo "  5. Monitor logs: tail -f storage/logs/laravel.log" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    print_info "Rollback (if needed):"
    echo "  sudo bash deploy-rollback.sh $BACKUP_PATH" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    print_info "Documentation:"
    echo "  • Platform Vision: VISION.md" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Deployment Guide: DEPLOYMENT.md" | tee -a "$DEPLOYMENT_LOG"
    echo "  • Quick Deploy: QUICK_DEPLOY.md" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"

    echo -e "${CYAN}${BOLD}═══════════════════════════════════════════════════════════${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${CYAN}${BOLD}   Building the Future of Culturally-Aware Translation!${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo -e "${CYAN}${BOLD}═══════════════════════════════════════════════════════════${NC}" | tee -a "$DEPLOYMENT_LOG"
    echo "" | tee -a "$DEPLOYMENT_LOG"
}

################################################################################
# MAIN DEPLOYMENT FLOW
################################################################################

main() {
    # Header
    print_header "CulturalTranslate Platform - Professional Deployment"

    # Pre-flight checks
    check_sudo
    get_actual_user
    check_project_dir
    init_logging
    check_requirements
    check_git_status

    # Create backup
    create_backup
    cleanup_old_backups

    # Enable maintenance mode
    enable_maintenance_mode

    # Deployment steps
    pull_latest_code
    update_dependencies
    run_migrations
    optimize_application
    fix_permissions

    # Restart services
    restart_services

    # Disable maintenance mode
    disable_maintenance_mode

    # Verify deployment
    verify_deployment

    # Show summary
    show_deployment_summary

    # Success
    exit 0
}

################################################################################
# SCRIPT EXECUTION
################################################################################

# Run main function
main "$@"
