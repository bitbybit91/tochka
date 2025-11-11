# Tochka Free Market - Customization Guide

Complete guide for customizing and extending Tochka Free Market.

## Table of Contents

- [Overview](#overview)
- [Configuration Options](#configuration-options)
- [Theme Customization](#theme-customization)
- [Template Customization](#template-customization)
- [Adding Payment Methods](#adding-payment-methods)
- [Custom Categories](#custom-categories)
- [Email Templates](#email-templates)
- [Localization](#localization)
- [Custom Modules](#custom-modules)
- [API Extensions](#api-extensions)
- [Database Customization](#database-customization)

---

## Overview

Tochka is designed to be highly customizable. This guide will help you modify various aspects of the marketplace to suit your needs.

---

## Configuration Options

### Application Settings (settings.json)

#### Core Settings

```json
{
  "debug": false,
  "host": "0.0.0.0",
  "port": "8081",
  "site_name": "Tochka Free Market",
  "site_description": "Decentralized Marketplace",
  "default_language": "en",
  "default_currency": "USD"
}
```

#### Database Configuration

```json
{
  "postgres_connection_string": "host=localhost port=5432 user=tochka_user password=pass dbname=tochka_db sslmode=disable",
  "max_db_connections": 8,
  "db_log_mode": false
}
```

#### Security Settings

```json
{
  "session_store_secret": "your-32-char-secret-key-here",
  "csrf_encryption_key": "your-csrf-key-here",
  "password_min_length": 12,
  "enable_2fa": true,
  "max_login_attempts": 5,
  "login_lockout_duration": 900
}
```

#### Cryptocurrency Settings

```json
{
  "bitcoin_network": "mainnet",
  "bitcoin_node_address": "localhost:8332",
  "bitcoin_node_user": "rpcuser",
  "bitcoin_node_password": "rpcpassword",
  "bitcoin_confirmation_required": 3,
  
  "monero_node_address": "localhost:18081",
  "monero_wallet_rpc_address": "localhost:18082",
  "monero_wallet_rpc_user": "monero",
  "monero_wallet_rpc_password": "password",
  "monero_confirmation_required": 10
}
```

#### Feature Flags

```json
{
  "enable_multisig": true,
  "enable_escrow": true,
  "enable_disputes": true,
  "enable_referrals": true,
  "enable_reviews": true,
  "enable_messageboard": true,
  "enable_shoutbox": true,
  "require_vendor_bond": false,
  "vendor_bond_amount": 1000.00
}
```

#### Email/Notification Settings

```json
{
  "smtp_server": "smtp.gmail.com",
  "smtp_port": "587",
  "smtp_user": "noreply@yourmarket.com",
  "smtp_password": "your_password",
  "smtp_from_name": "Tochka Market",
  "smtp_from_email": "noreply@yourmarket.com",
  
  "mattermost_incoming_hook_support": "https://mattermost.com/hooks/xxx",
  "mattermost_incoming_hook_transactions": "https://mattermost.com/hooks/xxx",
  "enable_email_notifications": true
}
```

#### Marketplace Settings

```json
{
  "commission_rate": 0.05,
  "min_listing_price": 1.00,
  "max_listing_price": 1000000.00,
  "enable_auto_finalize": true,
  "auto_finalize_days": 14,
  "dispute_resolution_days": 7,
  "vacation_mode_enabled": true
}
```

---

## Theme Customization

### Static Assets Location

- **CSS**: `static/css/`
- **JavaScript**: `static/js/`
- **Images**: `static/images/`
- **Fonts**: `static/fonts/`

### Main Stylesheet

Edit `static/css/main.css`:

```css
/* Custom color scheme */
:root {
  --primary-color: #3498db;
  --secondary-color: #2ecc71;
  --background-color: #ecf0f1;
  --text-color: #2c3e50;
  --border-color: #bdc3c7;
  --error-color: #e74c3c;
  --success-color: #27ae60;
}

/* Custom header styling */
.header {
  background-color: var(--primary-color);
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Custom button styling */
.btn-primary {
  background-color: var(--primary-color);
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background-color: #2980b9;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
```

### Logo Customization

Replace the logo files:

```bash
# Copy your logo
cp your_logo.png public/images/logo.png
cp your_logo_small.png public/images/logo_small.png

# Or edit in settings
# public/images/logo.svg for vector graphics
```

### Favicon

Replace `public/favicon.ico` with your custom favicon:

```bash
cp your_favicon.ico public/favicon.ico
```

---

## Template Customization

### Template Location

Templates are located in `templates/` directory using Amber template engine.

### Directory Structure

```
templates/
├── layout/
│   ├── base.amber          # Main layout wrapper
│   ├── header.amber        # Header component
│   └── footer.amber        # Footer component
├── auth/
│   ├── login.amber         # Login page
│   ├── register.amber      # Registration page
│   └── profile.amber       # User profile
├── items/
│   ├── list.amber          # Item listing page
│   ├── detail.amber        # Item detail page
│   └── create.amber        # Create item form
├── wallet/
│   ├── bitcoin.amber       # Bitcoin wallet
│   └── monero.amber        # Monero wallet
└── admin/
    ├── dashboard.amber     # Admin dashboard
    └── users.amber         # User management
```

### Editing Templates

Templates use Amber syntax (similar to Jade/Pug):

```amber
// templates/layout/base.amber
doctype html
html
  head
    title #{PageTitle} - #{SiteName}
    link[rel="stylesheet"][href="/static/css/main.css"]
    block head
  body
    include header
    
    .container
      block content
    
    include footer
    
    block scripts
      script[src="/static/js/main.js"]
```

### Custom Page Example

Create `templates/custom/landing.amber`:

```amber
extends ../layout/base

block content
  .hero-section
    h1 Welcome to #{SiteName}
    p Your secure marketplace
    
    .features
      .feature
        h3 🔒 Secure
        p End-to-end encryption
      
      .feature
        h3 💰 Cryptocurrency
        p Bitcoin & Monero accepted
      
      .feature
        h3 🌍 Global
        p Worldwide shipping
  
  .cta-section
    a.btn.btn-primary[href="/register"] Get Started
    a.btn.btn-secondary[href="/items"] Browse Products
```

---

## Adding Payment Methods

### Adding Monero Support (if not already present)

1. **Create Monero Wallet Model** - `modules/marketplace/models_wallet_monero.go`:

```go
package marketplace

import (
	"time"
	"github.com/bitbybit91/tochka/modules/apis"
	"github.com/bitbybit91/tochka/modules/util"
)

type UserMoneroWallet struct {
	Uuid          string     `json:"uuid" gorm:"primary_key"`
	Address       string     `json:"address" gorm:"unique"`
	UserUuid      string     `json:"user_uuid" gorm:"index"`
	PublicKey     string     `json:"public_key"`
	PrivateKey    string     `json:"private_key"`
	Balance       float64    `json:"balance"`
	UnlockedBalance float64  `json:"unlocked_balance"`
	CreatedAt     *time.Time `json:"created_at"`
	UpdatedAt     *time.Time `json:"updated_at"`
}

func (w *UserMoneroWallet) UpdateBalance() error {
	// Implementation for Monero balance checking
	balance, err := apis.GetMoneroBalance(w.Address)
	if err != nil {
		return err
	}
	w.Balance = balance
	return w.Save()
}

func (w *UserMoneroWallet) Save() error {
	return database.Save(&w).Error
}
```

2. **Create Monero Transaction Model** - `modules/marketplace/models_transaction_cc_monero.go`:

```go
package marketplace

import (
	"time"
)

type MoneroTransaction struct {
	Uuid              string     `json:"uuid" gorm:"primary_key"`
	TxHash            string     `json:"tx_hash"`
	Amount            float64    `json:"amount"`
	Confirmations     int        `json:"confirmations"`
	CreatedAt         *time.Time `json:"created_at"`
	UpdatedAt         *time.Time `json:"updated_at"`
}

func (mt *MoneroTransaction) CheckConfirmations() error {
	// Implementation for checking Monero confirmations
	return nil
}
```

3. **Add Monero API** - `modules/apis/payments_monero.go`:

```go
package apis

import (
	"encoding/json"
	"net/http"
)

func GetMoneroBalance(address string) (float64, error) {
	// Implement Monero RPC call
	return 0.0, nil
}

func CreateMoneroTransaction(to string, amount float64) (string, error) {
	// Implement Monero transaction creation
	return "", nil
}
```

4. **Update Database Sync** - Add to `modules/marketplace/models.go`:

```go
func SyncModels() {
	database.AutoMigrate(
		// ... existing models ...
		&UserMoneroWallet{},
		&MoneroTransaction{},
	)
	// ... rest of sync code ...
}
```

---

## Custom Categories

### Adding New Item Categories

Edit the database or create a migration:

```sql
-- Add new categories
INSERT INTO item_categories (id, name, parent_id, icon, description) VALUES
(100, 'Electronics', NULL, 'fa-laptop', 'Electronic devices and gadgets'),
(101, 'Books', NULL, 'fa-book', 'Physical and digital books'),
(102, 'Art', NULL, 'fa-palette', 'Artwork and creative items');
```

### Category Configuration

Create `categories.json`:

```json
[
  {
    "id": 1,
    "name": "Digital Goods",
    "icon": "fa-download",
    "subcategories": [
      {"id": 10, "name": "Software"},
      {"id": 11, "name": "E-books"},
      {"id": 12, "name": "Music"}
    ]
  },
  {
    "id": 2,
    "name": "Physical Goods",
    "icon": "fa-box",
    "subcategories": [
      {"id": 20, "name": "Electronics"},
      {"id": 21, "name": "Clothing"},
      {"id": 22, "name": "Books"}
    ]
  }
]
```

---

## Email Templates

### Location

Email templates are in `templates/emails/`

### Creating Custom Email Template

Create `templates/emails/custom_notification.amber`:

```amber
doctype html
html
  head
    style
      body { font-family: Arial, sans-serif; }
      .container { max-width: 600px; margin: 0 auto; }
      .header { background: #3498db; color: white; padding: 20px; }
      .content { padding: 20px; }
  body
    .container
      .header
        h1 #{SiteName}
      .content
        h2 Hello #{Username}!
        p #{Message}
        a.button[href="#{ActionURL}"] #{ActionText}
      .footer
        p &copy; #{Year} #{SiteName}. All rights reserved.
```

### Send Email Function

```go
func SendCustomEmail(to, username, message string) error {
	data := map[string]interface{}{
		"SiteName":  "Tochka",
		"Username":  username,
		"Message":   message,
		"ActionURL": "https://yoursite.com/action",
		"ActionText": "Click Here",
		"Year":      time.Now().Year(),
	}
	
	return util.SendEmail(to, "Custom Notification", "emails/custom_notification", data)
}
```

---

## Localization

### Adding New Language

1. **Create Language File** - `locales/es.json`:

```json
{
  "welcome": "Bienvenido",
  "login": "Iniciar sesión",
  "register": "Registrarse",
  "items": "Artículos",
  "wallet": "Cartera",
  "profile": "Perfil",
  "logout": "Cerrar sesión",
  "search": "Buscar",
  "add_to_cart": "Añadir al carrito",
  "checkout": "Pagar",
  "seller": "Vendedor",
  "buyer": "Comprador"
}
```

2. **Update Language Support** - `modules/util/localization.go`:

```go
var SupportedLanguages = map[string]string{
	"en": "English",
	"es": "Español",
	"de": "Deutsch",
	"fr": "Français",
	"ru": "Русский",
}
```

---

## Custom Modules

### Creating a Custom Module

Create `modules/custom/my_feature.go`:

```go
package custom

import (
	"github.com/bitbybit91/tochka/modules/marketplace"
)

type MyFeature struct {
	Enabled bool
	Config  map[string]interface{}
}

func (f *MyFeature) Initialize() error {
	// Initialization code
	return nil
}

func (f *MyFeature) Execute() error {
	// Feature logic
	return nil
}
```

### Integrate Module

In `server.go` or appropriate router:

```go
import "github.com/bitbybit91/tochka/modules/custom"

func setupCustomFeatures() {
	myFeature := custom.MyFeature{
		Enabled: true,
		Config: map[string]interface{}{
			"option1": "value1",
		},
	}
	
	if err := myFeature.Initialize(); err != nil {
		log.Fatal(err)
	}
}
```

---

## API Extensions

### Adding Custom API Endpoint

Edit `modules/marketplace/router.go`:

```go
// Custom API routes
apiRouter := router.Subrouter(Context{}, "/api/v1")
apiRouter.Get("/custom/stats", (*Context).CustomStatsAPI)
apiRouter.Post("/custom/action", (*Context).CustomActionAPI)
```

### Implement API Handler

In `modules/marketplace/views_api.go`:

```go
func (c *Context) CustomStatsAPI(w web.ResponseWriter, r *web.Request) {
	stats := map[string]interface{}{
		"total_items": CountAllItems(),
		"total_users": CountAllUsers(),
		"total_transactions": CountAllTransactions(),
	}
	
	util.APIResponse(w, r, stats)
}
```

---

## Database Customization

### Adding Custom Fields

1. **Extend User Model**:

```go
type User struct {
	// ... existing fields ...
	
	// Custom fields
	PhoneNumber      string    `json:"phone_number"`
	TelegramUsername string    `json:"telegram_username"`
	CustomField1     string    `json:"custom_field_1"`
	CustomField2     int       `json:"custom_field_2"`
}
```

2. **Run Migration**:

```bash
./tochka sync
```

### Custom Database Views

Add to `modules/marketplace/models_db_views.go`:

```go
func setupCustomViews() {
	database.Exec(`
		CREATE OR REPLACE VIEW v_custom_stats AS (
			SELECT 
				u.uuid,
				u.username,
				COUNT(i.uuid) as item_count,
				SUM(t.amount) as total_sales
			FROM users u
			LEFT JOIN items i ON u.uuid = i.user_uuid
			LEFT JOIN transactions t ON i.uuid = t.item_uuid
			GROUP BY u.uuid, u.username
		)
	`)
}
```

---

## Advanced Customization

### Custom Middleware

Create `modules/marketplace/middleware_custom.go`:

```go
func (c *Context) CustomAuthMiddleware(rw web.ResponseWriter, req *web.Request, next web.NextMiddlewareFunc) {
	// Custom authentication logic
	if c.ViewUser == nil {
		http.Redirect(rw, req.Request, "/login", 302)
		return
	}
	
	// Check custom permissions
	if !c.ViewUser.HasCustomPermission() {
		http.Error(rw, "Forbidden", 403)
		return
	}
	
	next(rw, req)
}
```

### Custom Validation

```go
func (i *Item) CustomValidate() error {
	if len(i.Name) < 5 {
		return errors.New("Name must be at least 5 characters")
	}
	
	if i.Price < 10.0 {
		return errors.New("Minimum price is $10")
	}
	
	// Custom business logic
	if i.IsRestrictedCategory() {
		return errors.New("This category requires special approval")
	}
	
	return nil
}
```

---

## Performance Optimization

### Caching Configuration

```go
import "github.com/bluele/gcache"

var ItemCache = gcache.New(1000).
	LRU().
	Expiration(time.Hour).
	Build()

func GetCachedItem(uuid string) (*Item, error) {
	value, err := ItemCache.Get(uuid)
	if err == nil {
		return value.(*Item), nil
	}
	
	item, err := FindItemByUuid(uuid)
	if err != nil {
		return nil, err
	}
	
	ItemCache.Set(uuid, item)
	return item, nil
}
```

---

## Testing Custom Features

### Unit Tests

Create `modules/custom/my_feature_test.go`:

```go
package custom

import (
	"testing"
)

func TestMyFeature(t *testing.T) {
	feature := MyFeature{Enabled: true}
	
	if err := feature.Initialize(); err != nil {
		t.Errorf("Initialization failed: %v", err)
	}
	
	if err := feature.Execute(); err != nil {
		t.Errorf("Execution failed: %v", err)
	}
}
```

### Integration Tests

```bash
# Run tests
go test ./...

# Run specific package tests
go test ./modules/custom

# Run with coverage
go test -cover ./...
```

---

## Best Practices

1. **Always Backup**: Backup database before making structural changes
2. **Version Control**: Use git to track customizations
3. **Documentation**: Document all custom modifications
4. **Testing**: Test thoroughly in development before deploying
5. **Security**: Validate and sanitize all user inputs
6. **Performance**: Monitor performance impact of customizations
7. **Upgrades**: Keep customizations modular for easier updates

---

## Support

For customization help:
- Check the source code comments
- Review existing modules for patterns
- Join the community forums
- Submit issues on GitHub

---

## Next Steps

- Review [SEO_GUIDE.md](SEO_GUIDE.md) for search optimization
- Check [INSTALL.md](INSTALL.md) for setup details
- Explore the codebase in `modules/` directory
- Test your customizations thoroughly
