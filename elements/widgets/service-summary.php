<?php
/**
 * Service Summary Widget
 * Sidebar card displaying service name, duration, price, workshop info,
 * tax calculation, and total — all driven by URL parameters.
 * Designed for the appointment-details page alongside Contact Form 7.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Service_Summary_Widget extends Widget_Base {

    public function get_name() { return 'service-summary'; }

    public function get_title() { return __( 'Service Summary', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-price-list'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-service-summary-css' ]; }

    public function get_script_depends() { return [ 'rmt-service-summary' ]; }

    public function get_keywords() { return [ 'service', 'summary', 'booking', 'appointment', 'price', 'tax', 'workshop' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- General ----
        $this->start_controls_section( 'section_general', [
            'label' => __( 'General', 'rakmyat-core' ),
        ] );

        $this->add_control( 'card_title', [
            'label'       => __( 'Card Title', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Service Summary',
            'label_block' => true,
        ] );

        $this->add_control( 'title_html_tag', [
            'label'   => __( 'Title HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
                'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4',
                'h5' => 'H5', 'h6' => 'H6', 'p' => 'p', 'div' => 'div',
            ],
        ] );

        $this->add_control( 'currency_symbol', [
            'label'   => __( 'Currency Symbol', 'rakmyat-core' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '$',
        ] );

        $this->add_responsive_control( 'text_alignment', [
            'label'   => __( 'Text Alignment', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => [ 'title' => __( 'Left', 'rakmyat-core' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => __( 'Right', 'rakmyat-core' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'left',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary' => 'text-align: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Default Values (fallbacks when no URL params) ----
        $this->start_controls_section( 'section_defaults', [
            'label' => __( 'Default Values', 'rakmyat-core' ),
        ] );

        $this->add_control( 'default_service_info', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( 'These values display when no URL parameters are present (e.g. in Elementor editor). On the live page, values come from <code>?service=</code>, <code>?price=</code>, <code>?duration=</code> URL parameters.', 'rakmyat-core' ),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ] );

        $this->add_control( 'default_service', [
            'label'       => __( 'Service Name', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => '—',
            'label_block' => true,
        ] );

        $this->add_control( 'default_price', [
            'label'   => __( 'Price', 'rakmyat-core' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '0.00',
        ] );

        $this->add_control( 'default_duration', [
            'label'   => __( 'Duration', 'rakmyat-core' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '—',
        ] );

        $this->end_controls_section();

        // ---- Tax Settings ----
        $this->start_controls_section( 'section_tax', [
            'label' => __( 'Tax Settings', 'rakmyat-core' ),
        ] );

        $this->add_control( 'tax_percentage', [
            'label'   => __( 'Tax Percentage (%)', 'rakmyat-core' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 5,
            'min'     => 0,
            'max'     => 100,
            'step'    => 0.5,
        ] );

        $this->add_control( 'show_tax', [
            'label'   => __( 'Show Tax Row', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'tax_label', [
            'label'     => __( 'Tax Label', 'rakmyat-core' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'Tax',
            'condition' => [ 'show_tax' => 'yes' ],
        ] );

        $this->end_controls_section();

        // ---- Workshop Info ----
        $this->start_controls_section( 'section_workshop', [
            'label' => __( 'Workshop Info', 'rakmyat-core' ),
        ] );

        $this->add_control( 'show_workshop', [
            'label'   => __( 'Show Workshop Section', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'workshop_section_title', [
            'label'     => __( 'Section Title', 'rakmyat-core' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'Workshop',
            'condition' => [ 'show_workshop' => 'yes' ],
        ] );

        $this->add_control( 'default_workshop', [
            'label'       => __( 'Workshop Name', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => '—',
            'label_block' => true,
            'condition'   => [ 'show_workshop' => 'yes' ],
        ] );

        $this->add_control( 'workshop_url', [
            'label'     => __( 'Workshop URL', 'rakmyat-core' ),
            'type'      => Controls_Manager::URL,
            'default'   => [ 'url' => '#' ],
            'condition' => [ 'show_workshop' => 'yes' ],
        ] );

        $this->add_control( 'default_address', [
            'label'       => __( 'Address', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => '—',
            'rows'        => 2,
            'condition'   => [ 'show_workshop' => 'yes' ],
        ] );

        $this->end_controls_section();

        // ---- Visibility Toggles ----
        $this->start_controls_section( 'section_visibility', [
            'label' => __( 'Visibility', 'rakmyat-core' ),
        ] );

        $this->add_control( 'show_duration', [
            'label'   => __( 'Show Duration', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_meta_price', [
            'label'   => __( 'Show Price in Meta Row', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'price_label', [
            'label'   => __( 'Service Price Label', 'rakmyat-core' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Service Price',
        ] );

        $this->add_control( 'total_label', [
            'label'   => __( 'Total Label', 'rakmyat-core' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Total',
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
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .rmt-svc-summary',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width'  => [ 'default' => [
                    'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true,
                ] ],
                'color' => [ 'default' => '#E5E7EB' ],
            ],
        ] );

        $this->add_responsive_control( 'card_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [
                'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12',
                'unit' => 'px', 'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_box_shadow',
            'selector' => '{{WRAPPER}} .rmt-svc-summary',
            'fields_options' => [
                'box_shadow_type' => [ 'default' => 'yes' ],
                'box_shadow' => [
                    'default' => [
                        'horizontal' => 0,
                        'vertical'   => 2,
                        'blur'       => 12,
                        'spread'     => 0,
                        'color'      => 'rgba(0,0,0,0.08)',
                    ],
                ],
            ],
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24',
                'unit' => 'px', 'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Card Title Style ----
        $this->start_controls_section( 'section_title_style', [
            'label' => __( 'Card Title', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'card_title_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'card_title_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__title',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 18, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '700' ],
            ],
        ] );

        $this->add_responsive_control( 'card_title_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '16', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Service Name Style ----
        $this->start_controls_section( 'section_service_name_style', [
            'label' => __( 'Service Name', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'service_name_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__service-name' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'service_name_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__service-name',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 15, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        $this->add_responsive_control( 'service_name_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '12', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__service-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Meta Row Style (Duration + Price icons) ----
        $this->start_controls_section( 'section_meta_style', [
            'label' => __( 'Meta Row (Duration & Price)', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'meta_text_color', [
            'label'     => __( 'Text Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__meta span' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'meta_icon_color', [
            'label'     => __( 'Icon Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#9CA3AF',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__meta svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'meta_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__meta span',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 13, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->add_responsive_control( 'meta_icon_size', [
            'label'      => __( 'Icon Size', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 30 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__meta svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'meta_gap', [
            'label'      => __( 'Gap Between Items', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 4, 'max' => 60 ] ],
            'default'    => [ 'size' => 20, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__meta' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'meta_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Divider Style ----
        $this->start_controls_section( 'section_divider_style', [
            'label' => __( 'Dividers', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'divider_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#E5E7EB',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__divider' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'divider_style', [
            'label'   => __( 'Style', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'  => __( 'Solid', 'rakmyat-core' ),
                'dashed' => __( 'Dashed', 'rakmyat-core' ),
                'dotted' => __( 'Dotted', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__divider' => 'border-top-style: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'divider_width', [
            'label'      => __( 'Width', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 1, 'max' => 5 ] ],
            'default'    => [ 'size' => 1, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'divider_spacing', [
            'label'      => __( 'Spacing', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 4, 'max' => 40 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__divider' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Workshop Style ----
        $this->start_controls_section( 'section_workshop_style', [
            'label'     => __( 'Workshop', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_workshop' => 'yes' ],
        ] );

        // Section Title
        $this->add_control( 'ws_heading_label', [
            'label' => __( 'Section Title', 'rakmyat-core' ),
            'type'  => Controls_Manager::HEADING,
        ] );

        $this->add_control( 'workshop_heading_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-heading' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'workshop_heading_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__workshop-heading',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 12, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        $this->add_responsive_control( 'workshop_heading_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '8', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        // Workshop Name
        $this->add_control( 'ws_name_label', [
            'label'     => __( 'Workshop Name', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'workshop_name_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563EB',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-name' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'workshop_name_hover_color', [
            'label'     => __( 'Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1D4ED8',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-name:hover' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'workshop_name_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__workshop-name',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        $this->add_responsive_control( 'workshop_name_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '8', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        // Address
        $this->add_control( 'ws_address_label', [
            'label'     => __( 'Address', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'workshop_address_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-address span' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'workshop_address_icon_color', [
            'label'     => __( 'Icon Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#9CA3AF',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-address svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'workshop_address_icon_size', [
            'label'      => __( 'Icon Size', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 30 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__workshop-address svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'workshop_address_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__workshop-address span',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 13, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->end_controls_section();

        // ---- Price Breakdown Style ----
        $this->start_controls_section( 'section_price_style', [
            'label' => __( 'Price Breakdown', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'breakdown_row_gap', [
            'label'      => __( 'Row Spacing', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'default'    => [ 'size' => 10, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-svc-summary__breakdown' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        // Service Price Row
        $this->add_control( 'price_row_heading', [
            'label'     => __( 'Service Price Row', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'price_label_color', [
            'label'     => __( 'Label Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__price-row .rmt-svc-summary__row-label' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'price_label_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__price-row .rmt-svc-summary__row-label',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->add_control( 'price_value_color', [
            'label'     => __( 'Value Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__price-row .rmt-svc-summary__row-value' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'price_value_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__price-row .rmt-svc-summary__row-value',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        // Tax Row
        $this->add_control( 'tax_row_heading', [
            'label'     => __( 'Tax Row', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'show_tax' => 'yes' ],
        ] );

        $this->add_control( 'tax_label_color', [
            'label'     => __( 'Label Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__tax-row .rmt-svc-summary__row-label' => 'color: {{VALUE}};',
            ],
            'condition' => [ 'show_tax' => 'yes' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'      => 'tax_label_typography',
            'selector'  => '{{WRAPPER}} .rmt-svc-summary__tax-row .rmt-svc-summary__row-label',
            'condition' => [ 'show_tax' => 'yes' ],
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 13, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->add_control( 'tax_value_color', [
            'label'     => __( 'Value Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__tax-row .rmt-svc-summary__row-value' => 'color: {{VALUE}};',
            ],
            'condition' => [ 'show_tax' => 'yes' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'      => 'tax_value_typography',
            'selector'  => '{{WRAPPER}} .rmt-svc-summary__tax-row .rmt-svc-summary__row-value',
            'condition' => [ 'show_tax' => 'yes' ],
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 13, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        // Total Row
        $this->add_control( 'total_row_heading', [
            'label'     => __( 'Total Row', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'total_border_color', [
            'label'     => __( 'Top Border Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#E5E7EB',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__total' => 'border-top-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'total_label_color', [
            'label'     => __( 'Label Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__total .rmt-svc-summary__row-label' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'total_label_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__total .rmt-svc-summary__row-label',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 15, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '700' ],
            ],
        ] );

        $this->add_control( 'total_value_color', [
            'label'     => __( 'Value Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#DC2626',
            'selectors' => [
                '{{WRAPPER}} .rmt-svc-summary__total .rmt-svc-summary__row-value' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'total_value_typography',
            'selector' => '{{WRAPPER}} .rmt-svc-summary__total .rmt-svc-summary__row-value',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 18, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '700' ],
            ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $s = $this->get_settings_for_display();

        $title_tag = in_array( $s['title_html_tag'], [ 'h2','h3','h4','h5','h6','p','div' ], true )
                     ? $s['title_html_tag'] : 'h3';
        $currency     = esc_html( $s['currency_symbol'] );
        $tax_pct      = floatval( $s['tax_percentage'] );
        $show_tax     = $s['show_tax'] === 'yes';
        $show_workshop = $s['show_workshop'] === 'yes';
        $show_duration = $s['show_duration'] === 'yes';
        $show_meta_price = $s['show_meta_price'] === 'yes';

        // Default values (used in editor preview & when no URL params)
        $def_service  = esc_attr( $s['default_service'] );
        $def_price    = esc_attr( $s['default_price'] );
        $def_duration = esc_attr( $s['default_duration'] );
        $def_workshop = esc_attr( $s['default_workshop'] );
        $def_address  = esc_attr( $s['default_address'] );

        $workshop_url = ! empty( $s['workshop_url']['url'] ) ? $s['workshop_url']['url'] : '#';
        $ws_target    = ! empty( $s['workshop_url']['is_external'] ) ? ' target="_blank"' : '';
        $ws_nofollow  = ! empty( $s['workshop_url']['nofollow'] ) ? ' rel="nofollow"' : '';

        $widget_id = $this->get_id();
        ?>

        <div class="rmt-svc-summary"
             id="rmt-svc-summary-<?php echo esc_attr( $widget_id ); ?>"
             data-tax-pct="<?php echo esc_attr( $tax_pct ); ?>"
             data-currency="<?php echo esc_attr( $s['currency_symbol'] ); ?>"
             data-default-service="<?php echo $def_service; ?>"
             data-default-price="<?php echo $def_price; ?>"
             data-default-duration="<?php echo $def_duration; ?>"
             data-default-workshop="<?php echo $def_workshop; ?>"
             data-default-address="<?php echo $def_address; ?>">

            <!-- Card Title -->
            <<?php echo esc_html( $title_tag ); ?> class="rmt-svc-summary__title">
                <?php echo esc_html( $s['card_title'] ); ?>
            </<?php echo esc_html( $title_tag ); ?>>

            <!-- Service Name (dynamic) -->
            <div class="rmt-svc-summary__service-name" data-field="service">
                <?php echo esc_html( $s['default_service'] ); ?>
            </div>

            <!-- Meta Row: Duration + Price -->
            <div class="rmt-svc-summary__meta">
                <?php if ( $show_duration ) : ?>
                <div class="rmt-svc-summary__meta-item">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
                    <span data-field="duration"><?php echo esc_html( $s['default_duration'] ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $show_meta_price ) : ?>
                <div class="rmt-svc-summary__meta-item">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    <span data-field="meta-price"><?php echo $currency . esc_html( $s['default_price'] ); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <?php if ( $show_workshop ) : ?>
            <!-- Divider -->
            <hr class="rmt-svc-summary__divider">

            <!-- Workshop Section -->
            <div class="rmt-svc-summary__workshop">
                <div class="rmt-svc-summary__workshop-heading">
                    <?php echo esc_html( $s['workshop_section_title'] ); ?>
                </div>
                <a href="<?php echo esc_url( $workshop_url ); ?>"
                   class="rmt-svc-summary__workshop-name"
                   data-field="workshop"
                   <?php echo $ws_target . $ws_nofollow; ?>>
                    <?php echo esc_html( $s['default_workshop'] ); ?>
                </a>
                <div class="rmt-svc-summary__workshop-address">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span data-field="address"><?php echo esc_html( $s['default_address'] ); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="rmt-svc-summary__divider">

            <!-- Price Breakdown -->
            <div class="rmt-svc-summary__breakdown">
                <!-- Service Price -->
                <div class="rmt-svc-summary__row rmt-svc-summary__price-row">
                    <span class="rmt-svc-summary__row-label"><?php echo esc_html( $s['price_label'] ); ?></span>
                    <span class="rmt-svc-summary__row-value" data-field="price-value"><?php echo $currency . esc_html( $s['default_price'] ); ?></span>
                </div>

                <?php if ( $show_tax ) : ?>
                <!-- Tax -->
                <div class="rmt-svc-summary__row rmt-svc-summary__tax-row">
                    <span class="rmt-svc-summary__row-label"><?php echo esc_html( $s['tax_label'] ); ?> (<?php echo esc_html( $tax_pct ); ?>%)</span>
                    <span class="rmt-svc-summary__row-value" data-field="tax-value"><?php
                        $def_p = floatval( $s['default_price'] );
                        $def_tax = $def_p * ( $tax_pct / 100 );
                        echo $currency . number_format( $def_tax, 2 );
                    ?></span>
                </div>
                <?php endif; ?>

                <!-- Total -->
                <div class="rmt-svc-summary__row rmt-svc-summary__total">
                    <span class="rmt-svc-summary__row-label"><?php echo esc_html( $s['total_label'] ); ?></span>
                    <span class="rmt-svc-summary__row-value" data-field="total-value"><?php
                        $def_total = $def_p + $def_tax;
                        echo $currency . number_format( $def_total, 2 );
                    ?></span>
                </div>
            </div>

        </div>
        <?php
    }
}
