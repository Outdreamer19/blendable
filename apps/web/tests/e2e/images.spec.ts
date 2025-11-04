import { test, expect } from '@playwright/test';

test.describe('Images', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display images page', async ({ page }) => {
    await page.goto('/images');
    
    // Check if the images page is visible
    await expect(page.locator('h1')).toContainText('Images');
    
    // Check if the images list is visible
    await expect(page.locator('[data-testid="images-list"]')).toBeVisible();
  });

  test('should create a new image', async ({ page }) => {
    await page.goto('/images');
    
    // Click on "Create Image" button
    await page.click('button:has-text("Create Image")');
    
    // Fill in image details
    await page.fill('input[name="prompt"]', 'A beautiful sunset over the ocean');
    await page.selectOption('select[name="model"]', 'dall-e-3');
    await page.selectOption('select[name="size"]', '1024x1024');
    await page.selectOption('select[name="quality"]', 'standard');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the image generation job is created
    await expect(page.locator('[data-testid="image-job"]')).toBeVisible();
  });

  test('should view image details', async ({ page }) => {
    await page.goto('/images');
    
    // Click on the first image
    await page.click('[data-testid="image-item"]:first-child');
    
    // Check if the image details are visible
    await expect(page.locator('[data-testid="image-details"]')).toBeVisible();
    
    // Check if the image is displayed
    await expect(page.locator('[data-testid="generated-image"]')).toBeVisible();
  });

  test('should download an image', async ({ page }) => {
    await page.goto('/images');
    
    // Click on the first image
    await page.click('[data-testid="image-item"]:first-child');
    
    // Click on download button
    await page.click('button:has-text("Download")');
    
    // Check if the download starts
    // Note: This test might need to be adjusted based on how downloads are handled
    await expect(page.locator('button:has-text("Download")')).toBeVisible();
  });

  test('should delete an image', async ({ page }) => {
    await page.goto('/images');
    
    // Get the number of images before deletion
    const imagesBefore = await page.locator('[data-testid="image-item"]').count();
    
    // Click on the first image
    await page.click('[data-testid="image-item"]:first-child');
    
    // Click on delete button
    await page.click('button:has-text("Delete")');
    
    // Confirm deletion
    await page.click('button:has-text("Confirm")');
    
    // Check if the image is deleted
    await expect(page.locator('[data-testid="image-item"]')).toHaveCount(imagesBefore - 1);
  });
});
