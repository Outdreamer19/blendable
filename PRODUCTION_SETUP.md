# Production Setup Guide

## Environment Configuration

Create a `.env` file with the following production settings:

```bash
# Production Environment Configuration
APP_NAME="Blendable"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

# Database (MySQL recommended for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blendable_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

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

## Troubleshooting

### 419 CSRF Token Mismatch Error

If you're getting a 419 error when trying to log in or submit forms, check the following:

1. **Verify Session Cookie Settings** (Critical for HTTPS):
   ```bash
   SESSION_SECURE_COOKIE=true
   SESSION_DOMAIN=.blendable.app  # For blendable.app - note the leading dot
   SESSION_SAME_SITE=lax
   ```

2. **Verify APP_URL matches your domain**:
   ```bash
   APP_URL=https://blendable.app  # Must match exactly, including https://
   ```

3. **Cloudflare Configuration** (if using Cloudflare):
   - The `TrustProxies` middleware is already configured to trust all proxies
   - Ensure Cloudflare SSL/TLS mode is set to "Full" or "Full (strict)"
   - Make sure "Always Use HTTPS" is enabled in Cloudflare
   - **For Chat Streaming (502 Bad Gateway Fix):**
     - Go to Cloudflare Dashboard → Rules → Page Rules
     - Create a new page rule for: `*blendable.app/chats/*/send-message`
     - Set "Cache Level" to "Bypass"
     - Set "Disable Performance" to ON
     - Set "Disable Security" to OFF (keep security enabled)
     - **Important:** On Cloudflare Pro plan or higher, you can increase timeout:
       - Go to Network → Page Rules → Advanced
       - Add custom header: `X-Cloudflare-Timeout: 600` (10 minutes)
     - **Alternative:** If on free plan, consider upgrading or using a subdomain for chat endpoints

4. **Clear config cache** after changing .env:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```

5. **Verify your domain** - Make sure `SESSION_DOMAIN` matches your actual domain:
   - For `blendable.app` → use `.blendable.app` (with leading dot)
   - For `www.blendable.app` → use `.blendable.app` (covers both www and non-www)
   - For subdomain only → use `subdomain.blendable.app` (no leading dot)

6. **Check browser console** for cookie issues:
   - Open DevTools → Application → Cookies
   - Verify session cookie is being set
   - Check cookie domain, path, and secure flags
   - Cookie should have: Secure ✅, SameSite: Lax, Domain: .blendable.app

7. **Test with curl** to verify cookies are being set:
   ```bash
   curl -v -c cookies.txt https://blendable.app/login
   curl -v -b cookies.txt -X POST https://blendable.app/login \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","password":"test"}'
   ```
