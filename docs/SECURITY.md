# Security & Privacy

- Content is siloed by workspace; policies enforced on all routes/queries.
- Share links (`/s/{token}`) are read‑only, no indexing headers set.
- Never train on user data.
- Secrets are stored in `.env` and rotated regularly.
- Rate limits on API endpoints; queues for heavy jobs.
- Audit logs for sensitive actions.
