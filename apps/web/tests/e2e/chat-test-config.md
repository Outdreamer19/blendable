# Chat AI Integration Test Configuration

## Prerequisites

Before running the chat AI integration tests, ensure you have:

1. **Laravel Server Running**: The application should be running on `http://127.0.0.1:8000`
2. **AI Model API Keys**: Configure the following environment variables:
   - `OPENAI_API_KEY` - For OpenAI models (GPT-4, GPT-3.5)
   - `ANTHROPIC_API_KEY` - For Claude models
   - `GOOGLE_AI_API_KEY` - For Google AI models

3. **Test User**: A test user with email `test@example.com` and password `password`

## Running the Tests

### Option 1: Using the Test Runner Script
```bash
./run-chat-tests.sh
```

### Option 2: Using Playwright Directly
```bash
# Run all chat tests
npx playwright test tests/e2e/ai-chat.spec.ts

# Run with browser UI (headed mode)
npx playwright test tests/e2e/ai-chat.spec.ts --headed

# Run specific test
npx playwright test tests/e2e/ai-chat.spec.ts -g "should send message and receive AI response"

# Run on all browsers
npx playwright test tests/e2e/ai-chat.spec.ts --project=chromium --project=firefox --project=webkit
```

## Test Coverage

The AI chat integration tests cover:

1. **Basic AI Response**: Math questions, factual questions
2. **Creative Tasks**: Poetry, creative writing
3. **Code Generation**: Python functions, programming examples
4. **Streaming Responses**: Long-form content generation
5. **Multiple Conversations**: Chat history maintenance
6. **Error Handling**: Empty messages, edge cases
7. **Model Switching**: Different AI models

## Expected Behavior

- Tests should wait for AI responses (up to 45-60 seconds for complex requests)
- Responses should be contextually appropriate
- Streaming should work smoothly
- Chat history should be maintained
- Error cases should be handled gracefully

## Troubleshooting

### Common Issues:

1. **Timeout Errors**: Increase timeout in test configuration
2. **API Key Issues**: Verify environment variables are set
3. **Server Not Running**: Ensure Laravel server is running on port 8000
4. **Test User Missing**: Run the test script to create the test user

### Debug Mode:
```bash
# Run with debug output
DEBUG=pw:api npx playwright test tests/e2e/ai-chat.spec.ts --headed
```

## Test Results

After running tests, check:
- Console output for test results
- `playwright-report/index.html` for detailed HTML report
- Screenshots and videos in `test-results/` directory
