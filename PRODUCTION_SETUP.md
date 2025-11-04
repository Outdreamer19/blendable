# Production Setup Guide

## Environment Configuration

Create a `.env` file with the following production settings:

```bash
# Production Environment Configuration
APP_NAME="OmniAI"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

# Database (MySQL recommended for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=omni_ai_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.yourdomain.com

# Cache (Redis recommended for production)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue (Redis recommended for production)
QUEUE_CONNECTION=redis

# AI API Keys
OPENAI_API_KEY=your_openai_key_here
ANTHROPIC_API_KEY=your_anthropic_key_here
GOOGLE_API_KEY=your_google_key_here

# Stripe Configuration
STRIPE_KEY=pk_live_your_stripe_key_here
STRIPE_SECRET=sk_live_your_stripe_secret_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here

# Security
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
```

## Production Deployment Steps

1. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

2. **Run Database Migrations**
   ```bash
   php artisan migrate --force
   ```

3. **Seed Database**
   ```bash
   php artisan db:seed --force
   ```

4. **Build Frontend Assets**
   ```bash
   npm run build
   ```

5. **Optimize for Production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

6. **Set Up Queue Worker**
   ```bash
   php artisan queue:work --daemon
   ```

## Security Features Implemented

- ✅ Rate limiting on chat endpoints (60 requests/minute)
- ✅ Rate limiting on AI test endpoints (30 requests/minute)
- ✅ Security headers middleware
- ✅ Content Security Policy
- ✅ XSS protection
- ✅ CSRF protection
- ✅ SQL injection protection

## Performance Optimizations

- ✅ Database query optimization
- ✅ Eager loading for relationships
- ✅ Frontend asset optimization
- ✅ Caching configuration
- ✅ Queue system for background jobs

## Monitoring

- ✅ AI response tracking
- ✅ Error logging
- ✅ Performance monitoring
- ✅ Usage analytics
