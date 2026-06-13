<?php
/**
 * Application Configuration
 */

define('APP_NAME', 'TruckLoad');
define('APP_URL', 'https://yourdomain.com'); // Change this
define('APP_ENV', 'production'); // 'development' or 'production'

// Platform Settings
define('PLATFORM_FEE', 500); // Fixed fee in cents ($5.00)
define('CURRENCY', 'USD');
define('MIN_DRIVER_RATING', 3.5);
define('MIN_SHIPPER_RATING', 3.5);

// JWT Configuration
define('JWT_SECRET', 'your_super_secret_jwt_key_change_this'); // Change this!
define('JWT_EXPIRY', 604800); // 7 days in seconds

// Stripe Configuration
define('STRIPE_SECRET_KEY', 'sk_test_your_stripe_secret_key'); // Change this
define('STRIPE_PUBLIC_KEY', 'pk_test_your_stripe_public_key'); // Change this
define('STRIPE_WEBHOOK_SECRET', 'whsec_your_webhook_secret'); // Change this

// Google Maps Configuration
define('GOOGLE_MAPS_API_KEY', 'your_google_maps_api_key'); // Change this

// Email Configuration
define('SMTP_HOST', 'mail.yourdomain.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@yourdomain.com');
define('SMTP_PASS', 'your_email_password');
define('SMTP_FROM', 'noreply@yourdomain.com');
define('SMTP_FROM_NAME', 'TruckLoad');

// AWS S3 (Optional - for file uploads)
define('USE_S3', false); // Set to true if using S3
define('AWS_ACCESS_KEY', 'your_aws_key');
define('AWS_SECRET_KEY', 'your_aws_secret');
define('AWS_BUCKET', 'truckload-uploads');
define('AWS_REGION', 'us-east-1');

// Admin Credentials
define('ADMIN_EMAIL', 'admin@yourdomain.com');
define('ADMIN_PASSWORD_HASH', password_hash('change_this_password', PASSWORD_BCRYPT));

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['image/jpeg', 'image/png', 'application/pdf']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('REMEMBER_ME_TIMEOUT', 2592000); // 30 days

// Rate Limiting
define('RATE_LIMIT_ENABLED', true);
define('RATE_LIMIT_REQUESTS', 100);
define('RATE_LIMIT_WINDOW', 900); // 15 minutes

// Timezone
date_default_timezone_set('UTC');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}
?>
