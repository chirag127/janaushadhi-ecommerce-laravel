# Deploy Jan Aushadhi Laravel on Render (free) + Neon Postgres

Chosen host: **Render** (git-push deploy, connects to Neon). Free web services
sleep after 15 min idle (~30-60s cold wake) — fine for demo/portfolio; upgrade to
always-on later with no code change. Deploy name: **janaushadhi-laravel** (stack
in the name, per rule).

## What's in the repo (ready to deploy)

- `Dockerfile` — PHP 8.3-fpm + nginx + supervisor; installs deps, caches
  config/routes/views, runs `migrate --force`, serves.
- `render.yaml` — Render Blueprint (free web service, env var slots).
- `docker/nginx.conf`, `docker/supervisord.conf` — server config.

## Steps

1. **Push** these files (done by the agent) to `chirag127/janaushadhi-ecommerce-laravel`.
2. **Render dashboard** → New → **Blueprint** → connect the GitHub repo → it reads
   `render.yaml` and creates the `janaushadhi-laravel` web service.
   (Or: New → Web Service → Docker → point at the repo.)
3. **Set env vars** (Render dashboard → the service → Environment). The
   `sync:false` ones must be pasted:
   - `APP_KEY` = run `php artisan key:generate --show` locally, paste the
     `base64:...` value.
   - `DATABASE_URL` = the Neon **pooled** connection string for project
     `icy-resonance-04758761` (get it: `npx neon connection-string --project-id icy-resonance-04758761`).
   - `RAZORPAY_KEY_ID` / `RAZORPAY_KEY_SECRET` = your Razorpay **test** keys.
   (The rest — APP_ENV, DB_CONNECTION=pgsql, SHARED_DB_CONNECTION, SESSION/CACHE
   drivers — are preset in `render.yaml`.)
4. **Deploy.** Render builds the Docker image, boots, runs `migrate --force`
   against Neon (schema already ported there), and serves.
5. **Seed the catalog** (once, shared across the 4 apps to avoid UUID drift):
   Render Shell → `php artisan db:seed` (or a catalog import command) — 2,439
   products / 60 categories into the Neon `products`/`categories` tables.
6. **Verify:** open the Render URL (`janaushadhi-laravel.onrender.com`) → home,
   /products, /login should load (first hit after idle is slow — cold wake).
7. **Custom domain (optional):** Render → Settings → Custom Domain →
   `janaushadhi.oriz.in` (but that subdomain currently points at the astro
   Worker — pick ONE canonical stack for the customer domain, or use a distinct
   subdomain like `laravel.janaushadhi.oriz.in`).

## Neon connection note

Render reaches Neon over standard TLS Postgres (no firewall). Use the **pooled**
connection string (`-pooler` host) for serverless-style connection reuse.
`DB_CONNECTION=pgsql`; the migration groundwork already created the schema on the
Neon project.

## Free-tier caveats
- **Sleeps after 15 min idle** → first request cold-starts (~30-60s). Ping it or
  upgrade ($7/mo) for always-on.
- Build minutes + bandwidth are metered on free; fine for low traffic.
- ⚠️ Verify Render's current free-tier terms at signup (policies shift; card may
  be requested for verification even on free).

## Why Render over the others (recap)
Oracle Always-Free is more powerful (never sleeps) but needs a VPS setup + ID
verify. InfinityFree is MySQL-only + no SSH. Railway/Fly need cards. Wasmer can't
run Laravel. Render = easiest git-push path that connects to Neon. See
`docs/free-hosting-guide.md` for the full comparison.
