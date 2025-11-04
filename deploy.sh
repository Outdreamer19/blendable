#!/bin/bash

# Omni-AI Deployment Script
# This script handles the deployment of the Omni-AI application

set -e

echo "🚀 Starting Omni-AI deployment..."

# Check if .env file exists
if [ ! -f "apps/web/.env" ]; then
    echo "❌ .env file not found. Please create one from .env.example"
    exit 1
fi

# Load environment variables
export $(cat apps/web/.env | grep -v '^#' | xargs)

# Build and start services
echo "📦 Building and starting services..."
docker-compose -f docker-compose.prod.yml up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Seed the database
echo "🌱 Seeding the database..."
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# Clear and cache configuration
echo "⚡ Optimizing application..."
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
docker-compose -f docker-compose.prod.yml exec app chmod -R 755 /var/www/html/storage
docker-compose -f docker-compose.prod.yml exec app chmod -R 755 /var/www/html/bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Application is available at: http://localhost"
echo "📧 Mailpit is available at: http://localhost:8025"
