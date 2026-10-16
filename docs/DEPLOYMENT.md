# CDMIT Deployment Guide

## Release checklist

1. Confirm PHP 8.2 and required extensions on the target server.
2. Back up the database and uploaded files before every release.
3. Deploy the reviewed Git revision and keep `.env` outside source control.
4. Run `composer install --no-dev --prefer-dist --optimize-autoloader`.
5. Run `npm ci && npm run build` on the build host, or deploy verified build artifacts.
6. Run `php artisan migrate --force` after application files are in place.
7. Run `php artisan storage:link` once. Ensure `storage` and `bootstrap/cache` are writable by the web user.
8. Run `php artisan optimize` and restart the application service.
9. Restart queue workers with `php artisan queue:restart`.
10. Verify `/up`, the home page, login, a CMS page and lead submission.

The web-server document root must be Laravel's `public` directory. Never expose the project root, `.env`, logs or database backups.

## Environment and security

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.example.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

- Redirect HTTP to HTTPS and use a valid certificate.
- Use a verified mail sender; visitor addresses are reply-to values only.
- Use least-privilege MySQL credentials limited to the CDMIT database.
- Store credentials in the hosting secret store.
- Keep queue workers supervised and automatically restarted.
- Monitor logs without exposing them publicly.

## Scheduler

Use the Laravel scheduler when scheduled content or maintenance commands are enabled:

```cron
* * * * * cd /path/to/cdmit && php artisan schedule:run >> /dev/null 2>&1
```

## Backup and restoration

Back up MySQL and `storage/app/public`. Store encrypted copies outside the application server with an explicit retention policy.

```bash
mysqldump --single-transaction --routines --triggers cdmit > cdmit-YYYYMMDD.sql
mysql cdmit < cdmit-YYYYMMDD.sql
```

Restore uploads to `storage/app/public`, recreate the storage link if needed, clear caches and verify representative records. Test restoration periodically.

## Monitoring and rollback

- Monitor `/up`, 5xx responses, queue failures, disk pressure and database growth.
- Review `failed_jobs`, mail failures, slow queries and backup completion.
- For rollback, restore the previous revision and matching assets.
- Roll back migrations only when verified reversible; otherwise restore the pre-release database backup.
- Run `php artisan optimize:clear`, rebuild caches, restart workers and complete smoke tests.
