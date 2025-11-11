# Tor Hidden Service Setup Guide

Complete guide for running Tochka marketplace as a Tor hidden service (.onion).

## Prerequisites

- Ubuntu/Debian server
- Apache 2.4 installed
- PHP 8.3+ with FPM
- PostgreSQL 12+
- Tor installed

## Table of Contents

1. [Install Tor](#install-tor)
2. [Configure Tor Hidden Service](#configure-tor-hidden-service)
3. [Configure Apache for Tor](#configure-apache-for-tor)
4. [Install Laravel Application](#install-laravel-application)
5. [Test Configuration](#test-configuration)
6. [Troubleshooting](#troubleshooting)

---

## Install Tor

### Ubuntu/Debian

```bash
# Add Tor repository
sudo apt install apt-transport-https

# Add Tor GPG key
curl https://deb.torproject.org/torproject.org/A3C4F0F979CAA22CDBA8F512EE8CBC9E886DDD89.asc | sudo gpg --dearmor -o /usr/share/keyrings/tor-archive-keyring.gpg

# Add repository
echo "deb [signed-by=/usr/share/keyrings/tor-archive-keyring.gpg] https://deb.torproject.org/torproject.org $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/tor.list

# Install Tor
sudo apt update
sudo apt install tor deb.torproject.org-keyring

# Verify installation
tor --version
```

---

## Configure Tor Hidden Service

### 1. Edit Tor Configuration

```bash
sudo nano /etc/tor/torrc
```

### 2. Add Hidden Service Configuration

Add these lines at the end of the file:

```
# Tochka Marketplace Hidden Service
HiddenServiceDir /var/lib/tor/tochka/
HiddenServicePort 80 127.0.0.1:80
```

**Explanation**:
- `HiddenServiceDir`: Directory where Tor stores keys and hostname
- `HiddenServicePort`: External port 80 maps to local 127.0.0.1:80

### 3. Set Permissions

```bash
# Ensure proper ownership
sudo chown -R debian-tor:debian-tor /var/lib/tor

# Restart Tor
sudo systemctl restart tor

# Check status
sudo systemctl status tor
```

### 4. Get Your .onion Address

```bash
# Wait a few seconds for Tor to generate the address
sleep 5

# Display your .onion address
sudo cat /var/lib/tor/tochka/hostname
```

**Save this address!** It will look like: `abc123def456ghi789jkl012mno345pqr567stu890vwx.onion`

---

## Configure Apache for Tor

### 1. Update Apache Ports Configuration

```bash
sudo nano /etc/apache2/ports.conf
```

Ensure Apache listens on 127.0.0.1:

```apache
# For Tor hidden service
Listen 127.0.0.1:80

# If you also want clearnet access
# Listen 80
```

### 2. Install Tochka Apache Configuration

```bash
cd /var/www/tochka/tochka

# Copy Tor-specific configuration
sudo cp apache-config/tochka-tor.conf /etc/apache2/sites-available/

# Edit with your .onion address
sudo nano /etc/apache2/sites-available/tochka-tor.conf
```

Update these lines with your actual .onion address:

```apache
ServerName your-actual-onion-address.onion
DocumentRoot /var/www/tochka/tochka/public
```

### 3. Update DocumentRoot Paths

Ensure the paths match your installation:

```apache
DocumentRoot /var/www/tochka/tochka/public

<Directory /var/www/tochka/tochka>
    ...
</Directory>

<Directory /var/www/tochka/tochka/public>
    ...
</Directory>
```

### 4. Enable Site and Modules

```bash
# Enable the site
sudo a2ensite tochka-tor.conf

# Enable required modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod proxy_fcgi
sudo a2enmod setenvif
sudo a2enmod expires
sudo a2enmod deflate

# Enable PHP-FPM
sudo a2enconf php8.3-fpm

# Test configuration
sudo apache2ctl configtest

# Should output: Syntax OK
```

### 5. Restart Apache

```bash
sudo systemctl restart apache2
sudo systemctl status apache2
```

---

## Install Laravel Application

### 1. Install Dependencies

```bash
cd /var/www/tochka/tochka

# PHP dependencies
composer install --no-dev --optimize-autoloader

# NPM dependencies
npm install
npm run build
```

### 2. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit configuration
nano .env
```

Update `.env` with your settings:

```env
APP_NAME=Tochka
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-onion-address.onion

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tochka_db
DB_USERNAME=tochka_user
DB_PASSWORD=your_secure_password

# Tor-specific settings
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
```

### 3. Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/tochka/tochka

# Set permissions
sudo chmod -R 755 /var/www/tochka/tochka
sudo chmod -R 775 /var/www/tochka/tochka/storage
sudo chmod -R 775 /var/www/tochka/tochka/bootstrap/cache
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database (creates admin user and mock data)
php artisan db:seed
```

### 5. Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer dump-autoload --optimize

# Cache views
php artisan view:cache
```

---

## Test Configuration

### 1. Check Services Status

```bash
# Check Tor
sudo systemctl status tor

# Check Apache
sudo systemctl status apache2

# Check PHP-FPM
sudo systemctl status php8.3-fpm

# Check PostgreSQL
sudo systemctl status postgresql
```

All should show: `active (running)`

### 2. Test Local Access

```bash
# Test from command line
curl -I http://127.0.0.1

# Should return: HTTP/1.1 200 OK
```

### 3. Test via Tor

**From another machine with Tor Browser**:

1. Open Tor Browser
2. Navigate to: `http://your-onion-address.onion`
3. Should see Tochka homepage

**From command line with Tor**:

```bash
# Install torsocks
sudo apt install torsocks

# Test via Tor
torsocks curl -I http://your-onion-address.onion

# Should return: HTTP/1.1 200 OK
```

### 4. Verify Admin Access

```bash
# Admin credentials (from seeder)
Username: admin
Password: admin123

# Change password after first login!
```

---

## Troubleshooting

### Service Unavailable (503) Error

**Symptom**: "Service Unavailable - The server is temporarily unable to service your request"

**Diagnosis**:

```bash
# Check PHP-FPM
sudo systemctl status php8.3-fpm

# Check PHP-FPM logs
sudo tail -50 /var/log/php8.3-fpm.log

# Check Apache logs
sudo tail -50 /var/log/apache2/tochka-tor-error.log

# Check Laravel logs
tail -50 /var/www/tochka/tochka/storage/logs/laravel.log
```

**Common Causes**:

1. **PHP-FPM not running**:
```bash
sudo systemctl start php8.3-fpm
sudo systemctl enable php8.3-fpm
```

2. **Wrong DocumentRoot**:
```bash
# Verify path exists
ls -la /var/www/tochka/tochka/public/index.php

# Update Apache config if needed
sudo nano /etc/apache2/sites-available/tochka-tor.conf
```

3. **Permission issues**:
```bash
sudo chown -R www-data:www-data /var/www/tochka/tochka
sudo chmod -R 775 /var/www/tochka/tochka/storage
sudo chmod -R 775 /var/www/tochka/tochka/bootstrap/cache
```

4. **Missing .env file**:
```bash
cd /var/www/tochka/tochka
cp .env.example .env
php artisan key:generate
```

5. **Laravel cache issues**:
```bash
cd /var/www/tochka/tochka
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Hidden Service Not Accessible

**Symptom**: Can't access .onion address

**Solutions**:

1. **Verify Tor is running**:
```bash
sudo systemctl status tor
sudo systemctl restart tor
```

2. **Check hidden service directory**:
```bash
ls -la /var/lib/tor/tochka/
# Should contain: hostname, private_key files
```

3. **Verify hostname**:
```bash
sudo cat /var/lib/tor/tochka/hostname
```

4. **Check Tor logs**:
```bash
sudo journalctl -u tor -n 50
```

5. **Test Tor connectivity**:
```bash
# Install torsocks
sudo apt install torsocks

# Test connection
torsocks curl -I http://check.torproject.org
```

### Apache Configuration Errors

```bash
# Test configuration
sudo apache2ctl configtest

# Common issues:
# - "DocumentRoot doesn't exist" → Update path
# - "Invalid ServerName" → Check .onion address
# - "Module not found" → Enable required modules
```

### Database Connection Errors

```bash
# Test database connection
psql -U tochka_user -d tochka_db -h localhost

# If connection fails:
sudo -u postgres psql

# Create user and database:
CREATE USER tochka_user WITH PASSWORD 'your_password';
CREATE DATABASE tochka_db OWNER tochka_user;
GRANT ALL PRIVILEGES ON DATABASE tochka_db TO tochka_user;
```

---

## Security Recommendations

### 1. Change Default Credentials

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('username', 'admin')->first();
$user->passphrase_hash = Hash::make('new-strong-password');
$user->save();
```

### 2. Disable Debug Mode

In `.env`:
```env
APP_DEBUG=false
```

### 3. Secure File Permissions

```bash
# Remove write permissions from config
sudo chmod 644 /var/www/tochka/tochka/.env

# Restrict storage access
sudo chmod 775 /var/www/tochka/tochka/storage
```

### 4. Configure Firewall

```bash
# Only allow local connections
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow from 127.0.0.1
sudo ufw enable
```

### 5. Regular Updates

```bash
# Update system
sudo apt update && sudo apt upgrade

# Update Tor
sudo apt update && sudo apt install tor

# Update Laravel dependencies
cd /var/www/tochka/tochka
composer update --no-dev
```

### 6. Enable Automated Backups

```bash
# Create backup script
sudo nano /usr/local/bin/tochka-backup.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/tochka"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
sudo -u postgres pg_dump tochka_db > $BACKUP_DIR/db_$DATE.sql

# Backup uploads (if any)
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz /var/www/tochka/tochka/storage/app

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/tochka-backup.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
0 2 * * * /usr/local/bin/tochka-backup.sh
```

---

## Performance Optimization

### 1. Enable OPcache

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Ensure OPcache is enabled:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 2. Optimize PHP-FPM

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```

Adjust pool settings:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10
```

### 3. Enable Apache Compression

Already configured in `tochka-tor.conf` via `mod_deflate`.

### 4. Database Optimization

```sql
-- Connect to database
psql -U tochka_user -d tochka_db

-- Create indexes (if not exists)
CREATE INDEX idx_items_user ON items(user_uuid);
CREATE INDEX idx_items_category ON items(item_category_id);
CREATE INDEX idx_packages_item ON packages(item_uuid);
```

---

## Monitoring

### Check Service Health

```bash
# Create monitoring script
nano ~/check-tochka.sh
```

```bash
#!/bin/bash

echo "=== Tochka Health Check ==="
echo ""

echo "Tor Status:"
systemctl is-active tor

echo "Apache Status:"
systemctl is-active apache2

echo "PHP-FPM Status:"
systemctl is-active php8.3-fpm

echo "PostgreSQL Status:"
systemctl is-active postgresql

echo ""
echo "HTTP Response:"
curl -I -s http://127.0.0.1 | head -1

echo ""
echo "Hidden Service Address:"
sudo cat /var/lib/tor/tochka/hostname
```

```bash
chmod +x ~/check-tochka.sh
~/check-tochka.sh
```

---

## Support Resources

- **TROUBLESHOOTING.md** - Common installation issues
- **LARAVEL_INSTALL.md** - Detailed Laravel setup
- **README.md** - Quick reference
- **GitHub Issues** - https://github.com/bitbybit91/tochka/issues

---

## Summary

You now have Tochka marketplace running as a Tor hidden service!

**Key Points**:
- Hidden service address: Check `/var/lib/tor/tochka/hostname`
- Admin login: admin / admin123 (change immediately!)
- Apache config: `/etc/apache2/sites-available/tochka-tor.conf`
- Laravel root: `/var/www/tochka/tochka`
- Logs: `/var/log/apache2/tochka-tor-error.log`

**Next Steps**:
1. Change admin password
2. Configure environment properly
3. Test all functionality
4. Set up automated backups
5. Monitor logs regularly
