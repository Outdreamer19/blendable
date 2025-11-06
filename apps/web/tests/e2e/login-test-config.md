# Login Page Test Configuration

## Prerequisites

Before running the login page tests, ensure you have:

1. **Laravel Server Running**: The application should be running on `http://127.0.0.1:8002`
2. **Demo User**: A test user with email `demo@blendable.com` and password `password`
3. **Node.js Dependencies**: All Playwright dependencies should be installed

## Running the Tests

### Option 1: Using the Test Runner Script
```bash
./run-login-tests.sh
```

### Option 2: Using Playwright Directly
```bash
# Run all login page tests
npx playwright test tests/e2e/login-page.spec.ts

# Run gradient background tests
npx playwright test tests/e2e/gradient-background.spec.ts

# Run with browser UI (headed mode)
npx playwright test tests/e2e/login-page.spec.ts --headed

# Run specific test
npx playwright test tests/e2e/login-page.spec.ts -g "should have animated gradient background"

# Run on all browsers
npx playwright test tests/e2e/login-page.spec.ts --project=chromium --project=firefox --project=webkit
```

## Test Coverage

The login page tests cover:

### 1. **Basic Functionality**
- Page title and heading
- Form elements (email, password, checkbox)
- Social login buttons
- Form validation
- Responsive design

### 2. **Animated Gradient Background**
- Component visibility and positioning
- SVG gradient elements
- Animation state and timing
- Performance optimizations
- Memory cleanup

### 3. **Testimonial Carousel**
- Carousel visibility and content
- Navigation buttons
- Progress indicators
- Auto-rotation functionality

### 4. **Accessibility**
- ARIA labels and attributes
- Keyboard navigation
- Color contrast
- Focus management

### 5. **Performance**
- Page load times
- Animation smoothness
- Memory usage
- Responsive behavior

## Expected Behavior

- **Gradient Background**: Should be visible and continuously animating
- **Form Elements**: Should be properly labeled and accessible
- **Testimonial Carousel**: Should auto-rotate every 5 seconds
- **Responsive Design**: Should work on mobile and desktop
- **Performance**: Page should load within 3 seconds

## Test Files

### `login-page.spec.ts`
Comprehensive tests for the login page including:
- Form functionality
- UI components
- Responsive design
- Accessibility features
- Performance metrics

### `gradient-background.spec.ts`
Specific tests for the animated gradient background:
- Animation state and timing
- SVG elements and attributes
- Performance optimizations
- Memory management
- Visual effects

## Troubleshooting

### Common Issues:

1. **Animation Not Visible**: 
   - Check if the AnimatedGradientBackground component is properly imported
   - Verify that the animation is starting in the component lifecycle

2. **Test Timeouts**: 
   - Increase timeout in test configuration
   - Check if the server is running properly

3. **Gradient Not Animating**: 
   - Verify that the animation loop is running
   - Check browser console for JavaScript errors

4. **Form Validation Issues**: 
   - Ensure form elements have proper IDs and labels
   - Check if validation is implemented correctly

## Performance Considerations

- **Animation Performance**: Tests verify that animations run smoothly
- **Memory Usage**: Tests check for proper cleanup of animation loops
- **Load Times**: Tests ensure page loads within acceptable time limits
- **Responsive Behavior**: Tests verify proper behavior across different screen sizes

## Browser Compatibility

Tests run on:
- **Chromium**: Primary testing browser
- **Firefox**: Cross-browser compatibility
- **WebKit**: Safari compatibility

## Continuous Integration

The tests are designed to run in CI environments:
- Headless mode for faster execution
- Proper cleanup of resources
- Reliable test data setup
- Cross-browser compatibility
