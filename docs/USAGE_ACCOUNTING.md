# Usage Accounting (Words × Multiplier)

Each assistant turn writes a ledger row:
- `words_out`: estimated from tokens
- `multiplier`: from `model_configs.multiplier`
- `words_debited = words_out × multiplier`

Objects: `chat`, `image`, `video`. Daily aggregation supports charts and plan enforcement.
