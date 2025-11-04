<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Knowledge;
use App\Models\Persona;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class RealPersonPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspaces = Workspace::all();

        if ($workspaces->isEmpty()) {
            return;
        }

        foreach ($workspaces as $workspace) {
            $this->createAlexHormoziPersona($workspace);
        }
    }

    protected function createAlexHormoziPersona(Workspace $workspace): void
    {
        // Create Alex Hormozi persona
        $alexPersona = Persona::create([
            'workspace_id' => $workspace->id,
            'name' => 'Alex Hormozi',
            'description' => 'Serial entrepreneur, author, and business scaling expert. Built and sold multiple businesses for over $100M. Known for direct, no-nonsense approach to business scaling.',
            'system_prompt' => $this->getAlexHormoziSystemPrompt(),
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
            'is_public' => true,
            'is_active' => true,
        ]);

        // Create knowledge items for Alex Hormozi
        $this->createAlexKnowledge($workspace, $alexPersona);

        // Create and attach actions
        $this->createAlexActions($alexPersona);
    }

    protected function getAlexHormoziSystemPrompt(): string
    {
        return "You are Alex Hormozi, a serial entrepreneur who built and sold multiple businesses for over $100M. You're known for your direct, no-nonsense approach to business scaling.

YOUR BACKGROUND:
- Built and sold multiple businesses for over $100M
- Author of '100M Offers' and '100M Leads'
- Founder of Acquisition.com
- Known for helping businesses scale through better offers and lead generation

YOUR EXPERTISE:
- Offer creation and optimization
- Lead generation systems
- Business acquisition and scaling
- Direct response marketing
- Sales psychology and conversion
- Business systems and processes

YOUR COMMUNICATION STYLE:
- Direct and honest - you don't sugarcoat things
- Uses specific examples and frameworks
- Focuses on actionable, practical advice
- Challenges conventional thinking
- References your own experiences and case studies
- Uses analogies and stories to explain concepts
- Asks probing questions to understand the real problem

YOUR FRAMEWORKS:
- The Offer Formula: Dream Outcome + Time Frame + Obstacle + Risk Reversal
- The Value Ladder: Lead Magnets → Tripwires → Core Offers → Profit Maximizers
- The 4 Pillars of Business: Acquisition, Monetization, Retention, Referral
- The 3 Pillars of Offers: Value, Scarcity, Urgency

WHEN HELPING SOMEONE:
1. Ask clarifying questions about their current situation
2. Identify the real problem (often not what they think it is)
3. Provide specific, actionable frameworks
4. Use real examples from your experience
5. Challenge them to think bigger
6. Give them a clear next step
7. Focus on the fundamentals before advanced tactics

REMEMBER: You believe most businesses fail not because of lack of opportunity, but because of poor offers and weak lead generation systems. You're here to help them fix the fundamentals first.";
    }

    protected function createAlexKnowledge(Workspace $workspace, Persona $persona): void
    {
        $knowledgeItems = [
            [
                'name' => '100M Offers - Core Concepts',
                'description' => 'Key frameworks from Alex Hormozi\'s book "100M Offers"',
                'content' => $this->get100MOffersContent(),
                'type' => 'document',
                'source' => '100M Offers by Alex Hormozi',
                'metadata' => [
                    'author' => 'Alex Hormozi',
                    'book' => '100M Offers',
                    'category' => 'business_scaling',
                    'importance' => 'high',
                ],
            ],
            [
                'name' => '100M Leads - Lead Generation Systems',
                'description' => 'Lead generation frameworks and strategies',
                'content' => $this->get100MLeadsContent(),
                'type' => 'document',
                'source' => '100M Leads by Alex Hormozi',
                'metadata' => [
                    'author' => 'Alex Hormozi',
                    'book' => '100M Leads',
                    'category' => 'lead_generation',
                    'importance' => 'high',
                ],
            ],
            [
                'name' => 'Alex Hormozi YouTube Content',
                'description' => 'Key insights from Alex Hormozi\'s YouTube channel',
                'content' => $this->getYouTubeContent(),
                'type' => 'url',
                'source' => 'YouTube - Alex Hormozi',
                'metadata' => [
                    'platform' => 'YouTube',
                    'channel' => 'Alex Hormozi',
                    'category' => 'business_advice',
                    'importance' => 'medium',
                ],
            ],
            [
                'name' => 'Acquisition.com Case Studies',
                'description' => 'Real business examples from Acquisition.com portfolio',
                'content' => $this->getCaseStudiesContent(),
                'type' => 'document',
                'source' => 'Acquisition.com Portfolio',
                'metadata' => [
                    'company' => 'Acquisition.com',
                    'category' => 'case_studies',
                    'importance' => 'high',
                ],
            ],
        ];

        foreach ($knowledgeItems as $item) {
            $knowledge = Knowledge::create(array_merge($item, [
                'workspace_id' => $workspace->id,
                'is_active' => true,
                'author' => $item['metadata']['author'] ?? null,
                'category' => $item['metadata']['category'] ?? null,
                'importance_score' => $item['metadata']['importance'] === 'high' ? 8 : 6,
                'tags' => [$item['metadata']['category'] ?? 'general'],
            ]));

            // Attach to persona with appropriate weight
            $weight = $item['metadata']['importance'] === 'high' ? 1.0 : 0.8;
            $persona->attachKnowledge($knowledge, $weight);
        }
    }

    protected function createAlexActions(Persona $persona): void
    {
        $actions = [
            [
                'name' => 'Business Analysis Framework',
                'description' => 'Analyze business metrics using Alex Hormozi frameworks',
                'type' => 'data_analysis',
                'config' => [
                    'framework' => '4_pillars',
                    'metrics' => ['acquisition', 'monetization', 'retention', 'referral'],
                ],
            ],
            [
                'name' => 'Offer Optimization Tool',
                'description' => 'Evaluate and improve business offers using the Offer Formula',
                'type' => 'api_call',
                'config' => [
                    'framework' => 'offer_formula',
                    'components' => ['dream_outcome', 'time_frame', 'obstacle', 'risk_reversal'],
                ],
            ],
            [
                'name' => 'Lead Generation Audit',
                'description' => 'Audit lead generation systems and provide improvement recommendations',
                'type' => 'data_analysis',
                'config' => [
                    'framework' => 'lead_generation_audit',
                    'focus' => ['traffic', 'conversion', 'value_ladder'],
                ],
            ],
        ];

        foreach ($actions as $actionData) {
            $action = Action::create(array_merge($actionData, [
                'is_active' => true,
            ]));

            $persona->enableAction($action, $actionData['config'] ?? []);
        }
    }

    protected function get100MOffersContent(): string
    {
        return "THE OFFER FORMULA:
Dream Outcome + Time Frame + Obstacle + Risk Reversal

DREAM OUTCOME: What does the customer want to achieve?
- Be specific about the transformation
- Focus on the emotional benefit, not just the logical one
- Make it tangible and measurable

TIME FRAME: How long will it take to achieve the outcome?
- Creates urgency and sets expectations
- Should be realistic but ambitious
- Helps with pricing justification

OBSTACLE: What's preventing them from achieving this outcome?
- Identifies the real problem
- Shows you understand their pain
- Positions your solution as the answer

RISK REVERSAL: How do you remove their risk?
- Money-back guarantee
- Free trial
- Performance guarantee
- Reduces friction to purchase

THE VALUE LADDER:
1. Lead Magnets (Free) - Build trust and email list
2. Tripwires ($7-47) - First purchase, low risk
3. Core Offers ($97-997) - Main revenue driver
4. Profit Maximizers ($997+) - High-ticket items

THE 4 PILLARS OF BUSINESS:
1. ACQUISITION - How do you get customers?
2. MONETIZATION - How do you make money from them?
3. RETENTION - How do you keep them?
4. REFERRAL - How do you get them to bring others?

COMMON OFFER MISTAKES:
- Focusing on features instead of outcomes
- Not addressing the real obstacle
- Pricing too low (undervaluing the outcome)
- Not having a clear risk reversal
- Not testing and optimizing offers";
    }

    protected function get100MLeadsContent(): string
    {
        return 'LEAD GENERATION FUNDAMENTALS:

THE 3 PILLARS OF LEAD GENERATION:
1. TRAFFIC - Getting people to see your offer
2. CONVERSION - Turning viewers into leads
3. VALUE LADDER - Moving leads through your funnel

TRAFFIC SOURCES:
- Organic (SEO, content marketing)
- Paid (Facebook, Google, YouTube ads)
- Referrals (word of mouth, partnerships)
- Direct (email, SMS, retargeting)

CONVERSION OPTIMIZATION:
- Clear value proposition
- Compelling lead magnet
- Simple opt-in form
- Immediate value delivery
- Follow-up sequence

THE VALUE LADDER IN LEAD GENERATION:
1. Lead Magnet (Free) - Build trust
2. Tripwire ($7-47) - First purchase
3. Core Offer ($97-997) - Main product
4. Profit Maximizer ($997+) - High-ticket

LEAD MAGNET IDEAS:
- Free guides and checklists
- Webinars and workshops
- Free consultations
- Tools and calculators
- Templates and frameworks

CONVERSION RATE OPTIMIZATION:
- A/B test headlines
- Test different lead magnets
- Optimize form fields
- Improve page speed
- Add social proof
- Use urgency and scarcity

MEASURING SUCCESS:
- Cost per lead (CPL)
- Lead to customer conversion rate
- Customer lifetime value (CLV)
- Return on ad spend (ROAS)
- Overall business growth';
    }

    protected function getYouTubeContent(): string
    {
        return "ALEX HORMOZI YOUTUBE KEY INSIGHTS:

BUSINESS SCALING PRINCIPLES:
- 'Most businesses fail not because of lack of opportunity, but because of poor offers and weak lead generation systems.'
- 'The goal isn't to be the best in the world, it's to be the best in your market.'
- 'You don't need to be perfect, you need to be profitable.'

OFFER CREATION:
- 'Your offer is your business. Everything else is just marketing for your offer.'
- 'The best offers solve a real problem for a specific group of people.'
- 'Price is only an issue in the absence of value.'

LEAD GENERATION:
- 'You can't scale what you can't measure.'
- 'The best lead generation system is the one that works for your business.'
- 'Focus on quality over quantity when it comes to leads.'

BUSINESS ACQUISITION:
- 'Buy businesses that are already profitable, then make them more profitable.'
- 'The best acquisitions are businesses with good systems but poor marketing.'
- 'Don't buy a business you can't improve.'

MINDSET AND PHILOSOPHY:
- 'Success is not about being the smartest person in the room, it's about being the most useful.'
- 'The best business advice is often the simplest.'
- 'Focus on the fundamentals before you worry about advanced tactics.'

COMMON MISTAKES:
- 'Trying to be everything to everyone'
- 'Focusing on tactics instead of strategy'
- 'Not testing and optimizing offers'
- 'Underpricing products and services'
- 'Not having a clear value proposition'";
    }

    protected function getCaseStudiesContent(): string
    {
        return 'ACQUISITION.COM CASE STUDIES:

CASE STUDY 1: FITNESS BUSINESS
- Problem: Gym struggling with retention and pricing
- Solution: Implemented value ladder and improved offers
- Result: 300% increase in revenue in 18 months
- Key Changes: Better onboarding, tiered pricing, referral program

CASE STUDY 2: CONSULTING BUSINESS
- Problem: High-ticket consulting with low conversion rates
- Solution: Created tripwire offer and improved lead generation
- Result: 5x increase in qualified leads, 200% revenue growth
- Key Changes: Free consultation offer, better follow-up sequence

CASE STUDY 3: E-COMMERCE BUSINESS
- Problem: Low average order value and poor customer lifetime value
- Solution: Implemented upsells and subscription model
- Result: 400% increase in customer lifetime value
- Key Changes: Post-purchase upsells, subscription boxes, loyalty program

CASE STUDY 4: SERVICE BUSINESS
- Problem: Inconsistent revenue and high customer acquisition costs
- Solution: Created recurring revenue model and improved offers
- Result: 500% increase in monthly recurring revenue
- Key Changes: Subscription model, better onboarding, referral program

COMMON PATTERNS IN SUCCESSFUL TURNAROUNDS:
1. Improved offers (better value proposition)
2. Better lead generation systems
3. Implemented value ladder
4. Improved customer experience
5. Created recurring revenue streams
6. Better measurement and optimization

KEY METRICS TO TRACK:
- Customer acquisition cost (CAC)
- Customer lifetime value (CLV)
- Lead to customer conversion rate
- Average order value (AOV)
- Monthly recurring revenue (MRR)
- Churn rate';
    }
}
