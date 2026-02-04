<?php
/**
 * Product Grid Widget
 *
 * Display WooCommerce products with category/tag filtering and column control
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

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
                    '{{WRAPPER}} .rmt-product-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
        // CONTENT - DISPLAY
        // =====================
        $this->start_controls_section(
            'section_display',
            [
                'label' => __( 'Display', 'rakmyat-core' ),
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __( 'Show Image', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __( 'Show Title', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => __( 'Show Price', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label' => __( 'Show Rating', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => __( 'Show Sale Badge', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_add_to_cart',
            [
                'label' => __( 'Show Add to Cart', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label' => __( 'Show Category', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => __( 'Content Alignment', 'rakmyat-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'rakmyat-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'rakmyat-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'rakmyat-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-info' => 'text-align: {{VALUE}};',
                ],
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
                'default' => [ 'size' => 20 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
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
                'default' => [ 'size' => 20 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - CARD
        // =====================
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => __( 'Card', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'card_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'card_tab_normal',
            [ 'label' => __( 'Normal', 'rakmyat-core' ) ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .rmt-product-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .rmt-product-card',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'card_tab_hover',
            [ 'label' => __( 'Hover', 'rakmyat-core' ) ]
        );

        $this->add_control(
            'card_hover_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_hover_border_color',
            [
                'label' => __( 'Border Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_hover_box_shadow',
                'selector' => '{{WRAPPER}} .rmt-product-card:hover',
            ]
        );

        $this->add_control(
            'card_hover_translate',
            [
                'label' => __( 'Hover Lift (px)', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -20, 'max' => 20 ],
                ],
                'default' => [ 'size' => -3 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'card_style_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_overflow',
            [
                'label' => __( 'Overflow', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'hidden' => __( 'Hidden', 'rakmyat-core' ),
                    'visible' => __( 'Visible', 'rakmyat-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_transition',
            [
                'label' => __( 'Transition Duration (ms)', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ],
                ],
                'default' => [ 'size' => 300 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - IMAGE
        // =====================
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __( 'Image', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 600 ],
                    'vh' => [ 'min' => 5, 'max' => 50 ],
                ],
                'default' => [ 'size' => 250, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => __( 'Object Fit', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __( 'Cover', 'rakmyat-core' ),
                    'contain' => __( 'Contain', 'rakmyat-core' ),
                    'fill' => __( 'Fill', 'rakmyat-core' ),
                    'none' => __( 'None', 'rakmyat-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => __( 'Object Position', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'top center' => __( 'Top', 'rakmyat-core' ),
                    'center center' => __( 'Center', 'rakmyat-core' ),
                    'bottom center' => __( 'Bottom', 'rakmyat-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'image_hover_heading',
            [
                'label' => __( 'Hover', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_hover_zoom',
            [
                'label' => __( 'Zoom on Hover', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'image_zoom_scale',
            [
                'label' => __( 'Zoom Scale', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 1.5, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1.05 ],
                'condition' => [ 'image_hover_zoom' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card:hover .rmt-product-image' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'image_hover_opacity',
            [
                'label' => __( 'Hover Opacity', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ],
                ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card:hover .rmt-product-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_overlay_color',
            [
                'label' => __( 'Overlay Color on Hover', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image-wrap::after' => 'content: ""; position: absolute; inset: 0; background-color: {{VALUE}}; opacity: 0; transition: opacity 0.3s ease; pointer-events: none;',
                    '{{WRAPPER}} .rmt-product-card:hover .rmt-product-image-wrap::after' => 'opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'image_transition',
            [
                'label' => __( 'Transition Duration (ms)', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ],
                ],
                'default' => [ 'size' => 300 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-image' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - CONTENT AREA
        // =====================
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content Area', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-info' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'top' => '12',
                    'right' => '14',
                    'bottom' => '14',
                    'left' => '14',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - CATEGORY
        // =====================
        $this->start_controls_section(
            'section_style_category',
            [
                'label' => __( 'Category', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_category' => 'yes' ],
            ]
        );

        $this->add_control(
            'cat_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-cat' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .rmt-product-cat',
            ]
        );

        $this->add_responsive_control(
            'cat_spacing',
            [
                'label' => __( 'Bottom Spacing', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default' => [ 'size' => 4 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-cat' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - TITLE
        // =====================
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .rmt-product-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 6 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_line_clamp',
            [
                'label' => __( 'Max Lines', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '0' => __( 'No Limit', 'rakmyat-core' ),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - PRICE
        // =====================
        $this->start_controls_section(
            'section_style_price',
            [
                'label' => __( 'Price', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Price Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rmt-product-price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .rmt-product-price',
            ]
        );

        $this->add_control(
            'old_price_heading',
            [
                'label' => __( 'Old Price (Strikethrough)', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-price del' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rmt-product-price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'old_price_size',
            [
                'label' => __( 'Font Size', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 8, 'max' => 30 ] ],
                'default' => [ 'size' => 13 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-price del' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_spacing',
            [
                'label' => __( 'Bottom Spacing', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 10 ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - RATING
        // =====================
        $this->start_controls_section(
            'section_style_rating',
            [
                'label' => __( 'Rating', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_rating' => 'yes' ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __( 'Star Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFC107',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-rating .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => __( 'Empty Star Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D4D4D4',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-rating .star-rating::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => __( 'Star Size', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 8, 'max' => 30 ] ],
                'default' => [ 'size' => 14 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_spacing',
            [
                'label' => __( 'Bottom Spacing', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default' => [ 'size' => 6 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - BADGE
        // =====================
        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => __( 'Sale Badge', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => __( 'Text Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .rmt-product-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'top' => '4',
                    'right' => '10',
                    'bottom' => '4',
                    'left' => '10',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => '4',
                    'right' => '4',
                    'bottom' => '4',
                    'left' => '4',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label' => __( 'Position', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => [
                    'top-left' => __( 'Top Left', 'rakmyat-core' ),
                    'top-right' => __( 'Top Right', 'rakmyat-core' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_offset_x',
            [
                'label' => __( 'Horizontal Offset', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 10 ],
            ]
        );

        $this->add_responsive_control(
            'badge_offset_y',
            [
                'label' => __( 'Vertical Offset', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 10 ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - BUTTON
        // =====================
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __( 'Add to Cart Button', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'btn_tabs' );

        $this->start_controls_tab(
            'btn_tab_normal',
            [ 'label' => __( 'Normal', 'rakmyat-core' ) ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Text Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .rmt-product-card .button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btn_tab_hover',
            [ 'label' => __( 'Hover', 'rakmyat-core' ) ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#2D2D3A',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => __( 'Text Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => __( 'Border Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_style_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .rmt-product-card .button',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'top' => '10',
                    'right' => '16',
                    'bottom' => '10',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_width',
            [
                'label' => __( 'Full Width', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-card .button' => '{{VALUE}}',
                ],
                'return_value' => 'display: block; width: 100%;',
            ]
        );

        $this->add_responsive_control(
            'btn_margin_top',
            [
                'label' => __( 'Top Spacing', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-product-action' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $args = [
            'status' => 'publish',
            'limit' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'return' => 'ids',
        ];

        if ( ! empty( $settings['category'] ) ) {
            $args['category'] = [ $settings['category'] ];
        }

        if ( ! empty( $settings['tag'] ) ) {
            $args['tag'] = [ $settings['tag'] ];
        }

        if ( $settings['show_only'] === 'featured' ) {
            $args['featured'] = true;
        }

        if ( $settings['show_only'] === 'on_sale' ) {
            $args['include'] = wc_get_product_ids_on_sale();
            if ( empty( $args['include'] ) ) {
                $args['include'] = [ 0 ];
            }
        }

        if ( $settings['hide_out_of_stock'] === 'yes' ) {
            $args['stock_status'] = 'instock';
        }

        $product_ids = wc_get_products( $args );

        if ( empty( $product_ids ) ) {
            echo '<p>' . esc_html__( 'No products found.', 'rakmyat-core' ) . '</p>';
            return;
        }

        $show_image = $settings['show_image'] === 'yes';
        $show_title = $settings['show_title'] === 'yes';
        $show_price = $settings['show_price'] === 'yes';
        $show_rating = $settings['show_rating'] === 'yes';
        $show_badge = $settings['show_badge'] === 'yes';
        $show_cart = $settings['show_add_to_cart'] === 'yes';
        $show_cat = $settings['show_category'] === 'yes';
        $badge_pos = $settings['badge_position'];
        $title_tag = $settings['title_tag'];
        $title_clamp = $settings['title_line_clamp'];

        $allowed_tags = [ 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ];
        if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
            $title_tag = 'h3';
        }

        // Badge position styles
        $badge_style = 'position:absolute;z-index:1;';
        $offset_y = isset( $settings['badge_offset_y']['size'] ) ? $settings['badge_offset_y']['size'] : 10;
        $offset_x = isset( $settings['badge_offset_x']['size'] ) ? $settings['badge_offset_x']['size'] : 10;
        $badge_style .= 'top:' . intval( $offset_y ) . 'px;';
        if ( $badge_pos === 'top-right' ) {
            $badge_style .= 'right:' . intval( $offset_x ) . 'px;';
        } else {
            $badge_style .= 'left:' . intval( $offset_x ) . 'px;';
        }

        // Title clamp
        $title_style = '';
        if ( $title_clamp !== '0' ) {
            $title_style = 'display:-webkit-box;-webkit-line-clamp:' . intval( $title_clamp ) . ';-webkit-box-orient:vertical;overflow:hidden;';
        }

        ?>
        <div class="rmt-product-grid">
            <?php foreach ( $product_ids as $product_id ) :
                $product = wc_get_product( $product_id );
                if ( ! $product ) continue;
            ?>
                <div class="rmt-product-card">
                    <?php if ( $show_image ) : ?>
                        <div class="rmt-product-image-wrap">
                            <?php if ( $show_badge && $product->is_on_sale() ) : ?>
                                <span class="rmt-product-badge" style="<?php echo esc_attr( $badge_style ); ?>"><?php esc_html_e( 'Sale', 'rakmyat-core' ); ?></span>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                <?php if ( $product->get_image_id() ) : ?>
                                    <img class="rmt-product-image"
                                         src="<?php echo esc_url( wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ) ); ?>"
                                         alt="<?php echo esc_attr( $product->get_name() ); ?>"
                                         loading="lazy">
                                <?php else : ?>
                                    <img class="rmt-product-image"
                                         src="<?php echo esc_url( wc_placeholder_img_src( 'woocommerce_thumbnail' ) ); ?>"
                                         alt="<?php echo esc_attr( $product->get_name() ); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="rmt-product-info">
                        <?php if ( $show_cat ) :
                            $cats = wc_get_product_category_list( $product->get_id() );
                            if ( $cats ) : ?>
                                <div class="rmt-product-cat"><?php echo wp_kses_post( $cats ); ?></div>
                            <?php endif;
                        endif; ?>

                        <?php if ( $show_title ) : ?>
                            <<?php echo esc_attr( $title_tag ); ?> class="rmt-product-title" <?php echo $title_style ? 'style="' . esc_attr( $title_style ) . '"' : ''; ?>>
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>
                            </<?php echo esc_attr( $title_tag ); ?>>
                        <?php endif; ?>

                        <?php if ( $show_rating && $product->get_average_rating() > 0 ) : ?>
                            <div class="rmt-product-rating">
                                <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $show_price ) : ?>
                            <div class="rmt-product-price">
                                <?php echo wp_kses_post( $product->get_price_html() ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $show_cart ) : ?>
                            <div class="rmt-product-action">
                                <?php
                                echo apply_filters(
                                    'woocommerce_loop_add_to_cart_link',
                                    sprintf(
                                        '<a href="%s" data-quantity="1" class="button %s" %s>%s</a>',
                                        esc_url( $product->add_to_cart_url() ),
                                        esc_attr( $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ajax_add_to_cart' : '' ),
                                        $product->is_purchasable() && $product->is_in_stock() ? 'data-product_id="' . esc_attr( $product->get_id() ) . '"' : '',
                                        esc_html( $product->add_to_cart_text() )
                                    ),
                                    $product
                                );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
