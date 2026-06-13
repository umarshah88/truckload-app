# Sawaari - Multi-Service Ride & Delivery Platform for Pakistan

**A production-ready, inDrive/Uber-style platform tailored for Pakistan with ride-sharing, goods delivery, and inter-city services.**

## 🌟 Features

### Services
- **Passenger Rides**: Cars, bikes, rickshaws (intra-city & inter-city)
- **Goods & Parcel Delivery**: Bikes, trucks, vans, loaders/bulties
- **Real-Time Tracking**: GPS-enabled tracking for rides & deliveries
- **Dynamic Pricing**: Surge pricing, distance-based, time-based fares
- **Multiple Payment Methods**: JazzCash, EasyPaisa, Sadapay, Bank Transfer, COD, Cards

### User Roles
1. **Customer**: Browse services, book rides/deliveries, real-time tracking, ratings
2. **Driver/Captain**: Accept jobs, navigation, earnings dashboard, document verification
3. **Admin**: Full platform management, analytics, pricing control, user management

### Technical Highlights
- **Backend**: Laravel 11 + MySQL
- **Frontend**: Blade + Tailwind CSS + Alpine.js + Livewire
- **Real-Time**: Laravel Echo + Pusher
- **Maps**: Google Maps API with Urdu label support
- **Admin Panel**: FilamentPHP
- **Mobile**: PWA-ready responsive design
- **Authentication**: Laravel Sanctum + OTP via Twilio
- **Payments**: Stripe + Local Pakistani gateways

## 🚀 Quick Start

### Prerequisites
- PHP 8.3+
- MySQL 8.0+
- Node.js 18+ (npm/yarn)
- Redis (for queue & cache)
- Composer

### Installation (Local Development)

```bash
# Clone the repository
git clone https://github.com/umarshah88/truckload-app.git sawaari
cd sawaari

# Copy environment file
cp .env.example .env

# Generate APP_KEY
php artisan key:generate

# Install dependencies
composer install
npm install

# Setup database
php artisan migrate --seed

# Create symbolic link for storage
php artisan storage:link

# Start development servers
php artisan serve
npm run dev

# Start queue worker (in another terminal)
php artisan queue:work

# Start Laravel Echo Server (real-time)
pusher-http-laravel
```

**Platform Access**:
- Customer App: `http://localhost:8000`
- Admin Panel: `http://localhost:8000/admin`
- API: `http://localhost:8000/api`

### Configuration

1. **Database**: Update `DB_*` variables in `.env`
2. **SMS/OTP**: Configure Twilio credentials
3. **Maps**: Get Google Maps API key
4. **Payments**: Setup Stripe & local payment gateway keys
5. **Real-Time**: Configure Pusher account (free tier available)

## 📂 Project Structure

```
sawaari/
├── app/
│   ├── Models/                 # Eloquent models
│   ├── Http/Controllers/       # API & Web controllers
│   ├── Services/               # Business logic services
│   ├── Jobs/                   # Queued jobs
│   └── Events/                 # Broadcasting events
├── database/
│   ├── migrations/             # Database schema
│   ├── seeders/                # Database seeders
│   └── factories/              # Model factories
├── resources/
│   ├── views/                  # Blade templates
│   ├── css/                    # Tailwind styles
│   └── js/                     # Alpine.js & Livewire components
├── routes/
│   ├── api.php                 # API routes
│   ├── web.php                 # Web routes
│   └── admin.php               # Admin routes
├── config/                     # Configuration files
├── tests/                      # PHPUnit & Feature tests
└── storage/                    # File uploads
```

## 🛠 Database Schema

### Core Tables
- `users` - Customers, Drivers, Admins
- `roles` - Role definitions
- `user_profiles` - Additional user info
- `vehicles` - Driver vehicles
- `rides` - Ride bookings
- `deliveries` - Delivery orders
- `driver_locations` - Real-time GPS tracking
- `payments` - Transaction records
- `wallets` - User wallet balances
- `cities` - Pakistani cities & zones
- `pricing_rules` - Dynamic pricing config
- `complaints` - Customer complaints
- `referrals` - Referral system

## 🌍 Supported Cities

- Karachi
- Lahore
- Islamabad
- Faisalabad
- Peshawar
- Quetta
- Rawalpindi
- Multan
- Hyderabad
- Gujranwala
- Inter-city routes (in progress)

## 💳 Payment Methods

- **Mobile Money**: JazzCash, EasyPaisa, Sadapay
- **Cards**: Stripe (Visa, Mastercard)
- **Bank Transfers**: Direct bank account transfers
- **Cash on Delivery (COD)**: For deliveries
- **Wallet**: In-app wallet with balance

## 🔐 Security Features

- Laravel Sanctum for API authentication
- Rate limiting on all endpoints
- Input validation & sanitization
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- XSS protection
- Document verification for drivers
- Phone OTP verification

## 📱 Mobile Responsiveness

Fully responsive design optimized for:
- Smartphones (iOS & Android via PWA)
- Tablets
- Desktop browsers

## 🎨 UI/UX

- **Design System**: Tailwind CSS with custom Pakistani green accents
- **Dark Mode**: Fully supported
- **Languages**: English & Urdu
- **RTL Support**: For Urdu text

## 📊 Admin Dashboard

**Analytics & Management**:
- Real-time ride/delivery statistics
- Revenue reports (by service, payment method)
- User growth charts
- Driver performance metrics
- Peak demand heatmaps
- Complaint management
- Payout management
- Promo code creation & tracking
- Surge pricing zones
- Commission & fee configuration

## 🚀 Deployment (Hostinger Shared Hosting)

### Hostinger Setup

1. **Create MySQL Database**
   - Go to Hostinger Control Panel → Databases
   - Create database & user
   - Note credentials

2. **Upload via Git/FTP**
   ```bash
   git clone [repo] public_html/sawaari
   cd public_html/sawaari
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan migrate --force
   php artisan storage:link
   ```

3. **Configure .env**
   - Update DB credentials
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate secure `APP_KEY`

4. **Set Permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chmod -R 755 public
   ```

5. **Setup Cron Job** (for queue & scheduled tasks)
   ```bash
   * * * * * /usr/bin/php /home/user/public_html/sawaari/artisan schedule:run >> /dev/null 2>&1
   ```

6. **SSL Certificate**: Enable via Hostinger control panel

## 📚 API Documentation

API endpoints follow REST conventions:

```
POST   /api/auth/register          - User registration
POST   /api/auth/send-otp          - Send OTP
POST   /api/auth/verify-otp        - Verify OTP & login
GET    /api/rides/estimate         - Fare estimation
POST   /api/rides                  - Book a ride
GET    /api/rides/{id}             - Ride details
POST   /api/deliveries             - Create delivery
GET    /api/tracking/{id}          - Real-time tracking
GET    /api/payments/history       - Payment history
POST   /api/wallet/add-balance     - Top-up wallet
POST   /api/complaints             - File complaint
```

**Full API docs**: `/api/docs` (Swagger/OpenAPI)

## 🤝 Contributing

Contributions are welcome! Please create a feature branch and submit a pull request.

```bash
git checkout -b feature/your-feature
git commit -m "Add your feature"
git push origin feature/your-feature
```

## 📝 License

MIT License - see LICENSE file

## 👨‍💼 Support

For issues, bugs, or feature requests, please open a GitHub issue.

---

**Made with ❤️ for Pakistan's Transportation & Logistics**
