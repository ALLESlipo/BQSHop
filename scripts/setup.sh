#!/usr/bin/env sh
# One-time store setup. Runs inside the wpcli container:
#   docker compose run --rm wpcli sh /scripts/setup.sh
# Safe to re-run: steps that already happened are skipped.
set -eu
export WP_CLI_CACHE_DIR=/tmp/wp-cli-cache

WP="wp --user=${ADMIN_USER}"

echo "==> Core install"
if ! wp core is-installed 2>/dev/null; then
  wp core install --url="$SITE_URL" --title="$SITE_TITLE" \
    --admin_user="$ADMIN_USER" --admin_password="$ADMIN_PASSWORD" \
    --admin_email="$ADMIN_EMAIL" --skip-email
fi
# Keep core current: WooCommerce tracks the latest WordPress release.
wp core update
wp core update-db
wp rewrite structure '/%postname%/' --hard
wp option update timezone_string 'Europe/Athens'

echo "==> Plugins & theme"
wp plugin install woocommerce --activate
wp theme install storefront --activate
wp plugin delete hello akismet 2>/dev/null || true

echo "==> WooCommerce store settings"
wp option update woocommerce_currency 'EUR'
wp option update woocommerce_currency_pos 'right_space'
wp option update woocommerce_price_thousand_sep '.'
wp option update woocommerce_price_decimal_sep ','
wp option update woocommerce_default_country 'GR'
wp option update woocommerce_store_address 'Example Street 1'
wp option update woocommerce_store_city 'Athens'
wp option update woocommerce_store_postcode '10431'
wp option update woocommerce_allowed_countries 'all'
wp option update woocommerce_ship_to_countries ''
wp option update woocommerce_calc_taxes 'no'
wp option update woocommerce_enable_guest_checkout 'yes'
wp option update woocommerce_enable_signup_and_login_from_checkout 'yes'
wp option update woocommerce_enable_myaccount_registration 'yes'
wp option update woocommerce_manage_stock 'yes'
# Skip the onboarding wizard and "coming soon" mode so the shop is usable now.
wp option update woocommerce_onboarding_profile '{"skipped":true}' --format=json
wp option update woocommerce_task_list_hidden 'yes' || true
wp option update woocommerce_coming_soon 'no'
wp option update woocommerce_store_pages_only 'no'

echo "==> Shop pages (Shop, Cart, Checkout, My Account)"
$WP wc tool run install_pages >/dev/null

if [ -z "$(wp post list --post_type=page --name=terms-and-conditions --field=ID)" ]; then
  TERMS_ID=$(wp post create --post_type=page --post_status=publish --porcelain \
    --post_title='Terms and Conditions' \
    --post_content='<!-- wp:paragraph --><p>Placeholder terms. Replace with your real terms, shipping and returns policy before launch.</p><!-- /wp:paragraph -->')
  wp option update woocommerce_terms_page_id "$TERMS_ID"
fi
PRIVACY_ID=$(wp option get wp_page_for_privacy_policy)
[ "$PRIVACY_ID" != "0" ] && wp post update "$PRIVACY_ID" --post_status=publish >/dev/null

# The shop page doubles as the home page while there's a single product.
SHOP_ID=$(wp option get woocommerce_shop_page_id)
wp option update show_on_front 'page'
wp option update page_on_front "$SHOP_ID"

echo "==> Payment methods (offline, for test orders)"
$WP wc payment_gateway update cod --enabled=true \
  --title='Cash on delivery' --description='Pay with cash when your order arrives.' >/dev/null
$WP wc payment_gateway update bacs --enabled=true >/dev/null

echo "==> Shipping (applies to all countries)"
if [ -z "$($WP wc shipping_zone_method list 0 --field=id)" ]; then
  $WP wc shipping_zone_method create 0 --method_id=flat_rate --enabled=true \
    --settings='{"title":"Standard shipping","cost":"4.90"}' >/dev/null
  $WP wc shipping_zone_method create 0 --method_id=free_shipping --enabled=true \
    --settings='{"title":"Free shipping","requires":"min_amount","min_amount":"50"}' >/dev/null
fi

echo "==> Product with bundles"
if [ -z "$(wp post list --post_type=product --name=mima-product --field=ID)" ]; then
  PRODUCT_ID=$($WP wc product create --porcelain \
    --name='Mima Product' --slug='mima-product' --type=variable --status=publish \
    --short_description='Our signature product. Buy more, save more with our bundles.' \
    --description='Full product description goes here: what it is, what it does, ingredients/materials, sizing and care instructions.' \
    --attributes='[{"name":"Bundle","options":["1 Pack","2 Pack","3 Pack"],"visible":true,"variation":true}]' \
    --default_attributes='[{"name":"Bundle","option":"2 Pack"}]')

  create_bundle() { # option, sku, regular price, sale price, weight (kg)
    $WP wc product_variation create "$PRODUCT_ID" \
      --attributes="[{\"name\":\"Bundle\",\"option\":\"$1\"}]" \
      --sku="$2" --regular_price="$3" --sale_price="$4" --weight="$5" \
      --manage_stock=true --stock_quantity=100 >/dev/null
  }
  create_bundle '1 Pack' 'MIMA-1' '24.90' ''      '0.3'
  create_bundle '2 Pack' 'MIMA-2' '49.80' '44.90' '0.6'
  create_bundle '3 Pack' 'MIMA-3' '74.70' '62.90' '0.9'
fi

echo "==> Done. Shop: $SITE_URL  Admin: $SITE_URL/wp-admin"
