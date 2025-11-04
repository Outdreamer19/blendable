#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
APP_DIR="$ROOT_DIR/apps/web"

if [[ -d "$APP_DIR" && -f "$APP_DIR/artisan" ]]; then
  echo "Laravel app already exists at $APP_DIR"
  exit 0
fi

mkdir -p "$ROOT_DIR/apps"
cd "$ROOT_DIR/apps"

# Create Laravel 12 app
composer create-project laravel/laravel web "^12.0" || {
  echo "If composer is missing, install Composer first: https://getcomposer.org/"
  exit 1
}

cd "$APP_DIR"

# Require core deps (rest to be handled by Cursor)
composer require laravel/sanctum laravel/cashier spatie/laravel-permission predis/predis   league/flysystem-aws-s3-v3 spatie/browsershot phpoffice/phpword spatie/laravel-query-builder   spatie/laravel-data spatie/laravel-health spatie/laravel-settings spatie/laravel-webhook-client   spatie/laravel-webhook-server spatie/laravel-queueable-action spatie/laravel-medialibrary   sentry/sentry-laravel laravel/horizon --no-interaction

composer require --dev barryvdh/laravel-ide-helper laravel/telescope --no-interaction

# JS deps
npm i -D vue @inertiajs/vue3 @inertiajs/progress typescript tailwindcss postcss autoprefixer   @tiptap/core @tiptap/starter-kit @tiptap/extension-link pinia axios lucide-vue-next uppy chart.js dayjs zod file-saver

echo "Done. Configure .env and run: php artisan migrate --seed && php artisan serve"
