# AGENTS.md

## Neon backend

This project uses [Neon](https://neon.tech) Postgres as the shared catalog+commerce database.

- **Project:** sweet-silence-15535482 (org org-old-mode-74272352, aws-us-east-2)
- **Connection:** run `neon env pull` after cloning — populates `DATABASE_URL` in `.env`.
- **`.neon`** tracks `orgId` + `projectId` (no secrets).
- **Pattern B:** each app links its own Neon project; `DATABASE_URL` comes from `neon env pull` at runtime. Never hardcode credentials.
