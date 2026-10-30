# SEO and indexing setup

Canonical production website: https://parthviexim.com/

- Public robots endpoint: `/robots.txt`, served through `robots.php` so environment configuration stays consistent. The checked-in robots.txt is a production fallback.
- Sitemap: `/sitemap.xml`, generated from the seven public pages, six multi-product category pages and currently published products in published categories. Current content produces 44 canonical page URLs, plus product image references. Drafts, old slugs, query strings and private/admin pages are excluded.
- Admin saves record truthful `_updated_at` values. Sitemap `lastmod` reflects known page/product/shared-content changes. Unknown legacy modification dates are omitted rather than guessed or regenerated on every request.
- HTTP and www production hosts redirect to the HTTPS non-www domain. Public PHP, trailing-slash, sitemap.php and robots.php URLs redirect to their clean endpoints. Previous product slugs redirect permanently after a product URL is edited; unpublished products and aliases return 404.
- Localhost, IP-address and non-canonical preview hosts send noindex and a robots file blocking preview crawling. On the configured canonical production host, public pages remain indexable. Keep the host/domain settings correct when deploying.
- Genuine unavailable pages return HTTP 404 with noindex. They do not advertise an unrelated canonical or structured entity. A real error response is retained; visitors are not redirected to Home.
- Public pages expose canonical, Open Graph, Twitter image/dimension/alt metadata, Organization/WebSite data and breadcrumbs. Products include descriptive Product data based on the stored catalogue, without invented prices, availability or reviews.
- Admin responses send noindex/nofollow headers and metadata. Configuration, includes, database files and development documentation remain blocked by server rules; robots rules are not treated as access control.

## Hosting rollout

Deploy the current application, hidden .htaccess files and assets to the domain document root. The default canonical URL is already https://parthviexim.com/. If a different real domain or URL prefix is used, set `PARTHVI_SITE_URL` to its absolute base URL. Do not set it to a preview hostname just to make previews indexable.

For an existing database, the website-manager migration makes the added missing-page copy editable: `scripts/enable-website-manager.php` (CLI). It preserves existing matching values. Timestamp tracking and old-slug redirects require no schema migration. Fresh-install SQL and seed include the current content.

After deployment, check the live robots.txt, sitemap.xml, representative pages, HTTPS redirects and 404 responses. Submit `https://parthviexim.com/sitemap.xml` in the domain's Google Search Console account. No hosting deployment, account verification, Search Console submission or external Rich Results Test was performed from this local task.

## Verification

`tests/seo-crawl.py` checks the local Apache routes and canonical production behaviour using request headers, without contacting the live domain. It passed 545 checks across 44 public URLs, including XML/image links, canonical/meta agreement, JSON-LD parsing, index/noindex rules, protected resources and redirects.

`tests/seo.php` targets the isolated development router/database on port 8097. It passed 17 integration checks covering admin metadata updates, recorded lastmod dates, draft exclusion and previous-slug redirects. Production content and credentials are not changed by these tests.

## Primary references

- [Google: Build and submit a sitemap](https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap)
- [Google: Robots meta tags and X-Robots-Tag](https://developers.google.com/search/docs/crawling-indexing/robots-meta-tag)
- [Google: Canonical URLs](https://developers.google.com/search/docs/crawling-indexing/consolidate-duplicate-urls)
- [Schema.org Product](https://schema.org/Product)
- [Google: Product snippet requirements](https://developers.google.com/search/docs/appearance/structured-data/product-snippet)

Descriptive Product data does not make this quotation-only catalogue eligible for Google's price/review product snippets. Those require genuine offers, reviews or ratings meeting Google's requirements; the site does not fabricate them.

The legacy two-product fallback descriptions were refreshed with `scripts/refresh-seo-defaults.php`; it changes only recognised old defaults and retains custom metadata. Run it once on an existing hosting database after backing it up. Unchanged admin saves retain their existing modification date. If changing the canonical domain, update both `PARTHVI_SITE_URL` and the production-host redirects in `.htaccess`.

Category SEO and performance enhancements are documented in `SEO-PLAN.md`. Single-product categories redirect to the product to avoid duplicate collections. Search Console verification tokens can be set in the admin; no owner token or verification result was invented.
