# October 2026 catalogue refresh

Local preview: http://localhost/parthviexim/

The website now has 31 products in 10 categories, based on `requirements/Parthvi exim.pdf` and the seven supplied screenshots. Individual product pages include the supplied dimensions, grades, moisture limits, applications and packaging where available. Additional specifications are requested through the inquiry form rather than invented. The existing cumin and psyllium product URLs are preserved.

Product records remain editable in the existing admin panel. Category navigation and counts follow published product records automatically. The front end uses local Bootstrap files and optimized WebP assets. The original client catalogue is available from the footer.

## Existing hosting update

Back up the hosting database before importing new content. Upload the revised PHP/CSS/JS and assets. Run `scripts/import-catalogue.php` from PHP CLI with the hosting database configuration; it matches existing products by slug and inserts the additional catalogue entries. It updates company content and page metadata while preserving admin accounts, certificates and inquiries. Do not re-run it after making newer content edits unless you intend to restore the supplied catalogue content.

For a fresh empty database, `database/parthviexim.sql` contains the refreshed content. Do not import that complete file into an existing site. `config/database.local.php` contains only the local XAMPP connection; omit it from hosting uploads. Production configuration remains in `config/database.php`, with `PARTHVI_DB_*` environment overrides.

The pre-redesign local database snapshots were moved out of the project during asset cleanup, into `/Users/siddharthramani/.codex/backups/parthviexim/`. No public hosting deployment was performed.

## Source assets

The one-time image extraction script was archived outside the project after the client source folder was removed. The product-only crops exclude screenshot controls and supplier names. `database/catalogue-2026.json` contains the sourced product data for the import script.

## Verification

All PHP files passed syntax checks. Browser checks passed at 375, 768, 1024 and 1440 pixels for Home, Products, Product Details, About and Contact, including image loading and horizontal overflow checks. Category filtering, combined search, empty/reset states, direct category URLs, mobile navigation and product inquiry preselection passed. All 31 product detail routes returned successfully. An inquiry submission passed against the separate `parthviexim_redesign_qa` database; no test inquiry was added to the actual local database. Full-page Home and Product Detail screenshots were visually reviewed.

## Detailed audit — 28 October 2026

Reviewed all 38 public pages (the seven main pages and 31 product pages) at 320, 390, 768, 1024 and 1440 px: 190 page/viewport combinations. No broken images, missing headings, visible text clipping or horizontal overflow remained in these checks. Reviewed full-page desktop/mobile screenshots of all main layouts and the detailed psyllium specification page.

Corrected the postal code to 394540 using the existing GST and IEC certificates, removed the obsolete CDN disclosure/preconnect, restored the editable homepage heading with multiline support, improved muted text/category-label contrast and small mobile controls, replaced the search glyph with SVG, removed a duplicate image attribute, added safe homepage image fallbacks, and styled the missing-product page with working navigation and noindex metadata.

The public download copy of the catalogue has the correct PIN code and “Peanut Butter” spelling. Its displayed download size follows the actual file. The original PDF under `requirements` remains unchanged.

Rechecked 130 unique internal link/download targets with no failures. All 30 admin/inquiry integration checks passed against `parthviexim_audit_test`, including editing, image/PDF uploads, CSRF checks, drafts, inquiries, sign-in and password changes. Category/search/reset, direct-category URLs, mobile menus, Escape handling and product inquiry preselection passed browser checks. PHP syntax checks passed. No QA entries were added to the actual inquiry inbox.

For an existing hosting database, after backing it up, run `scripts/apply-audit-fixes.php` once with the hosting database environment settings. This restores the audited address, initial homepage heading and updated privacy resource description; avoid running it over later editorial changes. Fresh-install SQL and seed content include these corrections. Changes are local and have not been deployed to public hosting.

## Website manager expansion

The current templates now read their marketing copy from structured page fields. The admin panel includes grouped page editors, image/PDF uploads, brand colours, section visibility, founder profile, catalogue replacement, category CRUD with rename propagation, homepage featured selections, product search and a row-based specification editor. Shared company settings and legal-page content remain editable. See `ADMIN-GUIDE.md` and the in-panel “How to manage” page.

For existing hosting, back up the database and run `scripts/enable-website-manager.php` after deploying the new files. Fresh-install SQL/seed are updated. The migration preserves existing matching content values and accounts. The admin JavaScript and styles use cache-busting versions.


## Unused asset cleanup — 28 October 2026

Removed 21 unused images/derived variants, the obsolete `assets/images/img` folder, the old SQLite/storage backups, two obsolete migration/source-generation tools and Finder metadata. Active product/category/founder images, certificates, catalogue, favicon and logo remain. The empty `assets/uploads` and `assets/optimized` directories remain because the admin panel uses them for future uploads. The unused hero/logistics settings were retired; social metadata now follows the managed homepage hero image.

An archive containing the removed files and a current MySQL snapshot is outside the project at `/Users/siddharthramani/.codex/backups/parthviexim/cleanup-20261028-180012.tar.gz`. All 38 public pages and 131 internal link/download targets passed checks after cleanup; the asset reference scan found no missing files or unused images.

## Dynamic controls verified

Completed an end-to-end save/render audit of all 233 registered text/link controls using unique values in an isolated database. All reached their public output; 249 checks passed. Completed the 30 existing and 33 website-manager regression checks again. Rechecked all 38 public pages at five widths and 131 internal targets. The actual website content and inbox contain no QA markers/entries, and temporary uploads were removed.

Added controls for the remaining business-facing button/footer/count/confirmation copy. Count templates now stay editable after JavaScript filtering. The send button restores the saved label after back navigation. WebP page/product uploads work even when the host GD build lacks WebP conversion; unsupported WebP favicon conversion now requests PNG/JPG clearly. Plain-text paragraph line breaks are preserved.


## Search indexing update

Updated production/preview robots handling, private route exclusions, clean sitemap/helper redirects, preview noindex headers, missing-page responses, canonical/social metadata and descriptive product/breadcrumb JSON-LD. Admin changes now track known modification dates; unchanged saves retain lastmod. Product URL edits preserve 301 aliases. Legacy fallback descriptions now reflect the full ingredient range.

Verification: 473 read-only Apache SEO checks across 38 canonical URLs, 17 isolated SEO integration checks, 30 existing admin checks, 33 website-manager checks and 253 dynamic-content checks passed. All 131 internal link/download targets are available. No hosting deployment or Search Console submission was performed; see `SEO-NOTES.md`.

## On-page SEO and speed pass

Added six multi-product category landing pages with buyer guidance, crawlable internal links, collection schema, sitemap inclusion and editable category SEO fields. Single-product categories redirect to the product. Refined the Home ingredient positioning and individual page/product/category metadata without unsupported claims. Added an optional real-token Search Console verification field.

Optimized logo delivery and added two responsive hero variants that are actively referenced in preload/srcset. The original client images remain available for admin editing. Local mobile lab samples improved LCP from 2,492 ms to 1,320 ms, with CLS 0; production field measurements are still pending. See `SEO-PLAN.md` for full configuration, evidence and launch/account work.

Latest checks: 545 SEO crawl assertions across 44 public URLs, 18 category SEO integration checks, 30 original integration checks, 33 website-manager checks, 262 dynamic-content checks and 24 responsive layout cases. Test content remained isolated and temporary QA assets were removed.

## Logo-led visual refinement — 30 October 2026

- Replaced the default green/brown palette with logo-aligned deep navy (#073777), blue (#006db6), white and cool light surfaces. Product photography keeps subtle neutral/natural backgrounds.
- Refined typography, spacing, card hierarchy, navigation, buttons, form controls, footer and keyboard focus. Mobile card layouts switch to one column below 360px; product detail columns stack below 768px. No extra font requests or replacement product imagery were added.
- Centralised brand colour tokens so the admin palette applies consistently. The exact original default green palette is resolved to the new defaults when read, including in the admin editor, without overwriting the live database. Other saved custom palettes are retained. Existing hosting needs updated files only for this visual change; do not reimport the installation SQL.
- Validation: 225 page/viewport cases (44 public pages and a 404 at 320, 390, 768, 1024 and 1440px), desktop/mobile product menus, product search/empty state/reset, custom colour inheritance, 33 isolated admin management checks and 545 SEO crawl checks. Screenshots reviewed across Home, catalogue, category cards, product details, Contact, menus and footer. This is local browser validation, not a claim that every physical device has been tested.
