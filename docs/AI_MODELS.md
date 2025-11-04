# AI Models & Providers

- OpenAI (GPT‑4o, gpt‑4o‑mini) — production
- Anthropic (Claude 3.7 Sonnet) — production
- Google (Gemini) — production
- Mistral, DeepSeek, Perplexity — stubs behind feature flags

`config/models.php` defines:
- `provider`, `model_key`, `display_name`, `context_window`
- `multiplier` for usage
- `pricing_json` (optional reference)

The **ModelRouter** chooses a provider by explicit selection or **Auto** heuristic.
