# BQSHop
The Shop for Mimatest Online — WordPress + WooCommerce, run locally with Docker.

| What | URL |
|---|---|
| Shop (home page) | http://localhost:8080 |
| Admin | http://localhost:8080/wp-admin — user/password from your `.env` |
| Test inbox (all emails) | http://localhost:8025 |

Before the first start: `cp .env.example .env` and set the passwords.

## What's in this repository (and what isn't)

The repository holds the **code**: the child theme, the Docker setup and the one-off scripts.
The **site content** — pages, products, the home page sections edited in the Site Editor,
menus, translations and orders — lives in the **database**, which is not committed
(database dumps contain personal data). Keep database backups outside the repository.

## Theme & custom CSS

The active theme is **Mimatest Child** (`wp-content/themes/mimatest-child/`), a child of *SaaS Company*.
It's mounted straight into WordPress, so edits show up on reload.

- `style.css`: all custom CSS. It loads after the theme and WooCommerce, so it overrides them.
  Use this instead of Appearance → Additional CSS.
- `functions.php`: loads the parent's CSS, and points the parent's header/footer at the child theme
  (the parent hard-codes its own name, which would otherwise leave pages without a header/footer).

Updating the parent theme from the admin is safe: nothing in the child folder is touched.

## Languages (TranslatePress)

- **Albanian (sq)** is the main language, at `/`. **English** is the translation, at `/en/`.
- Write and edit content **in Albanian** (Site Editor, products, pages). Then add the English
  through **TranslatePress → Translate Site** (or *Translate Page* in the admin bar while viewing a page).
  The admin dashboard has a "Translating the site" box with these steps and direct buttons.
- Texts you can't click on the page (button labels, the animation hints, image descriptions) are in
  the *Select string to translate* list of the translation panel.
- The animation texts (rotate hint, Replay, sound icon) are listed in Albanian in the child theme's
  `mimatest_ui_texts()`; their English is edited in TranslatePress like everything else.
- WordPress and WooCommerce texts (cart, checkout, account) come from the installed Albanian language packs.
- The header flags are the child theme's `[mimatest_language_switcher]` shortcode (in the header template part).
- `scripts/translate-sq-en.php` was a one-off that filled in the first translations. All translations
  now live in TranslatePress, so edit them there, not in the script.

## Everyday commands

```sh
docker compose up -d          # start
docker compose down           # stop (data is kept)
docker compose down -v        # stop AND wipe the database/site — start from scratch
docker compose run --rm wpcli wp <command>   # WP-CLI, e.g. wp plugin list
```

## Fresh install

```sh
docker compose up -d
set -a && . ./.env && set +a
docker compose run --rm -e SITE_URL -e SITE_TITLE -e ADMIN_USER -e ADMIN_PASSWORD -e ADMIN_EMAIL wpcli sh /scripts/setup.sh
```

`scripts/setup.sh` installs WooCommerce + Storefront and configures the store. It is safe to re-run.

## What's set up

- **Product:** "Mima Product", a variable product with a **Bundle** option:
  1 Pack €24.90 · 2 Pack €44.90 (was €49.80) · 3 Pack €62.90 (was €74.70). Stock is 100 each.
  To add a bundle size, go to Products → Mima Product → Attributes / Variations.
  New products are added normally under Products → Add New.
- **Payments (test):** Cash on delivery and direct bank transfer. Real card payments
  (Stripe / WooPayments) need an account and API keys, so they're added later.
- **Shipping:** €4.90 flat rate, free over €50, all countries.
- **Pages:** Shop (home), Cart, Checkout, My Account, Terms and Conditions (placeholder), Privacy Policy.
- **Currency/locale:** EUR, Greece. Taxes are off. Change these in WooCommerce → Settings.

## Placeholders to replace before launch

Store address, product name/description/images, prices, terms and refund policy, logo and colours
(Appearance → Customize), admin password. Taxes/VAT still need configuring.
