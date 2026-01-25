# Carlink Core Plugin (RakMyat Widget)

Advanced WordPress plugin for WooCommerce enhancements, custom shop features, and Elementor widgets.

## Description

This plugin provides comprehensive customization for WooCommerce storefronts:
- **Glassmorphic Product Card** - Custom WooCommerce product template with dynamic ratings and modern design
- **Custom Shop Page** - 3-column grid layout with search bar and sorting dropdown (shop pages only)
- **Shop Sidebar Filters** - Accordion-style filters for category, brand, price, availability, collections, and ratings
- **Product Reviews Widget** - Dynamic Elementor widget for single product page reviews
- **Elementor Widgets** - Hero slider, testimonial slider, wishlist icon, and more

## Plugin Structure

```
rakmyat-widget/
│
├── rakmyat-core.php                          # Main plugin file (entry point)
│
├── core/                                     # Core functionality classes
│   ├── class-widget-manager.php              # Elementor widgets registration & asset management
│   ├── class-wc-override.php                 # WooCommerce template override logic
│   ├── class-shop-page.php                   # Custom shop toolbar & grid layout
│   ├── class-shop-sidebar.php                # Accordion-style shop sidebar filters
│   └── class-shop-customizer.php             # WordPress Customizer settings for sidebar
│
├── templates/                                # WooCommerce template overrides
│   └── content-product.php                   # Glassmorphic product card with dynamic ratings
│
├── assets/                                   # Global plugin assets
│   ├── css/
│   │   ├── product-card.css                  # Glassmorphic card styles
│   │   ├── shop-page.css                     # Shop toolbar & 3-column grid
│   │   └── shop-sidebar.css                  # Sidebar filters styling
│   ├── js/
│   │   ├── product-card.js                   # AJAX add to cart & buy now
│   │   └── shop-sidebar.js                   # Accordion filter interactions
│   └── img/
│       └── add-to-cart.svg                   # Cart icon for button
│
├── elements/                                 # Elementor widgets
│   └── widgets/
│       ├── glassmorphic-hero-slider.php      # Hero slider with content positioning controls
│       ├── modern-testimonial-slider.php     # Customer testimonial slider
│       ├── wishlist-icon.php                 # Header wishlist icon with count
│       ├── woo-category-search.php           # Product search with category filter
│       ├── product-reviews.php               # Single product reviews widget
│       └── assets/                           # Widget-specific assets
│           ├── css/
│           │   ├── glassmorphic-hero-slider.css
│           │   ├── modern-testimonial-slider.css
│           │   ├── wishlist-icon.css
│           │   ├── woo-category-search.css
│           │   └── product-reviews.css
│           └── js/
│               ├── glassmorphic-hero-slider.js
│               ├── modern-testimonial-slider.js
│               ├── wishlist-icon.js
│               ├── woo-category-search.js
│               └── product-reviews.js
│
└── README.md                                 # This file
```

## Features

### 1. Glassmorphic Product Card

Overrides the default WooCommerce product loop template with modern glassmorphic design and dynamic ratings.

**Card Features:**
- Product image with hover zoom effect
- Sale badge (top left)
- Wishlist button (top right, icon-only) - WCBoost & YITH Wishlist support
- Product title (2-line clamp)
- **Safety Center** - Dynamic average rating from WooCommerce
- Stock status indicator
- Price display with sale price support
- Add to Cart button with AJAX (icon + text)
- Buy Now button (direct checkout)

**Design Specs:**
- Border: `1px solid rgba(238, 238, 238, 1)`
- Box Shadow: `1px 1px 20px 0px rgba(0, 0, 0, 0.1)`
- Border Radius: `8px`
- Background: `#fff`
- Padding: `12px 16px`

**Button Styles:**
- Add to Cart: `linear-gradient(270deg, rgba(138, 143, 145, 0.3) 0%, #8A8F91 100%)`
- Buy Now: `linear-gradient(90deg, #3A5F79 0%, rgba(58, 95, 121, 0.4) 100%)`
- Button Shadow: `4px 4px 6px 0px rgba(0, 0, 0, 0.25), 4px 4px 7px 0px rgba(255, 255, 255, 0.25) inset`
- Border Radius: `50px` (pill shape)

### 2. Custom Shop Page

Replaces default WooCommerce shop toolbar with custom design and implements 3-column grid layout.

**Features:**
- **Custom Toolbar:**
  - Left: "RESULT" title + search bar with icon button
  - Right: "Sort by" dropdown with options (Default, Popularity, Rating, Latest, Price Low→High, Price High→Low)
- **Product Grid:**
  - 3 columns on desktop
  - 2 columns on tablet (992px and below)
  - 2 columns on mobile (576px and below)
- **CSS Specificity:** Only affects WooCommerce shop loop (`.post-type-archive-product`, `.product-category`, `.product-tag`) - does NOT affect home page or normal page product grids

### 3. Shop Sidebar Filters

Custom accordion-style sidebar with functional WooCommerce filters (shop pages only).

**Filters Include:**
- **Category** - Hierarchical category list with product counts
- **Brand** - Brand filter (taxonomy-based)
- **Price Range** - Dual-slider for min/max price filtering
- **Availability** - In Stock / Out of Stock toggle
- **Collections** - Custom category grouping
- **Ratings** - Star rating filter (1-5 stars)

**Customizer Settings:**
- Enable/disable individual filters
- Select parent category for Collections filter
- Located under: Appearance → Customize → Shop Sidebar

**Features:**
- Mobile toggle button for sidebar visibility
- Accordion collapse/expand animations
- Persistent state with localStorage
- Works with WooCommerce product query

### 4. Product Reviews Widget (Elementor)

Dynamic Elementor widget for displaying WooCommerce product reviews on single product pages.

**Features:**
- **Left Column (Summary):**
  - Average rating display with stars
  - Rating breakdown bars (5-star to 1-star with counts and percentages)
  - Total review count

- **Right Column (Individual Reviews):**
  - Reviewer avatar (with initials from name)
  - Reviewer name
  - Star rating
  - Review text
  - Like/Dislike buttons (stored in localStorage)
  - "View more" link to full reviews

**Elementor Controls:**
- Content controls: Titles, reviews per page, button visibility, "View more" link
- Style controls: All colors, typography, spacing, and layout options

### 5. Elementor Widgets

Custom Elementor widgets registered under "RakMyat Elements" category.

**Available Widgets:**
- **Glassmorphic Hero Slider** - Hero slider with full padding/margin/positioning controls for content, 1440px container max-width
- **Modern Testimonial Slider** - Customer testimonial slider with min-height controls
- **Wishlist Icon** - Header wishlist icon with item count
- **WooCommerce Category Search** - Product search with category filter dropdown
- **Product Reviews** - Single product reviews with rating summary and individual review cards

## Technical Details

### WooCommerce Template Override

The plugin uses two filters to ensure template override works:

```php
add_filter( 'woocommerce_locate_template', 'override_locate_template', 9999, 3 );
add_filter( 'wc_get_template_part', 'override_template_part', 9999, 3 );
```

### Shop Page CSS Specificity

The 3-column grid layout is scoped to only WooCommerce shop loop pages using:
```css
.post-type-archive-product .rmt-shop-loop-main-grid ul.products { /* 3 columns */ }
.product-category .rmt-shop-loop-main-grid ul.products { /* 3 columns */ }
.product-tag .rmt-shop-loop-main-grid ul.products { /* 3 columns */ }
```

This ensures that product grids on home pages, normal pages, and anywhere else are NOT affected and maintain their default 4+ column layout.

### Product Ratings

The Safety Center rating displays the WooCommerce average rating dynamically:
```php
$average_rating = $product->get_average_rating();
$rating_display = $average_rating ? number_format( (float) $average_rating, 1 ) : '0.0';
```

### Asset Loading

Assets are intelligently loaded:
- Product card CSS: Only on WooCommerce pages
- Shop page CSS: Only on shop, category, and tag pages
- Shop sidebar: Only on shop pages via Customizer integration
- Widget assets: Auto-loaded by Widget Manager based on presence in page

## Requirements

- WordPress 5.0+
- WooCommerce 5.0+
- Elementor 3.0+ (for widgets)
- PHP 7.4+

## Optional Integrations

- **WCBoost Wishlist** - Wishlist button integration
- **YITH WooCommerce Wishlist** - Alternative wishlist support
- **WCFM Marketplace** - Vendor name display
- **Dokan** - Vendor name display

## Installation

1. Upload the `rakmyat-widget` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. Go to Appearance → Customize → Shop Sidebar to configure filters
4. Add Elementor widgets from "RakMyat Elements" category to your pages

## Customization

### Product Card Template
Edit: `templates/content-product.php`

### Shop Page Styling
Edit: `assets/css/shop-page.css`
Note: Uses specific selectors to only affect shop pages

### Shop Sidebar Styling
Edit: `assets/css/shop-sidebar.css`

### Product Card Styling
Edit: `assets/css/product-card.css`

### Elementor Widget Styling
Each widget has built-in style controls in Elementor editor:
- Colors, typography, spacing, layout options
- No need to edit CSS files for widget styling

## Hooks & Filters

### Actions Removed
The plugin removes these WooCommerce actions to prevent duplication:
- `woocommerce_before_shop_loop_item`
- `woocommerce_after_shop_loop_item`
- `woocommerce_before_shop_loop_item_title`
- `woocommerce_shop_loop_item_title`
- `woocommerce_after_shop_loop_item_title`
- `woocommerce_before_shop_loop` (toolbar)
- `woocommerce_catalog_ordering` (sort dropdown)

### AJAX Endpoints
- `get_wishlist_count` - Returns current wishlist count

## Changelog

### Version 2.0.0 (Latest)
- Added Custom Shop Page with 3-column grid (shop pages only)
- Added Shop Sidebar with accordion filters (Category, Brand, Price, Availability, Collections, Ratings)
- Added Product Reviews Elementor widget for single product pages
- Added Dynamic Safety Center rating to product cards using WooCommerce ratings
- Fixed product card to display icon-only wishlist button
- Made shop grid CSS specific to shop pages only - does not affect home page or normal pages
- Added comprehensive style controls to all Elementor widgets
- Added Shop Customizer settings for sidebar filter configuration

### Version 1.0.0
- Initial release
- Glassmorphic product card template
- Elementor widgets: Hero Slider, Wishlist Icon, Category Search
- WCBoost Wishlist integration
- AJAX Add to Cart functionality
- Buy Now button with direct checkout

## Author

**Yousef Abdallah**

## License

This plugin is licensed under the GPL v2 or later.
