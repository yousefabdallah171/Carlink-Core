<?php
/**
 * Icon Category Grid Widget
 * Neumorphic grid of icon + title cards with repeater.
 * Uses Figma neumorphic box-shadow with backdrop-filter blur.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Icon_Category_Grid_Widget extends Widget_Base {

    public function get_name() { return 'icon-category-grid'; }

    public function get_title() { return __( 'Icon Category Grid', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-apps'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-icon-category-grid-css' ]; }

    public function get_keywords() { return [ 'icon', 'category', 'grid', 'neumorphic', 'card', 'service' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- Items Repeater ----
        $this->start_controls_section( 'section_items', [
            'label' => __( 'Items', 'rakmyat-core' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'item_icon', [
            'label'   => __( 'Icon', 'rakmyat-core' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-cog',
                'library' => 'fa-solid',
            ],
        ] );

        $repeater->add_control( 'item_title', [
            'label'       => __( 'Title', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Category',
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $repeater->add_control( 'item_link', [
            'label'   => __( 'Link', 'rakmyat-core' ),
            'type'    => Controls_Manager::URL,
            'dynamic' => [ 'active' => true ],
        ] );

        $this->add_control( 'items', [
            'label'   => __( 'Items', 'rakmyat-core' ),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [
                    'item_title' => 'Transmission & Drivetrain',
                    'item_icon'  => [ 'value' => 'fas fa-cogs', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Engine',
                    'item_icon'  => [ 'value' => 'fas fa-cog', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Suspension & Steering',
                    'item_icon'  => [ 'value' => 'fas fa-car', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Braking System',
                    'item_icon'  => [ 'value' => 'fas fa-compact-disc', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Electrical & Electronics',
                    'item_icon'  => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'HVAC',
                    'item_icon'  => [ 'value' => 'fas fa-snowflake', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Intake & Exhaust',
                    'item_icon'  => [ 'value' => 'fas fa-wind', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Body & Exterior',
                    'item_icon'  => [ 'value' => 'fas fa-car-side', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Interior Parts',
                    'item_icon'  => [ 'value' => 'fas fa-couch', 'library' => 'fa-solid' ],
                ],
                [
                    'item_title' => 'Fluids & Lubricants',
                    'item_icon'  => [ 'value' => 'fas fa-oil-can', 'library' => 'fa-solid' ],
                ],
            ],
            'title_field' => '<i class="{{ item_icon.value }}"></i> {{{ item_title }}}',
        ] );

        $this->add_control( 'title_html_tag', [
            'label'   => __( 'Title HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
                'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4',
                'h5' => 'H5', 'h6' => 'H6', 'p' => 'p',
                'span' => 'span', 'div' => 'div',
            ],
        ] );

        $this->end_controls_section();

        // ---- Layout ----
        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
        ] );

        $this->add_responsive_control( 'columns', [
            'label'          => __( 'Columns', 'rakmyat-core' ),
            'type'           => Controls_Manager::SELECT,
            'default'        => '5',
            'tablet_default' => '3',
            'mobile_default' => '2',
            'options'        => [
                '1' => '1', '2' => '2', '3' => '3',
                '4' => '4', '5' => '5', '6' => '6',
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ] );

        $this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Grid Gap', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 60 ],
            ],
            'default'   => [ 'size' => 20, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'content_alignment', [
            'label'   => __( 'Alignment', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'rakmyat-core' ), 'icon' => 'eicon-text-align-left' ],
                'center'     => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => __( 'Right', 'rakmyat-core' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'align-items: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB
         * ============================================================ */

        // ---- Card Style ----
        $this->start_controls_section( 'section_card_style', [
            'label' => __( 'Card', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'card_bg_color', [
            'label'     => __( 'Background Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F2F2F2',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '24', 'right' => '16', 'bottom' => '20', 'left' => '16',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'card_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [
                'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16',
                'unit' => 'px', 'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'card_min_height', [
            'label'      => __( 'Min Height', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        // Neumorphic shadow toggle
        $this->add_control( 'neumorphic_heading', [
            'label'     => __( 'Neumorphic Shadow', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'neumorphic_style', [
            'label'        => __( 'Enable Neumorphic Shadow', 'rakmyat-core' ),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'description'  => __( 'Applies the Figma neumorphic multi-shadow effect. Turn off to use a custom box-shadow.', 'rakmyat-core' ),
            'prefix_class' => 'rmt-neumorphic-',
            'return_value' => 'yes',
        ] );

        // -- Neumorphic: Outer Drop Shadow --
        $this->add_control( 'neu_outer_heading', [
            'label'     => __( 'Outer Drop Shadow', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'neumorphic_style' => 'yes' ],
        ] );

        $this->add_control( 'neu_outer_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.12)',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-outer-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'neu_outer_blur', [
            'label'     => __( 'Blur', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default'   => [ 'size' => 30, 'unit' => 'px' ],
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-outer-blur: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'neu_outer_spread_color', [
            'label'     => __( 'Spread Shadow Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.1)',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-spread-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'neu_outer_spread_blur', [
            'label'     => __( 'Spread Shadow Blur', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'default'   => [ 'size' => 7.5, 'unit' => 'px' ],
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-spread-blur: {{SIZE}}{{UNIT}};',
            ],
        ] );

        // -- Neumorphic: Inner Glow --
        $this->add_control( 'neu_inner_heading', [
            'label'     => __( 'Inner Glow', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'neumorphic_style' => 'yes' ],
        ] );

        $this->add_control( 'neu_glow_color', [
            'label'     => __( 'Glow Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F2F2F2',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-glow-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'neu_glow_blur', [
            'label'     => __( 'Glow Blur', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
            'default'   => [ 'size' => 60, 'unit' => 'px' ],
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-glow-blur: {{SIZE}}{{UNIT}};',
            ],
        ] );

        // -- Neumorphic: Inner Highlight --
        $this->add_control( 'neu_highlight_heading', [
            'label'     => __( 'Inner Light Highlight', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'neumorphic_style' => 'yes' ],
        ] );

        $this->add_control( 'neu_highlight_color', [
            'label'     => __( 'Highlight Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.5)',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-highlight-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'neu_edge_light_color', [
            'label'     => __( 'Edge Light Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-edge-light: {{VALUE}};',
            ],
        ] );

        // -- Neumorphic: Inner Dark Edge --
        $this->add_control( 'neu_dark_heading', [
            'label'     => __( 'Inner Dark Edge', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'neumorphic_style' => 'yes' ],
        ] );

        $this->add_control( 'neu_edge_dark_tl', [
            'label'     => __( 'Dark Edge (Top-Left)', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#262626',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-edge-dark-tl: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'neu_edge_dark_br', [
            'label'     => __( 'Dark Edge (Bottom-Right)', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#333333',
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => '--rmt-neu-edge-dark-br: {{VALUE}};',
            ],
        ] );

        // -- Backdrop Blur --
        $this->add_control( 'neu_blur_heading', [
            'label'     => __( 'Backdrop Blur', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'neumorphic_style' => 'yes' ],
        ] );

        $this->add_control( 'neumorphic_blur_amount', [
            'label'     => __( 'Blur Amount', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'   => [ 'size' => 45, 'unit' => 'px' ],
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
            ],
        ] );

        // Custom shadow (when neumorphic is off)
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'      => 'card_custom_shadow',
            'selector'  => '{{WRAPPER}} .rmt-icon-cat-card__inner',
            'condition' => [ 'neumorphic_style' => '' ],
        ] );

        // Hover
        $this->add_control( 'card_hover_heading', [
            'label'     => __( 'Hover', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'card_hover_bg_color', [
            'label'     => __( 'Hover Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_hover_transform', [
            'label'     => __( 'Hover Lift (px)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 10, 'step' => 1 ] ],
            'default'   => [ 'size' => 2 ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
            ],
        ] );

        $this->add_control( 'card_transition', [
            'label'     => __( 'Transition Duration (ms)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ] ],
            'default'   => [ 'size' => 300 ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'transition-duration: {{SIZE}}ms;',
            ],
        ] );

        $this->end_controls_section();

        // ---- Icon Circle Style ----
        $this->start_controls_section( 'section_icon_style', [
            'label' => __( 'Icon', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'icon_circle_size', [
            'label'     => __( 'Circle Size', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 30, 'max' => 120 ] ],
            'default'   => [ 'size' => 64, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'icon_circle_bg', [
            'label'     => __( 'Circle Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'icon_circle_border',
            'selector' => '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width'  => [ 'default' => [
                    'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'isLinked' => true,
                ] ],
                'color' => [ 'default' => '#333333' ],
            ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label'     => __( 'Icon Size', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 10, 'max' => 80 ] ],
            'default'   => [ 'size' => 28, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'icon_color', [
            'label'     => __( 'Icon Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_hover_color', [
            'label'     => __( 'Icon Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__icon-wrap i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__icon-wrap svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_hover_circle_bg', [
            'label'     => __( 'Circle Hover Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__icon-wrap' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_hover_circle_border', [
            'label'     => __( 'Circle Hover Border Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__icon-wrap' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_spacing', [
            'label'     => __( 'Spacing Below Icon', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'   => [ 'size' => 14, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Title Style ----
        $this->start_controls_section( 'section_title_style', [
            'label' => __( 'Title', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'title_hover_color', [
            'label'     => __( 'Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .rmt-icon-cat-card__title',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        $this->add_responsive_control( 'title_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-icon-cat-card__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = $settings['items'];

        if ( empty( $items ) ) return;

        $title_tag = in_array( $settings['title_html_tag'], [ 'h2','h3','h4','h5','h6','p','span','div' ], true )
                     ? $settings['title_html_tag'] : 'h3';
        ?>

        <div class="rmt-icon-cat-grid">
            <?php foreach ( $items as $index => $item ) :
                $has_link = ! empty( $item['item_link']['url'] );
                $tag      = $has_link ? 'a' : 'div';
                $link_attr = '';
                if ( $has_link ) {
                    $link_attr .= ' href="' . esc_url( $item['item_link']['url'] ) . '"';
                    if ( ! empty( $item['item_link']['is_external'] ) ) $link_attr .= ' target="_blank"';
                    if ( ! empty( $item['item_link']['nofollow'] ) )    $link_attr .= ' rel="nofollow"';
                }
            ?>
            <div class="rmt-icon-cat-grid__item">
                <<?php echo $tag; ?> class="rmt-icon-cat-card__inner"<?php echo $link_attr; ?>>

                    <div class="rmt-icon-cat-card__icon-wrap">
                        <?php
                        if ( ! empty( $item['item_icon']['value'] ) ) {
                            Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>

                    <?php if ( ! empty( $item['item_title'] ) ) : ?>
                    <<?php echo esc_html( $title_tag ); ?> class="rmt-icon-cat-card__title">
                        <?php echo esc_html( $item['item_title'] ); ?>
                    </<?php echo esc_html( $title_tag ); ?>>
                    <?php endif; ?>

                </<?php echo $tag; ?>>
            </div>
            <?php endforeach; ?>
        </div>

        <?php
    }
}
