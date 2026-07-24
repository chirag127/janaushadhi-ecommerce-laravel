# Deploy the Jan Aushadhi Laravel app (free options)

Laravel needs **PHP 8.2+ + a database**. In prod it uses Neon Postgres (shared
janaushadhi project `sweet-silence-15535482`). Free PHP+Postgres hosting is the hardest of the 4 apps —
here are the realistic free paths, honest about tradeoffs.

## The catch
Laravel is not static — it needs a running PHP server + DB. Free tiers that do
this well are rare (most need a card or sleep aggressively).

## Free options (ranked)

### 1. Railway / Render (free tier, may need card)
- Deploy the Laravel app as a web service; it runs `php artisan serve` or via
  nixpacks. Connect to Neon Postgres via `DATABASE_URL` (set `DB_CONNECTION=pgsql`
  and paste the pooled connection string from `neon connection-string`).
- Set env: `APP_KEY` (`php artisan key:generate --show`), `DATABASE_URL`, the
  Razorpay + OAuth keys.
- ⚠️ Free tiers now often require a card on file and sleep after inactivity.

### 2. Fly.io (free allowance, needs card)
- `fly launch` in the laravel dir → detects Laravel → deploys a container.
- Same DB env as above. Small always-on allowance; card required to sign up.

### 3. InfinityFree / shared PHP host (free, no card) — MySQL only
- Free shared PHP hosts give **MySQL, not Postgres**. To use one, switch
  `DB_CONNECTION=mysql` and point at their MySQL DB (the app's Eloquent code is
  DB-agnostic; the shared `products`/`categories` from Neon would need to be
  imported into that MySQL, or the app runs fully self-contained on MySQL with
  its own seeded catalog — the SQLite dev seeder pattern ports to MySQL).
- No SSH → deploy via their file manager/FTP + run migrations via a bootstrap
  route or Adminer. Fiddly but free + no card.

### 4. Local only (free, no card, not public)
- `php artisan serve` on your machine with the SQLite dev DB (already set up —
  see the repo's `.env` SQLite fallback). Zero hosting; good for demos.

## Recommendation
- **Free + public + no card**: hard for Laravel. The cleanest is a **cheap
  ~$3/mo shared host with Postgres or MySQL** if it must be public + reliable.
- **Free + public, OK with a card on file (no charge if within limits)**:
  Railway or Fly.io connecting to Neon Postgres.
- **Free + no card**: run locally (SQLite), or InfinityFree with MySQL (effort).

## Env checklist (any host)
```
APP_KEY=          # php artisan key:generate --show
APP_ENV=production
APP_URL=https://<your-domain>
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://<user>:<password>@<host>/neondb?sslmode=require  # neon connection-string
RAZORPAY_KEY_ID=... RAZORPAY_KEY_SECRET=...   # test keys
GOOGLE_CLIENT_ID/SECRET, GITHUB_CLIENT_ID/SECRET   # social login
```
Then: `php artisan migrate --force`.
