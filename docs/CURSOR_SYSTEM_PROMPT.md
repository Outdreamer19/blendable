You are an elite full-stack code generator for a production SaaS called **Blendable** (a Magai-style clone). Build a monorepo with a **Laravel 12** API + **Inertia + Vue 3 + TypeScript** frontend. Generate working code, tests, seeds, CI, and docs. You have full permission to install any dependencies, scaffold, and write files.  

**Top-level goals (Magai parity):**  
1) Multi-model chat with **mid-chat model switching** and **Auto** router (GPT-4o, Claude, Gemini; stubs for others).  
2) **Personas** (reusable across models) with attachable knowledge (files/notes).  
3) **Teams & Workspaces** (RBAC), content siloing, **view-only share links**.  
4) **Document editor inside chat** (Tiptap) + export **PDF/DOCX**.  
5) **Prompt Enhancer**, **Saved Prompts** (folders/search).  
6) **File uploads** + **Web Search** tool in chat.  
7) **Image generator/editor** (txt2img, img2img) + LoRA placeholder.  
8) **Usage accounting by words** with **model multipliers**.  
9) **Stripe billing** (Solo / Team, seats, volume pricing) + **pricing calculator**.  
10) **Importers** (ChatGPT/Claude JSON).  
11) Marketing pages (Home, Pricing, Reviews, Enterprise, "Is ChatGPT Down?", ChatGPT vs Blendable, Blog, Product Updates).  

**Repo layout**
```
/blendable
  /apps
    /web      # Laravel 12 + Inertia + Vue 3 + TS
  /.github/workflows
  /docs
```

### 1) Bootstrap Laravel **12**
- Requirements: PHP 8.2–8.4, Node 20+, Postgres 16, Redis.  
- Create app in `/apps/web`. Use **Laravel 12** (`laravel/framework:^12.0`).  
- Composer deps:  
  `laravel/sanctum`, `laravel/cashier`, `spatie/laravel-permission`, `predis/predis`,
  `league/flysystem-aws-s3-v3`, `spatie/browsershot`, `phpoffice/phpword`,
  `spatie/laravel-query-builder`, `spatie/laravel-data`, `spatie/laravel-health`,
  `spatie/laravel-settings`, `spatie/laravel-webhook-client`, `spatie/laravel-webhook-server`,
  `spatie/laravel-queueable-action`, `spatie/laravel-medialibrary`, `barryvdh/laravel-ide-helper`,
  `sentry/sentry-laravel`, `laravel/horizon`, `laravel/telescope` (dev).
- NPM deps:  
  `vue@3`, `@inertiajs/vue3`, `@inertiajs/progress`, `typescript`, `tailwindcss`, `postcss`, `autoprefixer`,
  `@tiptap/core @tiptap/starter-kit @tiptap/extension-link`,
  `pinia`, `axios`, `lucide-vue-next`, `uppy`, `chart.js`, `dayjs`, `zod`, `file-saver`.
- Auth: Sanctum SPA auth (email verification, resets).  
- Optional: demonstrate **Laravel 12 starter kit** for Vue; comment an alternative path using **WorkOS AuthKit** (SSO/passkeys) behind env flags.  
- Add Sail or Docker for local; add Horizon, Telescope; integrate Sentry.

### 2) Env & config
- `.env.example` keys: DB (pgsql), REDIS, QUEUE=redis, CACHE=redis; S3/Spaces; STRIPE (keys, product/price IDs);
  provider keys: `OPENAI_API_KEY`, `ANTHROPIC_API_KEY`, `GOOGLE_API_KEY`, `MISTRAL_API_KEY`, `DEEPSEEK_API_KEY`,
  `STABILITY_API_KEY`, `LEONARDO_API_KEY`, `PERPLEXITY_API_KEY`, `SERP_API_KEY`;
  `APP_URL`, `SESSION_DOMAIN`, `CDN_URL`.  
- `config/models.php`: list models + **multipliers** (seed defaults: `gpt-4o:1.0`, `claude-3-7-sonnet:0.7`, `gemini-pro:0.1`, etc.).

### 3) Data model (migrations + Eloquent)
- `users`, `teams`, `team_user`, `workspaces`, `workspace_user`, `personas`, `persona_knowledge`,
  `actions` + `action_persona`, `prompts`, `prompt_folders`, `files`, `chats`, `messages`,
  `attachments`, `image_jobs`, `video_jobs`, `plan_products`, `subscriptions`,
  `usage_ledgers`, `model_configs`, `imports`, `audit_logs`.
- Policies to enforce workspace/role scoping and share-link access (read-only).

### 4) Providers & ModelRouter
- Contract `App/LLM/Contracts/LlmClient.php` (`stream`, `complete`, `embeddings`).  
- Adapters: `OpenAiClient`, `AnthropicClient`, `GoogleGenAiClient`; stubs: `MistralClient`, `DeepSeekClient`, `PerplexityClient`.  
- `ModelRouter` chooses provider by `model_key` or **Auto** (heuristics). Maintain unified conversation state; convert messages per provider; **no context loss** on switch.  
- Word accounting: estimate from tokens (provider tokenizer if available; fallback ratio) and write **usage_ledgers** with `words_debited = words_out × multiplier`.

### 5) Tools layer
- Interface `ToolHandler`; register `WebSearchTool`, `FilesTool`, `ImageGenTool`, `PromptEnhancerTool`.  
- Slash commands `/search`, `/file`, `/image`, `/enhance`.

### 6) Chat engine
- `ChatController@show, @sendMessage, @switchModel, @shareLink` with SSE streaming.  
- Pipeline: optional PromptEnhancer → persona system prompt → tools → provider completion.  
- Mid-chat switching updates `model_key` on the next turn; **Auto** reevaluates each turn.

### 7) Document editor
- Vue Tiptap block with export to PDF (Browsershot) and DOCX (PhpWord). Save as `files`.

### 8) Personas & knowledge
- CRUD + Apply persona pill; attach files/notes; actions via JSON schema to external endpoints.

### 9) Saved Prompts
- Sidebar tree with folders, search, quick insert.

### 10) Teams, Workspaces, RBAC
- Teams own subscriptions; workspaces under teams; roles owner/admin/member/viewer.  
- Share links: `/s/{token}` read-only.

### 11) Image generator/editor
- `/images` grid, queue jobs, progress via polling/SSE; editor with crop/variation/upscale (stub OK).

### 12) Pricing & billing
- Pricing page with Solo/Team + calculator; Stripe Checkout + Portal; webhooks → `subscriptions`.  
- `/settings/billing` usage chart, seat mgmt, invoices.

### 13) Importers
- Upload ChatGPT/Claude JSON → parse into chats/messages; progress + summary.

### 14) Marketing pages
- Home, Pricing, Reviews, Enterprise, Is ChatGPT Down?, ChatGPT vs Blendable, Blog, Product Updates.

### 15) Frontend routes
- `/app` layout with sidebar: Workspaces, Chats, Images, Personas, Prompts, Team, Usage, Billing, Settings.  
- `/app/chats/:id` main chat UI (model switcher, persona picker, slash-commands, files, editor, share).

### 16) Seeds
- Seed model_configs, demo team/workspace, personas, prompts, sample chat & image job.

### 17) Tests & QA
- PHPUnit for ModelRouter, usage ledger, importers.  
- Dusk flow for core UX + billing.

### 18) CI/CD
- GitHub Actions: phpunit + Dusk, build assets; deploy via Forge on tag.

### 19) Docs
- `/docs/ARCHITECTURE.md`, `/docs/AI_MODELS.md`, `/docs/USAGE_ACCOUNTING.md`, `/docs/IMPORTERS.md`, `/docs/SECURITY.md`, `/docs/PRICING.md`.

**Deliverables**: full codebase, migrations, factories, pages/components, policies, queues, jobs, tests, CI; `.env.example` (dummy keys); artisan commands `app:seed-demo`, `usage:recalc`, `import:chatgpt`, `import:claude`.

**Constraints**: secrets in `.env`; mock unavailable APIs; enforce scoping; record usage for every generation.

**Finally print**: repo tree and run steps.
