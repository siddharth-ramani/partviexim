# Parthvi Exim — SEO implementation and launch plan

Positioning: Gujarat-based exporter and sourcing partner for Indian ingredients. The site targets ingredient importers, distributors and food businesses. It does not claim to manufacture every ingredient or promise unsupported certifications, prices, reviews or rankings.

## Search intent and page ownership

| Page | Main search intent | Supporting content |
|---|---|---|
| Home | Indian ingredients exporter; agro products exporter in Gujarat | Real product range, sourcing introduction, registrations and inquiry flow |
| Products | Indian ingredients and agricultural product catalogue | Searchable product collection and category navigation |
| Dehydrated White Onion | Dehydrated white onion exporter from India | Five forms, available particle sizes and buyer guidance |
| Dehydrated Red Onion | Dehydrated red onion supplier from India | Five forms, specification links and packing discussions |
| Dehydrated Pink Onion | Dehydrated pink onion exporter from India | Five forms and application/ordering guidance |
| Dehydrated Garlic | Dehydrated garlic flakes, granules and powder supplier | Five forms with dimension and packing information |
| Peanuts | Indian peanuts exporter; bold, Java and blanched peanuts | Five product forms and kernel/packing considerations |
| Indian Spices | Indian cumin and green cardamom exporter | Actual listed products and cumin quality options |
| Individual products | Specific product/form queries | Unique titles/descriptions, actual product specifications and product inquiry links |
| About / Certificates / Contact | Business identity, documents and supplier contact | Named company representative, supplied registrations and genuine contact details |

These are intent mappings based on the actual catalogue, not keyword-volume estimates. No invented search demand, traffic forecasts or ranking positions are reported.

Six categories with multiple products have standalone collection pages. Categories containing one product send visitors directly to that product. This avoids creating four near-duplicate collection pages. Current sitemap: seven main pages, six collections and 31 product pages (44 URLs).

## Completed website work

- Canonical URLs, HTTPS/www/PHP redirects, old product/category slug redirects and real noindex 404 pages.
- Dynamic sitemap, product image references, draft exclusion and modification dates based on real saves.
- Production indexability and localhost/preview noindex safeguards; private route and development-file protections.
- Individual page/product/category search titles and descriptions, descriptive breadcrumbs and internal category/product links.
- Category introductions and buyer guidance based on supplied forms, grades, specifications and packaging.
- Organization, WebSite, collection and descriptive product structured data. No fake offers, prices, reviews or ratings.
- Descriptive product image alt text, priority loading and responsive hero images.
- Optimized transparent logo delivery: 574,493 bytes to 91,676 bytes, retaining the original for admin editing.
- Default theme no longer makes an unnecessary render-blocking stylesheet request. Custom brand colours remain editable.
- Search Console verification-token field in Pages & appearance → Shared content & appearance → Search verification. An empty field adds no verification claim.
- Category page headings, introductions, buyer guidance, URL slugs and SEO fields remain editable in Categories.

## Local performance evidence

A local Chrome mobile lab sample used a 390 px viewport, disabled cache, 150 ms simulated latency, approximately 1.6 Mbps download and 4× CPU slowdown.

| Metric | Before | After |
|---|---:|---:|
| LCP | 2,492 ms | 1,320 ms |
| Cumulative layout shift | 0 | 0 |
| Load event | 6,435 ms | 3,585 ms |
| Resource transfer | 1,203,976 bytes | 632,398 bytes |

These are individual local lab observations, not production field results, a Lighthouse score or a ranking prediction. Live hosting, network conditions, caching and actual visitor interactions must be measured after deployment. INP requires representative interactions and is not inferred from these load samples.

## Remaining launch/account work

1. Deploy the current files, assets and database changes to the canonical domain. Remove any server-level staging password/noindex only for the real public domain.
2. Verify domain ownership in Google Search Console. Use its real token in the admin field if choosing HTML-tag verification, or configure the actual DNS record when choosing domain verification.
3. Submit https://parthviexim.com/sitemap.xml and inspect representative Home, category and product URLs. Check live robots, canonical URLs and HTTPS redirects again on the actual host.
4. Measure live mobile page speed and Core Web Vitals. Record real inquiry conversions and relevant product queries; use actual results to prioritise improvements.
5. The business owner can verify/complete a genuine Google Business Profile if eligible, with the same real company information. No profile or account was created in this task.
6. Build genuine industry references and relevant supplier/buyer relationships. Publish useful new content only when the company has real expertise, specifications or answers to add. Do not purchase spam links, fabricate reviews or mass-produce country/keyword doorway pages.

The work is local. No deployment, Search Console submission, Google account verification, traffic acquisition or position-one ranking has been claimed.

## Primary guidance

- [Google SEO Starter Guide](https://developers.google.com/search/docs/fundamentals/seo-starter-guide)
- [Google: Helpful, reliable content](https://developers.google.com/search/docs/fundamentals/creating-helpful-content)
- [Google Search Essentials](https://developers.google.com/search/docs/essentials)
- [Web Vitals](https://web.dev/articles/vitals)

## Updating existing hosting

Back up the existing database, deploy the revised application and run `scripts/optimize-onpage-seo.php` and `scripts/enable-website-manager.php` with the hosting database environment settings. The content migration is a one-time upgrade; later client edits are retained on subsequent runs. Recognised original Home copy is updated only when it still matches its original defaults. Fresh installation SQL/seed are kept in sync.
