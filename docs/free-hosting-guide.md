# Jan Aushadhi Laravel — Free Hosting Guide (connect to Neon Postgres)

Complete guide to host this Laravel 12 app for FREE, connected to its Neon
Postgres (project `janaushadhi-laravel` / `icy-resonance-04758761`). Laravel =
PHP + DB, the hardest of the 4 janaushadhi stacks to host free.

## The honest landscape (2026)

| Option | Free / no card? | DB | Sleeps? | Verdict |
|---|---|---|---|---|
| **Oracle Cloud Always-Free** | ✅ no card (ID verify only) | Postgres native OR external Neon | **Never** | 🏆 Best — real VPS, full artisan |
| **InfinityFree** | ✅ genuinely no card | MySQL only | Yes (inactivity) | 🥈 Easiest, but MySQL + slow |
| ~~000webhost~~ | — | — | — | ❌ **SHUT DOWN Oct 2024 — dead** |
| Railway / Fly.io | ❌ free credit expired, card required | — | — | not free anymore |
| Render / Koyeb | ⚠️ card status unclear | external | 15-min sleep | staging only |
| Wasmer | ✅ freemium | — | — | ❌ **can't run Laravel** (WASM sandbox breaks fs/artisan/sessions) |
| Laravel Cloud (official) | ❌ paid only | — | — | not free |

⚠️ Web-fetch was proxy-blocked at research time — verify current card policies at
each signup page before trusting. Oracle's no-card always-free has held for years.

## 🏆 Recommended: Oracle Cloud Always-Free + Neon Postgres

A real Linux VPS (2 AMD vCPU, ~12 GB RAM), never sleeps, no card charged.

### Steps
1. **Sign up:** oracle.com/cloud/free — identity verification (no charge). Create
   an Always-Free compute instance (Ubuntu 22.04, AMD/ARM Always-Free shape).
2. **SSH in**, install the stack:
   ```bash
   sudo apt update && sudo apt install -y php8.3 php8.3-{cli,fpm,pgsql,mbstring,xml,curl,zip} nginx composer git
   ```
3. **Clone + install:**
   ```bash
   git clone https://github.com/chirag127/janaushadhi-ecommerce-laravel.git
   cd janaushadhi-ecommerce-laravel && composer install --no-dev
   cp .env.example .env && php artisan key:generate
   ```
4. **Point at Neon Postgres** in `.env` (the migration groundwork already ported
   the schema to Neon project `icy-resonance-04758761`):
   ```
   DB_CONNECTION=pgsql
   DB_URL="postgresql://<neon-pooled-connection-string>"   # from `neon connection-string`
   SHARED_DB_CONNECTION=pgsql
   ```
   Neon over TLS works from Oracle (no firewall). Use the POOLED connection string.
5. **Migrate + seed:** `php artisan migrate --force` then seed the 2,439-product
   catalog (coordinate UUIDs with sibling apps — seed the shared catalog ONCE).
6. **Razorpay:** test keys in `.env` (RAZORPAY_KEY_ID/SECRET — Razorpay is native in this app).
7. **Serve:** nginx + php-fpm (or `php artisan serve` behind a reverse proxy).
   Point `janaushadhi.oriz.in` (Cloudflare) at the Oracle instance IP; free SSL.
8. **Keep-alive:** Oracle reclaims idle Always-Free instances — run a light cron
   or uptime ping so it isn't flagged inactive.

### Deploy name (per rule)
Instance / project named `janaushadhi-laravel` (framework in the name).

## 🥈 Fallback: InfinityFree (zero friction, no card)

- Sign up (no card) → Softaculous-less; upload via FTP/file manager.
- PHP 8.3 available; **MySQL only** — so set `DB_CONNECTION=mysql` and use their
  MySQL (NOT Neon; the migration then targets MySQL, not the Neon Postgres).
- Sleeps after ~14–30 days inactivity; slow shared FPM; no SSH (run artisan via
  a bootstrap route or their tools).
- Good for a demo; not production.

## Why NOT the others
- **Wasmer**: runs PHP in WASM — Laravel needs real filesystem writes (cache,
  sessions, storage) + `artisan` CLI; the sandbox breaks it. Fine for the
  WordPress store attempt, NOT for Laravel.
- **Railway/Fly.io**: free credits expired → card required.
- **Render/Koyeb**: sleep on idle + card status unclear.

## Bottom line
**Oracle Cloud Always-Free** is the only genuinely-free, no-card, never-sleeps
host that runs full Laravel + connects to Neon Postgres. InfinityFree is the
no-setup fallback (MySQL, sleeps). Everything else needs a card or can't run
Laravel. For real customers, a ~₹250/mo shared host is the reliable paid step.
