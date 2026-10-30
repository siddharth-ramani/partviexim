# Website manager

Open http://localhost/parthviexim/admin/ and sign in with the existing administrator account. The administrator email and password have not been changed by this update.

## Everyday editing

- **Products:** edit names, photos, descriptions, grades, applications and specifications. Specifications have separate name/value rows. Choose an existing category or type a new name. Tick **Feature this product on Home** to show it in the homepage collection. Draft products are hidden.
- **Categories:** edit category names, card images, descriptions and order. A rename also updates the category on its products. Draft categories hide their products, detail pages and sitemap entries. A populated category must be emptied before deletion.
- **Pages & appearance:** choose the Home, About, Products, Contact, Certificates, Privacy or Terms page. Open a named section, make changes and click **Save changes**. Text is plain text; no coding is required. Homepage heading lines are entered on separate lines.
- **Shared content & appearance** (inside Pages & appearance): edit menu/footer labels, the shared enquiry banner, button destination, catalogue PDF, brand colours and floating WhatsApp visibility. Catalogue PDFs can be up to 32 MB, subject to the host's PHP upload limit.
- **Website settings:** manage business/contact information, logo, favicon, social links, company introduction, mission and vision. These details are shared across the website.
- **Certificates:** replace PDF documents and optional preview images, change their order or save them as drafts.
- **Why choose us / Core values:** manage repeatable company cards. All published benefits are shown. Core values can be enabled on About through its Section visibility settings.
- **Inquiry inbox:** find a buyer, review requirements, reply by email and update the inquiry status.
- **My account:** change login email/password using the current password.

Use **View saved page** to inspect the result. This opens the saved public page; unsaved changes remain in the editor. Upload previews show the selected image before saving. Leave a file input empty to retain the current asset. An unsaved-changes warning protects editing work when leaving the page.

The public site's layout and functional form/validation behaviour are maintained in code. Business copy, images, products, categories, documents, SEO content and the exposed appearance/visibility controls are managed through the panel.

## Existing hosting upgrade

Back up the database, then upload the revised application files and assets. Run `scripts/enable-website-manager.php` once with the hosting `PARTHVI_DB_*` environment settings. It adds editable content defaults, seeds categories from current products and enables initial featured products. Existing values, admin accounts, inquiries and existing product records are preserved. Do not reimport the complete installation SQL over an existing database.

Fresh-install content is included in `database/parthviexim.sql` and `includes/seed.php`. Keep `config/database.local.php` local to XAMPP. Site migration scripts are CLI-only. PHP needs an upload/post limit large enough for the selected catalogue (32 MB plus form overhead); the current local configuration supports 40 MB requests.

## Verification

Integration checks are in `tests/smoke.php` and `tests/website-manager.php`. Both target the isolated PHP development router on port 8097. Never run integration tests against the actual database. Use a fresh isolated copy, run the smoke suite first, then set `PARTHVI_TEST_PASSWORD` to the password created by that suite for the website-manager checks. These checks intentionally change only test content and the test account.

Completed checks for this update: 30 existing integration checks, 33 new website-manager checks, 41 admin/browser UI checks across 320/390/768/1440 px, 190 public page/viewport checks, 131 internal link/download targets and eight product-menu assertions. A browser check also verified that the specification row editor synchronises with the saved data field. Test changes were made in `parthviexim_manager_test`; actual public content and administrator credentials were preserved.

## Dynamic-content verification

All 233 editable text/link fields were saved with distinct test values through the administrator forms and found in the corresponding public responses, including conditional certificate placeholders, unavailable-product pages and inquiry success text. The isolated suite `tests/dynamic-content.php` completed 249 checks. It also covers WebP upload, section visibility and matching mission/vision/purpose headings. Browser checks confirm that editable product-count text survives client-side filtering and that inquiry button/loading labels survive back navigation. Site content was not replaced with test values.

Count labels use `{count}` and `{category}` placeholders; WhatsApp text uses `{product}` and the inquiry confirmation uses `{id}`. These tokens are replaced automatically. Favicon uploads use PNG/JPG on servers without WebP conversion support; ordinary product/page WebP uploads remain supported.


## Category landing pages

Categories now include a page URL, main heading, introduction, buyer information and optional search title/description. Published categories with multiple products have their own collection pages and appear in the sitemap. A single-product category links directly to its product. Changing a category URL preserves a redirect from its previous URL.

Search Console tokens are in Shared content & appearance → Search verification. Paste only the real verification token from the account; leaving it empty makes no verification claim. Responsive hero and optimized-logo assets are active performance assets, so keep `assets/optimized` with the site.
