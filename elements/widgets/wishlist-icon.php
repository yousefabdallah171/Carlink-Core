<?php
/**
 * Wishlist Icon Widget - SIMPLE VERSION
 * No AJAX - Just PHP like the old theme
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Wishlist_Icon_Widget extends Widget_Base {

    public function get_name() { return 'wishlist-icon'; }
    public function get_title() { return __( 'Wishlist Icon', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-heart'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-wishlist-icon-css' ]; }

    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ]);

        $this->add_control('wishlist_url', [
            'label' => __( 'Link', 'rakmyat-core' ),
            'type' => Controls_Manager::URL,
            'default' => [ 'url' => '/wishlist/' ],
        ]);

        $this->add_control('selected_icon', [
            'label' => __( 'Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-heart',
                'library' => 'fa-solid',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __( 'Style', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('icon_color', [
            'label' => __( 'Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} i, {{WRAPPER}} svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => __( 'Icon Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 22 ],
            'selectors' => [
                '{{WRAPPER}} i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;'
            ],
        ]);

        $this->add_control('badge_bg', [
            'label' => __( 'Badge Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ef4444',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-count' => 'background-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $url = !empty($settings['wishlist_url']['url']) ? $settings['wishlist_url']['url'] : '/wishlist/';

        // Get icon settings
        $icon_settings = !empty($settings['selected_icon']) ? $settings['selected_icon'] : [
            'value' => 'fas fa-heart',
            'library' => 'fa-solid',
        ];

        // Get wishlist count - SIMPLE PHP METHOD
        $count = 0;

        // In Elementor editor, show preview number
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            $count = 3;
        } else {
            // Try WCBoost Wishlist
            if ( class_exists( '\WCBoost\Wishlist\Helper' ) ) {
                try {
                    $wishlist = \WCBoost\Wishlist\Helper::get_wishlist();
                    if ( $wishlist && method_exists( $wishlist, 'count_items' ) ) {
                        $count = $wishlist->count_items();
                    }
                } catch ( \Exception $e ) {
                    $count = 0;
                }
            }
        }

        $show_class = ( $count > 0 ) ? 'show' : '';
        ?>

        <div class="rmt-wishlist-container">
            <a href="<?php echo esc_url($url); ?>" class="rmt-wishlist-link">
                <span class="rmt-wishlist-icon-wrap">
                    <?php
                    if ( !empty($icon_settings) ) {
                        Icons_Manager::render_icon( $icon_settings, [ 'aria-hidden' => 'true' ] );
                    } else {
                        echo '<i class="fas fa-heart"></i>';
                    }
                    ?>
                    <span class="rmt-wishlist-count <?php echo esc_attr($show_class); ?>">
                        <?php echo esc_html($count); ?>
                    </span>
                </span>
            </a>
        </div>
        <?php
    }
}
