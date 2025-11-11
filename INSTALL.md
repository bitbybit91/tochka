# Tochka Free Market - Installation Guide

Complete step-by-step installation and setup guide for Tochka Free Market.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation Steps](#installation-steps)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [CLI Commands](#cli-commands)
- [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before installing Tochka, ensure you have the following installed on your system:

### Required Software

1. **Go (Golang)** - Version 1.18 or higher
   - Download: https://golang.org/dl/
   - Verify installation: `go version`

2. **PostgreSQL** - Version 12 or higher
   - Download: https://www.postgresql.org/download/
   - Verify installation: `psql --version`

3. **Git**
   - Download: https://git-scm.com/downloads
   - Verify installation: `git --version`

4. **Tor/Torsocks** (Optional, for onion routing)
   - Download: https://www.torproject.org/download/
   - Verify installation: `tor --version`

### System Requirements

- **RAM**: Minimum 2GB, Recommended 4GB+
- **Disk Space**: At least 1GB free space
- **OS**: Linux, macOS, or Windows (WSL recommended for Windows)

---

## Installation Steps

### Step 1: Clone the Repository

```bash
# Clone the repository
git clone https://github.com/bitbybit91/tochka.git

# Navigate to the project directory
cd tochka
```

### Step 2: Install Go Dependencies

```bash
# Download and install all Go dependencies
go mod download

# Verify dependencies
go mod verify
```

### Step 3: Build the Application

```bash
# Build the binary
go build -o tochka

# Verify the build
./tochka --help
```

If the build is successful, you'll see a `tochka` binary in the current directory.

---

## Database Setup

### Step 1: Create PostgreSQL Database

```bash
# Switch to postgres user (Linux/macOS)
sudo -u postgres psql

# Or connect directly (if configured)
psql -U postgres
```

### Step 2: Create Database and User

```sql
-- Create the database
CREATE DATABASE tochka_db;

-- Create a user (optional, for better security)
CREATE USER tochka_user WITH PASSWORD 'your_secure_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE tochka_db TO tochka_user;

-- Exit psql
\q
```

### Step 3: Import Initial Data

```bash
# Import cities data
psql -U postgres -d tochka_db -f dumps/cities.sql

# Import countries data
psql -U postgres -d tochka_db -f dumps/countries.sql
```

### Step 4: Sync Database Models

```bash
# This will create all necessary tables and views
./tochka sync
```

---

## Configuration

### Step 1: Create Configuration File

```bash
# Copy the example configuration
cp settings.json.example settings.json
```

### Step 2: Edit Configuration

Open `settings.json` in your favorite text editor and configure the following:

```json
{
  "debug": true,
  "host": "127.0.0.1",
  "port": "8081",
  "postgres_connection_string": "host=localhost port=5432 user=tochka_user password=your_secure_password dbname=tochka_db sslmode=disable",
  
  "bitcoin_network": "mainnet",
  "bitcoin_node_address": "localhost:8332",
  "bitcoin_node_user": "your_rpc_user",
  "bitcoin_node_password": "your_rpc_password",
  
  "monero_node_address": "localhost:18081",
  "monero_wallet_rpc_address": "localhost:18082",
  
  "session_store_secret": "change_this_to_a_random_string_32_chars",
  "csrf_encryption_key": "change_this_to_another_random_string",
  
  "mattermost_incoming_hook_support": "",
  "mattermost_incoming_hook_authentication": "",
  "mattermost_incoming_hook_messageboard": "",
  
  "smtp_server": "smtp.gmail.com",
  "smtp_port": "587",
  "smtp_user": "your_email@gmail.com",
  "smtp_password": "your_email_password"
}
```

### Important Configuration Options

- **debug**: Set to `true` for development, `false` for production
- **host**: Server IP address (use `0.0.0.0` to listen on all interfaces)
- **port**: Server port (default: 8081)
- **postgres_connection_string**: PostgreSQL connection details
- **bitcoin_node_address**: Bitcoin Core RPC endpoint
- **monero_node_address**: Monero daemon endpoint
- **session_store_secret**: Random 32-character string for session encryption
- **csrf_encryption_key**: Random string for CSRF protection

---

## Running the Application

### Development Mode

```bash
# Run the server in debug mode
./tochka server
```

The application will be available at: `http://localhost:8081`

### Production Mode

1. **Edit settings.json**:
   - Set `debug: false`
   - Configure proper host/port
   - Set up SSL/TLS (recommended)

2. **Run with systemd** (Linux):

Create `/etc/systemd/system/tochka.service`:

```ini
[Unit]
Description=Tochka Free Market
After=network.target postgresql.service

[Service]
Type=simple
User=tochka
WorkingDirectory=/opt/tochka
ExecStart=/opt/tochka/tochka server
Restart=on-failure
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Start the service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable tochka
sudo systemctl start tochka
sudo systemctl status tochka
```

3. **Run with Docker** (Alternative):

```bash
# Build Docker image
docker build -t tochka .

# Run container
docker run -d -p 8081:8081 -v $(pwd)/settings.json:/app/settings.json tochka
```

---

## CLI Commands

Tochka provides several command-line utilities:

### Sync Database Models

Creates/updates all database tables and views:

```bash
./tochka sync
```

### Create/Manage Users

```bash
# Grant admin privileges to a user
./tochka user <username> grant admin

# Grant seller privileges to a user
./tochka user <username> grant seller
```

### Seed Mock Data

```bash
# Create 19 mock vendor users
./tochka seed-mock-users

# Create mock product listings for all vendors
./tochka seed-mock-listings
```

### Index Items

Re-index all items for search functionality:

```bash
./tochka index
```

### Import Metro Stations

Import metro station data for supported cities:

```bash
./tochka import-metro
```

### Staff Statistics

Generate staff performance statistics:

```bash
./tochka staff-stats
```

### Run Server

Start the web server:

```bash
./tochka server
# or simply:
./tochka
```

---

## First-Time Setup Workflow

Follow this sequence for a complete setup:

```bash
# 1. Build the application
go build -o tochka

# 2. Set up database
psql -U postgres -d tochka_db -f dumps/cities.sql
psql -U postgres -d tochka_db -f dumps/countries.sql

# 3. Sync models
./tochka sync

# 4. Create mock data (optional, for testing)
./tochka seed-mock-users
./tochka seed-mock-listings

# 5. Index items
./tochka index

# 6. Start server
./tochka server
```

### Creating Your First Admin User

1. Start the server: `./tochka server`
2. Open browser: `http://localhost:8081`
3. Click "Register" and create an account
4. Stop the server (Ctrl+C)
5. Grant admin privileges:
   ```bash
   ./tochka user your_username grant admin
   ```
6. Restart the server and login

---

## Troubleshooting

### Port Already in Use

```bash
# Find process using port 8081
lsof -i :8081  # macOS/Linux
netstat -ano | findstr :8081  # Windows

# Kill the process or change port in settings.json
```

### Database Connection Error

- Verify PostgreSQL is running: `sudo systemctl status postgresql`
- Check connection string in `settings.json`
- Ensure database exists: `psql -U postgres -l`
- Check firewall settings

### Build Errors

```bash
# Clean and rebuild
go clean
rm -rf go.sum
go mod tidy
go build -o tochka
```

### Permission Denied

```bash
# Make binary executable
chmod +x tochka
```

### Bitcoin/Monero Node Connection Issues

- Ensure nodes are running and synchronized
- Verify RPC credentials in settings.json
- Check node configuration files (bitcoin.conf, monero.conf)
- Test connection: `curl --user user:pass --data-binary '{"jsonrpc":"1.0","id":"test","method":"getblockcount","params":[]}' -H 'content-type: text/plain;' http://127.0.0.1:8332/`

### Missing Dependencies

```bash
# Reinstall all dependencies
go mod download
go mod verify
```

---

## Security Recommendations

1. **Change Default Credentials**: Update all passwords in settings.json
2. **Use HTTPS**: Set up SSL/TLS certificates (Let's Encrypt recommended)
3. **Firewall Configuration**: Only expose necessary ports
4. **Regular Backups**: Back up database and configuration regularly
5. **Update Regularly**: Keep dependencies and system packages updated
6. **Run as Non-Root**: Create dedicated user for running the application
7. **Enable 2FA**: Configure two-factor authentication for admin accounts
8. **Monitor Logs**: Regularly check application and system logs

---

## Next Steps

- Read [CUSTOMIZATION.md](CUSTOMIZATION.md) for customization options
- Read [SEO_GUIDE.md](SEO_GUIDE.md) for SEO optimization
- Configure payment processors (Bitcoin, Monero)
- Set up automated backups
- Configure monitoring and alerting
- Review security settings

---

## Support

For issues and questions:
- GitHub Issues: https://github.com/bitbybit91/tochka/issues
- Documentation: Check the README.md file
- Community: Join the discussion forum

---

## License

MIT License - See LICENSE file for details
