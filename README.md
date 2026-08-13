# Jan Aushadhi Ecommerce (Laravel)

> A self-hostable storefront for affordable generic medicines — built on Laravel 12, Livewire, and Tailwind.

[![License: MIT](https://img.shields.io/github/license/chirag127/janaushadhi-ecommerce-laravel?style=flat-square)](LICENSE)
[![Stars](https://img.shields.io/github/stars/chirag127/janaushadhi-ecommerce-laravel?style=flat-square)](https://github.com/chirag127/janaushadhi-ecommerce-laravel/stargazers)
[![Last commit](https://img.shields.io/github/last-commit/chirag127/janaushadhi-ecommerce-laravel?style=flat-square)](https://github.com/chirag127/janaushadhi-ecommerce-laravel/commits/main)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4?style=flat-square&logo=php&logoColor=white)](composer.json)
[![Laravel](https://img.shields.io/badge/Laravel-12-ff2d20?style=flat-square&logo=laravel&logoColor=white)](composer.json)
[![CI](https://img.shields.io/github/actions/workflow/status/chirag127/janaushadhi-ecommerce-laravel/test.yml?style=flat-square&label=tests)](.github/workflows/test.yml)

## What it is / why it exists

Jan Aushadhi is a full ecommerce platform for selling **generic (unbranded) medicines** — the kind sold under India's Pradhan Mantri Bhartiya Janaushadhi Pariyojana at a fraction of branded-drug prices. It gives a pharmacy a complete online store: a browsable catalog, cart, checkout with Razorpay + cash-on-delivery, customer accounts, and a full admin back office for products, inventory, orders, coupons, and reports.

It is a **self-hostable application** (Docker + Render config included) — not a hosted SaaS. Clone it, point it at a database, and run your own store.

## Links

- **Repo:** https://github.com/chirag127/janaushadhi-ecommerce-laravel
- **Landing page (GitHub Pages):** https://chirag127.github.io/janaushadhi-ecommerce-laravel/
- **Deploy:** self-hosted via Docker or Render (see [Quick start](#quick-start) and `docs/deploy-render.md`)

⭐ **If this is useful, please star the repo — it helps others find it.**

## Architecture

```mermaid
flowchart LR
    subgraph Client
        U[Shopper / Admin]
    end
    subgraph Laravel12["Laravel 12 app"]
        R[Routes\nweb.php]
        C[Controllers\nstorefront + admin]
        LW[Livewire components\ncatalog, cart, wishlist]
        S[Services\nCart · Checkout · Coupon · Order · Razorpay]
        M[Eloquent Models\nProduct · Order · Cart · Coupon · Review]
    end
    RP[(Razorpay API)]
    OA[(OAuth providers\nGoogle · GitHub · MS · Facebook)]
    DB[(PostgreSQL / Neon)]

    U --> R --> C
    C --> LW
    C --> S
    LW --> S
    S --> M --> DB
    S -->|online payment| RP
    C -->|social login| OA
```

```mermaid
sequenceDiagram
    actor Shopper
    participant Store as Storefront
    participant Checkout as CheckoutService
    participant Razorpay
    participant DB as PostgreSQL
    Shopper->>Store: Add to cart, go to checkout
    Store->>Checkout: place order (COD or online)
    alt Online payment
        Checkout->>Razorpay: create order + verify signature
        Razorpay-->>Checkout: payment confirmed
    end
    Checkout->>DB: persist order + items, decrement stock
    Checkout-->>Shopper: order confirmation
```

## Features

- **Storefront** — home, product catalog with search + category filtering, product detail pages, reviews.
- **Cart & wishlist** — Livewire-powered, live cart counter, add/remove without full reloads.
- **Checkout** — Razorpay online payments *and* cash-on-delivery; coupon redemption; configurable shipping fee and free-shipping threshold.
- **Customer accounts** — registration, password reset, profile, saved addresses, order history + cancellation.
- **Social login** — Google, GitHub, Microsoft, Facebook via Laravel Socialite; same email links to one account.
- **Admin portal (RBAC)** — dashboard, product/category/coupon CRUD, customer management, order status updates, inventory management, and reports.
- **SEO** — dynamic `sitemap.xml`, static about/faq/privacy/terms pages.
- **Deploy-ready** — Dockerfile (nginx + php-fpm + supervisord) and `render.yaml` for one-click Render deploys; Neon PostgreSQL friendly.

## Tech stack

| Layer | Technology |
| --- | --- |
| Language | PHP 8.2+ |
| Framework | Laravel 12 |
| Interactivity | Livewire 3 |
| Styling | Tailwind CSS 3, `@tailwindcss/forms`, `@tailwindcss/typography` |
| Build | Vite 5, `laravel-vite-plugin` |
| Payments | Razorpay PHP SDK |
| Auth (social) | Laravel Socialite (+ Facebook / Microsoft providers) |
| Database | PostgreSQL (Neon-friendly) |
| Testing | PHPUnit 11, Laravel Pint |
| Runtime image | php:8.3-fpm-alpine + nginx + supervisord |

## Repository structure

```
app/
├── Http/Controllers/       storefront + Admin/ + Auth/ + Customer/ controllers
├── Livewire/               CartManager, ProductCatalog, WishlistToggle, counters…
├── Models/                 Product, Category, Order, OrderItem, Cart, Coupon, Review, User…
├── Services/               CartService, CheckoutService, CouponService, OrderService, RazorpayService, WishlistService
├── Repositories/           data-access helpers
├── Jobs/ · Mail/ · Policies/ · Providers/
routes/web.php              all public, customer, and admin routes
database/                   migrations, factories, seeders
resources/                  Blade views, JS, CSS
docker/                     nginx.conf, supervisord.conf (production image)
docs/                       deploy guides (Render / free hosting) + Pages landing
Dockerfile                  php-fpm + nginx production image
render.yaml                 Render web-service blueprint
composer.json / package.json
```

## Quick start

**Prerequisites:** PHP 8.2+, Composer, Node 18+, a PostgreSQL database (local or Neon).

```bash
git clone https://github.com/chirag127/janaushadhi-ecommerce-laravel.git
cd janaushadhi-ecommerce-laravel

composer install
npm install

cp .env.example .env
php artisan key:generate

# point DATABASE_URL / DB_* at your Postgres instance, then:
php artisan migrate --seed

# run everything (server + queue + vite) in one command:
composer dev
# …or individually:
npm run dev          # front-end assets
php artisan serve    # http://localhost:8000
```

### Docker

```bash
# builds the php-fpm + nginx production image (see Dockerfile)
docker build -t janaushadhi .
docker run -p 8080:8080 \
  -e APP_KEY="$(php artisan key:generate --show)" \
  -e DATABASE_URL="postgresql://user:pass@host/db?sslmode=require" \
  janaushadhi
# on boot it caches config/routes/views, runs migrations, then serves on :8080
```

### Render

`render.yaml` defines a Docker web service. Push to GitHub, create a **New > Blueprint** on Render pointing at this repo, then set the `sync: false` secrets (`APP_KEY`, `DATABASE_URL`, `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`) in the dashboard. See `docs/deploy-render.md`.

## Configuration

Copy `.env.example` to `.env` and set the following (names only — never commit real values):

| Variable | Purpose |
| --- | --- |
| `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL` | Core Laravel app config (`APP_KEY` via `php artisan key:generate`) |
| `APP_TIMEZONE`, `APP_LOCALE` | Locale / timezone (defaults `Asia/Kolkata`, `en`) |
| `DB_CONNECTION`, `DATABASE_URL` | PostgreSQL connection (single Neon connection string) |
| `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` | Database-backed session/cache/queue drivers |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` | Outbound mail config |
| `STORE_CURRENCY`, `STORE_SHIPPING_FEE`, `STORE_FREE_SHIPPING_THRESHOLD`, `STORE_TAX_RATE`, `STORE_PRODUCTS_PER_PAGE` | Store business rules |
| `STORE_SUPPORT_EMAIL`, `STORE_SUPPORT_PHONE` | Storefront support contact |
| `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`, `RAZORPAY_WEBHOOK_SECRET`, `RAZORPAY_TEST_MODE` | Razorpay online-payment credentials (COD works without these) |
| `GOOGLE_*`, `GITHUB_*`, `MICROSOFT_*`, `FACEBOOK_*` `_CLIENT_ID` / `_CLIENT_SECRET` / `_REDIRECT_URI` | Social-login OAuth credentials |

## Screenshots

_Screenshots to be added. Run the app locally (`composer dev`) to preview the storefront and admin portal._

## Testing

```bash
composer test          # php artisan test --parallel (PHPUnit 11)
./vendor/bin/pint       # code style
```

## Part of the oriz family

Part of the [oriz](https://blog.oriz.in) family — a fleet of ~80 small, focused apps and sites. This one is a standalone self-hostable app rather than an `*.oriz.in` site.

## Hosting cost

Self-hosted. Runs on Render's free plan (Docker web service, sleeps when idle) or any host that can run a PHP 8.2+ + PostgreSQL container. No managed-service lock-in.

## Contributing

Issues and PRs welcome. Keep the style clean (`./vendor/bin/pint`), make sure `composer test` passes, and use conventional-commit messages.

## License

[MIT](LICENSE) © 2026 Chirag Singhal

## Author

Chirag Singhal — [chirag@oriz.in](mailto:chirag@oriz.in)

## Status

Feature-complete storefront + admin. Actively maintained. Conventional commits are the changelog.
