<?php
/**
 * Language Switcher Widget
 *
 * Polylang-powered language switcher. Shows current language
 * (e.g. "En") with a chevron and a dropdown list of all other
 * languages. Fully styled via Elementor controls.
 *
 * Requires: Polylang (free or pro)
 * RTL-aware, i18n-ready.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Language_Switcher_Widget extends Widget_Base {

    public function get_name()       { return 'language-switcher'; }
    public function get_title()      { return __( 'Language Switcher', 'rakmyat-core' ); }
    public function get_icon()       { return 'eicon-globe'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends()  { return [ 'rmt-language-switcher-css' ]; }
    public function get_script_depends() { return [ 'rmt-language-switcher' ]; }

    // ─────────────────────────────────────────────
    // CONTROLS
    // ─────────────────────────────────────────────
    protected function register_controls() {

        /* ── CONTENT ── */
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ] );

        $this->add_control( 'display_format', [
            'label'   => __( 'Display Format', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'code'   => __( 'Code — En / Ar', 'rakmyat-core' ),
                'name'   => __( 'Name — English / Arabic', 'rakmyat-core' ),
                'native' => __( 'Native — English / العربية', 'rakmyat-core' ),
            ],
            'default' => 'code',
        ] );

        $this->add_control( 'show_chevron', [
            'label'   => __( 'Show Chevron Arrow', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'hide_current', [
            'label'       => __( 'Hide Current Language in Dropdown', 'rakmyat-core' ),
            'type'        => Controls_Manager::SWITCHER,
            'default'     => 'yes',
            'description' => __( 'Recommended: hide the active language from the list.', 'rakmyat-core' ),
        ] );

        $this->end_controls_section();

        /* ── STYLE: BUTTON ── */
        $this->start_controls_section( 'section_style_button', [
            'label' => __( 'Button', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->start_controls_tabs( 'tabs_button' );

        $this->start_controls_tab( 'tab_button_normal', [
            'label' => __( 'Normal', 'rakmyat-core' ),
        ] );

        $this->add_control( 'button_bg', [
            'label'     => __( 'Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [ '{{WRAPPER}} .rmt-ls-btn' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_control( 'button_text_color', [
            'label'     => __( 'Text Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0A0A0A',
            'selectors' => [
                '{{WRAPPER}} .rmt-ls-label'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-ls-chevron' => 'color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_button_hover', [
            'label' => __( 'Hover / Open', 'rakmyat-core' ),
        ] );

        $this->add_control( 'button_bg_hover', [
            'label'     => __( 'Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [
                '{{WRAPPER}} .rmt-ls-btn:hover'           => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .rmt-ls-open .rmt-ls-btn'    => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_text_color_hover', [
            'label'     => __( 'Text Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3A5F79',
            'selectors' => [
                '{{WRAPPER}} .rmt-ls-btn:hover .rmt-ls-label'            => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-ls-btn:hover .rmt-ls-chevron'          => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-ls-open .rmt-ls-btn .rmt-ls-label'     => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-ls-open .rmt-ls-btn .rmt-ls-chevron'   => 'color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'      => 'button_typo',
            'separator' => 'before',
            'selector'  => '{{WRAPPER}} .rmt-ls-label',
        ] );

        $this->add_responsive_control( 'button_padding', [
            'label'      => __( 'Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'default'    => [
                'top' => '6', 'right' => '10',
                'bottom' => '6', 'left' => '10', 'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ls-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'button_border',
            'selector' => '{{WRAPPER}} .rmt-ls-btn',
        ] );

        $this->add_responsive_control( 'button_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ls-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: CHEVRON ── */
        $this->start_controls_section( 'section_style_chevron', [
            'label'     => __( 'Chevron Arrow', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_chevron' => 'yes' ],
        ] );

        $this->add_responsive_control( 'chevron_size', [
            'label'      => __( 'Size', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 8, 'max' => 24 ] ],
            'default'    => [ 'size' => 12 ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ls-chevron svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'chevron_gap', [
            'label'      => __( 'Gap from Text', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
            'default'    => [ 'size' => 6 ],
            'selectors'  => [ '{{WRAPPER}} .rmt-ls-btn' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: DROPDOWN ── */
        $this->start_controls_section( 'section_style_dropdown', [
            'label' => __( 'Dropdown', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'dropdown_bg', [
            'label'     => __( 'Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-ls-dropdown' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'dropdown_min_width', [
            'label'      => __( 'Min Width', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 60, 'max' => 300 ] ],
            'default'    => [ 'size' => 100 ],
            'selectors'  => [ '{{WRAPPER}} .rmt-ls-dropdown' => 'min-width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'dropdown_border',
            'selector' => '{{WRAPPER}} .rmt-ls-dropdown',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width'  => [ 'default' => [ 'top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','unit'=>'px' ] ],
                'color'  => [ 'default' => '#E5E7EB' ],
            ],
        ] );

        $this->add_responsive_control( 'dropdown_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top'=>'8','right'=>'8','bottom'=>'8','left'=>'8','unit'=>'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ls-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'dropdown_shadow',
            'selector' => '{{WRAPPER}} .rmt-ls-dropdown',
            'fields_options' => [
                'box_shadow_type' => [ 'default' => 'yes' ],
                'box_shadow' => [
                    'default' => [
                        'horizontal' => 0, 'vertical' => 4,
                        'blur' => 16, 'spread' => 0,
                        'color' => 'rgba(0,0,0,0.10)',
                    ],
                ],
            ],
        ] );

        $this->end_controls_section();

        /* ── STYLE: DROPDOWN ITEMS ── */
        $this->start_controls_section( 'section_style_items', [
            'label' => __( 'Dropdown Items', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'item_typo',
            'selector' => '{{WRAPPER}} .rmt-ls-dropdown a',
        ] );

        $this->add_responsive_control( 'item_padding', [
            'label'      => __( 'Item Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [ 'top'=>'8','right'=>'16','bottom'=>'8','left'=>'16','unit'=>'px' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-ls-dropdown a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'item_color', [
            'label'     => __( 'Text Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => [ '{{WRAPPER}} .rmt-ls-dropdown a' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'item_color_hover', [
            'label'     => __( 'Hover Text Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-ls-dropdown a:hover' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'item_bg_hover', [
            'label'     => __( 'Hover Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F3F4F6',
            'selectors' => [ '{{WRAPPER}} .rmt-ls-dropdown a:hover' => 'background-color: {{VALUE}};' ],
        ] );

        $this->end_controls_section();
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    /**
     * Returns a map of language slug → native display name.
     */
    private function native_names(): array {
        return [
            'ar' => 'العربية',
            'en' => 'English',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'tr' => 'Türkçe',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'he' => 'עברית',
            'fa' => 'فارسی',
            'ur' => 'اردو',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
        ];
    }

    /**
     * Returns the display label for a language based on the chosen format.
     */
    private function get_label( array $lang, string $format ): string {
        switch ( $format ) {
            case 'name':
                return $lang['name'];
            case 'native':
                $natives = $this->native_names();
                return $natives[ $lang['slug'] ] ?? $lang['name'];
            case 'code':
            default:
                return ucfirst( strtolower( $lang['slug'] ) );
        }
    }

    // ─────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────
    protected function render() {
        $settings = $this->get_settings_for_display();
        $format   = $settings['display_format'] ?? 'code';

        // ── Polylang availability check ──────────────
        if ( ! function_exists( 'pll_the_languages' ) || ! function_exists( 'pll_current_language' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="rmt-ls-notice">'
                    . esc_html__( 'Polylang is not active. Install Polylang to use this widget.', 'rakmyat-core' )
                    . '</div>';
            }
            return;
        }

        // ── Get languages ────────────────────────────
        $raw_languages = pll_the_languages( [ 'raw' => 1 ] );
        if ( empty( $raw_languages ) ) return;

        // Current language
        $current_slug  = pll_current_language( 'slug' );
        $current_lang  = $raw_languages[ $current_slug ] ?? reset( $raw_languages );
        $current_label = $this->get_label( $current_lang, $format );

        // Other languages
        $hide_current  = 'yes' === $settings['hide_current'];
        $other_langs   = array_filter( $raw_languages, function( $lang ) use ( $current_slug, $hide_current ) {
            if ( $hide_current && $lang['slug'] === $current_slug ) return false;
            if ( ! empty( $lang['no_translation'] ) ) return false;
            return true;
        } );

        $show_chevron = 'yes' === $settings['show_chevron'];
        $is_rtl       = is_rtl();
        ?>
        <div class="rmt-ls-switcher<?php echo $is_rtl ? ' rmt-ls-rtl' : ''; ?>">

            <button class="rmt-ls-btn"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    type="button">
                <span class="rmt-ls-label"><?php echo esc_html( $current_label ); ?></span>

                <?php if ( $show_chevron ) : ?>
                <span class="rmt-ls-chevron" aria-hidden="true">
                    <svg viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </span>
                <?php endif; ?>
            </button>

            <?php if ( ! empty( $other_langs ) ) : ?>
            <ul class="rmt-ls-dropdown" role="listbox" aria-label="<?php esc_attr_e( 'Select language', 'rakmyat-core' ); ?>">
                <?php foreach ( $other_langs as $lang ) :
                    $label = $this->get_label( $lang, $format );
                    $url   = esc_url( $lang['url'] );
                ?>
                <li role="option">
                    <a href="<?php echo $url; ?>"
                       hreflang="<?php echo esc_attr( $lang['slug'] ); ?>"
                       lang="<?php echo esc_attr( $lang['slug'] ); ?>">
                        <?php echo esc_html( $label ); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

        </div>
        <?php
    }
}
