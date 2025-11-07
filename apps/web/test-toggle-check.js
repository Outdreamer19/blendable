// Quick check to verify toggle is in the Vue file
const fs = require('fs');
const content = fs.readFileSync('resources/js/Pages/Billing/Index.vue', 'utf8');

const hasToggle = content.includes('button:has-text("Monthly")') || 
                  content.includes('Monthly') && content.includes('Yearly') && content.includes('isYearly');
const hasPriceFunction = content.includes('getPlanPrice');
const hasIsYearly = content.includes('const isYearly = ref');

console.log('Toggle in template:', content.includes('Monthly') && content.includes('Yearly'));
console.log('isYearly ref defined:', hasIsYearly);
console.log('getPlanPrice function:', hasPriceFunction);
console.log('Toggle section found:', content.includes('Monthly/Yearly Toggle'));

// Find the line numbers
const lines = content.split('\n');
lines.forEach((line, i) => {
  if (line.includes('Monthly/Yearly Toggle') || 
      (line.includes('Monthly') && line.includes('button'))) {
    console.log(`Line ${i+1}: ${line.trim()}`);
  }
});
