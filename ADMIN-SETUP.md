# Parthvi Exim — MySQL live setup

The project now uses MySQL/MariaDB through PDO MySQL. SQLite is no longer used by the website or admin panel.

## Files and database

- Production URL: https://parthviexim.com/
- Local MySQL database: `parthviexim`
- Import file: `database/parthviexim.sql`
- Connection settings: `config/database.php`
- Admin URL: https://parthviexim.com/admin/
- Existing admin login is preserved. Change the initial password before launch.
- Time displayed in the website/admin is Asia/Kolkata (IST). Database timestamps are UTC and converted for display.

## Upload today

1. In your hosting panel, create an **empty MySQL database** and a database user. Assign that user to the database with SELECT, INSERT, UPDATE and DELETE privileges (the import account also needs CREATE, ALTER and INDEX).
2. Open phpMyAdmin, select the new database, choose Import, and select `database/parthviexim.sql`. Import this file once. It contains all current content, settings and existing admin password hashes. It does not drop tables, reset data or create the database itself. Do not import `schema.sql` separately.
3. Edit `config/database.php`: set `host`, `port`, `name`, `user`, and `password` to the hosting database values. Use the full database and username including any hosting prefix. `root` with an empty password is for local XAMPP only. If the hosting has `PARTHVI_DB_*` environment variables, those override this file.
4. Upload the project files directly into the domain's document root (usually `public_html`), including hidden `.htaccess` files. Include `config`, `includes`, `admin` and all `assets` subfolders. Keep the original images and documents and the `assets/optimized` files together. Do not put the site in an extra `parthviexim` subfolder.
5. For the running site, do not upload the development-only `tests` folder, the `database` export/tools folder, or local database backups. Keep these locally. They are also blocked by their `.htaccess` files if present on Apache/LiteSpeed.
6. Use PHP 8.1+ with PDO MySQL, GD, mbstring, and fileinfo. Allow the web process to write to `assets/uploads` and, for automatic image optimization, `assets/optimized`. Enable HTTPS for the domain. Apache/LiteSpeed must allow `.htaccess` rewrite rules.
7. Check Home, Products, both individual product pages, Certificates/PDF downloads, Contact, and `/admin/`. Send a real test inquiry and confirm it appears in the admin inbox. Check a favicon/SEO/settings save. Change the admin password in My account before sharing access.
8. Open `https://parthviexim.com/sitemap.xml` and submit it in Google Search Console after domain verification.

## Existing data and future updates

The supplied SQL is the migration snapshot of the original database. After launch, MySQL is the source of truth. Back it up through phpMyAdmin Export before changes. Do not reimport the installation SQL over an active site. Uploading later PHP/CSS fixes does not require importing the database again.

If the previous infotechgravity.com/parthviexim site remains online, configure redirects at that old hosting location to the corresponding new-domain URLs. This project cannot configure the old host remotely.

## Validation completed locally

The migration compares all seven tables against the original SQLite database. Functional testing runs against a separate `parthviexim_migration_test` database, so the real content and credentials are not changed by tests. The site does not create or seed tables during page requests.

## Git deployment

Hosting credentials in config/database.php and config/database.local.php are excluded from Git. Preserve the existing hosting configuration. On a new server, copy config/database.example.php to config/database.php and enter the real credentials.

Connect hosting deployment to this repository main branch. A Git push alone does not configure deployment. Preserve server configuration and uploads; avoid commands that delete untracked server files. Back up the live database before content migrations.
