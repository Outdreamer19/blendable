# Testing Guide

This document provides comprehensive information about testing the Omni-AI application.

## Test Types

### 1. Unit Tests
Unit tests test individual components in isolation.

**Location**: `tests/Unit/`
**Command**: `php artisan test --testsuite=Unit`

**Examples**:
- `ModelRouterTest.php` - Tests the model routing logic
- `StripeServiceTest.php` - Tests Stripe integration
- `ToolManagerTest.php` - Tests tool management

### 2. Feature Tests
Feature tests test the application from a user's perspective, including HTTP requests and responses.

**Location**: `tests/Feature/`
**Command**: `php artisan test --testsuite=Feature`

**Examples**:
- `ChatControllerTest.php` - Tests chat functionality
- `BillingControllerTest.php` - Tests billing features
- `ImportControllerTest.php` - Tests import functionality

### 3. End-to-End (E2E) Tests
E2E tests test the entire application flow using a real browser.

**Location**: `tests/e2e/`
**Command**: `npm run test`

**Examples**:
- `home.spec.ts` - Tests the home page
- `chat.spec.ts` - Tests chat functionality
- `billing.spec.ts` - Tests billing features

## Running Tests

### PHP Tests (Unit & Feature)

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Unit/ModelRouterTest.php

# Run with coverage
php artisan test --coverage

# Run in parallel
php artisan test --parallel
```

### E2E Tests (Playwright)

```bash
# Run all E2E tests
npm run test

# Run tests with UI
npm run test:ui

# Run tests in headed mode (see browser)
npm run test:headed

# Debug tests
npm run test:debug

# Run specific test file
npx playwright test tests/e2e/chat.spec.ts

# Run tests in specific browser
npx playwright test --project=chromium
```

## Test Configuration

### PHP Tests
- **Configuration**: `phpunit.xml`
- **Database**: Uses SQLite in-memory for tests
- **Environment**: `.env.testing`

### E2E Tests
- **Configuration**: `playwright.config.ts`
- **Browsers**: Chromium, Firefox, WebKit
- **Base URL**: `http://127.0.0.1:8000`

## Test Data

### Fixtures
Test fixtures are located in `tests/fixtures/`:

- `chatgpt-export.json` - Sample ChatGPT export
- `claude-export.json` - Sample Claude export
- `invalid-file.txt` - Invalid file for testing

### Factories
Laravel factories are used to create test data:

- `UserFactory` - Creates test users
- `TeamFactory` - Creates test teams
- `WorkspaceFactory` - Creates test workspaces
- `ChatFactory` - Creates test chats
- `MessageFactory` - Creates test messages

### Seeders
Database seeders populate test data:

- `UserSeeder` - Creates test user
- `TeamSeeder` - Creates test team
- `WorkspaceSeeder` - Creates test workspace
- `PersonaSeeder` - Creates test personas
- `PromptSeeder` - Creates test prompts

## Writing Tests

### PHP Tests

#### Unit Test Example
```php
<?php

namespace Tests\Unit;

use App\Services\StripeService;
use Tests\TestCase;

class StripeServiceTest extends TestCase
{
    public function test_calculate_usage_cost()
    {
        $service = new StripeService();
        $cost = $service->calculateUsageCost('openai', 'gpt-4o', 1000);
        
        $this->assertEquals(0.03, $cost);
    }
}
```

#### Feature Test Example
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    public function test_user_can_send_message()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/chats/1/messages', [
                'message' => 'Hello, AI!',
                'model' => 'gpt-4o'
            ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'content' => 'Hello, AI!',
            'user_id' => $user->id
        ]);
    }
}
```

### E2E Tests

#### Playwright Test Example
```typescript
import { test, expect } from '@playwright/test';

test('should send a message', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'test@example.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  
  await page.goto('/dashboard');
  await page.click('button:has-text("New Chat")');
  
  await page.fill('[data-testid="message-input"]', 'Hello, AI!');
  await page.click('[data-testid="send-button"]');
  
  await expect(page.locator('[data-testid="message"]').first())
    .toContainText('Hello, AI!');
});
```

## Test Best Practices

### 1. Test Structure
- **Arrange**: Set up test data and conditions
- **Act**: Perform the action being tested
- **Assert**: Verify the expected outcome

### 2. Test Naming
- Use descriptive test names
- Follow the pattern: `test_should_do_something_when_condition`
- Group related tests in test classes

### 3. Test Data
- Use factories for creating test data
- Keep test data minimal and focused
- Use realistic data that represents real usage

### 4. Test Isolation
- Each test should be independent
- Clean up after each test
- Don't rely on test execution order

### 5. Assertions
- Use specific assertions
- Test both positive and negative cases
- Verify side effects (database changes, API calls)

## Continuous Integration

### GitHub Actions
The CI pipeline runs:

1. **PHP Tests**: Unit and feature tests
2. **Code Quality**: PHPStan, Laravel Pint
3. **Security**: Security checker
4. **E2E Tests**: Playwright tests
5. **Build**: Frontend build

### Local Development
Before committing, run:

```bash
# PHP tests
php artisan test

# Code quality
./vendor/bin/phpstan analyse
./vendor/bin/pint --test

# E2E tests
npm run test
```

## Debugging Tests

### PHP Tests
```bash
# Run with verbose output
php artisan test --verbose

# Run specific test with debug
php artisan test --filter=test_name --verbose

# Use dd() or dump() in tests for debugging
```

### E2E Tests
```bash
# Run in headed mode
npm run test:headed

# Debug mode
npm run test:debug

# Use page.pause() in tests for debugging
```

## Performance Testing

### Load Testing
- Use tools like Apache Bench or Artillery
- Test API endpoints under load
- Monitor database performance

### E2E Performance
- Use Playwright's performance APIs
- Measure page load times
- Test with slow network conditions

## Test Coverage

### PHP Coverage
```bash
# Generate coverage report
php artisan test --coverage

# View coverage in browser
php artisan test --coverage --coverage-html=coverage
```

### E2E Coverage
- Use Playwright's coverage features
- Monitor critical user paths
- Test error scenarios

## Troubleshooting

### Common Issues

1. **Database Issues**
   - Ensure test database is properly configured
   - Run migrations before tests
   - Use database transactions for isolation

2. **E2E Test Failures**
   - Check if application is running
   - Verify test selectors are correct
   - Use page.pause() for debugging

3. **Environment Issues**
   - Check .env.testing configuration
   - Verify all required services are running
   - Ensure proper file permissions

### Getting Help
- Check test logs in `storage/logs/`
- Use Laravel Telescope for debugging
- Review Playwright test reports
- Check CI/CD pipeline logs

## Conclusion

Testing is crucial for maintaining code quality and ensuring the application works as expected. Follow these guidelines to write effective tests and maintain a robust test suite.

For more information, see:
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Playwright Documentation](https://playwright.dev/)
- [PHPUnit Documentation](https://phpunit.de/)
