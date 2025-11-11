# Tochka Free Market - SEO Optimization Guide

Complete guide for optimizing your Tochka marketplace for search engines.

## Table of Contents

- [SEO Fundamentals](#seo-fundamentals)
- [On-Page SEO](#on-page-seo)
- [Technical SEO](#technical-seo)
- [Meta Tags Configuration](#meta-tags-configuration)
- [URL Structure](#url-structure)
- [Content Optimization](#content-optimization)
- [Performance Optimization](#performance-optimization)
- [Schema Markup](#schema-markup)
- [Sitemap Generation](#sitemap-generation)
- [Analytics Setup](#analytics-setup)
- [Security & SEO](#security--seo)

---

## SEO Fundamentals

### Understanding SEO for Marketplaces

Marketplace SEO is unique because you have:
- Multiple vendors/sellers
- Thousands of product listings
- User-generated content
- Dynamic pages
- Multiple categories and filters

### Key Ranking Factors

1. **Content Quality**: Unique, valuable product descriptions
2. **Site Speed**: Fast loading times
3. **Mobile-Friendly**: Responsive design
4. **User Experience**: Easy navigation
5. **Backlinks**: Quality external links
6. **Security**: HTTPS encryption
7. **Technical SEO**: Proper HTML structure

---

## On-Page SEO

### Page Title Optimization

Edit templates to include dynamic titles:

```amber
// templates/layout/base.amber
doctype html
html
  head
    title
      if PageTitle
        | #{PageTitle} | #{SiteName}
      else
        | #{SiteName} - #{SiteDescription}
```

**Best Practices**:
- Keep titles under 60 characters
- Include primary keyword
- Make titles unique per page
- Front-load important keywords

**Examples**:
```
Homepage: "Tochka Market - Secure Decentralized Marketplace"
Category: "Electronics - Buy & Sell | Tochka Market"
Product: "Bitcoin Mining Rig S19 Pro | Electronics | Tochka"
```

### Meta Description Configuration

Add dynamic meta descriptions:

```amber
// templates/layout/base.amber
head
  meta[name="description"][content=MetaDescription]
  meta[name="keywords"][content=MetaKeywords]
```

In your Go handlers:

```go
func (c *Context) ItemDetailPage(w web.ResponseWriter, r *web.Request) {
	item, _ := FindItemByUuid(r.PathParams["uuid"])
	
	c.PageTitle = item.Name + " - " + item.Category.Name
	c.MetaDescription = util.TruncateString(item.Description, 155)
	c.MetaKeywords = strings.Join(item.Tags, ", ")
	
	util.RenderTemplate(w, "items/detail", c)
}
```

**Meta Description Best Practices**:
- Length: 150-160 characters
- Include primary keyword
- Add call-to-action
- Make it compelling and unique

### Header Tags (H1, H2, H3)

Proper heading structure:

```amber
// templates/items/detail.amber
.item-detail
  h1.item-title #{Item.Name}
  
  .item-info
    h2 Product Description
    p #{Item.Description}
    
    h2 Specifications
    ul
      each spec in Item.Specifications
        li #{spec}
    
    h2 Seller Information
    .seller-info
      h3 #{Seller.Username}
      p #{Seller.Description}
```

**Heading Best Practices**:
- One H1 per page (main topic)
- H2 for main sections
- H3 for subsections
- Use keywords naturally
- Maintain logical hierarchy

### Image Optimization

Optimize images for SEO:

```amber
// templates/items/detail.amber
.item-images
  each image in Item.Images
    img[src="/images/items/#{image.UUID}.jpg"]
       [alt="#{Item.Name} - #{image.Description}"]
       [title="#{Item.Name}"]
       [loading="lazy"]
       [width="800"]
       [height="600"]
```

**Image SEO Best Practices**:
- Use descriptive filenames: `bitcoin-miner-s19-pro.jpg` not `img123.jpg`
- Add alt text with keywords
- Compress images (use WebP format)
- Specify dimensions (width/height)
- Use lazy loading
- Create image sitemap

### Internal Linking

Strategic internal linking:

```amber
// templates/items/list.amber
.breadcrumbs
  a[href="/"] Home
  span /
  a[href="/category/#{Category.ID}"] #{Category.Name}
  span /
  span #{Item.Name}

.related-items
  h3 Related Products
  each item in RelatedItems
    a[href="/item/#{item.UUID}"] #{item.Name}

.category-links
  h3 Browse by Category
  each category in Categories
    a[href="/category/#{category.ID}"] #{category.Name}
```

---

## Technical SEO

### Robots.txt Configuration

Create `public/robots.txt`:

```txt
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /api/
Disallow: /user/*/wallet/
Disallow: /user/*/messages/
Disallow: /search?*
Disallow: /cart/
Disallow: /checkout/

# Sitemap location
Sitemap: https://yourmarket.com/sitemap.xml
Sitemap: https://yourmarket.com/sitemap-products.xml
Sitemap: https://yourmarket.com/sitemap-categories.xml

# Crawl delay (optional, if needed)
Crawl-delay: 10
```

### Canonical URLs

Prevent duplicate content:

```amber
// templates/layout/base.amber
head
  link[rel="canonical"][href="#{CanonicalURL}"]
```

In Go handlers:

```go
func (c *Context) ItemDetailPage(w web.ResponseWriter, r *web.Request) {
	item, _ := FindItemByUuid(r.PathParams["uuid"])
	
	// Set canonical URL
	c.CanonicalURL = fmt.Sprintf("https://yourmarket.com/item/%s", item.UUID)
	
	util.RenderTemplate(w, "items/detail", c)
}
```

### Pagination Tags

For paginated content:

```amber
// templates/items/list.amber
head
  if PrevPage
    link[rel="prev"][href="#{PrevPageURL}"]
  if NextPage
    link[rel="next"][href="#{NextPageURL}"]
```

### Structured Data (Schema.org)

Add JSON-LD structured data:

```amber
// templates/items/detail.amber
script[type="application/ld+json"]
  | {
  |   "@context": "https://schema.org/",
  |   "@type": "Product",
  |   "name": "#{Item.Name}",
  |   "image": "#{Item.MainImage}",
  |   "description": "#{Item.Description}",
  |   "sku": "#{Item.SKU}",
  |   "brand": {
  |     "@type": "Brand",
  |     "name": "#{Item.Brand}"
  |   },
  |   "offers": {
  |     "@type": "Offer",
  |     "url": "#{Item.URL}",
  |     "priceCurrency": "USD",
  |     "price": "#{Item.Price}",
  |     "availability": "https://schema.org/InStock",
  |     "seller": {
  |       "@type": "Organization",
  |       "name": "#{Seller.Username}"
  |     }
  |   },
  |   "aggregateRating": {
  |     "@type": "AggregateRating",
  |     "ratingValue": "#{Item.AverageRating}",
  |     "reviewCount": "#{Item.ReviewCount}"
  |   }
  | }
```

### Breadcrumb Structured Data

```amber
script[type="application/ld+json"]
  | {
  |   "@context": "https://schema.org",
  |   "@type": "BreadcrumbList",
  |   "itemListElement": [{
  |     "@type": "ListItem",
  |     "position": 1,
  |     "name": "Home",
  |     "item": "https://yourmarket.com"
  |   },{
  |     "@type": "ListItem",
  |     "position": 2,
  |     "name": "#{Category.Name}",
  |     "item": "https://yourmarket.com/category/#{Category.ID}"
  |   },{
  |     "@type": "ListItem",
  |     "position": 3,
  |     "name": "#{Item.Name}"
  |   }]
  | }
```

### Organization Schema

Add to homepage:

```amber
// templates/index.amber
script[type="application/ld+json"]
  | {
  |   "@context": "https://schema.org",
  |   "@type": "Organization",
  |   "name": "Tochka Market",
  |   "url": "https://yourmarket.com",
  |   "logo": "https://yourmarket.com/images/logo.png",
  |   "description": "Secure decentralized marketplace",
  |   "sameAs": [
  |     "https://twitter.com/yourmarket",
  |     "https://facebook.com/yourmarket"
  |   ]
  | }
```

---

## URL Structure

### SEO-Friendly URLs

Current structure:
```
/item/uuid123456 ❌ Not SEO-friendly
```

Improved structure:
```
/item/bitcoin-miner-s19-pro/uuid123456 ✅ SEO-friendly
```

### Implement URL Rewriting

Update router in `modules/marketplace/router.go`:

```go
// SEO-friendly product URLs
itemRouter.Get("/:slug/:uuid", (*Context).ItemDetailPage)

// Category URLs
router.Get("/c/:category-slug", (*Context).CategoryPage)

// Vendor URLs
router.Get("/vendor/:username", (*Context).VendorPage)
```

Generate slugs:

```go
func (i *Item) GenerateSlug() string {
	slug := strings.ToLower(i.Name)
	slug = regexp.MustCompile(`[^a-z0-9]+`).ReplaceAllString(slug, "-")
	slug = strings.Trim(slug, "-")
	return slug
}

func (i *Item) GetURL() string {
	return fmt.Sprintf("/item/%s/%s", i.GenerateSlug(), i.Uuid)
}
```

---

## Content Optimization

### Product Descriptions

**SEO-Optimized Product Description Template**:

```
[Product Name] - [Key Benefit]

Overview:
[150-300 word description with primary keyword in first 100 words]

Key Features:
• [Feature 1 with keyword]
• [Feature 2 with keyword]
• [Feature 3 with keyword]

Specifications:
- [Spec 1]
- [Spec 2]
- [Spec 3]

Why Choose [Product Name]:
[Unique selling points with secondary keywords]

Shipping & Delivery:
[Shipping information]

100% Satisfaction Guaranteed
```

### Keyword Research

Tools and strategies:
- Use Google Keyword Planner
- Analyze competitor sites
- Long-tail keywords (3-5 words)
- Include LSI (Latent Semantic Indexing) keywords

**Example Keywords**:
- Primary: "bitcoin mining hardware"
- Secondary: "crypto mining rig", "ASIC miner"
- Long-tail: "best bitcoin mining hardware 2024"

### Category Pages

Optimize category pages:

```amber
// templates/category/view.amber
.category-page
  h1 #{Category.Name} for Sale
  
  .category-description
    p #{Category.LongDescription}
  
  h2 Browse #{Category.Name}
  .items-grid
    each item in Items
      .item-card
        // Item listing
  
  .category-info
    h2 About #{Category.Name}
    p #{Category.SEOContent}
    
    h3 Popular #{Category.Name} Searches
    ul
      each keyword in PopularKeywords
        li
          a[href="/search?q=#{keyword}"] #{keyword}
```

---

## Performance Optimization

### Page Speed Optimization

1. **Enable Compression**:

```go
// In server.go
import "github.com/NYTimes/gziphandler"

func runWebserver() {
	rootRouter := web.New(marketplace.Context{})
	
	// Add gzip compression
	gzipHandler := gziphandler.GzipHandler(rootRouter)
	
	http.ListenAndServe(address, gzipHandler)
}
```

2. **Image Optimization**:

```bash
# Install imagemagick
sudo apt-get install imagemagick

# Optimize images
mogrify -strip -quality 85 -resize 1200x1200\> *.jpg

# Convert to WebP
for img in *.jpg; do
    cwebp -q 85 "$img" -o "${img%.jpg}.webp"
done
```

3. **CSS/JS Minification**:

```bash
# Install minifiers
npm install -g clean-css-cli uglify-js

# Minify CSS
cleancss -o static/css/main.min.css static/css/main.css

# Minify JS
uglifyjs static/js/main.js -o static/js/main.min.js
```

4. **Browser Caching**:

Configure in nginx or add headers in Go:

```go
func (c *Context) SetCacheHeaders(w web.ResponseWriter, duration time.Duration) {
	w.Header().Set("Cache-Control", fmt.Sprintf("public, max-age=%d", int(duration.Seconds())))
	w.Header().Set("Expires", time.Now().Add(duration).Format(http.TimeFormat))
}
```

### Lazy Loading

Implement lazy loading for images:

```amber
img[src="placeholder.jpg"]
   [data-src="/images/#{Item.Image}"]
   [alt="#{Item.Name}"]
   [class="lazyload"]
```

Add JavaScript:

```javascript
// static/js/lazyload.js
document.addEventListener("DOMContentLoaded", function() {
    var lazyImages = [].slice.call(document.querySelectorAll("img.lazyload"));
    
    if ("IntersectionObserver" in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.classList.remove("lazyload");
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });
        
        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }
});
```

---

## Sitemap Generation

### XML Sitemap Implementation

Create `modules/marketplace/sitemap.go`:

```go
package marketplace

import (
	"encoding/xml"
	"time"
)

type URLSet struct {
	XMLName xml.Name `xml:"urlset"`
	Xmlns   string   `xml:"xmlns,attr"`
	URLs    []URL    `xml:"url"`
}

type URL struct {
	Loc        string  `xml:"loc"`
	LastMod    string  `xml:"lastmod,omitempty"`
	ChangeFreq string  `xml:"changefreq,omitempty"`
	Priority   float64 `xml:"priority,omitempty"`
}

func GenerateSitemap() (*URLSet, error) {
	sitemap := &URLSet{
		Xmlns: "http://www.sitemaps.org/schemas/sitemap/0.9",
	}
	
	// Add homepage
	sitemap.URLs = append(sitemap.URLs, URL{
		Loc:        "https://yourmarket.com/",
		ChangeFreq: "daily",
		Priority:   1.0,
	})
	
	// Add categories
	categories, _ := GetAllCategories()
	for _, cat := range categories {
		sitemap.URLs = append(sitemap.URLs, URL{
			Loc:        fmt.Sprintf("https://yourmarket.com/category/%s", cat.Slug),
			ChangeFreq: "weekly",
			Priority:   0.8,
		})
	}
	
	// Add items
	items, _ := GetAllActiveItems()
	for _, item := range items {
		sitemap.URLs = append(sitemap.URLs, URL{
			Loc:        item.GetURL(),
			LastMod:    item.UpdatedAt.Format("2006-01-02"),
			ChangeFreq: "monthly",
			Priority:   0.6,
		})
	}
	
	return sitemap, nil
}

func (c *Context) SitemapHandler(w web.ResponseWriter, r *web.Request) {
	sitemap, err := GenerateSitemap()
	if err != nil {
		http.Error(w, "Error generating sitemap", 500)
		return
	}
	
	w.Header().Set("Content-Type", "application/xml")
	output, _ := xml.MarshalIndent(sitemap, "", "  ")
	w.Write([]byte(xml.Header))
	w.Write(output)
}
```

Add route:

```go
// In router.go
router.Get("/sitemap.xml", (*Context).SitemapHandler)
```

---

## Analytics Setup

### Google Analytics Integration

Add to base template:

```amber
// templates/layout/base.amber
head
  // Google Analytics
  script[async][src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXXX-X"]
  script
    | window.dataLayer = window.dataLayer || [];
    | function gtag(){dataLayer.push(arguments);}
    | gtag('js', new Date());
    | gtag('config', 'UA-XXXXXXXXX-X');
```

### Track E-commerce Events

```javascript
// Track product views
gtag('event', 'view_item', {
    items: [{
        id: '#{Item.UUID}',
        name: '#{Item.Name}',
        category: '#{Item.Category}',
        price: #{Item.Price}
    }]
});

// Track purchases
gtag('event', 'purchase', {
    transaction_id: '#{Transaction.ID}',
    value: #{Transaction.Amount},
    currency: 'USD',
    items: [...]
});
```

### Google Search Console

1. Verify ownership (add meta tag):

```amber
head
  meta[name="google-site-verification"][content="your-verification-code"]
```

2. Submit sitemap in Search Console
3. Monitor crawl errors
4. Check mobile usability
5. Review search queries

---

## Security & SEO

### HTTPS Configuration

SEO requires HTTPS. Configure SSL:

**Using Let's Encrypt**:

```bash
# Install certbot
sudo apt-get install certbot

# Get certificate
sudo certbot certonly --standalone -d yourmarket.com

# Configure in settings.json
{
  "ssl_cert": "/etc/letsencrypt/live/yourmarket.com/fullchain.pem",
  "ssl_key": "/etc/letsencrypt/live/yourmarket.com/privkey.pem",
  "port": "443"
}
```

### HSTS Header

```go
func (c *Context) SecurityHeaders(w web.ResponseWriter, r *web.Request, next web.NextMiddlewareFunc) {
	w.Header().Set("Strict-Transport-Security", "max-age=31536000; includeSubDomains")
	w.Header().Set("X-Content-Type-Options", "nosniff")
	w.Header().Set("X-Frame-Options", "DENY")
	w.Header().Set("X-XSS-Protection", "1; mode=block")
	next(w, r)
}
```

---

## SEO Monitoring & Maintenance

### Regular SEO Audits

**Monthly Checklist**:
- [ ] Check broken links (use Screaming Frog)
- [ ] Verify sitemap is updated
- [ ] Review Google Analytics data
- [ ] Check Search Console for errors
- [ ] Monitor page speed (PageSpeed Insights)
- [ ] Check mobile-friendliness
- [ ] Review top performing pages
- [ ] Update meta descriptions
- [ ] Add new content

### Tools

1. **Google Search Console**: Monitor search performance
2. **Google Analytics**: Track traffic and user behavior
3. **Google PageSpeed Insights**: Test page speed
4. **Screaming Frog**: Crawl and audit site
5. **Ahrefs/SEMrush**: Keyword research and backlink analysis
6. **GTmetrix**: Performance testing

---

## Advanced SEO Strategies

### Content Marketing

1. **Blog Integration**:
   - Create buying guides
   - How-to articles
   - Industry news
   - Vendor spotlights

2. **User-Generated Content**:
   - Encourage reviews
   - Q&A sections
   - Community forums

3. **Video Content**:
   - Product demonstrations
   - Vendor interviews
   - Tutorials

### Link Building

Strategies:
- Partner with related websites
- Guest posting
- Directory submissions
- Social media engagement
- Press releases
- Influencer partnerships

### Local SEO (if applicable)

```amber
script[type="application/ld+json"]
  | {
  |   "@context": "https://schema.org",
  |   "@type": "LocalBusiness",
  |   "name": "Tochka Market",
  |   "address": {
  |     "@type": "PostalAddress",
  |     "streetAddress": "123 Market St",
  |     "addressLocality": "San Francisco",
  |     "addressRegion": "CA",
  |     "postalCode": "94103"
  |   },
  |   "telephone": "+1-415-555-1234"
  | }
```

---

## Mobile SEO

### Responsive Design

Ensure responsive templates:

```css
/* static/css/responsive.css */
@media (max-width: 768px) {
    .container {
        padding: 10px;
    }
    
    .item-grid {
        grid-template-columns: 1fr;
    }
    
    h1 {
        font-size: 24px;
    }
}
```

### Mobile-Specific Meta Tags

```amber
head
  meta[name="viewport"][content="width=device-width, initial-scale=1.0"]
  meta[name="format-detection"][content="telephone=no"]
  link[rel="apple-touch-icon"][href="/images/icon-180x180.png"]
  meta[name="theme-color"][content="#3498db"]
```

---

## Conclusion

SEO is an ongoing process. Consistently:
- Create quality content
- Optimize technical aspects
- Monitor performance
- Adapt to algorithm changes
- Focus on user experience

For more information, refer to:
- [INSTALL.md](INSTALL.md) for setup
- [CUSTOMIZATION.md](CUSTOMIZATION.md) for advanced features

---

**Last Updated**: 2024
**Version**: 1.0
