# Troubleshooting Guide

Quick solutions for common installation issues.

## NPM Package.json Not Found

**Error**: `npm error enoent Could not read package.json`

### Cause
The `package.json` file is missing from your installation. This usually happens when:
- Git repository was not fully cloned
- Files were copied manually without all files
- Wrong directory location

### Solution

1. **Verify you're in the correct directory**:
```bash
pwd
# Should show: /var/www/tochka/tochka or your installation path

ls -la package.json
# Should show: -rw-r--r-- 1 user user 383 Nov 11 package.json
```

2. **If package.json is missing, pull the latest code**:
```bash
# Check git status
git status

# Pull latest changes
git pull origin copilot/update-codebase-to-laravel

# Or reset to latest remote
git fetch origin
git reset --hard origin/copilot/update-codebase-to-laravel
```

3. **Verify all Laravel/NPM files are present**:
```bash
ls -la | grep -E "(package.json|composer.json|artisan|vite.config)"
```

You should see:
- `artisan` - Laravel command-line tool
- `composer.json` - PHP dependencies
- `package.json` - NPM dependencies
- `vite.config.js` - Build configuration
- `postcss.config.js` - PostCSS configuration
- `tailwind.config.js` - Tailwind CSS configuration

4. **If files are still missing, re-clone the repository**:
```bash
cd /var/www/tochka
rm -rf tochka
git clone -b copilot/update-codebase-to-laravel https://github.com/bitbybit91/tochka.git tochka
cd tochka
```

5. **After confirming files exist, run npm install**:
```bash
npm install
npm run build
```

---

## Composer "Running as Root" Warning

**Warning**: `Do not run Composer as root/super user!`

### Solution

This is a warning, not an error. However, for security:

1. **Option 1: Continue as root (if necessary)**:
```bash
composer install --optimize-autoloader --no-dev
# Type 'yes' when prompted
```

2. **Option 2: Use proper user (recommended)**:
```bash
# Create or use existing non-root user
sudo -u www-data composer install --optimize-autoloader --no-dev

# Or switch to the user
su - www-data
composer install --optimize-autoloader --no-dev
```

3. **Option 3: Use environment variable**:
```bash
COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader --no-dev
```

---

## Node.js Version Issues

**Error**: NPM commands fail or produce errors

### Solution

Ensure Node.js 18+ is installed:

```bash
# Check Node version
node --version
# Should show: v18.x.x or higher

# If version is old, install newer version
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify
node --version
npm --version
```

---

## Permission Errors After Installation

**Error**: Permission denied errors when running Laravel

### Solution

```bash
# Set correct ownership (www-data is Apache user)
sudo chown -R www-data:www-data /var/www/tochka/tochka

# Set correct permissions for storage and cache
sudo chmod -R 775 /var/www/tochka/tochka/storage
sudo chmod -R 775 /var/www/tochka/tochka/bootstrap/cache

# Verify
ls -la storage/ | head -5
```

---

## Apache Configuration Issues

**Error**: Apache doesn't serve the site or shows errors

### Solution

1. **Verify configuration file exists**:
```bash
ls -la /etc/apache2/sites-available/tochka.conf
```

2. **Check configuration syntax**:
```bash
sudo apache2ctl configtest
```

3. **Ensure site is enabled**:
```bash
sudo a2ensite tochka.conf
sudo a2enmod rewrite headers proxy_fcgi setenvif
sudo a2enconf php8.3-fpm
```

4. **Update DocumentRoot in config**:
```bash
sudo nano /etc/apache2/sites-available/tochka.conf
# Change DocumentRoot to your actual path: /var/www/tochka/tochka/public
```

5. **Restart Apache**:
```bash
sudo systemctl restart apache2
sudo systemctl status apache2
```

---

## Database Connection Errors

**Error**: SQLSTATE connection errors

### Solution

1. **Update .env file**:
```bash
nano .env

# Ensure these are correct:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tochka_db
DB_USERNAME=tochka_user
DB_PASSWORD=your_password
```

2. **Test database connection**:
```bash
psql -U tochka_user -d tochka_db -h localhost
```

3. **Clear config cache**:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Creating Admin Users

**Issue**: No admin user exists after seeding, or need to create additional admin users.

### Solution 1: Run Database Seeder

The seeder now creates a default admin user:

```bash
php artisan db:seed
```

**Default Admin Credentials**:
- Username: `admin`
- Password: `admin123`

### Solution 2: Create New Admin User

Use the custom artisan command:

```bash
# Create a new admin user
php artisan user:create-admin myusername mypassword

# Example:
php artisan user:create-admin admin admin123
```

### Solution 3: Grant Admin to Existing User

Make an existing user an admin:

```bash
php artisan user:make-admin username

# Example:
php artisan user:make-admin Plugutopia
```

### Solution 4: Using Tinker

Manually create or update a user:

```bash
php artisan tinker
```

Then run:

```php
// Create new admin user
$user = App\Models\User::create([
    'uuid' => (string) Str::uuid(),
    'username' => 'admin',
    'passphrase_hash' => Hash::make('admin123'),
    'registration_date' => now(),
    'invite_code' => (string) Str::uuid(),
    'is_admin' => true,
    'is_staff' => true,
]);

// Or update existing user
$user = App\Models\User::where('username', 'someuser')->first();
if ($user) {
    $user->is_admin = true;
    $user->is_staff = true;
    $user->save();
    echo "User updated to admin\n";
} else {
    echo "User not found\n";
}
```

**Common Tinker Error**: `Attempt to assign property on null`

This happens when the user doesn't exist. Always check if `$user` is not null:

```php
$user = App\Models\User::where('username', 'admin')->first();

if ($user === null) {
    echo "User 'admin' not found. Create it first.\n";
} else {
    $user->is_admin = true;
    $user->save();
    echo "Admin privileges granted.\n";
}
```

---

## Quick Verification Checklist

After installation, verify everything is working:

```bash
# 1. Check Laravel is installed
php artisan --version
# Should show: Laravel Framework 11.46.1

# 2. Check routes are registered
php artisan route:list
# Should show 10 routes

# 3. Check database connection
php artisan migrate:status

# 4. Check file permissions
ls -la storage/ bootstrap/cache/

# 5. Check Apache configuration
sudo apache2ctl -t

# 6. Check Apache is running
sudo systemctl status apache2

# 7. Test the site
curl -I http://localhost
# Should return: HTTP/1.1 200 OK
```

---

## Getting More Help

If issues persist:

1. **Check Laravel logs**:
```bash
tail -f storage/logs/laravel.log
```

2. **Check Apache logs**:
```bash
sudo tail -f /var/log/apache2/tochka-error.log
```

3. **Check PHP-FPM logs**:
```bash
sudo tail -f /var/log/php8.3-fpm.log
```

4. **Enable debug mode temporarily** (`.env`):
```env
APP_DEBUG=true
```

5. **Check GitHub issues**: https://github.com/bitbybit91/tochka/issues

---

## Complete Reinstallation

If all else fails, start fresh:

```bash
# 1. Remove old installation
cd /var/www/tochka
sudo rm -rf tochka

# 2. Clone repository
git clone -b copilot/update-codebase-to-laravel https://github.com/bitbybit91/tochka.git tochka
cd tochka

# 3. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 4. Configure
cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials

# 5. Database
php artisan migrate
php artisan db:seed

# 6. Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 7. Apache
sudo cp apache-config/tochka.conf /etc/apache2/sites-available/
# Edit DocumentRoot in tochka.conf to match your path
sudo a2ensite tochka.conf
sudo systemctl restart apache2

# 8. Test
curl -I http://localhost
```

---

For detailed installation instructions, see [LARAVEL_INSTALL.md](LARAVEL_INSTALL.md).
