<?php
/**
 * CTA Banner Widget
 * Full-width banner with background image, overlay, centered title,
 * description, and glassmorphic CTA button (rmt-glass-btn style).
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Cta_Banner_Widget extends Widget_Base {

    public function get_name() { return 'cta-banner'; }

    public function get_title() { return __( 'CTA Banner', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-call-to-action'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-glassmorphic-hero-slider-css', 'rmt-cta-banner-css' ]; }

    public function get_keywords() { return [ 'cta', 'banner', 'call to action', 'hero', 'promo', 'brand' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- Background ----
        $this->start_controls_section( 'section_background', [
            'label' => __( 'Background', 'rakmyat-core' ),
        ] );

        $this->add_control( 'bg_image', [
            'label'   => __( 'Background Image', 'rakmyat-core' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ] );

        $this->add_control( 'bg_image_position', [
            'label'   => __( 'Image Position', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'center center',
            'options' => [
                'center center' => __( 'Center', 'rakmyat-core' ),
                'center top'    => __( 'Top', 'rakmyat-core' ),
                'center bottom' => __( 'Bottom', 'rakmyat-core' ),
                'left center'   => __( 'Left', 'rakmyat-core' ),
                'right center'  => __( 'Right', 'rakmyat-core' ),
                'left top'      => __( 'Top Left', 'rakmyat-core' ),
                'right top'     => __( 'Top Right', 'rakmyat-core' ),
                'left bottom'   => __( 'Bottom Left', 'rakmyat-core' ),
                'right bottom'  => __( 'Bottom Right', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__bg' => 'object-position: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'bg_image_size', [
            'label'   => __( 'Image Size', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover'   => __( 'Cover', 'rakmyat-core' ),
                'contain' => __( 'Contain', 'rakmyat-core' ),
                'fill'    => __( 'Fill', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__bg' => 'object-fit: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Content ----
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ] );

        $this->add_control( 'title', [
            'label'       => __( 'Title', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Find Parts for Your Brand',
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $this->add_control( 'title_html_tag', [
            'label'   => __( 'Title HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'p'  => 'p', 'div' => 'div',
            ],
        ] );

        $this->add_control( 'description', [
            'label'       => __( 'Description', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => "Browse 32 premium automotive brands\nwith 58,791+ quality parts and accessories",
            'rows'        => 3,
            'dynamic'     => [ 'active' => true ],
        ] );

        $this->add_control( 'button_heading', [
            'label'     => __( 'Button', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'show_button', [
            'label'   => __( 'Show Button', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'button_text', [
            'label'     => __( 'Button Text', 'rakmyat-core' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'Browse All Products',
            'condition' => [ 'show_button' => 'yes' ],
            'dynamic'   => [ 'active' => true ],
        ] );

        $this->add_control( 'button_link', [
            'label'     => __( 'Button Link', 'rakmyat-core' ),
            'type'      => Controls_Manager::URL,
            'default'   => [ 'url' => '/shop/' ],
            'condition' => [ 'show_button' => 'yes' ],
            'dynamic'   => [ 'active' => true ],
        ] );

        $this->add_control( 'button_icon', [
            'label'   => __( 'Show Arrow Icon', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [ 'show_button' => 'yes' ],
        ] );

        $this->add_control( 'button_icon_position', [
            'label'   => __( 'Icon Position', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'after',
            'options' => [
                'before' => __( 'Before Text', 'rakmyat-core' ),
                'after'  => __( 'After Text', 'rakmyat-core' ),
            ],
            'condition' => [
                'show_button'  => 'yes',
                'button_icon' => 'yes',
            ],
        ] );

        $this->end_controls_section();

        // ---- Layout ----
        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
        ] );

        $this->add_responsive_control( 'banner_height', [
            'label'      => __( 'Banner Height', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [
                'px' => [ 'min' => 100, 'max' => 800, 'step' => 1 ],
                'vh' => [ 'min' => 5, 'max' => 100 ],
            ],
            'default'   => [ 'size' => 240, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'content_max_width', [
            'label'      => __( 'Content Max Width', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [
                'px' => [ 'min' => 200, 'max' => 1200 ],
                '%'  => [ 'min' => 20, 'max' => 100 ],
            ],
            'default'   => [ 'size' => 700, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'content_alignment', [
            'label'   => __( 'Alignment', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => [ 'title' => __( 'Left', 'rakmyat-core' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => __( 'Right', 'rakmyat-core' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__content' => 'text-align: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'content_horizontal_position', [
            'label'   => __( 'Horizontal Position', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'rakmyat-core' ), 'icon' => 'eicon-h-align-left' ],
                'center'     => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-h-align-center' ],
                'flex-end'   => [ 'title' => __( 'Right', 'rakmyat-core' ), 'icon' => 'eicon-h-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__inner' => 'justify-content: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'content_vertical_position', [
            'label'   => __( 'Vertical Position', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Top', 'rakmyat-core' ), 'icon' => 'eicon-v-align-top' ],
                'center'     => [ 'title' => __( 'Middle', 'rakmyat-core' ), 'icon' => 'eicon-v-align-middle' ],
                'flex-end'   => [ 'title' => __( 'Bottom', 'rakmyat-core' ), 'icon' => 'eicon-v-align-bottom' ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__inner' => 'align-items: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'content_padding', [
            'label'      => __( 'Content Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
                'top' => '24', 'right' => '32', 'bottom' => '24', 'left' => '32',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB
         * ============================================================ */

        // ---- Overlay ----
        $this->start_controls_section( 'section_overlay_style', [
            'label' => __( 'Overlay', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'overlay_color', [
            'label'     => __( 'Overlay Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(55, 65, 81, 0.7)',
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__overlay' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Container ----
        $this->start_controls_section( 'section_container_style', [
            'label' => __( 'Container', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'banner_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-cta-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'banner_box_shadow',
            'selector' => '{{WRAPPER}} .rmt-cta-banner',
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
                '{{WRAPPER}} .rmt-cta-banner__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .rmt-cta-banner__title',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 24, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '700' ],
            ],
        ] );

        $this->add_group_control( Group_Control_Text_Shadow::get_type(), [
            'name'     => 'title_text_shadow',
            'selector' => '{{WRAPPER}} .rmt-cta-banner__title',
        ] );

        $this->add_responsive_control( 'title_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '8', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Description Style ----
        $this->start_controls_section( 'section_description_style', [
            'label' => __( 'Description', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'description_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.85)',
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__description' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'description_typography',
            'selector' => '{{WRAPPER}} .rmt-cta-banner__description',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 15, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '400' ],
            ],
        ] );

        $this->add_responsive_control( 'description_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '20', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-cta-banner__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // Button style is handled by the shared .rmt-glass-btn class (glassmorphic-hero-slider.css)
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $title_tag   = in_array( $settings['title_html_tag'], [ 'h1','h2','h3','h4','h5','h6','p','div' ], true )
                       ? $settings['title_html_tag'] : 'h2';
        $show_button = $settings['show_button'] === 'yes';
        $show_icon   = $settings['button_icon'] === 'yes';
        $icon_pos    = $settings['button_icon_position'];
        $image_url   = ! empty( $settings['bg_image']['url'] ) ? $settings['bg_image']['url'] : '';
        ?>

        <div class="rmt-cta-banner">

            <?php if ( $image_url ) : ?>
                <img src="<?php echo esc_url( $image_url ); ?>"
                     alt=""
                     class="rmt-cta-banner__bg"
                     loading="lazy">
            <?php endif; ?>

            <div class="rmt-cta-banner__overlay"></div>

            <div class="rmt-cta-banner__inner">
                <div class="rmt-cta-banner__content">

                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <<?php echo esc_html( $title_tag ); ?> class="rmt-cta-banner__title">
                            <?php echo esc_html( $settings['title'] ); ?>
                        </<?php echo esc_html( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['description'] ) ) : ?>
                        <p class="rmt-cta-banner__description">
                            <?php echo nl2br( esc_html( $settings['description'] ) ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( $show_button && ! empty( $settings['button_text'] ) ) :
                        $btn_url  = ! empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '#';
                        $btn_attr = '';
                        if ( ! empty( $settings['button_link']['is_external'] ) ) $btn_attr .= ' target="_blank"';
                        if ( ! empty( $settings['button_link']['nofollow'] ) )    $btn_attr .= ' rel="nofollow"';

                        $arrow_svg = '<span class="rmt-cta-banner__btn-icon"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9.29 6.71a1 1 0 0 0 0 1.42L13.17 12l-3.88 3.88a1 1 0 1 0 1.42 1.41l4.59-4.59a1 1 0 0 0 0-1.41L10.71 6.7a1 1 0 0 0-1.42.01z"/></svg></span>';
                    ?>
                        <a href="<?php echo esc_url( $btn_url ); ?>"
                           class="rmt-cta-banner__btn rmt-glass-btn"
                           <?php echo $btn_attr; ?>>
                            <?php if ( $show_icon && $icon_pos === 'before' ) echo $arrow_svg; ?>
                            <span class="rmt-cta-banner__btn-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
                            <?php if ( $show_icon && $icon_pos === 'after' ) echo $arrow_svg; ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <?php
    }
}
