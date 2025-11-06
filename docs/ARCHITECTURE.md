# Architecture Overview

Blendable is a multi‑tenant SaaS (teams → workspaces) with a Laravel 12 backend and Inertia/Vue 3 frontend.
Key subsystems:
- Chat engine with provider‑agnostic **ModelRouter**
- Tools layer (WebSearch, Files, ImageGen, PromptEnhancer)
- Personas & knowledge attachments
- Usage accounting (words × multiplier)
- Billing (Stripe + seats + volume pricing)
- Importers for ChatGPT/Claude
- Marketing pages

See `AI_MODELS.md` for provider adapters and `USAGE_ACCOUNTING.md` for ledger rules.
