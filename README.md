# Carlink Core Plugin

Custom WordPress plugin for WooCommerce enhancements and Elementor widgets.

## Description

This plugin provides:
- **Glassmorphic Product Card** - Custom WooCommerce product loop template with modern design
- **Elementor Widgets** - Custom widgets for enhanced functionality

## Plugin Structure

```
rakmyat-widget/
│
├── rakmyat-core.php              # Main plugin file (entry point)
│
├── core/                         # Core functionality classes
│   ├── class-widget-manager.php  # Elementor widgets registration & management
│   └── class-wc-override.php     # WooCommerce template override logic
│
├── templates/                    # WooCommerce template overrides
│   └── content-product.php       # Glassmorphic product card template
│
├── assets/                       # Product card assets
│   ├── css/
│   │   └── product-card.css      # Glassmorphic card styles
│   ├── js/
│   │   └── product-card.js       # AJAX add to cart & buy now functionality
│   └── img/
│       └── add-to-cart.svg       # Cart icon for button
│
├── elements/                     # Elementor widgets
│   └── widgets/
│       ├── glassmorphic-hero-slider.php
│       ├── wishlist-icon.php
│       ├── woo-category-search.php
│       └── assets/               # Widget-specific assets
│           ├── css/
│           │   ├── glassmorphic-hero-slider.css
│           │   ├── wishlist-icon.css
│           │   └── woo-category-search.css
│           └── js/
│               ├── glassmorphic-hero-slider.js
│               ├── wishlist-icon.js
│               └── woo-category-search.js
│
└── README.md                     # This file
```

## Features

### 1. Glassmorphic Product Card

Overrides the default WooCommerce product loop template with a modern glassmorphic design.

**Locations where it applies:**
- Shop page
- Product category archives
- Related products
- Upsells

**Card Features:**
- Product image with hover zoom effect
- Sale badge (top left)
- Wishlist button (top right) - Supports WCBoost Wishlist & YITH Wishlist
- Product title (2-line clamp)
- Stock status indicator
- Price with "Price" label
- Add to Cart button with cart icon (AJAX enabled)
- Buy Now button (redirects to checkout)

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

### 2. Elementor Widgets

Custom Elementor widgets registered under "RakMyat Elements" category.

**Available Widgets:**
- **Glassmorphic Hero Slider** - Hero slider with glassmorphic design
- **Wishlist Icon** - Header wishlist icon with count
- **WooCommerce Category Search** - Product search with category filter

## Technical Details

### WooCommerce Template Override

The plugin uses two filters to ensure the template override works:

```php
// Primary method - for wc_get_template() calls
add_filter( 'woocommerce_locate_template', 'override_locate_template', 9999, 3 );

// Backup method - for wc_get_template_part() calls (related/upsells)
add_filter( 'wc_get_template_part', 'override_template_part', 9999, 3 );
```

### Theme Compatibility

The plugin removes conflicting hooks from:
- Default WooCommerce hooks
- Martfury theme hooks

### Asset Loading

Assets are only loaded on WooCommerce pages for performance optimization.

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
3. The product card template will automatically override WooCommerce defaults

## Customization

### Modifying the Product Card Template

Edit: `templates/content-product.php`

### Modifying Styles

Edit: `assets/css/product-card.css`

### Modifying JavaScript

Edit: `assets/js/product-card.js`

## Hooks & Filters

### Actions

The plugin removes these WooCommerce actions to prevent duplication:
- `woocommerce_before_shop_loop_item`
- `woocommerce_after_shop_loop_item`
- `woocommerce_before_shop_loop_item_title`
- `woocommerce_shop_loop_item_title`
- `woocommerce_after_shop_loop_item_title`

### AJAX Endpoints

- `get_wishlist_count` - Returns current wishlist item count

## Changelog

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
