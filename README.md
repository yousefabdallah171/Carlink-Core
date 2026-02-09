# Carlink Core Plugin (RakMyat Widget)

Advanced WordPress plugin for WooCommerce enhancements, custom shop features, checkout/cart customization, and Elementor widgets.

## Description

This plugin provides comprehensive customization for WooCommerce storefronts:
- **WooCommerce Cart Page** - Modern 2-column layout with glassmorphic checkout button and trust badges
- **WooCommerce Checkout Page** - Clean 2-column layout with custom form styling and coupon integration
- **Glassmorphic Product Card** - Custom WooCommerce product template with dynamic ratings and modern design
- **Custom Shop Page** - 3-column grid layout with search bar and sorting dropdown (shop pages only)
- **Shop Sidebar Filters** - Accordion-style filters for category, brand, price, availability, collections, and ratings
- **Product Reviews Widget** - Dynamic Elementor widget for single product page reviews
- **Add to Cart Widget** - Quantity selector with wishlist and buy now buttons
- **Product Brands Grid Widget** - Elementor widget displaying WooCommerce product brands in a grid with two layout styles
- **Brand Showcase Slider Widget** - Full-width slider with centered title, overlay, arrow navigation, and subtitle text
- **Elementor Widgets** - Hero slider, testimonial slider, wishlist icon, and more

## Plugin Structure

```
rakmyat-widget/
│
├── rakmyat-core.php                          # Main plugin file (entry point)
│
├── core/                                     # Core functionality classes
│   ├── class-global-assets.php               # Enqueue CSS/JS for cart & checkout pages
│   ├── class-widget-manager.php              # Elementor widgets registration & asset management
│   ├── class-wc-override.php                 # WooCommerce template override logic
│   ├── class-shop-page.php                   # Custom shop toolbar & grid layout
│   ├── class-shop-sidebar.php                # Accordion-style shop sidebar filters
│   ├── class-shop-customizer.php             # WordPress Customizer settings for sidebar
│   ├── class-checkout-customizer.php         # WooCommerce checkout customization via hooks/filters
│   └── class-brand-taxonomy.php             # Product brand "Country" custom meta field
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
│       ├── add-to-cart.svg                   # Cart icon for button
│       └── del icon.svg                      # Delete/trash icon for cart items (red #DC2626)
│
├── elements/                                 # Elementor widgets
│   └── widgets/
│       ├── glassmorphic-hero-slider.php      # Hero slider with content positioning controls
│       ├── modern-testimonial-slider.php     # Customer testimonial slider
│       ├── wishlist-icon.php                 # Header wishlist icon with count
│       ├── woo-category-search.php           # Product search with category filter
│       ├── product-reviews.php               # Single product reviews widget
│       ├── add-to-cart.php                   # Add to cart widget with quantity selector
│       ├── product-brands-grid.php          # Product brands grid widget with 2 layout styles
│       ├── brand-showcase-slider.php        # Full-width brand showcase slider widget
│       ├── cta-banner.php                   # CTA banner with glassmorphic button
│       ├── icon-category-grid.php           # Neumorphic icon category grid
│       └── assets/                           # Widget-specific assets
│           ├── css/
│           │   ├── glassmorphic-hero-slider.css
│           │   ├── modern-testimonial-slider.css
│           │   ├── wishlist-icon.css
│           │   ├── woo-category-search.css
│           │   ├── product-reviews.css
│           │   ├── add-to-cart.css
│           │   ├── product-brands-grid.css   # Product brands grid styling
│           │   ├── brand-showcase-slider.css # Brand showcase slider styling
│           │   ├── cta-banner.css           # CTA banner styling
│           │   ├── icon-category-grid.css   # Neumorphic icon grid styling
│           │   ├── woo-cart.css              # WooCommerce cart page styling
│           │   └── woo-checkout.css          # WooCommerce checkout page styling
│           └── js/
│               ├── glassmorphic-hero-slider.js
│               ├── modern-testimonial-slider.js
│               ├── wishlist-icon.js
│               ├── woo-category-search.js
│               ├── product-reviews.js
│               ├── add-to-cart.js
│               └── brand-showcase-slider.js  # Slider logic with fade/slide, autoplay, touch
│
└── README.md                                 # This file
```

## Features

### 1. WooCommerce Cart Page Styling

Modern 2-column layout matching Figma design with glassmorphic checkout button.

**File:** `elements/widgets/assets/css/woo-cart.css`

**Design Features:**
- **Layout:** 2-column side-by-side on desktop (left: cart table ~65%, right: order summary ~32%)
- **Cart Table:**
  - Dark teal header (#3A5F79) with white text
  - Product image, name, quantity selector, price, subtotal columns
  - Quantity selector: 150px width with white minus button, gray plus button (#8A8F91)
  - Delete button: Red background (#DC2626) with SVG trash icon

- **Order Summary Card:**
  - "Order Summary" title (Poppins, 31px, 600 weight, #0A0A0A) at the top
  - Right sidebar with white background, light border (#E5E5E5), 12px border-radius
  - Sticky positioning (top: 20px) on desktop
  - Subtotal, Shipping (FREE in yellow), Tax, and Total rows

- **Checkout Button:**
  - Glassmorphic design with gradient background
  - Backdrop blur (42px), rounded (50px)
  - Full-width button with proper spacing

- **Trust Badges:**
  - 3 checkmark badges below checkout button
  - White text on transparent background
  - Circle checkmark icons with teal background

- **Product Text:**
  - Font: Poppins, 16px, weight 400
  - Color: #9F9F9F (gray)

- **Responsive:**
  - Tablet (992px): Single column stacked
  - Mobile (768px): Compact sizing with reduced padding
  - Small Mobile (480px): Card-based layout

**Activation:** CSS is enqueued automatically on cart pages via `class-global-assets.php`

### 2. WooCommerce Checkout Page Styling

Clean 2-column layout with modern form styling and coupon integration.

**File:** `elements/widgets/assets/css/woo-checkout.css`
**PHP Class:** `core/class-checkout-customizer.php`

**Design Features:**
- **Layout:** 2-column side-by-side on desktop (left: billing details ~55%, right: order review ~40%)
- **Billing Details (Left):**
  - Light gray background input fields (#F0F0F0)
  - No borders, 6px border-radius, 15px padding
  - Labels: Dark gray (#666666), 13px font size
  - Focus state: White background with subtle teal shadow
  - "Save this information" checkbox styled cleanly

- **Order Review Card (Right):**
  - White background with 1px border (#E5E5E5), 12px border-radius
  - 30px padding with subtle shadow (0 2px 8px rgba(0,0,0,0.06))
  - Product table without borders, clean typography (14px)
  - Subtotal, Shipping, Tax, Total rows clearly displayed
  - Spacing between rows with light dividers (#F0F0F0)

- **Payment Methods:**
  - Clean radio buttons for payment selection
  - Payment icons display (Visa, Mastercard, etc)
  - Transparent background (no default gray box)

- **Coupon Section:**
  - Positioned inside order review card (between totals and payment methods)
  - Input: White background, light border (#D1D5DB), 6px radius
  - Button: Dark teal (#3A5F79), 6px radius, 600 weight
  - Flexbox layout: input (flex:1) + button (flex-shrink:0)
  - Top border separator (1px solid #F0F0F0)

- **Place Order Button:**
  - Solid dark blue (#3A5F79), 55px height, 6px radius
  - Full-width, centered text
  - Hover: Darker shade (#2A3F50) with slight lift effect

- **Responsive:**
  - Tablet (992px): Single column stacked, order review below billing
  - Mobile (768px): Compact sizing, reduced padding
  - Small Mobile (480px): Extra compact with minimal padding

**PHP Implementation (Hooks & Filters):**
The `class-checkout-customizer.php` uses WooCommerce hooks to customize the checkout:

```php
// Hook: Customize form field output
add_filter( 'woocommerce_form_field', [ $this, 'customize_form_field' ], 10, 4 );

// Hook: Render coupon form inside order review (before payment section)
add_action( 'woocommerce_review_order_before_payment', [ $this, 'render_coupon_form' ], 5 );

// Filter: Enable coupons
add_filter( 'woocommerce_coupons_enabled', '__return_true' );
```

**Activation:** CSS and PHP class are loaded automatically via:
- `class-global-assets.php` - Enqueues CSS on checkout pages
- `rakmyat-core.php` - Initializes `Checkout_Customizer` class in `plugins_loaded` hook

### 3. Glassmorphic Product Card

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

### 4. Custom Shop Page

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

### 5. Shop Sidebar Filters

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

### 6. Product Reviews Widget (Elementor)

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

### 7. Add to Cart Widget (Elementor)

Custom Elementor widget for adding quantity selector with wishlist and buy now buttons.

**File:** `elements/widgets/add-to-cart.php`
**CSS:** `elements/widgets/assets/css/add-to-cart.css`
**JS:** `elements/widgets/assets/js/add-to-cart.js`

**Features:**
- Quantity selector with +/- buttons
- Add to Cart button
- Wishlist button (WCBoost integration)
- Buy Now button (direct checkout)
- All styling controls in Elementor editor

**Button Styling:**
- Minus button: White background (#FFFFFF), black text
- Plus button: Gray background (#8A8F91), white text
- 40px height, 6px border-radius

### 8. Elementor Widgets

Custom Elementor widgets registered under "RakMyat Elements" category.

**Available Widgets:**
- **Glassmorphic Hero Slider** - Hero slider with full padding/margin/positioning controls for content, 1440px container max-width
- **Modern Testimonial Slider** - Customer testimonial slider with min-height controls
- **Wishlist Icon** - Header wishlist icon with item count
- **WooCommerce Category Search** - Product search with category filter dropdown
- **Product Reviews** - Single product reviews with rating summary and individual review cards
- **Add to Cart** - Quantity selector with wishlist and buy now buttons
- **Product Brands Grid** - Brand taxonomy grid with two layout styles and full Elementor controls

### 9. Product Brands Grid Widget (Elementor)

Displays WooCommerce `product_brand` taxonomy terms in a customizable grid layout.

**File:** `elements/widgets/product-brands-grid.php`
**CSS:** `elements/widgets/assets/css/product-brands-grid.css`
**Taxonomy Meta:** `core/class-brand-taxonomy.php`

**Layout Styles:**
- **Style 1 — Simple:** Brand Image + Brand Name + Product Count
- **Style 2 — Detailed:** Brand Image + Brand Name + Country + Product Count

**Features:**
- Two layout styles switchable in Elementor controls
- Thumbnail source: Choose between WooCommerce default (`thumbnail_id`) or Martfury second thumbnail (`brand_thumbnail_id`) with automatic fallback
- Custom "Country" meta field added to `product_brand` taxonomy
- Placeholder with brand initial when no image is set
- Hover zoom effect on images (toggleable)
- Links to brand archive pages

**Elementor Controls — Content:**
- Layout style selector (Style 1 / Style 2)
- Thumbnail source (First / Second thumbnail)
- Count suffix text (customizable, default: "parts")
- Number formatting toggle (comma separators)
- Show/hide product count
- Show/hide brand image
- Name HTML tag (H1-H6, p, span, div)

**Elementor Controls — Query:**
- Include specific brands (Select2 multi-select)
- Exclude specific brands (Select2 multi-select)
- Order by: Name, Product Count, Term ID, Slug, None
- Order direction: ASC / DESC
- Limit number of brands (1-50)
- Hide empty brands toggle

**Elementor Controls — Layout:**
- Responsive columns (1-6 per device)
- Responsive grid gap
- Responsive text alignment

**Elementor Controls — Style:**
- **Card:** Background color, border, border-radius, box-shadow, content padding, hover states (bg, shadow, border color), transition duration
- **Image:** Height, border-radius, object-fit (cover/contain/fill), hover zoom toggle and scale
- **Brand Name:** Color, hover color, typography (full Elementor typography group), margin
- **Country** (Style 2): Color, typography, margin
- **Product Count:** Color, typography, margin

**Brand Taxonomy — Country Field:**
- Added via `class-brand-taxonomy.php`
- Text input field on Add/Edit Brand screens
- Meta key: `brand_country`
- Displayed as a column in the Brands admin list table
- Used in Style 2 layout to show country below brand name

### 10. Brand Showcase Slider Widget (Elementor)

Full-width slider with repeater slides, centered title, dark overlay, bottom-center arrow navigation, and bottom-right subtitle text.

**File:** `elements/widgets/brand-showcase-slider.php`
**CSS:** `elements/widgets/assets/css/brand-showcase-slider.css`
**JS:** `elements/widgets/assets/js/brand-showcase-slider.js`

**Features:**
- Repeater-based slides (unlimited)
- Fade and slide transition effects
- Autoplay with pause-on-hover
- Infinite loop support
- Keyboard navigation (arrow keys)
- Touch/swipe support for mobile
- Per-slide overlay color override
- Optional dot pagination
- Accessible ARIA labels on all controls

**Repeater Fields (per slide):**
- Background image
- Title text (centered, e.g., "KIA")
- Subtitle text (bottom right, e.g., "Sportage | Seltos | ...")
- Link (optional URL)
- Overlay color override (optional)

**Elementor Controls — Content:**
- Title HTML tag (H1-H6, p, span, div)
- Slider height (responsive, px/vh)
- Autoplay toggle, speed, pause-on-hover
- Infinite loop toggle
- Transition speed and effect (fade/slide)
- Show/hide arrows, show/hide dots

**Elementor Controls — Style:**
- **Overlay:** Color with opacity (default: rgba(55, 65, 81, 0.7))
- **Container:** Border radius, box shadow
- **Image:** Object fit (cover/contain/fill), object position
- **Title:** Color, full typography (default: 64px, 700, 12px letter-spacing, uppercase), text shadow, vertical offset
- **Subtitle:** Color, typography, bottom offset, right offset
- **Arrows:** Icon size, color, hover color, container background, hover background, border, border-radius, padding, divider toggle/color, gap, bottom offset
- **Dots:** Size, color, active color, gap, bottom offset

### 11. CTA Banner Widget (Elementor)

Full-width call-to-action banner with background image, overlay, centered title, description, and glassmorphic button (rmt-glass-btn style).

**File:** `elements/widgets/cta-banner.php`
**CSS:** `elements/widgets/assets/css/cta-banner.css`

**Features:**
- Background image with position and size controls
- Overlay with adjustable color and opacity (default: rgba(55, 65, 81, 0.7))
- Centered title and multi-line description
- Glassmorphic CTA button matching `rmt-btn-buy` gradient style
- Arrow icon on button (toggleable, position before/after)
- Full content positioning controls (horizontal, vertical, alignment)

**Elementor Controls — Content:**
- Background image, position, size
- Title text + HTML tag (H1-H6, p, div)
- Description (textarea with line breaks)
- Button text, link, show/hide, arrow icon toggle + position

**Elementor Controls — Layout:**
- Banner height (responsive, px/vh)
- Content max width, alignment, horizontal/vertical position
- Content padding

**Elementor Controls — Style:**
- **Overlay:** Color with opacity
- **Container:** Border radius, box shadow
- **Title:** Color, typography, text shadow, margin
- **Description:** Color, typography, margin
- **Button (Normal/Hover tabs):** Text color, gradient background, box shadow, hover lift, padding, border radius, border, arrow icon size/color/gap

### 12. Icon Category Grid Widget (Elementor)

Neumorphic grid of icon + title cards using a repeater. Features the exact Figma neumorphic multi-shadow effect with backdrop-filter blur.

**File:** `elements/widgets/icon-category-grid.php`
**CSS:** `elements/widgets/assets/css/icon-category-grid.css`

**Neumorphic Shadow (from Figma):**
```css
box-shadow:
    0px 0px 60px 0px #F2F2F2 inset,
    0px 0px 11.25px 0px rgba(255,255,255,0.5) inset,
    -3.75px -3.75px 1.87px -3.75px #FFFFFF inset,
    3.75px 3.75px 1.87px -3.75px #FFFFFF inset,
    -3.75px -3.75px 0px -1.87px #262626 inset,
    3.75px 3.75px 0px -1.87px #333333 inset,
    0px 3.75px 30px 0px rgba(0,0,0,0.12),
    0px 0px 7.5px 0px rgba(0,0,0,0.1);
backdrop-filter: blur(45px);
```

**Default Items (10):**
Transmission & Drivetrain, Engine, Suspension & Steering, Braking System, Electrical & Electronics, HVAC, Intake & Exhaust, Body & Exterior, Interior Parts, Fluids & Lubricants

**Elementor Controls — Content:**
- Repeater: Icon (Elementor Icons), Title, Link per item
- Title HTML tag (H2-H6, p, span, div)

**Elementor Controls — Layout:**
- Responsive columns (1-6, default: 5 / 3 / 2)
- Grid gap, content alignment

**Elementor Controls — Style:**
- **Card:** Background color (#F2F2F2), padding, border-radius (16px), min-height, neumorphic toggle + intensity + backdrop blur, custom box-shadow fallback, hover background, hover lift, transition
- **Icon:** Circle size (64px), circle background, circle border (2px solid #333), icon size (28px), icon color, hover icon color, hover circle bg, hover circle border color, spacing below
- **Title:** Color, hover color, typography (14px/600), margin

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

### Checkout Customization via Hooks

The checkout page is customized using WooCommerce hooks and filters instead of template overrides:

**Key Hooks Used:**
- `woocommerce_form_field` - Filter to customize form field output
- `woocommerce_review_order_before_payment` - Action to render coupon form inside order review
- `woocommerce_coupons_enabled` - Filter to ensure coupons are enabled

**Advantages:**
- ✅ No template file editing needed
- ✅ Works with any WooCommerce theme
- ✅ Automatic updates compatibility
- ✅ Follows WooCommerce best practices

### Cart Page Hooks

The cart page uses WooCommerce hooks to add dynamic content:

```php
// Add Order Summary title
add_action( 'woocommerce_before_cart_totals', [ $this, 'add_order_summary_title' ] );

// Add trust badges
add_action( 'woocommerce_after_cart_totals', [ $this, 'add_trust_badges' ] );
```

**Advantages:**
- ✅ Dynamic content injection without template overrides
- ✅ Works with any WooCommerce theme
- ✅ Maintains theme compatibility

### Asset Loading

Assets are intelligently loaded:
- Cart CSS: Only on cart pages (via `is_cart()`)
- Checkout CSS: Only on checkout pages (via `is_checkout()`)
- Product card CSS: Only on WooCommerce pages
- Shop page CSS: Only on shop, category, and tag pages
- Shop sidebar: Only on shop pages via Customizer integration
- Widget assets: Auto-loaded by Widget Manager based on presence in page

### Cart SVG Icons

**Delete Icon:** `assets/img/del icon.svg`
- Red color: #DC2626
- 21x22px viewBox
- Used for delete/remove buttons in cart table

## File Paths Reference

### Core Classes
- Main Plugin: `rakmyat-core.php`
- Global Assets: `core/class-global-assets.php`
- Widget Manager: `core/class-widget-manager.php`
- WC Override: `core/class-wc-override.php`
- Shop Page: `core/class-shop-page.php`
- Shop Sidebar: `core/class-shop-sidebar.php`
- Shop Customizer: `core/class-shop-customizer.php`
- **Checkout Customizer: `core/class-checkout-customizer.php`** ⭐ NEW
- **Brand Taxonomy: `core/class-brand-taxonomy.php`** ⭐ NEW

### CSS Files
- Product Card: `assets/css/product-card.css`
- Shop Page: `assets/css/shop-page.css`
- Shop Sidebar: `assets/css/shop-sidebar.css`
- **Cart Page: `elements/widgets/assets/css/woo-cart.css`** ⭐ NEW
- **Checkout Page: `elements/widgets/assets/css/woo-checkout.css`** ⭐ NEW
- Add to Cart Widget: `elements/widgets/assets/css/add-to-cart.css`
- Product Reviews Widget: `elements/widgets/assets/css/product-reviews.css`
- **Product Brands Grid: `elements/widgets/assets/css/product-brands-grid.css`** ⭐ NEW
- **Brand Showcase Slider: `elements/widgets/assets/css/brand-showcase-slider.css`** ⭐ NEW
- **CTA Banner: `elements/widgets/assets/css/cta-banner.css`** ⭐ NEW
- **Icon Category Grid: `elements/widgets/assets/css/icon-category-grid.css`** ⭐ NEW

### JS Files
- Product Card: `assets/js/product-card.js`
- Shop Sidebar: `assets/js/shop-sidebar.js`
- Add to Cart Widget: `elements/widgets/assets/js/add-to-cart.js`
- Product Reviews Widget: `elements/widgets/assets/js/product-reviews.js`
- **Brand Showcase Slider: `elements/widgets/assets/js/brand-showcase-slider.js`** ⭐ NEW

### Images
- Cart Icon: `assets/img/add-to-cart.svg`
- **Delete Icon: `assets/img/del icon.svg`** ⭐ NEW

### Elementor Widgets
- Hero Slider: `elements/widgets/glassmorphic-hero-slider.php`
- Testimonial Slider: `elements/widgets/modern-testimonial-slider.php`
- Wishlist Icon: `elements/widgets/wishlist-icon.php`
- Category Search: `elements/widgets/woo-category-search.php`
- Product Reviews: `elements/widgets/product-reviews.php`
- **Add to Cart: `elements/widgets/add-to-cart.php`** ⭐ NEW
- **Product Brands Grid: `elements/widgets/product-brands-grid.php`** ⭐ NEW
- **Brand Showcase Slider: `elements/widgets/brand-showcase-slider.php`** ⭐ NEW
- **CTA Banner: `elements/widgets/cta-banner.php`** ⭐ NEW
- **Icon Category Grid: `elements/widgets/icon-category-grid.php`** ⭐ NEW

### Templates
- Product Card: `templates/content-product.php`

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
5. Cart and Checkout pages will be automatically styled on visit

## Customization

### Cart Page Styling
Edit: `elements/widgets/assets/css/woo-cart.css`
- Modify colors, spacing, button styles
- Currently styled for Figma design: 2-column layout with glassmorphic button

### Checkout Page Styling
Edit: `elements/widgets/assets/css/woo-checkout.css`
- Modify form field colors, borders, spacing
- Coupon section styling
- Order review card styling

### Checkout Form Logic
Edit: `core/class-checkout-customizer.php`
- Customize form fields
- Add/remove hooks
- Modify coupon form output

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

### Cart Page Hooks Used
- `woocommerce_before_cart_totals` - Add Order Summary title
- `woocommerce_after_cart_totals` - Add trust badges

### Checkout Hooks Used
- `woocommerce_form_field` - Customize form field output
- `woocommerce_review_order_before_payment` - Render coupon form
- `woocommerce_coupons_enabled` - Enable coupons
- `woocommerce_checkout_show_terms` - Hide/show terms

### AJAX Endpoints
- `get_wishlist_count` - Returns current wishlist count

## Changelog

### Version 4.0.0 (Latest)
- ⭐ **NEW: Product Brands Grid Elementor Widget**
  - Two layout styles: Simple (Name + Count) and Detailed (Name + Country + Count)
  - Thumbnail source: WooCommerce default or Martfury second thumbnail with fallback
  - Full Elementor controls: content, query, layout, and style tabs
  - Responsive grid columns (1-6 per device)
  - Query controls: include/exclude brands, order by, limit, hide empty
  - Card styling: background, border, radius, shadow, hover states, transition
  - Image styling: height, radius, object-fit, hover zoom
  - Typography controls for name, country, and count
  - Placeholder with brand initial when no image is set
  - Comma-formatted product counts with customizable suffix text

- ⭐ **NEW: Brand Taxonomy Country Meta Field**
  - Custom "Country" text field on Add/Edit Brand screens
  - Meta key: `brand_country`
  - Country column in Brands admin list table
  - Used in Style 2 layout of Product Brands Grid widget

- ⭐ **NEW: Icon Category Grid Elementor Widget**
  - Neumorphic grid of icon + title cards with repeater
  - Exact Figma neumorphic multi-shadow (8 box-shadows + backdrop-filter blur)
  - Neumorphic toggle with intensity and blur controls
  - Icon inside circle with full border/color/size controls
  - 10 default items: Transmission, Engine, Suspension, Braking, Electrical, HVAC, Intake, Body, Interior, Fluids
  - Responsive grid (default: 5 / 3 / 2 columns)
  - Hover lift animation with transition controls

- ⭐ **NEW: CTA Banner Elementor Widget**
  - Full-width call-to-action banner with background image and overlay
  - Centered title, multi-line description, and glassmorphic CTA button
  - Button uses `rmt-glass-btn` / `rmt-btn-buy` gradient styling with inset shadow
  - Arrow icon on button (toggleable, position before/after)
  - Full content positioning controls (horizontal, vertical, alignment)
  - Normal/Hover tabs for button styling with gradient background controls
  - Responsive layout with adjustable height, max-width, padding

- ⭐ **NEW: Brand Showcase Slider Elementor Widget**
  - Full-width slider with repeater-based slides
  - Centered title with dark overlay (default: rgba(55, 65, 81, 0.7))
  - Bottom-center arrow navigation in rounded pill container with divider
  - Bottom-right subtitle text (e.g., model names)
  - Fade and slide transition effects
  - Autoplay with pause-on-hover and infinite loop
  - Keyboard navigation and touch/swipe support
  - Optional dot pagination
  - Per-slide overlay color override
  - Full style controls: overlay, container, image, title, subtitle, arrows, dots

### Version 3.0.0
- ⭐ **NEW: WooCommerce Cart Page Styling**
  - Modern 2-column layout (cart table + order summary)
  - "Order Summary" title via `woocommerce_before_cart_totals` hook (Poppins, 31px, 600 weight)
  - Dark teal header with glassmorphic checkout button
  - Quantity selector with custom +/- buttons
  - Red trash delete button with SVG icon
  - Trust badges below checkout (via `woocommerce_after_cart_totals` hook)
  - Fully responsive (tablet & mobile)

- ⭐ **NEW: WooCommerce Checkout Page Styling**
  - 2-column layout (billing details + order review card)
  - Light gray input field backgrounds (#F0F0F0)
  - Custom coupon form integrated in order review
  - Clean payment methods section
  - Solid dark blue Place Order button
  - Fully responsive (tablet & mobile)

- ⭐ **NEW: Checkout Customizer Class**
  - PHP-based customization using WooCommerce hooks/filters
  - Coupon form rendering via `woocommerce_review_order_before_payment` hook
  - Form field customization filter
  - Coupon enablement via filters

- ⭐ **NEW: Delete Icon SVG**
  - Red trash icon (#DC2626) for cart item removal
  - 21x22px size

- Improved Global Assets class to enqueue cart & checkout CSS
- All files follow WooCommerce best practices
- Comprehensive README documentation with file paths

### Version 2.0.0
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
