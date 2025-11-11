# Tochka Free Market - Laravel/PHP Installation Guide

This application has been converted from Go to Laravel/PHP framework and configured to run on Apache port 80.

## Prerequisites

- **PHP** 8.2 or higher
- **Apache** 2.4 or higher
- **PostgreSQL** 12 or higher
- **Composer** 2.x
- **Node.js** 18+ and NPM (for asset compilation)

## Installation Steps

### 1. System Requirements

```bash
# Install PHP and required extensions
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-pgsql php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath

# Install Apache
sudo apt install apache2

# Install PostgreSQL
sudo apt install postgresql postgresql-contrib

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Clone and Setup

```bash
# Clone the repository
git clone https://github.com/bitbybit91/tochka.git
cd tochka

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies and compile assets
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file:

```env
APP_NAME="Tochka Market"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tochka_db
DB_USERNAME=tochka_user
DB_PASSWORD=your_secure_password

# Bitcoin Configuration
BITCOIN_NETWORK=mainnet
BITCOIN_NODE_ADDRESS=localhost:8332
BITCOIN_NODE_USER=rpcuser
BITCOIN_NODE_PASSWORD=rpcpassword

# Monero Configuration
MONERO_NODE_ADDRESS=localhost:18081
MONERO_WALLET_RPC_ADDRESS=localhost:18082
```

### 4. Database Setup

```bash
# Create PostgreSQL database
sudo -u postgres psql

CREATE DATABASE tochka_db;
CREATE USER tochka_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE tochka_db TO tochka_user;
\q

# Import initial data
psql -U tochka_user -d tochka_db -f go-backup/dumps/cities.sql
psql -U tochka_user -d tochka_db -f go-backup/dumps/countries.sql

# Run Laravel migrations
php artisan migrate

# Seed mock data (optional)
php artisan db:seed
```

### 5. Apache Configuration

```bash
# Copy Apache configuration
sudo cp apache-config/tochka.conf /etc/apache2/sites-available/

# Update DocumentRoot path in the file
sudo nano /etc/apache2/sites-available/tochka.conf
# Change /var/www/tochka to your actual installation path

# Enable required Apache modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod proxy_fcgi
sudo a2enmod setenvif
sudo a2enmod expires
sudo a2enmod deflate

# Enable PHP-FPM
sudo a2enconf php8.3-fpm

# Enable the site
sudo a2ensite tochka.conf

# Disable default site (optional)
sudo a2dissite 000-default.conf

# Test Apache configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### 6. Configure to Run on Port 80

Edit `/etc/apache2/ports.conf`:

```apache
Listen 80

<IfModule ssl_module>
    Listen 443
</IfModule>

<IfModule mod_gnutls.c>
    Listen 443
</IfModule>
```

Ensure your virtual host listens on port 80 (already configured in `tochka.conf`).

### 7. Set Up Cron Jobs

Add to crontab (`sudo crontab -e`):

```cron
* * * * * cd /path/to/tochka && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Configure Queue Workers (Optional)

For background job processing:

```bash
# Create systemd service
sudo nano /etc/systemd/system/tochka-queue.service
```

Add:

```ini
[Unit]
Description=Tochka Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/tochka
ExecStart=/usr/bin/php /var/www/tochka/artisan queue:work --tries=3
Restart=always

[Install]
WantedBy=multi-user.target
```

Enable and start:

```bash
sudo systemctl enable tochka-queue
sudo systemctl start tochka-queue
```

## Artisan Commands

### Database Management

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh

# Seed database with test data
php artisan db:seed
```

### User Management

```bash
# Create admin user (you'll need to create this command or use tinker)
php artisan tinker
>>> $user = App\Models\User::where('username', 'admin')->first();
>>> $user->is_admin = true;
>>> $user->save();
```

### Cache Management

```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Optimize for production
php artisan optimize
```

### Maintenance Mode

```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up
```

## Directory Structure

```
tochka/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/              # Eloquent models
│   └── Policies/            # Authorization policies
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── public/                  # Web root (DocumentRoot)
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   └── web.php             # Web routes
├── storage/                 # Generated files, logs
├── apache-config/           # Apache configuration
└── .env                    # Environment configuration
```

## Security Considerations

### 1. File Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/tochka

# Set directory permissions
sudo find /var/www/tochka -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/tochka -type f -exec chmod 644 {} \;

# Storage and cache directories need write access
sudo chmod -R 775 /var/www/tochka/storage
sudo chmod -R 775 /var/www/tochka/bootstrap/cache
```

### 2. Environment File

```bash
# Protect .env file
chmod 600 .env
```

### 3. HTTPS Configuration

For production, always use HTTPS:

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Obtain SSL certificate
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

### 4. Firewall

```bash
# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Troubleshooting

### Apache Not Starting

```bash
# Check Apache status
sudo systemctl status apache2

# Check error logs
sudo tail -f /var/log/apache2/tochka-error.log

# Test configuration
sudo apache2ctl configtest
```

### Permission Issues

```bash
# Reset permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues

```bash
# Test PostgreSQL connection
psql -U tochka_user -d tochka_db -h localhost

# Check PostgreSQL logs
sudo tail -f /var/log/postgresql/postgresql-*.log
```

### 500 Internal Server Error

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Composer Issues

```bash
# Update Composer
composer self-update

# Clear Composer cache
composer clear-cache

# Reinstall dependencies
rm -rf vendor
composer install
```

### NPM/Package.json Issues

If you encounter `npm error enoent Could not read package.json`:

```bash
# Verify package.json exists
ls -la package.json

# If missing, ensure you have the latest code
git status
git pull origin main

# Verify all npm config files are present
ls -la package.json vite.config.js postcss.config.js tailwindcss.config.js

# If files are present but npm still fails, try:
npm cache clean --force
npm install

# Alternative: Use specific node version (18+)
nvm use 18
npm install
```

**Common causes**:
- Incomplete git clone/pull
- Missing files due to .gitignore issues
- Old Node.js version (need 18+)
- Corrupted npm cache

**Solution**: Always ensure you pull the complete repository:
```bash
# Verify all Laravel files are present
ls -la artisan composer.json package.json

# If missing, re-clone or pull
git fetch origin
git reset --hard origin/copilot/update-codebase-to-laravel
```

## Performance Optimization

### 1. Cache Configuration

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 2. OPcache Configuration

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 3. Database Optimization

```sql
-- Create indexes on frequently queried columns
CREATE INDEX idx_items_user_uuid ON items(user_uuid);
CREATE INDEX idx_items_category_id ON items(item_category_id);
CREATE INDEX idx_packages_item_uuid ON packages(item_uuid);
```

## Monitoring

### Application Logs

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Watch Apache error logs
sudo tail -f /var/log/apache2/tochka-error.log

# Watch Apache access logs
sudo tail -f /var/log/apache2/tochka-access.log
```

### System Monitoring

```bash
# Monitor PHP-FPM
sudo systemctl status php8.3-fpm

# Monitor Apache
sudo systemctl status apache2

# Monitor PostgreSQL
sudo systemctl status postgresql
```

## Backup Strategy

### Database Backup

```bash
# Backup database
pg_dump -U tochka_user tochka_db > backup_$(date +%Y%m%d).sql

# Restore database
psql -U tochka_user tochka_db < backup_20241111.sql
```

### Application Backup

```bash
# Backup entire application
tar -czf tochka_backup_$(date +%Y%m%d).tar.gz \
    --exclude=node_modules \
    --exclude=vendor \
    --exclude=storage/logs \
    /var/www/tochka
```

## Production Checklist

Before going live:

- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Configure HTTPS with valid SSL certificate
- [ ] Set strong `APP_KEY`
- [ ] Configure database backups
- [ ] Set up monitoring and alerting
- [ ] Configure firewall rules
- [ ] Enable rate limiting
- [ ] Set up log rotation
- [ ] Test all functionality
- [ ] Configure cron jobs
- [ ] Set up queue workers
- [ ] Optimize with `php artisan optimize`

## Support

For issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Check Apache logs: `/var/log/apache2/tochka-error.log`
- Review configuration: `php artisan config:show`
- GitHub Issues: https://github.com/bitbybit91/tochka/issues

## Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Apache Documentation: https://httpd.apache.org/docs/
- PostgreSQL Documentation: https://www.postgresql.org/docs/
- PHP Manual: https://www.php.net/manual/

---

**Note**: This is a complete rewrite from Go to Laravel/PHP. The original Go code has been backed up to the `go-backup/` directory.
