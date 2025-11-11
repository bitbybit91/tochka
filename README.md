# Tochka Free Market - Laravel/PHP Edition

**Modified by IO and CP | Converted to Laravel/PHP**

Tochka Free Market is a secure, decentralized marketplace application now built with Laravel/PHP framework, configured to run on Apache web server on port 80.

[![Framework](https://img.shields.io/badge/framework-Laravel%2011-red)]()
[![PHP Version](https://img.shields.io/badge/php-8.2+-blue)]()
[![License](https://img.shields.io/badge/license-MIT-green)]()

## 🚀 Major Changes

This application has been **completely rewritten from Go to Laravel/PHP**:

- ✅ **Framework**: Go → Laravel 11
- ✅ **ORM**: GORM → Eloquent
- ✅ **Templates**: Amber → Blade
- ✅ **Web Server**: Built-in Go server → Apache 2.4 on port 80
- ✅ **Models**: All marketplace models converted to Eloquent
- ✅ **Controllers**: MVC architecture with Laravel controllers
- ✅ **Routes**: Laravel routing system
- ✅ **Views**: Responsive Blade templates

## ✨ Features

- 🔐 **Secure & Private**: Built with Laravel security features
- 💰 **Cryptocurrency Support**: Bitcoin and Monero payments
- 🛡️ **Escrow System**: Secure transaction management  
- 👥 **Multi-Vendor**: Support for multiple sellers
- 🔍 **Search**: Database-driven search functionality
- 💬 **Messaging**: Private messaging system
- ⭐ **Reviews & Ratings**: Vendor reputation system
- 🌍 **Internationalization**: Multi-language ready
- 📱 **Responsive Design**: Mobile-friendly interface

## 🏗️ Quick Start

### Prerequisites

- **PHP** 8.2 or higher
- **Apache** 2.4 or higher
- **PostgreSQL** 12 or higher
- **Composer** 2.x
- **Node.js** 18+ and NPM

### Installation

```bash
# Clone repository
git clone https://github.com/bitbybit91/tochka.git
cd tochka

# Install dependencies
composer install
npm install && npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate

# Configure Apache (see LARAVEL_INSTALL.md for details)
sudo cp apache-config/tochka.conf /etc/apache2/sites-available/
sudo a2ensite tochka.conf
sudo systemctl restart apache2
```

Access at: `http://localhost` or `http://your-domain.com`

## 📚 Documentation

Comprehensive documentation available:

- **[LARAVEL_INSTALL.md](LARAVEL_INSTALL.md)** - Complete Laravel installation guide with Apache configuration
- **[CUSTOMIZATION.md](CUSTOMIZATION.md)** - Customization options (updated for Laravel)
- **[SEO_GUIDE.md](SEO_GUIDE.md)** - SEO optimization strategies
- **[REST_API.md](REST_API.md)** - API documentation

## 🛠️ Laravel Artisan Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan db:seed             # Seed database

# User management
php artisan user:create-admin username password  # Create admin user
php artisan user:make-admin username            # Grant admin to existing user

# Cache management
php artisan cache:clear         # Clear cache
php artisan config:cache        # Cache configuration
php artisan optimize            # Optimize for production

# Development
php artisan serve               # Dev server (port 8000)
php artisan tinker              # Interactive shell
```

**Default Admin Credentials** (after running `php artisan db:seed`):
- Username: `admin`
- Password: `admin123`

## 🗂️ Project Structure

```
tochka/
├── app/
│   ├── Http/Controllers/      # Laravel controllers
│   ├── Models/                # Eloquent models
│   └── Policies/              # Authorization
├── resources/
│   └── views/                 # Blade templates
├── routes/
│   └── web.php                # Web routes
├── public/                    # Web root (Apache DocumentRoot)
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── apache-config/             # Apache configuration
├── go-backup/                 # Original Go code (archived)
└── .env                       # Environment configuration
```

## 🔧 Apache Configuration

The application is configured to run on **Apache port 80**. The configuration file is located at `apache-config/tochka.conf`.

Key features:
- Document root: `public/` directory
- URL rewriting for clean URLs
- PHP-FPM integration
- Security headers
- Compression and caching

See [LARAVEL_INSTALL.md](LARAVEL_INSTALL.md) for complete Apache setup instructions.

## 🔐 Security Features

- Laravel authentication system
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- XSS protection
- Password hashing (bcrypt)
- Secure session management
- Rate limiting
- HTTPS support (SSL/TLS)

## 🎨 Customization

Laravel provides extensive customization options:

- **Views**: Edit Blade templates in `resources/views/`
- **Styles**: Modify CSS in `resources/css/`
- **Controllers**: Add/edit in `app/Http/Controllers/`
- **Models**: Eloquent models in `app/Models/`
- **Routes**: Configure in `routes/web.php`
- **Config**: Environment settings in `.env`

## 📦 Models

Key Eloquent models:

- `User` - User accounts and vendors
- `Item` - Product listings
- `Package` - Product packages/variants
- `PackagePrice` - Pricing information
- `ItemCategory` - Product categories
- `RatingReview` - Product reviews

## 🌐 Routes

Main application routes:

- `/` - Homepage
- `/items` - Browse products
- `/items/{uuid}` - Product details
- `/vendors` - Vendor directory
- `/vendor/{username}` - Vendor profile

## 💾 Database

The application uses **PostgreSQL** with the existing schema from the Go version. Database migrations are provided for Laravel.

## 🧪 Development

```bash
# Run development server
php artisan serve

# Watch for asset changes
npm run dev

# Run tests (if configured)
php artisan test
```

## 📈 Production Deployment

For production:

1. Set `APP_DEBUG=false` in `.env`
2. Configure Apache virtual host
3. Set up SSL/TLS certificates
4. Configure database backups
5. Set up queue workers
6. Enable OPcache
7. Configure cron jobs

See [LARAVEL_INSTALL.md](LARAVEL_INSTALL.md) for complete production checklist.

## 🔄 Migration from Go

The original Go codebase has been archived to `go-backup/` directory. The Laravel version maintains compatibility with the existing database schema.

**What was migrated**:
- ✅ Database models (GORM → Eloquent)
- ✅ Business logic (Go → PHP)
- ✅ Templates (Amber → Blade)
- ✅ Routing (gocraft/web → Laravel)
- ✅ User authentication
- ✅ Item/vendor management
- ✅ Package system

**Go backup location**: `go-backup/`

## 📞 Support

For issues and questions:
- **GitHub Issues**: https://github.com/bitbybit91/tochka/issues
- **Laravel Logs**: `storage/logs/laravel.log`
- **Apache Logs**: `/var/log/apache2/tochka-error.log`

## 📄 License

The MIT License (MIT)

Copyright (c) 2015 Chris Kibble

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## 🙏 Acknowledgments

- Original Tochka Free Market (Go version)
- Laravel Framework
- All contributors

---

**Note**: This is a complete framework conversion from Go to Laravel/PHP. The application now runs on Apache web server on port 80 as requested.
