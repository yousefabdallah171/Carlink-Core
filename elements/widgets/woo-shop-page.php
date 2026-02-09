<?php
/**
 * WooCommerce Shop Page Widget
 *
 * Complete shop page experience: sidebar filters, breadcrumbs, toolbar
 * (search + sorting), product grid using content-product.php, and pagination.
 * Drop onto any Elementor page to get the full shop layout.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Woo_Shop_Page_Widget extends Widget_Base {

    public function get_name() { return 'woo-shop-page'; }

    public function get_title() { return __( 'Shop Page', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-products'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_keywords() { return [ 'shop', 'woocommerce', 'products', 'store', 'catalog' ]; }

    public function get_style_depends() { return [ 'rmt-woo-shop-page-css' ]; }

    private function get_product_categories() {
        $options = [ '' => __( 'All Categories', 'rakmyat-core' ) ];
        if ( ! taxonomy_exists( 'product_cat' ) ) return $options;

        $terms = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ] );

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
        }
        return $options;
    }

    private function get_product_tags() {
        $options = [ '' => __( 'All Tags', 'rakmyat-core' ) ];
        if ( ! taxonomy_exists( 'product_tag' ) ) return $options;

        $terms = get_terms( [
            'taxonomy'   => 'product_tag',
            'hide_empty' => true,
        ] );

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
        }
        return $options;
    }

    protected function register_controls() {

        /* ============================================================
         * CONTENT — QUERY
         * ============================================================ */
        $this->start_controls_section( 'section_query', [
            'label' => __( 'Query', 'rakmyat-core' ),
        ] );

        $this->add_control( 'posts_per_page', [
            'label'   => __( 'Products Per Page', 'rakmyat-core' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 100,
        ] );

        $this->add_responsive_control( 'columns', [
            'label'          => __( 'Columns', 'rakmyat-core' ),
            'type'           => Controls_Manager::SELECT,
            'default'        => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options'        => [
                '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5',
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-woo-shop-page ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr) !important;',
            ],
        ] );

        $this->add_control( 'category', [
            'label'   => __( 'Category', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => $this->get_product_categories(),
        ] );

        $this->add_control( 'tag', [
            'label'   => __( 'Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => $this->get_product_tags(),
        ] );

        $this->add_control( 'default_orderby', [
            'label'   => __( 'Default Sort', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'menu_order',
            'options' => [
                'menu_order' => __( 'Default', 'rakmyat-core' ),
                'date'       => __( 'Latest', 'rakmyat-core' ),
                'price'      => __( 'Price (Low→High)', 'rakmyat-core' ),
                'price-desc' => __( 'Price (High→Low)', 'rakmyat-core' ),
                'popularity' => __( 'Popularity', 'rakmyat-core' ),
                'rating'     => __( 'Rating', 'rakmyat-core' ),
                'title'      => __( 'Title', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'filter_heading', [
            'label'     => __( 'Filters', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'show_only', [
            'label'   => __( 'Show Only', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                ''         => __( 'All Products', 'rakmyat-core' ),
                'featured' => __( 'Featured', 'rakmyat-core' ),
                'on_sale'  => __( 'On Sale', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'hide_out_of_stock', [
            'label'   => __( 'Hide Out of Stock', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * CONTENT — LAYOUT
         * ============================================================ */
        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
        ] );

        $this->add_control( 'show_sidebar', [
            'label'   => __( 'Show Sidebar', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_breadcrumbs', [
            'label'   => __( 'Show Breadcrumbs', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_toolbar', [
            'label'   => __( 'Show Toolbar', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_pagination', [
            'label'   => __( 'Show Pagination', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE — GRID
         * ============================================================ */
        $this->start_controls_section( 'section_style_grid', [
            'label' => __( 'Grid', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'column_gap', [
            'label'      => __( 'Column Gap', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default'    => [ 'size' => 24 ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-woo-shop-page ul.products' => 'column-gap: {{SIZE}}{{UNIT}} !important;',
            ],
        ] );

        $this->add_responsive_control( 'row_gap', [
            'label'      => __( 'Row Gap', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default'    => [ 'size' => 24 ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-woo-shop-page ul.products' => 'row-gap: {{SIZE}}{{UNIT}} !important;',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is required.', 'rakmyat-core' ) . '</p>';
            return;
        }

        $settings     = $this->get_settings_for_display();
        $show_sidebar = $settings['show_sidebar'] === 'yes';

        // Load product card CSS
        wp_enqueue_style(
            'rmt-product-card',
            RMT_URL . 'assets/css/product-card.css',
            [],
            filemtime( RMT_PATH . 'assets/css/product-card.css' )
        );

        // Load sidebar assets when enabled
        if ( $show_sidebar ) {
            wp_enqueue_style(
                'rmt-shop-sidebar',
                RMT_URL . 'assets/css/shop-sidebar.css',
                [],
                filemtime( RMT_PATH . 'assets/css/shop-sidebar.css' )
            );

            wp_enqueue_script(
                'rmt-shop-sidebar',
                RMT_URL . 'assets/js/shop-sidebar.js',
                [ 'jquery' ],
                filemtime( RMT_PATH . 'assets/js/shop-sidebar.js' ),
                true
            );

            // Use current page URL so sidebar filters redirect back here
            wp_localize_script( 'rmt-shop-sidebar', 'rmt_sidebar', [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'shop_url' => get_permalink(),
            ] );
        }

        // URL parameters (from toolbar forms + sidebar filters)
        $current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : $settings['default_orderby'];
        $search_query    = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
        $paged           = max( 1, get_query_var( 'paged', 1 ) );
        if ( ! $paged ) {
            $paged = max( 1, absint( isset( $_GET['paged'] ) ? $_GET['paged'] : 1 ) );
        }

        // Sidebar filter URL params
        $filter_cat    = isset( $_GET['product_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) : '';
        $filter_brand  = isset( $_GET['product_brand'] ) ? sanitize_text_field( wp_unslash( $_GET['product_brand'] ) ) : '';
        $filter_min    = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
        $filter_max    = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';
        $filter_avail  = isset( $_GET['availability'] ) ? sanitize_text_field( wp_unslash( $_GET['availability'] ) ) : '';
        $filter_collect = isset( $_GET['collection'] ) ? sanitize_text_field( wp_unslash( $_GET['collection'] ) ) : '';
        $filter_rating = isset( $_GET['rating_filter'] ) ? intval( $_GET['rating_filter'] ) : 0;

        // Build query args
        $query_args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'paged'          => $paged,
        ];

        // Sorting
        switch ( $current_orderby ) {
            case 'popularity':
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby']  = 'meta_value_num';
                $query_args['order']    = 'DESC';
                break;
            case 'rating':
                $query_args['meta_key'] = '_wc_average_rating';
                $query_args['orderby']  = 'meta_value_num';
                $query_args['order']    = 'DESC';
                break;
            case 'date':
                $query_args['orderby'] = 'date';
                $query_args['order']   = 'DESC';
                break;
            case 'price':
                $query_args['meta_key'] = '_price';
                $query_args['orderby']  = 'meta_value_num';
                $query_args['order']    = 'ASC';
                break;
            case 'price-desc':
                $query_args['meta_key'] = '_price';
                $query_args['orderby']  = 'meta_value_num';
                $query_args['order']    = 'DESC';
                break;
            case 'title':
                $query_args['orderby'] = 'title';
                $query_args['order']   = 'ASC';
                break;
            default:
                $query_args['orderby'] = 'menu_order title';
                $query_args['order']   = 'ASC';
                break;
        }

        // Search
        if ( $search_query ) {
            $query_args['s'] = $search_query;
        }

        // Category — sidebar URL param overrides Elementor control
        if ( $filter_cat ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $filter_cat,
            ];
        } elseif ( ! empty( $settings['category'] ) ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $settings['category'],
            ];
        }

        // Brand (from sidebar)
        if ( $filter_brand ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => $filter_brand,
            ];
        }

        // Tag (from Elementor control)
        if ( ! empty( $settings['tag'] ) ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $settings['tag'],
            ];
        }

        // Collection (from sidebar)
        if ( $filter_collect ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $filter_collect,
            ];
        }

        // Featured (from Elementor control)
        if ( $settings['show_only'] === 'featured' ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ];
        }

        // On sale
        if ( $settings['show_only'] === 'on_sale' ) {
            $sale_ids = wc_get_product_ids_on_sale();
            $query_args['post__in'] = ! empty( $sale_ids ) ? $sale_ids : [ 0 ];
        }

        // Price range (from sidebar)
        if ( $filter_min !== '' ) {
            $query_args['meta_query'][] = [
                'key'     => '_price',
                'value'   => $filter_min,
                'compare' => '>=',
                'type'    => 'DECIMAL',
            ];
        }
        if ( $filter_max !== '' ) {
            $query_args['meta_query'][] = [
                'key'     => '_price',
                'value'   => $filter_max,
                'compare' => '<=',
                'type'    => 'DECIMAL',
            ];
        }

        // Availability (from sidebar)
        if ( $filter_avail ) {
            $query_args['meta_query'][] = [
                'key'     => '_stock_status',
                'value'   => $filter_avail,
                'compare' => '=',
            ];
        }

        // Rating (from sidebar)
        if ( $filter_rating > 0 ) {
            $query_args['meta_query'][] = [
                'key'     => '_wc_average_rating',
                'value'   => $filter_rating,
                'compare' => '>=',
                'type'    => 'DECIMAL',
            ];
        }

        // Hide out of stock (from Elementor control, only if sidebar availability not active)
        if ( $settings['hide_out_of_stock'] === 'yes' && ! $filter_avail ) {
            $query_args['meta_query'][] = [
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ];
        }

        $products = new \WP_Query( $query_args );

        // Sorting options for toolbar dropdown
        $orderby_options = [
            'menu_order' => __( 'Sort by', 'woocommerce' ),
            'popularity' => __( 'Sort by popularity', 'woocommerce' ),
            'rating'     => __( 'Sort by average rating', 'woocommerce' ),
            'date'       => __( 'Sort by latest', 'woocommerce' ),
            'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
            'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
        ];

        $columns = $settings['columns'] ? $settings['columns'] : '3';
        ?>

        <div class="rmt-woo-shop-page woocommerce">
            <div class="rmt-shop-layout <?php echo $show_sidebar ? 'rmt-has-sidebar' : ''; ?>">

                <?php if ( $show_sidebar && class_exists( '\RakmyatCore\Core\Shop_Sidebar' ) ) : ?>
                    <aside class="rmt-shop-sidebar-col">
                        <?php \RakmyatCore\Core\Shop_Sidebar::instance()->output_sidebar_html(); ?>
                    </aside>
                <?php endif; ?>

                <div class="rmt-shop-loop-main-grid">

                    <?php if ( $show_sidebar ) : ?>
                        <button class="rmt-filter-toggle-btn" type="button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 21v-7m0-4V3m8 18v-9m0-4V3m8 18v-5m0-4V3M1 14h6M9 8h6M17 16h6"/>
                            </svg>
                            <span><?php esc_html_e( 'Filters', 'rakmyat-core' ); ?></span>
                        </button>
                    <?php endif; ?>

                    <?php if ( $settings['show_breadcrumbs'] === 'yes' && function_exists( 'woocommerce_breadcrumb' ) ) : ?>
                        <div class="rmt-breadcrumbs-wrapper">
                            <?php woocommerce_breadcrumb( [
                                'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
                                'wrap_after'  => '</nav>',
                                'before'      => '<span class="rmt-breadcrumb-item">',
                                'after'       => '</span>',
                                'delimiter'   => '&nbsp;/&nbsp;',
                            ] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $settings['show_toolbar'] === 'yes' ) : ?>
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
                                            <option value="" disabled><?php esc_html_e( 'Select Sorting', 'rakmyat-core' ); ?></option>
                                            <?php foreach ( $orderby_options as $value => $label ) : ?>
                                                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_orderby, $value ); ?>>
                                                    <?php echo esc_html( $label ); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <img class="rmt-orderby-icon" src="<?php echo esc_url( RMT_URL . 'assets/img/filter_list.svg' ); ?>" alt="Sort" />
                                    </div>
                                    <input type="hidden" name="paged" value="1" />
                                    <?php
                                    // Preserve existing query string params
                                    if ( ! empty( $_GET ) ) {
                                        foreach ( $_GET as $key => $val ) {
                                            if ( in_array( $key, [ 'orderby', 'submit', 'paged', 'product-page' ], true ) ) continue;
                                            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( sanitize_text_field( wp_unslash( $val ) ) ) . '" />';
                                        }
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( $products->have_posts() ) : ?>
                        <ul class="products columns-<?php echo esc_attr( $columns ); ?>">
                            <?php
                            while ( $products->have_posts() ) :
                                $products->the_post();
                                global $product;
                                $product = wc_get_product( get_the_ID() );
                                wc_get_template_part( 'content', 'product' );
                            endwhile;
                            ?>
                        </ul>

                        <?php if ( $settings['show_pagination'] === 'yes' && $products->max_num_pages > 1 ) : ?>
                            <nav class="woocommerce-pagination">
                                <?php
                                echo paginate_links( [
                                    'total'     => $products->max_num_pages,
                                    'current'   => $paged,
                                    'prev_text' => '&larr;',
                                    'next_text' => esc_html__( 'Next', 'rakmyat-core' ) . ' &rarr;',
                                    'type'      => 'list',
                                ] );
                                ?>
                            </nav>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="woocommerce-info"><?php esc_html_e( 'No products found.', 'rakmyat-core' ); ?></p>
                    <?php endif; ?>

                </div>
            </div>

            <?php if ( $show_sidebar ) : ?>
                <div class="rmt-sidebar-overlay"></div>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
    }
}
