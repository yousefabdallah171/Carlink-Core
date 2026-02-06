<?php
/**
 * Product Grid Widget
 *
 * Grid controller for WooCommerce products — uses the existing
 * content-product.php template & product-card.css for card styling.
 * This widget only controls: query, columns, and grid gaps.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Product_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'product-grid';
    }

    public function get_title() {
        return __( 'Product Grid', 'rakmyat-core' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'rakmyat-elements' ];
    }

    public function get_keywords() {
        return [ 'product', 'woocommerce', 'shop', 'grid', 'store' ];
    }

    public function get_style_depends() {
        return [ 'rmt-product-grid-css' ];
    }

    private function get_product_categories() {
        $options = [ '' => __( 'All Categories', 'rakmyat-core' ) ];
        if ( ! taxonomy_exists( 'product_cat' ) ) return $options;

        $terms = get_terms( [
            'taxonomy' => 'product_cat',
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
            'taxonomy' => 'product_tag',
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

        // =====================
        // CONTENT - QUERY
        // =====================
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'rakmyat-core' ),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Number of Products', 'rakmyat-core' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Products Per Row', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-grid ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr) !important;',
                ],
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Category', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_product_categories(),
            ]
        );

        $this->add_control(
            'tag',
            [
                'label' => __( 'Tag', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_product_tags(),
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order By', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __( 'Date', 'rakmyat-core' ),
                    'price' => __( 'Price', 'rakmyat-core' ),
                    'popularity' => __( 'Popularity', 'rakmyat-core' ),
                    'rating' => __( 'Rating', 'rakmyat-core' ),
                    'title' => __( 'Title', 'rakmyat-core' ),
                    'rand' => __( 'Random', 'rakmyat-core' ),
                    'menu_order' => __( 'Menu Order', 'rakmyat-core' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => __( 'Descending', 'rakmyat-core' ),
                    'ASC' => __( 'Ascending', 'rakmyat-core' ),
                ],
            ]
        );

        $this->add_control(
            'filter_heading',
            [
                'label' => __( 'Filter', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_only',
            [
                'label' => __( 'Show Only', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'All Products', 'rakmyat-core' ),
                    'featured' => __( 'Featured', 'rakmyat-core' ),
                    'on_sale' => __( 'On Sale', 'rakmyat-core' ),
                ],
            ]
        );

        $this->add_control(
            'hide_out_of_stock',
            [
                'label' => __( 'Hide Out of Stock', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - GRID
        // =====================
        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => __( 'Grid', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Column Gap', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                    'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 24 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-grid ul.products' => 'column-gap: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __( 'Row Gap', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                    'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 24 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-grid ul.products' => 'row-gap: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is required.', 'rakmyat-core' ) . '</p>';
            return;
        }

        $settings = $this->get_settings_for_display();

        // Build WP_Query args
        $query_args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby'        => $settings['orderby'],
            'order'          => $settings['order'],
        ];

        // Category filter
        if ( ! empty( $settings['category'] ) ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $settings['category'],
            ];
        }

        // Tag filter
        if ( ! empty( $settings['tag'] ) ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $settings['tag'],
            ];
        }

        // Featured filter
        if ( $settings['show_only'] === 'featured' ) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ];
        }

        // On sale filter
        if ( $settings['show_only'] === 'on_sale' ) {
            $sale_ids = wc_get_product_ids_on_sale();
            $query_args['post__in'] = ! empty( $sale_ids ) ? $sale_ids : [ 0 ];
        }

        // Hide out of stock
        if ( $settings['hide_out_of_stock'] === 'yes' ) {
            $query_args['meta_query'][] = [
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ];
        }

        // Popularity ordering
        if ( $settings['orderby'] === 'popularity' ) {
            $query_args['meta_key'] = 'total_sales';
            $query_args['orderby']  = 'meta_value_num';
        }

        // Rating ordering
        if ( $settings['orderby'] === 'rating' ) {
            $query_args['meta_key'] = '_wc_average_rating';
            $query_args['orderby']  = 'meta_value_num';
        }

        // Price ordering
        if ( $settings['orderby'] === 'price' ) {
            $query_args['meta_key'] = '_price';
            $query_args['orderby']  = 'meta_value_num';
        }

        $products = new \WP_Query( $query_args );

        if ( ! $products->have_posts() ) {
            echo '<p>' . esc_html__( 'No products found.', 'rakmyat-core' ) . '</p>';
            return;
        }

        ?>
        <div class="rmt-product-grid woocommerce">
            <ul class="products">
                <?php
                while ( $products->have_posts() ) :
                    $products->the_post();
                    global $product;
                    $product = wc_get_product( get_the_ID() );

                    wc_get_template_part( 'content', 'product' );
                endwhile;
                ?>
            </ul>
        </div>
        <?php

        wp_reset_postdata();
    }
}
