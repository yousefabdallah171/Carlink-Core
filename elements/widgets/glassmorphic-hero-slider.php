<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Glassmorphic_Hero_Slider_Widget extends Widget_Base {

    public function get_name() { return 'glassmorphic-hero-slider'; }
    public function get_title() { return __( 'Glassmorphic Hero Slider', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-slideshow'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-glassmorphic-hero-slider-css' ]; }
    public function get_script_depends() { return [ 'rmt-glassmorphic-hero-slider' ]; }

    protected function register_controls() {

        // --- CONTENT TAB ---
        $this->start_controls_section('section_slides', [
            'label' => __( 'Slides', 'rakmyat-core' ),
        ]);

        $repeater = new Repeater();

        $repeater->add_control('image', [
            'label' => __( 'Background Image', 'rakmyat-core' ),
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);

        $repeater->add_control('title', [
            'label' => __( 'Title', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __( 'Stop worrying about counterfeit parts.', 'rakmyat-core' ),
        ]);

        $repeater->add_control('subtitle', [
            'label' => __( 'Subtitle', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Find Auto Parts Now', 'rakmyat-core' ),
        ]);

        $repeater->add_control('button_text', [
            'label' => __( 'Button Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Buy Now', 'rakmyat-core' ),
        ]);

        $repeater->add_control('button_link', [
            'label' => __( 'Button Link', 'rakmyat-core' ),
            'type' => Controls_Manager::URL,
        ]);

        $this->add_control('slides', [
            'label' => __( 'Slides List', 'rakmyat-core' ),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'title' => 'Stop worrying about counterfeit parts. (Focus on Reliability)', 'subtitle' => 'Find Auto Parts Now' ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->add_control('prev_icon', [
            'label' => __( 'Previous Arrow Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
        ]);

        $this->add_control('next_icon', [
            'label' => __( 'Next Arrow Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
        ]);

        $this->add_control('autoplay', [
            'label' => __( 'Autoplay', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('autoplay_delay', [
            'label' => __( 'Autoplay Delay (ms)', 'rakmyat-core' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 5000,
            'condition' => [ 'autoplay' => 'yes' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: CONTAINER ---
        $this->start_controls_section('section_container', [
            'label' => __( 'Container', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('container_max_width', [
            'label' => __( 'Container Max Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw' ],
            'range' => [
                'px' => [ 'min' => 600, 'max' => 1920 ],
                '%' => [ 'min' => 50, 'max' => 100 ],
                'vw' => [ 'min' => 50, 'max' => 100 ],
            ],
            'default' => [ 'size' => 1440, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-slide-container' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('container_padding', [
            'label' => __( 'Container Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'default' => [
                'top' => '0',
                'right' => '15',
                'bottom' => '0',
                'left' => '15',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-slide-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: CONTENT BOX ---
        $this->start_controls_section('section_content_box', [
            'label' => __( 'Content Box', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('content_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_margin', [
            'label' => __( 'Margin', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-slide-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_max_width', [
            'label' => __( 'Max Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw' ],
            'range' => [
                'px' => [ 'min' => 200, 'max' => 1200 ],
                '%' => [ 'min' => 10, 'max' => 100 ],
                'vw' => [ 'min' => 10, 'max' => 100 ],
            ],
            'default' => [ 'size' => 700, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-slide-content' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('content_text_align', [
            'label' => __( 'Text Alignment', 'rakmyat-core' ),
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
            'selectors' => [ '{{WRAPPER}} .rmt-slide-content' => 'text-align: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('content_horizontal_position', [
            'label' => __( 'Horizontal Position', 'rakmyat-core' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __( 'Start', 'rakmyat-core' ),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __( 'Center', 'rakmyat-core' ),
                    'icon' => 'eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => __( 'End', 'rakmyat-core' ),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'flex-start',
            'selectors' => [ '{{WRAPPER}} .rmt-slide-container' => 'justify-content: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('content_vertical_position', [
            'label' => __( 'Vertical Position', 'rakmyat-core' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __( 'Top', 'rakmyat-core' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __( 'Middle', 'rakmyat-core' ),
                    'icon' => 'eicon-v-align-middle',
                ],
                'flex-end' => [
                    'title' => __( 'Bottom', 'rakmyat-core' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'default' => 'center',
            'selectors' => [ '{{WRAPPER}} .rmt-slide-container' => 'align-items: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: OVERLAY ---
        $this->start_controls_section('section_overlay', [
            'label' => __( 'Overlay Gradient', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'overlay_bg',
                'label' => __( 'Overlay Gradient', 'rakmyat-core' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .rmt-slide-overlay',
                'fields_options' => [
                    'background' => [ 'default' => 'gradient' ],
                    'color' => [ 'default' => '#3A5F79' ],
                    'color_b' => [ 'default' => '#8A8F91' ],
                ],
            ]
        );

        $this->add_control('overlay_opacity', [
            'label' => __( 'Opacity', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 1 ] ],
            'default' => [ 'size' => 0.7 ],
            'selectors' => [ '{{WRAPPER}} .rmt-slide-overlay' => 'opacity: {{SIZE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: TITLE ---
        $this->start_controls_section('section_title_style', [
            'label' => __( 'Title Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .rmt-title',
        ]);

        $this->add_control('title_color', [
            'label' => __( 'Title Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-title' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('title_highlight_color', [
            'label' => __( 'Highlight Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffcc00',
            'selectors' => [ '{{WRAPPER}} .rmt-title .rmt-highlight' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_highlight_typo',
            'label' => __( 'Highlight Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-title .rmt-highlight',
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: SUBTITLE ---
        $this->start_controls_section('section_subtitle_style', [
            'label' => __( 'Subtitle Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typo',
            'selector' => '{{WRAPPER}} .rmt-subtitle',
        ]);

        $this->add_control('subtitle_color', [
            'label' => __( 'Subtitle Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-subtitle' => 'color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('subtitle_margin', [
            'label' => __( 'Margin', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('subtitle_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: BUTTON ---
        $this->start_controls_section('section_btn_style', [
            'label' => __( 'Glass Button Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'btn_typo',
            'selector' => '{{WRAPPER}} .rmt-glass-btn',
        ]);

        $this->add_control('btn_text_color', [
            'label' => __( 'Button Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-glass-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('btn_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-glass-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('btn_margin', [
            'label' => __( 'Margin', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-glass-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="rmt-slider-container">
            <div class="swiper rmt-glass-slider" data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>" data-delay="<?php echo esc_attr($settings['autoplay_delay']); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide rmt-glass-slide" style="background-image: url('<?php echo esc_url($slide['image']['url']); ?>');">
                            <div class="rmt-slide-overlay"></div>
                            <div class="rmt-slide-container">
                                <div class="rmt-slide-content">
                                <h2 class="rmt-title"><?php echo wp_kses_post(preg_replace('/\{\{(.*?)\}\}/', '<span class="rmt-highlight">$1</span>', $slide['title'])); ?></h2>
                                <h4 class="rmt-subtitle"><?php echo esc_html($slide['subtitle']); ?></h4>
                                <a href="<?php echo esc_url($slide['button_link']['url']); ?>" class="rmt-glass-btn">
                                    <?php echo esc_html($slide['button_text']); ?>
                                    <i class="fas fa-chevron-right" style="margin-left:10px; font-size: 0.8em;"></i>
                                </a>
                            </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Navigation -->
                <div class="swiper-button-prev rmt-nav-btn">
                    <?php Icons_Manager::render_icon( $settings['prev_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <div class="swiper-button-next rmt-nav-btn">
                    <?php Icons_Manager::render_icon( $settings['next_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <?php
    }
}