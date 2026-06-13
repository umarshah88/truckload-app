# TruckLoad - Truck Loading Platform for Hostinger

A fully functional truck loading platform optimized for Hostinger shared hosting.

## Features

✅ **Real-time Ride Matching** - Intelligent algorithm for pairing shippers with drivers
✅ **Fixed Platform Fee** - Transparent, predictable pricing
✅ **Real-time Location Tracking** - GPS tracking for loads and drivers
✅ **In-app Messaging** - Direct communication between users
✅ **Payment Processing** - Stripe integration
✅ **Ratings & Reviews** - Bidirectional rating system
✅ **Admin Dashboard** - Complete management interface
✅ **Mobile Responsive** - Works on all devices
✅ **Driver Verification** - Document upload and KYC
✅ **Load Management** - Detailed load specifications
✅ **Dispute Resolution** - Built-in support system
✅ **Analytics** - Comprehensive metrics

## Tech Stack (Hostinger Compatible)

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+
- **Maps**: Google Maps API
- **Payments**: Stripe
- **Storage**: Hostinger File Manager
- **Real-time**: AJAX Polling

## Quick Start

1. Upload all files to `public_html` via FTP
2. Create MySQL database in Hostinger Control Panel
3. Import `database.sql` file
4. Edit `config/db.php` with database credentials
5. Edit `config/config.php` with API keys
6. Visit your domain

See `SETUP_GUIDE.md` for detailed instructions.

## Folder Structure

```
public_html/
├── api/                    # API endpoints
├── pages/                  # User pages
├── config/                 # Configuration files
├── includes/               # Shared includes
├── assets/                 # CSS, JS, images
├── uploads/                # User uploads
└── database.sql            # Database schema
```

## License

MIT
