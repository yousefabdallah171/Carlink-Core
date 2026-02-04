<?php
/**
 * FAQ Accordion Widget
 *
 * Clean accordion with left "+" decorator, question text, and right toggle icon
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Faq_Accordion_Widget extends Widget_Base {

    public function get_name() {
        return 'faq-accordion';
    }

    public function get_title() {
        return __( 'FAQ Accordion', 'rakmyat-core' );
    }

    public function get_icon() {
        return 'eicon-accordion';
    }

    public function get_categories() {
        return [ 'rakmyat-elements' ];
    }

    public function get_keywords() {
        return [ 'faq', 'accordion', 'toggle', 'question', 'answer' ];
    }

    public function get_style_depends() {
        return [ 'rmt-faq-accordion-css' ];
    }

    public function get_script_depends() {
        return [ 'rmt-faq-accordion' ];
    }

    protected function register_controls() {

        // =====================
        // CONTENT
        // =====================
        $this->start_controls_section(
            'section_faq_items',
            [
                'label' => __( 'FAQ Items', 'rakmyat-core' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label' => __( 'Question', 'rakmyat-core' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'FAQ question here?', 'rakmyat-core' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'answer',
            [
                'label' => __( 'Answer', 'rakmyat-core' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( 'The answer to the question goes here.', 'rakmyat-core' ),
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'faq_items',
            [
                'label' => __( 'FAQ Items', 'rakmyat-core' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => __( 'Are there withdrawal fees?', 'rakmyat-core' ),
                        'answer' => __( 'No, there are no withdrawal fees on our platform.', 'rakmyat-core' ),
                    ],
                    [
                        'question' => __( 'How are payments collected?', 'rakmyat-core' ),
                        'answer' => __( 'Payments are collected through our secure payment gateway.', 'rakmyat-core' ),
                    ],
                    [
                        'question' => __( 'Can I buy a used item?', 'rakmyat-core' ),
                        'answer' => __( 'Yes, you can browse and purchase used items from verified sellers.', 'rakmyat-core' ),
                    ],
                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->add_control(
            'schema_markup',
            [
                'label' => __( 'FAQ Schema (SEO)', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
                'description' => __( 'Add JSON-LD FAQ schema for search engines.', 'rakmyat-core' ),
            ]
        );

        $this->add_control(
            'first_open',
            [
                'label' => __( 'First Item Open', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - ITEM
        // =====================
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => __( 'Item', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'top' => '20',
                    'right' => '24',
                    'bottom' => '20',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Space Between Items', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default' => [ 'size' => 16 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-item + .rmt-faq-item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - LEFT ICON
        // =====================
        $this->start_controls_section(
            'section_style_left_icon',
            [
                'label' => __( 'Left Icon (+)', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_left_icon',
            [
                'label' => __( 'Show Left Icon', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'left_icon_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#2D2D3A',
                'condition' => [ 'show_left_icon' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-left-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'left_icon_size',
            [
                'label' => __( 'Size', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 50 ],
                ],
                'default' => [ 'size' => 22 ],
                'condition' => [ 'show_left_icon' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-left-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'left_icon_gap',
            [
                'label' => __( 'Gap to Text', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default' => [ 'size' => 20 ],
                'condition' => [ 'show_left_icon' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-left-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - TOGGLE ICON
        // =====================
        $this->start_controls_section(
            'section_style_toggle_icon',
            [
                'label' => __( 'Toggle Icon', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'toggle_bg',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_color',
            [
                'label' => __( 'Icon Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_active_bg',
            [
                'label' => __( 'Active Background', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-item.is-open .rmt-faq-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_active_color',
            [
                'label' => __( 'Active Icon Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-item.is-open .rmt-faq-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_size',
            [
                'label' => __( 'Button Size', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 60 ],
                ],
                'default' => [ 'size' => 32 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-toggle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_icon_size',
            [
                'label' => __( 'Icon Size', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 8, 'max' => 30 ],
                ],
                'default' => [ 'size' => 14 ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                    '%' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - QUESTION
        // =====================
        $this->start_controls_section(
            'section_style_question',
            [
                'label' => __( 'Question', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'question_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E2F',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-question' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'question_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .rmt-faq-question',
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - ANSWER
        // =====================
        $this->start_controls_section(
            'section_style_answer',
            [
                'label' => __( 'Answer', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-answer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'answer_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .rmt-faq-answer',
            ]
        );

        $this->add_responsive_control(
            'answer_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '24',
                    'bottom' => '20',
                    'left' => '66',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-faq-answer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['faq_items'];
        $show_left = $settings['show_left_icon'] === 'yes';
        $first_open = $settings['first_open'] === 'yes';

        if ( empty( $items ) ) return;
        ?>
        <div class="rmt-faq-accordion">
            <?php foreach ( $items as $index => $item ) :
                $is_open = $first_open && $index === 0;
                $item_class = 'rmt-faq-item' . ( $is_open ? ' is-open' : '' );
            ?>
                <div class="<?php echo esc_attr( $item_class ); ?>">
                    <button class="rmt-faq-header" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                        <?php if ( $show_left ) : ?>
                            <span class="rmt-faq-left-icon" aria-hidden="true">+</span>
                        <?php endif; ?>
                        <span class="rmt-faq-question"><?php echo esc_html( $item['question'] ); ?></span>
                        <span class="rmt-faq-toggle" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line class="rmt-toggle-h" x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </span>
                    </button>
                    <div class="rmt-faq-body" <?php echo $is_open ? 'style="display:block;"' : ''; ?>>
                        <div class="rmt-faq-answer"><?php echo wp_kses_post( $item['answer'] ); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        // FAQ Schema
        if ( $settings['schema_markup'] === 'yes' && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [],
            ];
            foreach ( $items as $item ) {
                $schema['mainEntity'][] = [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => wp_strip_all_tags( $item['answer'] ),
                    ],
                ];
            }
            echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE ) . '</script>';
        }
    }

    protected function content_template() {
        ?>
        <#
        var showLeft = settings.show_left_icon === 'yes';
        var firstOpen = settings.first_open === 'yes';
        #>
        <div class="rmt-faq-accordion">
            <# _.each(settings.faq_items, function(item, index) {
                var isOpen = firstOpen && index === 0;
                var itemClass = 'rmt-faq-item' + (isOpen ? ' is-open' : '');
            #>
                <div class="{{{ itemClass }}}">
                    <button class="rmt-faq-header" aria-expanded="{{{ isOpen ? 'true' : 'false' }}}">
                        <# if (showLeft) { #>
                            <span class="rmt-faq-left-icon" aria-hidden="true">+</span>
                        <# } #>
                        <span class="rmt-faq-question">{{{ _.escape(item.question) }}}</span>
                        <span class="rmt-faq-toggle" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line class="rmt-toggle-h" x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </span>
                    </button>
                    <div class="rmt-faq-body" <# if(isOpen){ #>style="display:block;"<# } #>>
                        <div class="rmt-faq-answer">{{{ item.answer }}}</div>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}
