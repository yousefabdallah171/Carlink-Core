<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Override {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // 1. Override template using woocommerce_locate_template (PRIMARY METHOD)
        add_filter( 'woocommerce_locate_template', [ $this, 'override_locate_template' ], 9999, 3 );

        // 2. Override template part for related/upsells (BACKUP METHOD)
        add_filter( 'wc_get_template_part', [ $this, 'override_template_part' ], 9999, 3 );

        // 3. Enqueue Assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // 4. Remove conflicting theme hooks early
        add_action( 'wp', [ $this, 'remove_theme_hooks' ], 1 );
        add_action( 'woocommerce_init', [ $this, 'remove_theme_hooks' ], 1 );

        // 5. Override shop toolbar
        add_action( 'wp', [ $this, 'override_shop_toolbar' ], 20 );
    }

    /**
     * Override WooCommerce template location
     * This is the CORRECT way to override templates from a plugin
     */
    public function override_locate_template( $template, $template_name, $template_path ) {
        if ( 'content-product.php' === $template_name ) {
            $plugin_template = RMT_PATH . 'templates/content-product.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Override template part (for wc_get_template_part calls in related/upsells)
     */
    public function override_template_part( $template, $slug, $name ) {
        if ( 'content' === $slug && 'product' === $name ) {
            $plugin_template = RMT_PATH . 'templates/content-product.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Remove theme hooks that conflict with our template
     */
    public function remove_theme_hooks() {
        // Remove ALL default WooCommerce loop hooks to prevent duplication
        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

        // Remove Martfury theme specific hooks
        remove_action( 'woocommerce_before_shop_loop_item', 'martfury_product_loop_inner_open', 5 );
        remove_action( 'woocommerce_after_shop_loop_item', 'martfury_product_loop_inner_close', 15 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'martfury_template_loop_product_thumbnail', 10 );
        remove_action( 'woocommerce_shop_loop_item_title', 'martfury_template_loop_product_title', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'martfury_template_loop_price', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'martfury_template_loop_add_to_cart', 10 );

        // Remove any Martfury class hooks
        if ( class_exists( 'Martfury_WooCommerce' ) ) {
            global $martfury_woocommerce;
            if ( $martfury_woocommerce ) {
                remove_action( 'woocommerce_before_shop_loop_item', [ $martfury_woocommerce, 'product_loop_inner_open' ], 5 );
                remove_action( 'woocommerce_after_shop_loop_item', [ $martfury_woocommerce, 'product_loop_inner_close' ], 15 );
            }
        }
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

        // Add our custom toolbar
        add_action( 'woocommerce_before_shop_loop', [ $this, 'render_custom_toolbar' ], 20 );
    }

    /**
     * Render custom shop toolbar with search and sorting
     */
    public function render_custom_toolbar() {
        $orderby_options = [
            'menu_order' => __( 'Default sorting', 'woocommerce' ),
            'popularity' => __( 'Sort by popularity', 'woocommerce' ),
            'rating'     => __( 'Sort by average rating', 'woocommerce' ),
            'date'       => __( 'Sort by latest', 'woocommerce' ),
            'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
            'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
        ];

        $current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'menu_order';
        $search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
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
                    <select name="orderby" class="rmt-orderby" onchange="this.form.submit()">
                        <option value="" disabled><?php esc_html_e( 'Sort by', 'rakmyat-core' ); ?></option>
                        <?php foreach ( $orderby_options as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_orderby, $value ); ?>>
                                <?php echo esc_html( $label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="paged" value="1" />
                    <?php wc_query_string_form_fields( null, [ 'orderby', 'submit', 'paged', 'product-page' ] ); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue CSS and JS assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'rmt-product-card',
            RMT_URL . 'assets/css/product-card.css',
            [],
            time()
        );

        wp_enqueue_script(
            'rmt-product-card',
            RMT_URL . 'assets/js/product-card.js',
            [ 'jquery' ],
            time(),
            true
        );

        wp_localize_script( 'rmt-product-card', 'rmt_vars', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
        ]);
    }
}
