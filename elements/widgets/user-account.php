<?php
/**
 * User Account Widget
 *
 * Displays a person icon + "Hello / First Name" when logged in,
 * or "Hello / Sign In" when logged out — both linked to the
 * WooCommerce My Account page.
 *
 * Supports RTL (Arabic) automatically via is_rtl().
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_User_Account_Widget extends Widget_Base {

    public function get_name()       { return 'user-account'; }
    public function get_title()      { return __( 'User Account', 'rakmyat-core' ); }
    public function get_icon()       { return 'eicon-person'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }
    public function get_style_depends() { return [ 'rmt-user-account-css' ]; }

    // ─────────────────────────────────────────────
    // CONTROLS
    // ─────────────────────────────────────────────
    protected function register_controls() {

        /* ── CONTENT ── */
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ] );

        $this->add_control( 'hello_text', [
            'label'       => __( 'Hello Text', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Hello', 'rakmyat-core' ),
            'placeholder' => __( 'Hello', 'rakmyat-core' ),
            'description' => __( 'Shown above the name or Sign In link.', 'rakmyat-core' ),
        ] );

        $this->add_control( 'signin_text', [
            'label'       => __( 'Sign In Text', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Sign In', 'rakmyat-core' ),
            'placeholder' => __( 'Sign In', 'rakmyat-core' ),
            'description' => __( 'Shown when the user is not logged in.', 'rakmyat-core' ),
        ] );

        $this->add_control( 'show_icon', [
            'label'   => __( 'Show Icon', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->end_controls_section();

        /* ── STYLE: LAYOUT ── */
        $this->start_controls_section( 'section_style_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'gap', [
            'label'      => __( 'Gap Between Icon & Text', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'size' => 10 ],
            'selectors'  => [ '{{WRAPPER}} .rmt-ua-wrap' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'lines_gap', [
            'label'      => __( 'Gap Between Hello & Name', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
            'default'    => [ 'size' => 2 ],
            'selectors'  => [ '{{WRAPPER}} .rmt-ua-text' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'alignment', [
            'label'     => __( 'Alignment', 'rakmyat-core' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [ 'title' => __( 'Start', 'rakmyat-core' ), 'icon' => 'eicon-h-align-left' ],
                'center'     => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-h-align-center' ],
                'flex-end'   => [ 'title' => __( 'End', 'rakmyat-core' ), 'icon' => 'eicon-h-align-right' ],
            ],
            'default'   => 'flex-start',
            'selectors' => [ '{{WRAPPER}} .rmt-ua-wrap' => 'justify-content: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: ICON ── */
        $this->start_controls_section( 'section_style_icon', [
            'label'     => __( 'Icon', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_icon' => 'yes' ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label'      => __( 'Icon Size', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 16, 'max' => 80 ] ],
            'default'    => [ 'size' => 32 ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ua-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'icon_color', [
            'label'     => __( 'Icon Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-ua-icon svg' => 'stroke: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: HELLO TEXT ── */
        $this->start_controls_section( 'section_style_hello', [
            'label' => __( 'Hello Text', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'hello_typo',
            'selector' => '{{WRAPPER}} .rmt-ua-hello',
        ] );

        $this->add_control( 'hello_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-ua-hello' => 'color: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: NAME / SIGN IN TEXT ── */
        $this->start_controls_section( 'section_style_name', [
            'label' => __( 'Name / Sign In Text', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'name_typo',
            'selector' => '{{WRAPPER}} .rmt-ua-name',
        ] );

        $this->add_control( 'name_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-ua-name' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'name_color_hover', [
            'label'     => __( 'Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-ua-wrap:hover .rmt-ua-name' => 'color: {{VALUE}};' ],
        ] );

        $this->end_controls_section();
    }

    // ─────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Account URL — WooCommerce My Account or fallback to login
        $account_url = function_exists( 'wc_get_page_permalink' )
            ? wc_get_page_permalink( 'myaccount' )
            : wp_login_url( get_permalink() );

        // Resolve the bottom line text
        $is_logged_in = is_user_logged_in();
        if ( $is_logged_in ) {
            $user       = wp_get_current_user();
            $name_line  = ! empty( $user->first_name )
                ? $user->first_name
                : $user->display_name;
        } else {
            $name_line = ! empty( $settings['signin_text'] )
                ? $settings['signin_text']
                : __( 'Sign In', 'rakmyat-core' );
        }

        $hello_text = ! empty( $settings['hello_text'] )
            ? $settings['hello_text']
            : __( 'Hello', 'rakmyat-core' );

        $is_rtl = is_rtl();
        ?>
        <a href="<?php echo esc_url( $account_url ); ?>"
           class="rmt-ua-wrap<?php echo $is_rtl ? ' rmt-ua-rtl' : ''; ?>"
           aria-label="<?php echo esc_attr( $hello_text . ' ' . $name_line ); ?>">

            <?php if ( 'yes' === $settings['show_icon'] ) : ?>
            <span class="rmt-ua-icon" aria-hidden="true">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <!-- Head -->
                    <circle cx="16" cy="11" r="5"/>
                    <!-- Body arc -->
                    <path d="M5 28c0-6.075 4.925-11 11-11s11 4.925 11 11"/>
                </svg>
            </span>
            <?php endif; ?>

            <span class="rmt-ua-text">
                <span class="rmt-ua-hello"><?php echo esc_html( $hello_text ); ?></span>
                <span class="rmt-ua-name"><?php echo esc_html( $name_line ); ?></span>
            </span>

        </a>
        <?php
    }
}
