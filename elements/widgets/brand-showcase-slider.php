<?php
/**
 * Brand Showcase Slider Widget
 * Full-width slider with repeater slides, centered title, overlay,
 * bottom-center arrow navigation, and bottom-right subtitle text.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Brand_Showcase_Slider_Widget extends Widget_Base {

    public function get_name() { return 'brand-showcase-slider'; }

    public function get_title() { return __( 'Brand Showcase Slider', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-slider-push'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-brand-showcase-slider-css' ]; }

    public function get_script_depends() { return [ 'rmt-brand-showcase-slider' ]; }

    public function get_keywords() { return [ 'slider', 'brand', 'showcase', 'carousel', 'hero', 'banner' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- Slides (Repeater) ----
        $this->start_controls_section( 'section_slides', [
            'label' => __( 'Slides', 'rakmyat-core' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'slide_image', [
            'label'   => __( 'Background Image', 'rakmyat-core' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'slide_title', [
            'label'       => __( 'Title', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'KIA',
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $repeater->add_control( 'slide_subtitle', [
            'label'       => __( 'Subtitle (Bottom Right)', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Sportage  |  Seltos  |  Sorento  |  K4  |  K3  |  K8',
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $repeater->add_control( 'slide_link', [
            'label' => __( 'Link', 'rakmyat-core' ),
            'type'  => Controls_Manager::URL,
        ] );

        $repeater->add_control( 'slide_overlay_color', [
            'label'       => __( 'Overlay Color (Override)', 'rakmyat-core' ),
            'type'        => Controls_Manager::COLOR,
            'description' => __( 'Leave empty to use the global overlay color.', 'rakmyat-core' ),
        ] );

        $this->add_control( 'slides', [
            'label'       => __( 'Slides', 'rakmyat-core' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'slide_title'    => 'KIA',
                    'slide_subtitle' => 'Sportage  |  Seltos  |  Sorento  |  K4  |  K3  |  K8',
                ],
                [
                    'slide_title'    => 'BMW',
                    'slide_subtitle' => 'X5  |  X3  |  320i  |  M4  |  i7',
                ],
                [
                    'slide_title'    => 'Toyota',
                    'slide_subtitle' => 'Camry  |  Corolla  |  RAV4  |  Supra',
                ],
            ],
            'title_field' => '{{{ slide_title }}}',
        ] );

        $this->end_controls_section();

        // ---- General Settings ----
        $this->start_controls_section( 'section_settings', [
            'label' => __( 'Slider Settings', 'rakmyat-core' ),
        ] );

        $this->add_control( 'title_html_tag', [
            'label'   => __( 'Title HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1'   => 'H1',
                'h2'   => 'H2',
                'h3'   => 'H3',
                'h4'   => 'H4',
                'h5'   => 'H5',
                'h6'   => 'H6',
                'p'    => 'p',
                'span' => 'span',
                'div'  => 'div',
            ],
        ] );

        $this->add_responsive_control( 'slider_height', [
            'label'      => __( 'Slider Height', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [
                'px' => [ 'min' => 150, 'max' => 900, 'step' => 1 ],
                'vh' => [ 'min' => 10, 'max' => 100 ],
            ],
            'default'   => [ 'size' => 420, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'autoplay', [
            'label'     => __( 'Autoplay', 'rakmyat-core' ),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'yes',
            'separator' => 'before',
        ] );

        $this->add_control( 'autoplay_speed', [
            'label'     => __( 'Autoplay Speed (ms)', 'rakmyat-core' ),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 5000,
            'min'       => 1000,
            'max'       => 20000,
            'step'      => 500,
            'condition' => [ 'autoplay' => 'yes' ],
        ] );

        $this->add_control( 'pause_on_hover', [
            'label'     => __( 'Pause on Hover', 'rakmyat-core' ),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'yes',
            'condition' => [ 'autoplay' => 'yes' ],
        ] );

        $this->add_control( 'loop', [
            'label'   => __( 'Infinite Loop', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'transition_speed', [
            'label'   => __( 'Transition Speed (ms)', 'rakmyat-core' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 600,
            'min'     => 100,
            'max'     => 3000,
            'step'    => 100,
        ] );

        $this->add_control( 'transition_effect', [
            'label'   => __( 'Transition Effect', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'fade',
            'options' => [
                'fade'  => __( 'Fade', 'rakmyat-core' ),
                'slide' => __( 'Slide', 'rakmyat-core' ),
            ],
        ] );

        $this->end_controls_section();

        // ---- Navigation Settings ----
        $this->start_controls_section( 'section_navigation', [
            'label' => __( 'Navigation', 'rakmyat-core' ),
        ] );

        $this->add_control( 'show_arrows', [
            'label'   => __( 'Show Arrows', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_dots', [
            'label'   => __( 'Show Dots', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB
         * ============================================================ */

        // ---- Overlay Style ----
        $this->start_controls_section( 'section_overlay_style', [
            'label' => __( 'Overlay', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'overlay_color', [
            'label'   => __( 'Overlay Color', 'rakmyat-core' ),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(55, 65, 81, 0.7)',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__overlay' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Slider Container Style ----
        $this->start_controls_section( 'section_container_style', [
            'label' => __( 'Slider Container', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'slider_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-showcase-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'slider_box_shadow',
            'selector' => '{{WRAPPER}} .rmt-showcase-slider',
        ] );

        $this->end_controls_section();

        // ---- Image Style ----
        $this->start_controls_section( 'section_image_style', [
            'label' => __( 'Image', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'image_object_fit', [
            'label'     => __( 'Object Fit', 'rakmyat-core' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'cover',
            'options'   => [
                'cover'   => __( 'Cover', 'rakmyat-core' ),
                'contain' => __( 'Contain', 'rakmyat-core' ),
                'fill'    => __( 'Fill', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__image' => 'object-fit: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'image_object_position', [
            'label'     => __( 'Object Position', 'rakmyat-core' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'center center',
            'options'   => [
                'center center' => __( 'Center', 'rakmyat-core' ),
                'center top'    => __( 'Top', 'rakmyat-core' ),
                'center bottom' => __( 'Bottom', 'rakmyat-core' ),
                'left center'   => __( 'Left', 'rakmyat-core' ),
                'right center'  => __( 'Right', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__image' => 'object-position: {{VALUE}};',
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
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .rmt-showcase-slider__title',
            'fields_options' => [
                'font_size' => [ 'default' => [ 'size' => 64, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '700' ],
                'letter_spacing' => [ 'default' => [ 'size' => 12, 'unit' => 'px' ] ],
                'text_transform' => [ 'default' => 'uppercase' ],
            ],
        ] );

        $this->add_group_control( Group_Control_Text_Shadow::get_type(), [
            'name'     => 'title_text_shadow',
            'selector' => '{{WRAPPER}} .rmt-showcase-slider__title',
        ] );

        $this->add_responsive_control( 'title_offset_y', [
            'label'      => __( 'Vertical Offset', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [
                'px' => [ 'min' => -200, 'max' => 200 ],
                '%'  => [ 'min' => -50, 'max' => 50 ],
            ],
            'default'   => [ 'size' => 0, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__content' => 'transform: translateY({{SIZE}}{{UNIT}});',
            ],
        ] );

        $this->end_controls_section();

        // ---- Subtitle Style ----
        $this->start_controls_section( 'section_subtitle_style', [
            'label' => __( 'Subtitle (Bottom Right)', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'subtitle_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__subtitle' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'subtitle_typography',
            'selector' => '{{WRAPPER}} .rmt-showcase-slider__subtitle',
            'fields_options' => [
                'font_size' => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->add_responsive_control( 'subtitle_bottom', [
            'label'      => __( 'Bottom Offset', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 24, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-showcase-slider__subtitle' => 'bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'subtitle_right', [
            'label'      => __( 'Right Offset', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 32, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-showcase-slider__subtitle' => 'right: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Arrow Style ----
        $this->start_controls_section( 'section_arrow_style', [
            'label'     => __( 'Arrows', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_arrows' => 'yes' ],
        ] );

        $this->add_responsive_control( 'arrow_size', [
            'label'     => __( 'Icon Size', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default'   => [ 'size' => 18, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'arrow_color', [
            'label'     => __( 'Icon Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__arrow svg' => 'stroke: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'arrow_hover_color', [
            'label'     => __( 'Icon Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__arrow:hover svg' => 'stroke: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'nav_bg_color', [
            'label'     => __( 'Container Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.1)',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav' => 'background-color: {{VALUE}};',
            ],
            'separator' => 'before',
        ] );

        $this->add_control( 'nav_hover_bg_color', [
            'label'     => __( 'Container Hover Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'nav_border',
            'selector' => '{{WRAPPER}} .rmt-showcase-slider__nav',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width'  => [ 'default' => [
                    'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true,
                ] ],
                'color' => [ 'default' => 'rgba(255, 255, 255, 0.4)' ],
            ],
        ] );

        $this->add_responsive_control( 'nav_border_radius', [
            'label'      => __( 'Container Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 50, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-showcase-slider__nav' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'nav_padding', [
            'label'      => __( 'Container Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default'    => [
                'top' => '6', 'right' => '10', 'bottom' => '6', 'left' => '10',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'nav_divider_heading', [
            'label'     => __( 'Divider', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'show_nav_divider', [
            'label'   => __( 'Show Divider', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'nav_divider_color', [
            'label'     => __( 'Divider Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.3)',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav-divider' => 'background-color: {{VALUE}};',
            ],
            'condition' => [ 'show_nav_divider' => 'yes' ],
        ] );

        $this->add_responsive_control( 'arrow_gap', [
            'label'     => __( 'Gap Between Arrows', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'   => [ 'size' => 8, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav' => 'gap: {{SIZE}}{{UNIT}};',
            ],
            'separator' => 'before',
        ] );

        $this->add_responsive_control( 'nav_bottom_offset', [
            'label'     => __( 'Bottom Offset', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'   => [ 'size' => 24, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__nav' => 'bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Dots Style ----
        $this->start_controls_section( 'section_dots_style', [
            'label'     => __( 'Dots', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_dots' => 'yes' ],
        ] );

        $this->add_responsive_control( 'dot_size', [
            'label'     => __( 'Size', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 4, 'max' => 20 ] ],
            'default'   => [ 'size' => 8, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'dot_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.4)',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__dot' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'dot_active_color', [
            'label'     => __( 'Active Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__dot--active' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'dot_gap', [
            'label'     => __( 'Gap', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 2, 'max' => 20 ] ],
            'default'   => [ 'size' => 6, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__dots' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'dots_bottom_offset', [
            'label'     => __( 'Bottom Offset', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'   => [ 'size' => 60, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-showcase-slider__dots' => 'bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides   = $settings['slides'];

        if ( empty( $slides ) ) return;

        $title_tag    = in_array( $settings['title_html_tag'], [ 'h1','h2','h3','h4','h5','h6','p','span','div' ], true )
                        ? $settings['title_html_tag'] : 'h2';
        $show_arrows  = $settings['show_arrows'] === 'yes';
        $show_dots    = $settings['show_dots'] === 'yes';
        $show_divider = $settings['show_nav_divider'] === 'yes';
        $effect       = $settings['transition_effect'];

        $slider_settings = wp_json_encode( [
            'autoplay'       => $settings['autoplay'] === 'yes',
            'autoplay_speed' => intval( $settings['autoplay_speed'] ),
            'pause_on_hover' => $settings['pause_on_hover'] === 'yes',
            'loop'           => $settings['loop'] === 'yes',
            'speed'          => intval( $settings['transition_speed'] ),
            'effect'         => $effect,
        ] );

        $slider_class = 'rmt-showcase-slider';
        $slider_class .= ' rmt-showcase-slider--' . esc_attr( $effect );
        ?>

        <div class="<?php echo esc_attr( $slider_class ); ?>" data-settings='<?php echo esc_attr( $slider_settings ); ?>'>

            <div class="rmt-showcase-slider__wrapper">
                <?php foreach ( $slides as $index => $slide ) :
                    $active_class = $index === 0 ? ' rmt-showcase-slider__slide--active' : '';
                    $image_url    = ! empty( $slide['slide_image']['url'] ) ? $slide['slide_image']['url'] : '';
                    $has_link     = ! empty( $slide['slide_link']['url'] );
                    $per_overlay  = ! empty( $slide['slide_overlay_color'] ) ? $slide['slide_overlay_color'] : '';
                ?>
                <div class="rmt-showcase-slider__slide<?php echo esc_attr( $active_class ); ?>" data-index="<?php echo esc_attr( $index ); ?>">

                    <?php if ( $image_url ) : ?>
                        <img src="<?php echo esc_url( $image_url ); ?>"
                             alt="<?php echo esc_attr( $slide['slide_title'] ); ?>"
                             class="rmt-showcase-slider__image"
                             loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                    <?php endif; ?>

                    <div class="rmt-showcase-slider__overlay"
                         <?php if ( $per_overlay ) : ?>
                         style="background-color: <?php echo esc_attr( $per_overlay ); ?>;"
                         <?php endif; ?>></div>

                    <div class="rmt-showcase-slider__content">
                        <?php if ( $has_link ) : ?>
                            <a href="<?php echo esc_url( $slide['slide_link']['url'] ); ?>"
                               <?php echo ! empty( $slide['slide_link']['is_external'] ) ? 'target="_blank"' : ''; ?>
                               <?php echo ! empty( $slide['slide_link']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                               class="rmt-showcase-slider__link">
                        <?php endif; ?>

                        <<?php echo esc_html( $title_tag ); ?> class="rmt-showcase-slider__title">
                            <?php echo esc_html( $slide['slide_title'] ); ?>
                        </<?php echo esc_html( $title_tag ); ?>>

                        <?php if ( $has_link ) : ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
                        <div class="rmt-showcase-slider__subtitle">
                            <?php echo esc_html( $slide['slide_subtitle'] ); ?>
                        </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </div>

            <?php if ( $show_arrows && count( $slides ) > 1 ) : ?>
            <div class="rmt-showcase-slider__nav">
                <button class="rmt-showcase-slider__arrow rmt-showcase-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'rakmyat-core' ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <?php if ( $show_divider ) : ?>
                    <span class="rmt-showcase-slider__nav-divider"></span>
                <?php endif; ?>
                <button class="rmt-showcase-slider__arrow rmt-showcase-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'rakmyat-core' ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if ( $show_dots && count( $slides ) > 1 ) : ?>
            <div class="rmt-showcase-slider__dots">
                <?php foreach ( $slides as $i => $s ) : ?>
                    <button class="rmt-showcase-slider__dot<?php echo $i === 0 ? ' rmt-showcase-slider__dot--active' : ''; ?>"
                            data-index="<?php echo esc_attr( $i ); ?>"
                            aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'rakmyat-core' ), $i + 1 ) ); ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
        <?php
    }
}
