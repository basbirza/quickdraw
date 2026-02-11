# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Quickdraw Pressing Co. is a full-stack e-commerce website for a premium selvedge denim and Americana wear brand. It consists of:
- **Frontend**: Static HTML with vanilla JavaScript
- **Backend**: Laravel 12 API with Filament admin panel

## Tech Stack

### Frontend
- **HTML5** - Static pages, no build process
- **Tailwind CSS** - CDN-based (no compilation needed)
- **Vanilla JavaScript** - No frameworks
- **Google Fonts** - Rye font for branding

### Backend (`/backend` directory)
- **Laravel 12** - PHP framework
- **Filament 3.x** - Admin panel
- **SQLite** - Database (development)
- **Intervention Image** - Image processing
- **RESTful API** - JSON responses with CORS support

## Development Commands

### Frontend Development
```bash
# Use a local server for the frontend
python3 -m http.server 8001
# Then visit http://localhost:8001
```

### Backend Development
```bash
# Navigate to backend directory
cd backend

# Start Laravel development server
php artisan serve --port=8000
# Backend API available at http://localhost:8000

# Access Filament admin panel
# Visit: http://localhost:8000/admin
# Login: admin@quickdraw.com / password

# Run migrations and seeders
php artisan migrate:fresh --seed

# Create storage link (for image access)
php artisan storage:link
```

## File Structure

```
/
├── index.html                    # Homepage with hero slideshow
├── quickdraw-pressing-co.html    # Original blueprint/sitemap page
├── cart.js                       # Shared shopping cart module
│
├── Category pages:
├── new-in.html
├── denim.html
├── americana.html
├── accessories.html
├── sale.html
├── denim-guide.html
├── journal.html
│
├── Product category pages:
├── selvedge-jeans.html
├── denim-jackets.html
├── shirts-flannels.html
├── outerwear.html
├── tees-henleys.html
│
├── Product detail pages:
├── frontier-slim.html
├── rancher-straight.html
├── culprits-speedcrew.html
│
└── Product images (.png files)
```

### Backend Structure (`/backend`)
```
backend/
├── app/
│   ├── Models/                    # Eloquent models
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── ProductImage.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   └── NewsletterSubscriber.php
│   ├── Http/
│   │   ├── Controllers/Api/       # API controllers
│   │   ├── Resources/             # API transformers
│   │   └── Requests/              # Request validation
│   ├── Filament/Resources/        # Admin panel resources
│   └── Services/                  # Business logic
│       ├── PaymentService.php
│       └── ImageUploadService.php
├── database/
│   ├── migrations/                # 7 database migrations
│   └── seeders/                   # Category & Product seeders
├── routes/
│   └── api.php                    # API routes
└── storage/app/public/products/   # Product images
```

## Laravel Backend API

### API Endpoints

**Products**
- `GET /api/products` - List products (with filters: category, tag, featured, pagination)
- `GET /api/products/{slug}` - Single product details

**Categories**
- `GET /api/categories` - List categories (hierarchical with children)
- `GET /api/categories/{slug}` - Single category with products

**Newsletter**
- `POST /api/newsletter/subscribe` - Subscribe email to newsletter
  - Body: `{"email": "user@example.com"}`
  - Response: `{"success": true, "message": "..."}`

**Orders**
- `POST /api/orders` - Create order (requires payment processing)

### Database Schema

**Products**: name, slug, short_description, price, tag (NEW/BESTSELLER), color_hex, weight, mill, composition, sizes_available (JSON), stock_quantity, is_active, is_featured

**Categories**: name, slug, type (main/sub), parent_id, description, sort_order, is_active

**Product Images**: product_id, image_path, image_type (main/gallery), sort_order

**Orders**: order_number, customer details, billing/shipping addresses, subtotal, shipping_cost, total, payment_method, payment_status, status

**Newsletter Subscribers**: email, status, source, subscribed_at

### Filament Admin Panel

Access at `http://localhost:8000/admin` with:
- **Email**: admin@quickdraw.com
- **Password**: password

Features:
- Product management (CRUD, image upload, categories)
- Category management (hierarchical structure)
- Order viewing and status updates
- Newsletter subscriber list

## Architecture

### Cart System (`cart.js`)

- **Global singleton** loaded on all pages via `<script src="cart.js"></script>`
- **Storage**: Uses `localStorage` with key `quickdraw_cart`
- **API**: Exposed as `window.QuickdrawCart` with methods:
  - `add(product)` - Add product to cart (auto-opens cart drawer)
  - `open()` - Open cart drawer
  - `close()` - Close cart drawer
  - `getCount()` - Get total item count
- **UI**: Dynamically injects cart icon (fixed position) and slide-out drawer
- **Security**: HTML escaping on all user-controlled data to prevent XSS
- **Limits**: Max 50 items in cart, max 20 qty per item

### Page Structure

All pages follow consistent structure:
1. **Announcement bar** - Black bar with shipping/promo message
2. **Header** - Logo (Rye font) + navigation menu
3. **Main content** - Page-specific content
4. **Footer** - 4-column footer with links
5. **Cookie consent popup** (on homepage only)

### Homepage Features

- **Hero slideshow**: Auto-advances through 3 branded images every 2 seconds
- **Featured products grid**: 3 products with "ADD TO BAG" hover buttons
- **Category tiles**: 6 clickable category sections
- **Newsletter form**: Has frontend validation, backend TODO at `/api/newsletter/subscribe`
- **Cookie consent**: Uses `localStorage.getItem('cookieConsent')` for persistence

### Product Pages

- **Image gallery**: Main image + thumbnail strip (5 images)
- **Product details**: Title, price, description, fabric specs
- **Size selector**: Dropdown with waist sizes
- **Accordion sections**: Details, Sizing, Care Instructions, Shipping & Returns
- **Add to cart**: Integrates with `cart.js`

## Key Implementation Details

### Newsletter Form
- Frontend validation only
- Backend endpoint `/api/newsletter/subscribe` is **not implemented**
- Located in `index.html` around line 450
- TODO: Replace with actual newsletter service (Mailchimp, ConvertKit, etc.)

### Cookie Consent
- Shows popup 1 second after page load (if not previously accepted/declined)
- Stores choice in `localStorage.cookieConsent`
- Only implemented on homepage (`index.html`)

### Styling Conventions
- Uses Tailwind utility classes
- Custom CSS in `<style>` tags for:
  - Logo fonts (`.logo-title`, `.logo-subtitle`)
  - Accordion animations (`.accordion-content`)
  - Cookie popup animations
- Color scheme:
  - Primary: Black (#111)
  - Accent: #c45a3a (rust/burnt orange for SALE items)
  - Background gradients: Blue tones (#4a6a8a, #7a9ab5, #2c3e50)

### SEO & Meta Tags
Homepage includes:
- Description meta tags
- Open Graph tags for social sharing
- Favicon links
- All configured in `index.html` lines 8-22

## Common Tasks

### Adding a new product
1. Create new HTML file (copy from `frontier-slim.html`)
2. Update product images and details
3. Add product data to `index.html` `featuredProducts` array (if featured)
4. Ensure all "ADD TO BAG" buttons call `QuickdrawCart.add()`

### Adding a new page
1. Create HTML file with standard header/footer structure
2. Add navigation link in `<nav>` section (present in all pages)
3. Link cart.js: `<script src="cart.js"></script>`

### Modifying cart behavior
- All cart logic is in `cart.js`
- Cart data structure: `{name, desc, price, priceNum, size, image, qty}`
- Modify `addToCart()`, `removeFromCart()`, or `updateQty()` functions

## Deployment

This is a static site that can be deployed to:
- GitHub Pages (already configured for `basbirza.github.io/quickdraw/`)
- Netlify
- Vercel
- Any static hosting service

No build step required - just upload all HTML, JS, and image files.

## Important Notes

- **Laravel Backend**: Fully functional API at `http://localhost:8000/api`
- **Newsletter Integration**: Connected to Laravel backend (`index.html` line ~465)
- **Cart System**: Still uses localStorage (frontend-only for now)
- **Checkout**: Requires payment integration (Stripe/Mollie - stub implementation provided)
- **Product Images**: Copy existing `.png` files to `backend/storage/app/public/products/` for API access
- **Admin Panel**: Full CRUD for products, categories, orders, and newsletter subscribers
- **Database**: Using SQLite for development (switch to MySQL/PostgreSQL for production)
