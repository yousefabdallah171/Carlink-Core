<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shop_Page {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Enqueue shop page assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Override shop toolbar
        add_action( 'wp', [ $this, 'override_shop_toolbar' ], 20 );
    }

    /**
     * Enqueue shop page CSS
     */
    public function enqueue_assets() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! is_search() ) {
            return;
        }

        wp_enqueue_style(
            'rmt-shop-page',
            RMT_URL . 'assets/css/shop-page.css',
            [],
            time()
        );
    }

    /**
     * Override shop toolbar - remove default and add custom
     */
    public function override_shop_toolbar() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        // Remove default WooCommerce result count and ordering
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

        // Remove Martfury toolbar if exists
        remove_action( 'woocommerce_before_shop_loop', 'martfury_shop_toolbar', 20 );

        // Add wrapper and custom toolbar
        add_action( 'woocommerce_before_shop_loop', [ $this, 'open_shop_wrapper' ], 15 );
        add_action( 'woocommerce_before_shop_loop', [ $this, 'render_custom_toolbar' ], 20 );
        add_action( 'woocommerce_after_shop_loop', [ $this, 'close_shop_wrapper' ], 5 );
    }

    /**
     * Open wrapper for shop page
     */
    public function open_shop_wrapper() {
        echo '<div class="rmt-shop-loop-main-grid">';
    }

    /**
     * Close wrapper for shop page
     */
    public function close_shop_wrapper() {
        echo '</div>';
    }

    /**
     * Render custom shop toolbar with search and sorting
     */
    public function render_custom_toolbar() {
        $orderby_options = [
            'menu_order' => __( 'Sort by', 'woocommerce' ),
            'popularity' => __( 'Sort by popularity', 'woocommerce' ),
            'rating'     => __( 'Sort by average rating', 'woocommerce' ),
            'date'       => __( 'Sort by latest', 'woocommerce' ),
            'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
            'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
        ];

        $current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'menu_order';
        $search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

        // Render breadcrumbs
        if ( function_exists( 'woocommerce_breadcrumb' ) ) {
            echo '<div class="rmt-breadcrumbs-wrapper">';
            woocommerce_breadcrumb( [
                'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
                'wrap_after'  => '</nav>',
                'before'      => '<span class="rmt-breadcrumb-item">',
                'after'       => '</span>',
                'delimiter'   => '&nbsp;/&nbsp;',
            ] );
            echo '</div>';
        }
        ?>
        <div class="rmt-shop-toolbar">
            <div class="rmt-toolbar-left">
                <h2 class="rmt-result-title"><?php esc_html_e( 'RESULT', 'rakmyat-core' ); ?></h2>
                <form role="search" method="get" class="rmt-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search"
                           class="rmt-search-field"
                           placeholder="<?php esc_attr_e( 'Search', 'rakmyat-core' ); ?>"
                           value="<?php echo esc_attr( $search_query ); ?>"
                           name="s" />
                    <input type="hidden" name="post_type" value="product" />
                    <button type="submit" class="rmt-search-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>
            <div class="rmt-toolbar-right">
                <form class="rmt-ordering" method="get">
                    <div class="rmt-orderby-wrapper">
                        <select name="orderby" class="rmt-orderby" onchange="this.form.submit()">
                            <option value="" disabled><?php esc_html_e( 'Selecte Sorting', 'rakmyat-core' ); ?></option>
                            <?php foreach ( $orderby_options as $value => $label ) : ?>
                                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_orderby, $value ); ?>>
                                    <?php echo esc_html( $label ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <img class="rmt-orderby-icon" src="<?php echo esc_url( RMT_URL . 'assets/img/filter_list.svg' ); ?>" alt="Sort" />
                    </div>
                    <input type="hidden" name="paged" value="1" />
                    <?php wc_query_string_form_fields( null, [ 'orderby', 'submit', 'paged', 'product-page' ] ); ?>
                </form>
            </div>
        </div>
        <?php
    }
}
