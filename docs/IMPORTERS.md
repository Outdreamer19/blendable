# Importers

Supported sources:
- ChatGPT: JSON export
- Claude: JSON export

Process:
1. Upload file → create `imports` record
2. Parse roles/timestamps → create `chats` + `messages`
3. Link attachments when available
4. Report summary

CLI: `php artisan import:chatgpt path` and `import:claude path`
