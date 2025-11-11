# Tochka Free Market

**Modified by IO and CP**

Tochka Free Market is free Dark Net Marketplace (DNM) software for building secure, decentralized trading communities.

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)]()
[![Go Version](https://img.shields.io/badge/go-1.18+-blue)]()
[![License](https://img.shields.io/badge/license-MIT-green)]()

## ✨ Features

- 🔐 **Secure & Private**: Built with security and anonymity in mind
- 💰 **Cryptocurrency Support**: Bitcoin and Monero payments
- 🛡️ **Escrow System**: Built-in escrow for safe transactions
- 👥 **Multi-Vendor**: Support for multiple sellers/vendors
- 🔍 **Advanced Search**: Full-text search with Bleve
- 💬 **Messaging**: Private messaging and forum system
- ⭐ **Reviews & Ratings**: Reputation system for buyers and sellers
- 🌍 **Internationalization**: Multi-language support
- 📱 **Responsive Design**: Works on desktop and mobile

## 🚀 Quick Start

### Prerequisites

- **Go** 1.18 or higher
- **PostgreSQL** 12 or higher
- **Git**
- **Tor/Torsocks** (optional, for onion routing)

### Installation

```bash
# Clone the repository
git clone https://github.com/bitbybit91/tochka.git
cd tochka

# Install dependencies
go mod download

# Build the application
go build -o tochka

# Set up database
createdb tochka_db
psql tochka_db < dumps/cities.sql
psql tochka_db < dumps/countries.sql

# Initialize database models
./tochka sync

# Configure settings
cp settings.json.example settings.json
# Edit settings.json with your configuration

# Run the server
./tochka server
```

Visit `http://localhost:8081` in your browser.

### First User Setup

```bash
# Register via web interface, then grant admin privileges
./tochka user <your_username> grant admin
```

## 📚 Documentation

Comprehensive documentation is available:

- **[INSTALL.md](INSTALL.md)** - Complete installation and setup guide
- **[CUSTOMIZATION.md](CUSTOMIZATION.md)** - Customization and extension guide
- **[SEO_GUIDE.md](SEO_GUIDE.md)** - SEO optimization strategies
- **[REST_API.md](REST_API.md)** - API documentation

## 🛠️ CLI Commands

```bash
# Database management
./tochka sync                    # Sync database models

# User management
./tochka user <name> grant admin # Grant admin privileges
./tochka user <name> grant seller # Grant seller privileges

# Mock data (for testing)
./tochka seed-mock-users         # Create 19 test vendors
./tochka seed-mock-listings      # Create 304 test products

# Utilities
./tochka index                   # Re-index items for search
./tochka import-metro            # Import metro station data
./tochka staff-stats             # Generate staff statistics

# Server
./tochka server                  # Start web server
```

## 🎨 Customization

Tochka is highly customizable:

- **Themes**: Modify CSS in `static/css/`
- **Templates**: Edit Amber templates in `templates/`
- **Payment Methods**: Add custom cryptocurrency support
- **Localization**: Add new languages in `locales/`
- **Categories**: Configure product categories
- **Email Templates**: Customize notifications

See [CUSTOMIZATION.md](CUSTOMIZATION.md) for details.

## 🔧 Configuration

Key settings in `settings.json`:

```json
{
  "host": "127.0.0.1",
  "port": "8081",
  "postgres_connection_string": "host=localhost...",
  "bitcoin_node_address": "localhost:8332",
  "monero_node_address": "localhost:18081",
  "session_store_secret": "your-secret-key",
  "debug": true
}
```

## 🏗️ Architecture

```
tochka/
├── modules/
│   ├── marketplace/     # Core marketplace logic
│   ├── apis/            # Payment API integrations
│   ├── util/            # Utility functions
│   ├── settings/        # Configuration
│   └── bots/            # Bot integrations
├── templates/           # Amber template files
├── static/              # CSS, JS, images
├── public/              # Public assets
├── dumps/               # Database dumps
└── data/                # Runtime data
```

## 🧪 Development

```bash
# Run in debug mode
./tochka server

# Run tests
go test ./...

# Build for production
go build -ldflags="-s -w" -o tochka
```

## 📦 Dependencies

- **Web Framework**: gocraft/web
- **ORM**: GORM
- **Database**: PostgreSQL
- **Search**: Bleve
- **Templates**: Amber
- **Crypto**: Bitcoin Core, Monero

## 🔒 Security

- HTTPS/TLS encryption recommended
- Two-factor authentication support
- PGP message encryption
- Escrow protection
- Regular security audits

See security best practices in [INSTALL.md](INSTALL.md).

## 🌐 SEO Optimization

Tochka includes SEO-friendly features:

- Clean URL structures
- Meta tags and Open Graph
- XML sitemaps
- Schema.org structured data
- Performance optimization
- Mobile-responsive design

See [SEO_GUIDE.md](SEO_GUIDE.md) for optimization strategies.

## 📈 Production Deployment

For production deployment:

1. Set `debug: false` in settings.json
2. Configure SSL/TLS certificates
3. Set up reverse proxy (nginx/Apache)
4. Configure firewall rules
5. Enable automated backups
6. Set up monitoring and logging

Detailed instructions in [INSTALL.md](INSTALL.md).

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests
5. Submit a pull request

## 📄 License
 
The MIT License (MIT)

Copyright (c) 2015 Chris Kibble

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## 🙏 Acknowledgments

- Original Tochka Free Market project
- All contributors and community members
- Open source libraries and tools used

## 📞 Support

For issues and questions:
- **GitHub Issues**: https://github.com/bitbybit91/tochka/issues
- **Documentation**: Check the guides in this repository
- **Security Issues**: Report privately to maintainers

---

**Note**: This software is provided for educational and research purposes. Users are responsible for ensuring compliance with applicable laws and regulations in their jurisdiction.
