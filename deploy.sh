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

# Clear all caches before optimizing
$FORGE_PHP artisan config:clear
$FORGE_PHP artisan route:clear
$FORGE_PHP artisan view:clear
$FORGE_PHP artisan cache:clear

# Optimize application
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan event:cache

# Create storage symlink
$FORGE_PHP artisan storage:link

# Run database migrations
$FORGE_PHP artisan migrate --force

# Return to release directory root
cd $FORGE_RELEASE_DIRECTORY

$ACTIVATE_RELEASE()

# Restart queue workers and Horizon
$RESTART_QUEUES()
$FORGE_PHP artisan horizon:terminate || true
