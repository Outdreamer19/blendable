#!/bin/bash

# Laravel Forge Deployment Script
# Updated for Laravel 12 with apps/web structure

$CREATE_RELEASE()

cd $FORGE_RELEASE_DIRECTORY

# Navigate to Laravel application directory
cd apps/web

# Install PHP dependencies
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies and build assets (including SSR)
npm ci && npm run build

# Clear file-based caches (these don't require database)
# Clear config cache first to ensure fresh .env values are loaded
$FORGE_PHP artisan config:clear || true
$FORGE_PHP artisan route:clear || true
$FORGE_PHP artisan view:clear || true
# Note: We skip 'cache:clear' because it may require database connection
# if CACHE_STORE is set to 'database'. The cache will be rebuilt below.

# Optimize application (these commands may try to use cache, so we handle DB errors gracefully)
# Temporarily use file cache to avoid DB connection during cache operations
CACHE_STORE=file $FORGE_PHP artisan config:cache || true
$FORGE_PHP artisan route:cache || true
$FORGE_PHP artisan view:cache || true
$FORGE_PHP artisan event:cache || true

# Create storage symlink
$FORGE_PHP artisan storage:link

# Run database migrations (only if database is configured)
# If database is not available, migrations will be skipped and can be run manually later
$FORGE_PHP artisan migrate --force || echo "⚠️  Database migrations skipped - database may not be configured yet"

# Return to release directory root before activating
cd $FORGE_RELEASE_DIRECTORY

$ACTIVATE_RELEASE()

# Restart queue workers and Horizon
# Navigate back to app directory for artisan commands
cd $FORGE_RELEASE_DIRECTORY/apps/web
$RESTART_QUEUES()
$FORGE_PHP artisan horizon:terminate || true
