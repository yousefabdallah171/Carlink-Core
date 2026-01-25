<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Modern_Testimonial_Slider_Widget extends Widget_Base {

    public function get_name() { return 'modern-testimonial-slider'; }
    public function get_title() { return __( 'Modern Testimonial Slider', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-testimonial-carousel'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-modern-testimonial-slider-css' ]; }
    public function get_script_depends() { return [ 'rmt-modern-testimonial-slider' ]; }

    protected function register_controls() {

        // --- CONTENT TAB: SLIDES ---
        $this->start_controls_section('section_slides', [
            'label' => __( 'Slides', 'rakmyat-core' ),
        ]);

        $repeater = new Repeater();

        $repeater->add_control('client_image', [
            'label' => __( 'Client Image', 'rakmyat-core' ),
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);

        $repeater->add_control('client_name', [
            'label' => __( 'Client Name', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'John Doe', 'rakmyat-core' ),
        ]);

        $repeater->add_control('rating', [
            'label' => __( 'Rating (1-5)', 'rakmyat-core' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1 Star', '2' => '2 Stars', '3' => '3 Stars', '4' => '4 Stars', '5' => '5 Stars',
            ],
            'default' => '5',
        ]);

        $repeater->add_control('review_text', [
            'label' => __( 'Review Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __( 'The quality of auto parts provided is unmatched. Highly recommend Safety Center!', 'rakmyat-core' ),
        ]);

        $repeater->add_control('quote_icon', [
            'label' => __( 'Quote Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-quote-right', 'library' => 'fa-solid' ],
        ]);

        $this->add_control('testimonials', [
            'label' => __( 'Testimonials List', 'rakmyat-core' ),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'client_name' => 'John Doe', 'rating' => '5', 'quote_icon' => [ 'value' => 'fas fa-quote-right', 'library' => 'fa-solid' ] ],
                [ 'client_name' => 'Jane Smith', 'rating' => '4', 'quote_icon' => [ 'value' => 'fas fa-quote-right', 'library' => 'fa-solid' ] ],
                [ 'client_name' => 'Alex Munroe', 'rating' => '5', 'quote_icon' => [ 'value' => 'fas fa-quote-right', 'library' => 'fa-solid' ] ],
            ],
            'title_field' => '{{{ client_name }}}',
        ]);

        $this->end_controls_section();

        // --- CONTENT TAB: SLIDER SETTINGS ---
        $this->start_controls_section('section_slider_settings', [
            'label' => __( 'Slider Settings', 'rakmyat-core' ),
        ]);

        $this->add_control('loop', [
            'label' => __( 'Loop', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('autoplay', [
            'label' => __( 'Autoplay', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('autoplay_speed', [
            'label' => __( 'Autoplay Speed', 'rakmyat-core' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 5000,
            'condition' => [ 'autoplay' => 'yes' ],
        ]);

        $this->add_control('show_arrows', [
            'label' => __( 'Navigation Arrows', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('prev_icon', [
            'label' => __( 'Left Arrow Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
            'condition' => [ 'show_arrows' => 'yes' ],
        ]);

        $this->add_control('next_icon', [
            'label' => __( 'Right Arrow Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
            'condition' => [ 'show_arrows' => 'yes' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: CARD ---
        $this->start_controls_section('section_style_card', [
            'label' => __( 'Card Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-testimonial-card' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('card_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [ '{{WRAPPER}} .rmt-testimonial-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .rmt-testimonial-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [ '{{WRAPPER}} .rmt-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('card_min_height', [
            'label' => __( 'Min Height', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [
                'px' => [ 'min' => 100, 'max' => 600 ],
                'vh' => [ 'min' => 10, 'max' => 100 ],
            ],
            'selectors' => [ '{{WRAPPER}} .rmt-testimonial-card' => 'min-height: {{SIZE}}{{UNIT}};' ],
            'description' => __( 'Set min-height to make all cards the same height', 'rakmyat-core' ),
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: QUOTE ICON ---
        $this->start_controls_section('section_style_quote', [
            'label' => __( 'Quote Icon Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('quote_color', [
            'label' => __( 'Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(58, 95, 121, 0.15)',
            'selectors' => [
                '{{WRAPPER}} .rmt-quote-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-quote-icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-quote-icon svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .rmt-quote-icon svg path' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('quote_size', [
            'label' => __( 'Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
            'default' => [ 'size' => 80 ],
            'selectors' => [ '{{WRAPPER}} .rmt-quote-icon' => 'font-size: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('quote_bottom', [
            'label' => __( 'Bottom Offset', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -100, 'max' => 100 ] ],
            'default' => [ 'size' => -10 ],
            'selectors' => [ '{{WRAPPER}} .rmt-quote-icon' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('quote_right', [
            'label' => __( 'Right Offset', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -100, 'max' => 100 ] ],
            'default' => [ 'size' => 10 ],
            'selectors' => [ '{{WRAPPER}} .rmt-quote-icon' => 'right: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $swiper_data = [
            'loop' => ($settings['loop'] === 'yes'),
            'autoplay' => ($settings['autoplay'] === 'yes') ? ['delay' => $settings['autoplay_speed']] : false,
        ];
        ?>

        <div class="rmt-slider-wrapper">
            <div class="rmt-testimonial-slider swiper" data-settings='<?php echo json_encode($swiper_data); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['testimonials'] as $item ) : ?>
                        <div class="swiper-slide">
                            <div class="rmt-testimonial-card">
                                <div class="rmt-card-header">
                                    <div class="rmt-client-img">
                                        <img src="<?php echo esc_url($item['client_image']['url']); ?>" alt="<?php echo esc_attr($item['client_name']); ?>">
                                    </div>
                                    <div class="rmt-client-info">
                                        <h4 class="rmt-client-name"><?php echo esc_html($item['client_name']); ?></h4>
                                        <div class="rmt-star-rating">
                                            <?php for ($i = 1; $i <= 5; $i++) :
                                                $active = ($i <= (int)$item['rating']) ? 'rmt-star-active' : ''; ?>
                                                <i class="fas fa-star <?php echo $active; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="rmt-card-body">
                                    <p class="rmt-review-text"><?php echo esc_html($item['review_text']); ?></p>
                                </div>
                                <!-- Overlapping Quote Icon -->
                                <div class="rmt-quote-icon">
                                    <?php
                                    if ( isset( $item['quote_icon'] ) && ! empty( $item['quote_icon']['value'] ) ) {
                                        Icons_Manager::render_icon( $item['quote_icon'], [ 'aria-hidden' => 'true' ] );
                                    } else {
                                        ?>
                                        <i class="fas fa-quote-right" aria-hidden="true"></i>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                <div class="swiper-button-prev rmt-nav-prev"><?php Icons_Manager::render_icon( $settings['prev_icon'] ); ?></div>
                <div class="swiper-button-next rmt-nav-next"><?php Icons_Manager::render_icon( $settings['next_icon'] ); ?></div>
            <?php endif; ?>
        </div>

        <?php
    }
}