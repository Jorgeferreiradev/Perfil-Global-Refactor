# Copilot / AI Agent Instructions for PerfilGlobal V2

## Big picture ✅
- This is a small PHP MVC-style web app (no framework). Key directories:
  - `public/` — web entrypoint (`index.php`) and public assets.
  - `app/Controllers/` — HTTP controllers. They include views directly and perform redirects.
  - `app/Models/` — DB access via `Config\Database` (PDO singleton).
  - `app/Services/` — helper services (Excel, PDF, Import, Backup).
  - `resources/views/` — plain PHP views (partials: `layouts/header.php`, `layouts/footer.php`).
  - `config/` — app config and `Database` connector. There is a basic `.env` loader (`vlucas/phpdotenv`).
  - `Storage/` — generated files: backups, exports, qrcodes, logs.

## How routing & middleware work 🔧
- All routes are declared in `public/index.php` using `Bramus\Router`. Example:
  - `$router->get('/', "App\Controllers\AuthController@showLogin");`
- Protected routes are mounted under `/dashboard` and use `App\Middleware\SessionMiddleware` (called with `$router->before`).
- Role protection uses `RoleMiddleware` (called manually inside `/admin` mount).
- When adding a route: keep controller in `app/Controllers`, create views in `resources/views/...` and include `header.php`, `sidebar.php` and `footer.php` as existing controllers do.

## Data access patterns & DB conventions 💾
- DB connection: `Config\Database::getInstance()` returns a PDO instance (singleton). Use prepared statements.
- Models typically define a `$table` property and methods that use PDO prepared statements (see `app/Models/Usuario.php`).
- Soft deletes use a `deleted_at` column — most `SELECT` queries check `deleted_at IS NULL`.
- DB schema is in `perfilglobal_v2.sql` at repo root — import into MySQL (example: `mysql -u root -p perfilglobal_v2 < perfilglobal_v2.sql`).

## Environment & local dev 🛠️
- Dependencies: run `composer install` then `composer dump-autoload` after adding classes.
- Environment variables: `vlucas/phpdotenv` is used; place `.env` in `config/` (or set env vars in your server). Required keys: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
- Local server options:
  - XAMPP/Apache: set DocumentRoot to `<repo>/public` (recommended for matching production paths). 
  - PHP built-in server (for quick testing): `php -S 127.0.0.1:8000 -t public` (ensure `config/` env loading works for this setup).
- `config/config.php` contains `BASE_URL` (currently configured for ngrok in the repo). Update safely for your dev environment.

## Patterns to follow when editing code ✍️
- Controllers perform redirects using `header('Location: ' . BASE_URL . '/...')` followed by `exit;` — always include `exit` after redirects.
- Views are plain PHP files; follow the existing pattern to include `header.php` and `footer.php`.
- Use prepared statements and parameter binding — all models follow this pattern.
- For new classes, follow PSR-4 structure (`App\` namespace -> `app/`), and run `composer dump-autoload`.

## Integration points & external libs 🔗
- Excel exports: `phpoffice/phpspreadsheet` (`app/Services/ExcelReportService.php`).
- PDF reports: `tecnickcom/tcpdf` (`app/Services/PdfReportService.php`).
- QR generation: `chillerlan/php-qrcode` and `endroid/qr-code` used in `EventoController::mostrarQR`.
- Email: `phpmailer/phpmailer`.
- Time: `nesbot/carbon`.

## Error, success and UX conventions 💡
- Controllers pass status feedback via query params (e.g. `?success=creado`, `?error=fecha_pasada`) — views read these params and show messages. Mimic this pattern for consistency.
- Session inactivity check: `SessionMiddleware` invalidates sessions after 30 minutes — consider this for long-running operations.

## Testing & reliability notes ⚠️
- There are no automated tests in the repository. For any change that affects DB or reports, include a clear manual QA checklist in PRs (include sample DB seed or steps to reproduce).
- Avoid changing vendor packages in-place. Update `composer.json` and run `composer update` as needed.

## Useful files to check for context 👀
- `public/index.php` — central routing & middleware setup
- `config/Database.php` — DB singleton
- `config/config.php` — BASE_URL (ngrok note) and timezone
- `app/Controllers/*` — controller patterns and view inclusion
- `app/Models/*` — DB patterns, table naming, soft delete convention
- `app/Services/*` — Excel/PDF/Import/Backup logic
- `resources/views/layouts/*` — header/sidebar/footer patterns
- `perfilglobal_v2.sql` — DB schema

---
If anything above is unclear or if you want specific examples added (e.g., a sample route + controller + view PR template), tell me which area and I'll iterate. ✅
