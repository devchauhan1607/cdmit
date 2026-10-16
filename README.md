# CDMIT Website and Content Platform

Production-oriented corporate website, CMS and lead workflow for CDMIT. The application uses Laravel 12, PHP 8.2, React 19, TypeScript, Inertia, Tailwind CSS 4, Fortify and MySQL.

## Requirements

- PHP 8.2 with PDO MySQL, mbstring, OpenSSL, fileinfo, DOM, XML and cURL
- Composer 2.8+, Node.js 22+, npm and MySQL 8+
- A queue worker for production notifications

## Local setup

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Create a MySQL database named `cdmit`, configure its credentials in `.env`, then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
composer run dev
```

The catalog seeder adds confirmed CDMIT service and technology offerings. It does not create case studies, testimonials, project outcomes or an administrator.

## Super Admin

Public registration is disabled. Provision the initial Super Admin interactively so the password is never stored in source control or shell history:

```bash
php artisan cdmit:admin admin@example.com --name="CDMIT Administrator"
```

Only accounts with `is_super_admin = true` can authenticate into `/admin`.

## Lead notifications

Configure a verified sender and recipient in `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="CDMIT"
LEAD_NOTIFICATION_EMAIL=business@example.com
```

Run `php artisan queue:work --tries=3 --timeout=90` under a process supervisor in production. Lead records are committed before notifications are dispatched. Failures stay visible in the admin interface and can be retried.

## Quality checks

```bash
php artisan test
vendor/bin/pint --test
npm run typecheck
npm run build
```

See [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) for production, backup and rollback guidance.
