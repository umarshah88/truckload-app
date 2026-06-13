# TruckLoad - Hostinger Setup Guide

## Quick Setup in 5 Steps

### Step 1: Upload Files to Hostinger

**Via FTP:**
1. Get FTP credentials from Hostinger Control Panel
2. Use FileZilla or WinSCP
3. Upload all files to `public_html` directory
4. Ensure `.htaccess` file is visible and uploaded

**Via File Manager:**
1. Go to Hostinger Control Panel → File Manager
2. Navigate to `public_html`
3. Upload all files

### Step 2: Create MySQL Database

1. Go to Hostinger Control Panel → MySQL
2. Click "Create New Database"
3. Database Name: `truckload_db`
4. Note credentials:
   - Host: `localhost`
   - Username: your username
   - Password: your password

### Step 3: Import Database Schema

1. Go to Hostinger Control Panel → phpMyAdmin
2. Select your database
3. Click "Import" tab
4. Upload `database.sql` file
5. Click "Go"

### Step 4: Configure Application

**Edit `config/db.php`:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'truckload_db');
```

**Edit `config/config.php`:**
```php
define('APP_URL', 'https://yourdomain.com');
define('JWT_SECRET', 'change_to_random_string');
define('STRIPE_SECRET_KEY', 'sk_test_xxxx');
define('GOOGLE_MAPS_API_KEY', 'your_api_key');
```

### Step 5: Set File Permissions

1. File Manager → Right-click `uploads` folder
2. Change Permissions to `755`
3. Check "Recursive"

## Test Installation

1. Visit `https://yourdomain.com`
2. Register at `/pages/register.php`
3. Test API at `/api/auth.php?action=login`

## Troubleshooting

### Database Connection Error
- Verify credentials in `config/db.php`
- Check database exists in phpMyAdmin
- Ensure user has privileges

### 404 Errors on Pages
- Ensure `.htaccess` is uploaded
- Contact Hostinger if mod_rewrite is disabled

### File Upload Issues
- Set `uploads` folder to 755 permissions
- Check disk space available
- Verify PHP max upload size

## Features Ready to Use

✅ User Registration & Login
✅ Post Truck Loads
✅ Find Available Loads
✅ Real-time Chat
✅ Payment Processing (Stripe)
✅ Ratings System
✅ Dashboard

## API Documentation

**POST** `/api/auth.php?action=register` - Register new user
**POST** `/api/auth.php?action=login` - Login user
**POST** `/api/rides.php` - Create new ride
**GET** `/api/rides.php?action=available` - Get available rides
**POST** `/api/chat.php?action=send` - Send message
**POST** `/api/ratings.php` - Submit rating

## Support

For issues, check:
- `SETUP_GUIDE.md`
- Hostinger Knowledge Base
- PHP error logs in Hostinger Control Panel
