<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shop_Sidebar {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Enqueue assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Replace sidebar content
        add_action( 'wp', [ $this, 'setup_sidebar' ] );

        // Multiple hooks to ensure sidebar content is rendered
        add_action( 'woocommerce_sidebar', [ $this, 'inject_sidebar_content' ], 5 );
        add_action( 'get_sidebar', [ $this, 'inject_on_get_sidebar' ], 10 );

        // Use wp_footer as fallback to inject via JS if needed
        add_action( 'wp_footer', [ $this, 'fallback_sidebar_injection' ], 5 );

        // Filter products by availability
        add_action( 'woocommerce_product_query', [ $this, 'filter_by_availability' ] );

        // Filter products by rating
        add_action( 'woocommerce_product_query', [ $this, 'filter_by_rating' ] );
    }

    /**
     * Enqueue sidebar assets
     */
    public function enqueue_assets() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! is_search() ) {
            return;
        }

        wp_enqueue_style(
            'rmt-shop-sidebar',
            RMT_URL . 'assets/css/shop-sidebar.css',
            [],
            time()
        );

        wp_enqueue_script(
            'rmt-shop-sidebar',
            RMT_URL . 'assets/js/shop-sidebar.js',
            [ 'jquery' ],
            time(),
            true
        );

        wp_localize_script( 'rmt-shop-sidebar', 'rmt_sidebar', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'shop_url' => get_permalink( wc_get_page_id( 'shop' ) ),
        ]);
    }

    /**
     * Setup sidebar override
     */
    public function setup_sidebar() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        if ( ! get_theme_mod( 'rmt_enable_custom_sidebar', true ) ) {
            return;
        }

        // Hook into sidebar to add our content
        add_action( 'wp_footer', [ $this, 'render_mobile_elements' ] );

        // Replace sidebar content using widget area
        add_filter( 'sidebars_widgets', [ $this, 'replace_sidebar_widgets' ] );

        // Register our own widget to display sidebar
        add_action( 'widgets_init', [ $this, 'register_sidebar_widget' ] );

        // Add content directly to sidebar
        add_action( 'dynamic_sidebar_before', [ $this, 'render_custom_sidebar' ], 5 );
    }

    /**
     * Replace sidebar widgets - clear all widgets from shop sidebars
     */
    public function replace_sidebar_widgets( $sidebars_widgets ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return $sidebars_widgets;
        }

        // Common shop sidebar IDs
        $shop_sidebars = [
            'shop-sidebar',
            'sidebar-shop',
            'catalog-sidebar',
            'primary-sidebar',
            'shop-sidebar-1',
            'woocommerce-sidebar'
        ];

        foreach ( $shop_sidebars as $sidebar ) {
            if ( isset( $sidebars_widgets[ $sidebar ] ) ) {
                $sidebars_widgets[ $sidebar ] = [];
            }
        }

        return $sidebars_widgets;
    }

    /**
     * Register sidebar widget
     */
    public function register_sidebar_widget() {
        // This is just a placeholder
    }

    /**
     * Track if sidebar has been rendered
     */
    private static $sidebar_rendered = false;

    /**
     * Inject sidebar content directly via woocommerce_sidebar hook
     */
    public function inject_sidebar_content() {
        if ( self::$sidebar_rendered ) {
            return;
        }

        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        if ( ! get_theme_mod( 'rmt_enable_custom_sidebar', true ) ) {
            return;
        }

        $this->output_sidebar_html();
        self::$sidebar_rendered = true;
    }

    /**
     * Inject on get_sidebar hook
     */
    public function inject_on_get_sidebar( $name ) {
        if ( self::$sidebar_rendered ) {
            return;
        }

        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        if ( ! get_theme_mod( 'rmt_enable_custom_sidebar', true ) ) {
            return;
        }

        // Check if this is a shop-related sidebar
        $shop_sidebars = [ 'shop', 'catalog', 'shop-sidebar', 'woocommerce' ];
        if ( $name && ! in_array( $name, $shop_sidebars ) ) {
            return;
        }

        $this->output_sidebar_html();
        self::$sidebar_rendered = true;
    }

    /**
     * Fallback: Inject sidebar via JavaScript if not rendered yet
     */
    public function fallback_sidebar_injection() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        if ( ! get_theme_mod( 'rmt_enable_custom_sidebar', true ) ) {
            return;
        }

        // Capture sidebar HTML
        ob_start();
        $this->output_sidebar_html();
        $sidebar_html = ob_get_clean();

        // Escape for JS
        $sidebar_html = str_replace( [ "\r\n", "\r", "\n" ], '', $sidebar_html );
        $sidebar_html = str_replace( "'", "\\'", $sidebar_html );
        ?>
        <script>
        (function() {
            // Check if sidebar already exists
            if (document.querySelector('.rmt-shop-sidebar')) {
                return;
            }

            // Target sidebar containers
            var selectors = [
                '#primary-sidebar',
                '.primary-sidebar',
                '.catalog-sidebar',
                '#secondary',
                '.sidebar-shop',
                '.shop-sidebar',
                'aside.widgets-area'
            ];

            var sidebar = null;
            for (var i = 0; i < selectors.length; i++) {
                sidebar = document.querySelector(selectors[i]);
                if (sidebar) break;
            }

            if (sidebar) {
                sidebar.innerHTML = '<?php echo $sidebar_html; ?>';
            }
        })();
        </script>
        <?php
    }

    /**
     * Output the sidebar HTML
     */
    private function output_sidebar_html() {
        $filters_title = get_theme_mod( 'rmt_filters_title', __( 'Filters', 'rakmyat-core' ) );
        ?>
        <div class="rmt-shop-sidebar">
            <h2 class="rmt-sidebar-title"><?php echo esc_html( $filters_title ); ?></h2>

            <?php
            // Category Filter
            if ( get_theme_mod( 'rmt_enable_category_filter', true ) ) {
                $this->render_category_filter();
            }

            // Brand Filter
            if ( get_theme_mod( 'rmt_enable_brand_filter', true ) ) {
                $this->render_brand_filter();
            }

            // Price Range Filter
            if ( get_theme_mod( 'rmt_enable_price_filter', true ) ) {
                $this->render_price_filter();
            }

            // Availability Filter
            if ( get_theme_mod( 'rmt_enable_availability_filter', true ) ) {
                $this->render_availability_filter();
            }

            // Collections Filter
            if ( get_theme_mod( 'rmt_enable_collections_filter', true ) ) {
                $this->render_collections_filter();
            }

            // Ratings Filter
            if ( get_theme_mod( 'rmt_enable_ratings_filter', true ) ) {
                $this->render_ratings_filter();
            }
            ?>

            <!-- Mobile Close Button -->
            <button class="rmt-filter-close-btn" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <?php
    }

    /**
     * Render mobile elements (toggle button and overlay)
     */
    public function render_mobile_elements() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        ?>
        <!-- Mobile Filter Toggle Button -->
        <button class="rmt-filter-toggle-btn" type="button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 21v-7m0-4V3m8 18v-9m0-4V3m8 18v-5m0-4V3M1 14h6M9 8h6M17 16h6"/>
            </svg>
            <span><?php esc_html_e( 'Filters', 'rakmyat-core' ); ?></span>
        </button>
        <div class="rmt-sidebar-overlay"></div>
        <?php
    }

    /**
     * Render custom sidebar
     */
    public function render_custom_sidebar( $sidebar_id ) {
        // Common shop sidebar IDs
        $shop_sidebars = [
            'shop-sidebar',
            'sidebar-shop',
            'catalog-sidebar',
            'primary-sidebar',
            'shop-sidebar-1',
            'woocommerce-sidebar'
        ];

        if ( ! in_array( $sidebar_id, $shop_sidebars ) ) {
            return;
        }

        // Prevent duplicate rendering
        static $rendered = false;
        if ( $rendered ) {
            return;
        }
        $rendered = true;

        $filters_title = get_theme_mod( 'rmt_filters_title', __( 'Filters', 'rakmyat-core' ) );
        ?>
        <div class="rmt-shop-sidebar">
            <h2 class="rmt-sidebar-title"><?php echo esc_html( $filters_title ); ?></h2>

            <?php
            // Category Filter
            if ( get_theme_mod( 'rmt_enable_category_filter', true ) ) {
                $this->render_category_filter();
            }

            // Brand Filter
            if ( get_theme_mod( 'rmt_enable_brand_filter', true ) ) {
                $this->render_brand_filter();
            }

            // Price Range Filter
            if ( get_theme_mod( 'rmt_enable_price_filter', true ) ) {
                $this->render_price_filter();
            }

            // Availability Filter
            if ( get_theme_mod( 'rmt_enable_availability_filter', true ) ) {
                $this->render_availability_filter();
            }

            // Collections Filter
            if ( get_theme_mod( 'rmt_enable_collections_filter', true ) ) {
                $this->render_collections_filter();
            }

            // Ratings Filter
            if ( get_theme_mod( 'rmt_enable_ratings_filter', true ) ) {
                $this->render_ratings_filter();
            }
            ?>

            <!-- Mobile Close Button -->
            <button class="rmt-filter-close-btn" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <?php
    }

    /**
     * Render Category Filter
     */
    private function render_category_filter() {
        $categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0,
        ]);

        if ( empty( $categories ) || is_wp_error( $categories ) ) {
            return;
        }

        $current_cat = isset( $_GET['product_cat'] ) ? sanitize_text_field( $_GET['product_cat'] ) : '';
        ?>
        <div class="rmt-filter-section" data-filter="category">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Category', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <ul class="rmt-filter-list">
                    <?php foreach ( $categories as $category ) : ?>
                        <li>
                            <label class="rmt-filter-item">
                                <input type="checkbox"
                                       name="product_cat"
                                       value="<?php echo esc_attr( $category->slug ); ?>"
                                       <?php checked( $current_cat, $category->slug ); ?>>
                                <span class="rmt-checkbox"></span>
                                <span class="rmt-filter-label"><?php echo esc_html( $category->name ); ?></span>
                                <span class="rmt-filter-count">(<?php echo esc_html( $category->count ); ?>)</span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render Brand Filter
     */
    private function render_brand_filter() {
        $brands = get_terms([
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
        ]);

        if ( empty( $brands ) || is_wp_error( $brands ) ) {
            return;
        }

        $current_brand = isset( $_GET['product_brand'] ) ? sanitize_text_field( $_GET['product_brand'] ) : '';
        ?>
        <div class="rmt-filter-section" data-filter="brand">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Brand', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <ul class="rmt-filter-list">
                    <?php foreach ( $brands as $brand ) : ?>
                        <li>
                            <label class="rmt-filter-item">
                                <input type="checkbox"
                                       name="product_brand"
                                       value="<?php echo esc_attr( $brand->slug ); ?>"
                                       <?php checked( $current_brand, $brand->slug ); ?>>
                                <span class="rmt-checkbox"></span>
                                <span class="rmt-filter-label"><?php echo esc_html( $brand->name ); ?></span>
                                <span class="rmt-filter-count">(<?php echo esc_html( $brand->count ); ?>)</span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render Price Filter
     */
    private function render_price_filter() {
        global $wpdb;

        // Get min and max prices
        $prices = $wpdb->get_row("
            SELECT MIN( CAST( meta_value AS DECIMAL(10,2) ) ) as min_price,
                   MAX( CAST( meta_value AS DECIMAL(10,2) ) ) as max_price
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_price'
            AND meta_value > 0
        ");

        $min_price = floor( $prices->min_price ?? 0 );
        $max_price = ceil( $prices->max_price ?? 1000 );

        $current_min = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $min_price;
        $current_max = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $max_price;

        $currency_symbol = get_woocommerce_currency_symbol();
        ?>
        <div class="rmt-filter-section" data-filter="price">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Price Range', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <div class="rmt-price-range">
                    <div class="rmt-price-inputs">
                        <div class="rmt-price-input-group">
                            <span class="rmt-currency"><?php echo esc_html( $currency_symbol ); ?></span>
                            <input type="number"
                                   class="rmt-price-min"
                                   name="min_price"
                                   value="<?php echo esc_attr( $current_min ); ?>"
                                   min="<?php echo esc_attr( $min_price ); ?>"
                                   max="<?php echo esc_attr( $max_price ); ?>"
                                   placeholder="Min">
                        </div>
                        <span class="rmt-price-separator">-</span>
                        <div class="rmt-price-input-group">
                            <span class="rmt-currency"><?php echo esc_html( $currency_symbol ); ?></span>
                            <input type="number"
                                   class="rmt-price-max"
                                   name="max_price"
                                   value="<?php echo esc_attr( $current_max ); ?>"
                                   min="<?php echo esc_attr( $min_price ); ?>"
                                   max="<?php echo esc_attr( $max_price ); ?>"
                                   placeholder="Max">
                        </div>
                    </div>
                    <div class="rmt-price-slider-container">
                        <div class="rmt-price-slider"
                             data-min="<?php echo esc_attr( $min_price ); ?>"
                             data-max="<?php echo esc_attr( $max_price ); ?>"
                             data-current-min="<?php echo esc_attr( $current_min ); ?>"
                             data-current-max="<?php echo esc_attr( $current_max ); ?>">
                            <div class="rmt-price-track"></div>
                            <div class="rmt-price-range-fill"></div>
                            <input type="range" class="rmt-range-min" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" value="<?php echo esc_attr( $current_min ); ?>">
                            <input type="range" class="rmt-range-max" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" value="<?php echo esc_attr( $current_max ); ?>">
                        </div>
                    </div>
                    <button type="button" class="rmt-price-apply"><?php esc_html_e( 'Apply', 'rakmyat-core' ); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render Availability Filter
     */
    private function render_availability_filter() {
        global $wpdb;

        // Count in stock products
        $in_stock_count = $wpdb->get_var("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND pm.meta_key = '_stock_status'
            AND pm.meta_value = 'instock'
        ");

        // Count out of stock products
        $out_of_stock_count = $wpdb->get_var("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND pm.meta_key = '_stock_status'
            AND pm.meta_value = 'outofstock'
        ");

        $current_availability = isset( $_GET['availability'] ) ? sanitize_text_field( $_GET['availability'] ) : '';
        ?>
        <div class="rmt-filter-section rmt-filter-open" data-filter="availability">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Availability', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <ul class="rmt-filter-list">
                    <li>
                        <label class="rmt-filter-item rmt-radio-item">
                            <input type="radio"
                                   name="availability"
                                   value="instock"
                                   <?php checked( $current_availability, 'instock' ); ?>>
                            <span class="rmt-radio"></span>
                            <span class="rmt-filter-label"><?php esc_html_e( 'In Stock', 'rakmyat-core' ); ?></span>
                            <span class="rmt-filter-count">(<?php echo esc_html( $in_stock_count ); ?>)</span>
                        </label>
                    </li>
                    <li>
                        <label class="rmt-filter-item rmt-radio-item">
                            <input type="radio"
                                   name="availability"
                                   value="outofstock"
                                   <?php checked( $current_availability, 'outofstock' ); ?>>
                            <span class="rmt-radio"></span>
                            <span class="rmt-filter-label"><?php esc_html_e( 'Out Of Stock', 'rakmyat-core' ); ?></span>
                            <span class="rmt-filter-count">(<?php echo esc_html( $out_of_stock_count ); ?>)</span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render Collections Filter
     */
    private function render_collections_filter() {
        $parent_cat_id = get_theme_mod( 'rmt_collections_category', 0 );

        $collections = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $parent_cat_id ? $parent_cat_id : 0,
        ]);

        if ( empty( $collections ) || is_wp_error( $collections ) ) {
            return;
        }

        $current_collection = isset( $_GET['collection'] ) ? sanitize_text_field( $_GET['collection'] ) : '';
        ?>
        <div class="rmt-filter-section" data-filter="collections">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Collections', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <ul class="rmt-filter-list">
                    <?php foreach ( $collections as $collection ) : ?>
                        <li>
                            <label class="rmt-filter-item">
                                <input type="checkbox"
                                       name="collection"
                                       value="<?php echo esc_attr( $collection->slug ); ?>"
                                       <?php checked( $current_collection, $collection->slug ); ?>>
                                <span class="rmt-checkbox"></span>
                                <span class="rmt-filter-label"><?php echo esc_html( $collection->name ); ?></span>
                                <span class="rmt-filter-count">(<?php echo esc_html( $collection->count ); ?>)</span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render Ratings Filter
     */
    private function render_ratings_filter() {
        $current_rating = isset( $_GET['rating_filter'] ) ? intval( $_GET['rating_filter'] ) : 0;
        ?>
        <div class="rmt-filter-section" data-filter="ratings">
            <div class="rmt-filter-header">
                <span class="rmt-filter-title"><?php esc_html_e( 'Ratings', 'rakmyat-core' ); ?></span>
                <span class="rmt-filter-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            </div>
            <div class="rmt-filter-content">
                <ul class="rmt-filter-list rmt-rating-list">
                    <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                        <li>
                            <label class="rmt-filter-item rmt-rating-item">
                                <input type="radio"
                                       name="rating_filter"
                                       value="<?php echo esc_attr( $i ); ?>"
                                       <?php checked( $current_rating, $i ); ?>>
                                <span class="rmt-radio"></span>
                                <span class="rmt-rating-stars">
                                    <?php for ( $j = 1; $j <= 5; $j++ ) : ?>
                                        <span class="rmt-star <?php echo $j <= $i ? 'rmt-star-filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </span>
                                <span class="rmt-rating-text"><?php echo esc_html( $i ); ?>+ <?php esc_html_e( 'stars', 'rakmyat-core' ); ?></span>
                            </label>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Filter products by availability
     */
    public function filter_by_availability( $query ) {
        if ( ! isset( $_GET['availability'] ) ) {
            return;
        }

        $availability = sanitize_text_field( $_GET['availability'] );

        $meta_query = $query->get( 'meta_query' );
        if ( ! is_array( $meta_query ) ) {
            $meta_query = [];
        }

        $meta_query[] = [
            'key'     => '_stock_status',
            'value'   => $availability,
            'compare' => '=',
        ];

        $query->set( 'meta_query', $meta_query );
    }

    /**
     * Filter products by rating
     */
    public function filter_by_rating( $query ) {
        if ( ! isset( $_GET['rating_filter'] ) ) {
            return;
        }

        $rating = intval( $_GET['rating_filter'] );

        $meta_query = $query->get( 'meta_query' );
        if ( ! is_array( $meta_query ) ) {
            $meta_query = [];
        }

        $meta_query[] = [
            'key'     => '_wc_average_rating',
            'value'   => $rating,
            'compare' => '>=',
            'type'    => 'DECIMAL',
        ];

        $query->set( 'meta_query', $meta_query );
    }
}
