# Sawaari - Hostinger Shared Hosting Setup Guide

## Prerequisites

- Hostinger Business/Premium plan (recommended)
- SSH access enabled
- PHP 8.3+ with required extensions
- MySQL database access
- Node.js available (for build)

## Step-by-Step Installation

### 1. Connect via SSH

```bash
ssh your_username@your_domain.com
cd public_html
```

### 2. Clone Repository

```bash
git clone https://github.com/umarshah88/truckload-app.git sawaari
cd sawaari
```

### 3. Install Composer Dependencies

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install --optimize-autoloader --no-dev
```

Or if Composer is globally installed:

```bash
composer install --optimize-autoloader --no-dev
```

### 4. Setup Environment File

```bash
cp .env.example .env
php artisan key:generate
```

**Edit .env with your Hostinger database credentials:**

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use database queue for shared hosting
QUEUE_CONNECTION=database
CACHE_DRIVER=file
BROADCAST_DRIVER=log
```

### 5. Build Frontend Assets

```bash
npm install
npm run build
```

If Node.js not available on server, build locally and upload:

```bash
# On your local machine
npm run build
git add public/build
git commit -m "Build assets"
git push
```

### 6. Run Migrations & Seeders

```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

### 7. Setup Storage & Permissions

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
chown -R nobody:nobody storage bootstrap/cache
```

### 8. Setup Cron Job for Queue & Scheduler

**In Hostinger Control Panel:**
- Go to Advanced → Cron Jobs
- Add new cron job:

```
* * * * * /usr/bin/php /home/yourusername/public_html/sawaari/artisan schedule:run >> /dev/null 2>&1
```

### 9. Configure Web Root (if needed)

- In Hostinger, set public root to: `/public_html/sawaari/public`
- Or use .htaccess redirect if using subdirectory

### 10. Enable SSL Certificate

- Hostinger → SSL Manager → Generate free Let's Encrypt certificate
- Update `APP_URL=https://yourdomain.com` in .env

### 11. Create Admin User

```bash
php artisan tinker

# In tinker shell:
$user = new \App\Models\User();
$user->phone = '03001234567';
$user->email = 'admin@sawaari.com';
$user->password = Hash\make('your_secure_password');
$user->role_id = 1; // Admin role
$user->is_verified = true;
$user->save();

exit
```

Or use artisan command:

```bash
php artisan make:user:admin
```

## Configuration for Shared Hosting

### Database Queue (instead of Redis)

Since shared hosting doesn't have Redis:

```bash
php artisan queue:table
php artisan migrate

# In .env
QUEUE_CONNECTION=database

# Add cron job to process queue:
* * * * * /usr/bin/php /home/yourusername/public_html/sawaari/artisan queue:work --max-tries=3 --max-time=3600 >> /dev/null 2>&1
```

### File-Based Caching

```bash
# In .env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### Mail Configuration

For SMTP via Hostinger:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_email_password
```

## Performance Optimization

### 1. Enable Caching

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Compress Assets

```bash
npm run build
# This creates minified JS/CSS in public/build
```

### 3. Setup .htaccess (for public directory)

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ index.php [QSA,L]
</IfModule>
```

### 4. Database Optimization

```bash
php artisan optimize:clear
php artisan optimize
```

## Updating the Application

```bash
cd /home/yourusername/public_html/sawaari

git pull origin main
composer install --no-dev
npm install && npm run build

php artisan migrate --force
php artisan cache:clear
php artisan config:cache
```

## Troubleshooting

### 1. 500 Error

Check error logs:

```bash
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log
```

### 2. Permission Denied

```bash
chown -R nobody:nobody storage bootstrap/cache public/storage
chmod -R 755 storage bootstrap/cache
```

### 3. Database Connection Error

- Verify credentials in .env
- Check MySQL is running: `mysql -u root -p`
- Create database if not exists

### 4. Memory Limit Exceeded

In .htaccess or php.ini:

```
php_value memory_limit 256M
php_value max_execution_time 300
```

### 5. Queue Not Processing

Ensure cron job is running:

```bash
# Check cron logs
grep CRON /var/log/syslog | tail -20

# Manually test queue
php artisan queue:work --once
```

## Environment Variables for Production

```
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

# Use file-based options
QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file
BROADCAST_DRIVER=log

# Security
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Payment & API Keys (from your providers)
STRIPE_SECRET_KEY=sk_live_...
GOOGLE_MAPS_API_KEY=AIzaSy...
TWILIO_ACCOUNT_SID=AC...
PUSHER_APP_KEY=key...
```

## Monitoring & Maintenance

### Weekly Tasks

```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Clear old logs
php artisan tinker
>>> Illuminate\Support\Facades\Storage::deleteDirectory('logs');

# Check disk space
df -h
```

### Monthly Tasks

- Review error logs
- Update dependencies: `composer update --no-dev`
- Check for security updates: `composer audit`
- Verify backups are working
- Monitor database size and optimize tables

## Useful Commands

```bash
# View application logs
tail -f storage/logs/laravel-*.log

# Clear all caches
php artisan cache:clear
php artisan config:clear

# Restart queue worker
php artisan queue:restart

# Database optimization
php artisan tinker
>>> DB::statement('OPTIMIZE TABLE users, rides, deliveries, payments');

# Generate sitemap
php artisan sitemap:generate
```

---

**Questions?** Check logs in `storage/logs/` or review `.env` configuration.
