# Omni-AI - Multi-Model AI Chat Platform

A comprehensive AI chat platform built with Laravel 12, Vue 3, and Inertia.js that supports multiple AI models, team collaboration, and advanced features.

## 🚀 Features

### Core Features
- **Multi-Model Support**: OpenAI GPT-4o, Claude 3 Opus, Google Gemini Pro
- **Real-time Chat**: Server-Sent Events (SSE) for streaming responses
- **Model Switching**: Switch between AI models mid-conversation
- **Auto Router**: Intelligent model selection based on context
- **Personas**: Custom AI personalities with knowledge attachments
- **Team Collaboration**: Multi-user workspaces with role-based access
- **Usage Tracking**: Comprehensive usage analytics and billing
- **Image Generation**: DALL-E 3 and Stable Diffusion integration
- **File Management**: Upload and manage documents
- **Web Search**: Integrated web search capabilities
- **Prompt Library**: Save and share custom prompts
- **Import/Export**: Import chats from ChatGPT and Claude

### Advanced Features
- **Stripe Integration**: Subscription billing with usage-based pricing
- **Queue System**: Background job processing with Laravel Horizon
- **Real-time Updates**: WebSocket support for live collaboration
- **API Access**: RESTful API for third-party integrations
- **Webhook Support**: Stripe webhooks for billing events
- **Health Monitoring**: Application health checks and monitoring
- **Docker Support**: Containerized deployment with Docker Compose
- **CI/CD Pipeline**: GitHub Actions for automated testing and deployment

## 🛠️ Tech Stack

### Backend
- **Laravel 12**: PHP framework with modern features
- **PostgreSQL**: Primary database
- **Redis**: Caching and session storage
- **Laravel Horizon**: Queue management
- **Laravel Telescope**: Debug and monitoring
- **Laravel Sanctum**: API authentication
- **Laravel Cashier**: Stripe integration

### Frontend
- **Vue 3**: Progressive JavaScript framework
- **Inertia.js**: SPA-like experience without API complexity
- **TypeScript**: Type-safe JavaScript
- **Tailwind CSS**: Utility-first CSS framework
- **TipTap**: Rich text editor
- **Vite**: Fast build tool

### AI Integration
- **OpenAI API**: GPT-4o, GPT-3.5 Turbo
- **Anthropic API**: Claude 3 Opus, Claude 3 Sonnet
- **Google AI**: Gemini Pro
- **DALL-E 3**: Image generation
- **Stable Diffusion**: Alternative image generation

## 📋 Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+
- PostgreSQL 16+
- Redis 7+
- Docker (optional)

## 🚀 Quick Start

### Using Docker (Recommended)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd omni-ai
   ```

2. **Set up environment**
   ```bash
   cp apps/web/.env.example apps/web/.env
   # Edit .env with your configuration
   ```

3. **Deploy with Docker**
   ```bash
   ./deploy.sh
   ```

4. **Access the application**
   - Application: http://localhost
   - Mailpit: http://localhost:8025

### Manual Installation

1. **Install dependencies**
   ```bash
   cd apps/web
   composer install
   npm install
   ```

2. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Build frontend**
   ```bash
   npm run build
   ```

5. **Start services**
   ```bash
   php artisan serve
   ```

## 🔧 Configuration

### Environment Variables

Key environment variables to configure:

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=omni_ai
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# AI Providers
OPENAI_API_KEY=your_openai_key
ANTHROPIC_API_KEY=your_anthropic_key
GOOGLE_AI_API_KEY=your_google_key

# Stripe
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
STRIPE_WEBHOOK_SECRET=your_webhook_secret

# Queue
QUEUE_CONNECTION=redis
```

### AI Provider Setup

1. **OpenAI**: Get API key from [OpenAI Platform](https://platform.openai.com)
2. **Anthropic**: Get API key from [Anthropic Console](https://console.anthropic.com)
3. **Google AI**: Get API key from [Google AI Studio](https://makersuite.google.com)

### Stripe Setup

1. Create a Stripe account at [stripe.com](https://stripe.com)
2. Get your API keys from the dashboard
3. Set up webhooks for billing events
4. Configure subscription plans in `config/stripe.php`

## 📁 Project Structure

```
omni-ai/
├── apps/
│   └── web/                 # Laravel application
│       ├── app/
│       │   ├── Http/        # Controllers, middleware
│       │   ├── Models/      # Eloquent models
│       │   ├── Services/    # Business logic
│       │   └── LLM/         # AI client adapters
│       ├── database/
│       │   ├── migrations/  # Database migrations
│       │   └── seeders/     # Database seeders
│       ├── resources/
│       │   └── js/          # Vue.js frontend
│       └── tests/           # Test suites
├── docker/                  # Docker configuration
├── docs/                    # Documentation
└── docker-compose.yml       # Docker services
```

## 🧪 Testing

### Run Tests
```bash
cd apps/web
php artisan test
```

### Run Specific Test Suites
```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage
```

### Frontend Testing
```bash
npm run test
npm run test:e2e
```

## 🚀 Deployment

### Production Deployment

1. **Set up production environment**
   ```bash
   cp apps/web/.env.example apps/web/.env.production
   # Configure production settings
   ```

2. **Deploy with Docker**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

3. **Run migrations**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

### Manual Deployment

1. **Build for production**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Set up web server**
   - Configure Nginx/Apache
   - Set up SSL certificates
   - Configure process manager (Supervisor)

## 📊 Monitoring

### Health Checks
- Application health: `/health`
- Database status: `/health/database`
- Queue status: `/health/queue`
- Redis status: `/health/redis`

### Logs
- Application logs: `storage/logs/laravel.log`
- Queue logs: `storage/logs/horizon.log`
- Web server logs: `/var/log/nginx/`

### Metrics
- Laravel Telescope: `/telescope` (development)
- Laravel Horizon: `/horizon` (queue monitoring)

## 🔒 Security

### Authentication
- Laravel Sanctum for API authentication
- Session-based authentication for web
- Role-based access control (RBAC)

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection
- Rate limiting

### API Security
- API key authentication
- Rate limiting per user
- Request validation
- Response sanitization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

### Development Setup
```bash
git clone <your-fork>
cd omni-ai
cp apps/web/.env.example apps/web/.env
composer install
npm install
php artisan migrate
php artisan db:seed
npm run dev
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- Documentation: [docs/](docs/)
- Issues: [GitHub Issues](https://github.com/your-repo/issues)
- Discussions: [GitHub Discussions](https://github.com/your-repo/discussions)

## 🙏 Acknowledgments

- Laravel team for the amazing framework
- Vue.js team for the reactive framework
- OpenAI, Anthropic, and Google for AI APIs
- All contributors and users

---

Built with ❤️ by the Omni-AI team