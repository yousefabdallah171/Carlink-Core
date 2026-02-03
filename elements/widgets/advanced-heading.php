<?php
/**
 * Advanced Heading Widget
 *
 * Heading with highlighted span text - separate styling for main text and span
 * Supports: colors, typography, gap control, alignment, HTML tags
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Advanced_Heading_Widget extends Widget_Base {

    public function get_name() {
        return 'advanced-heading';
    }

    public function get_title() {
        return __( 'Advanced Heading', 'rakmyat-core' );
    }

    public function get_icon() {
        return 'eicon-t-letter-bold';
    }

    public function get_categories() {
        return [ 'rakmyat-elements' ];
    }

    public function get_keywords() {
        return [ 'heading', 'title', 'text', 'span', 'highlight', 'advanced' ];
    }

    public function get_style_depends() {
        return [ 'rmt-advanced-heading-css' ];
    }

    protected function register_controls() {
        // =====================
        // CONTENT SECTION
        // =====================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'rakmyat-core' ),
            ]
        );

        $this->add_control(
            'text_before',
            [
                'label' => __( 'Text Before Span', 'rakmyat-core' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Welcome to', 'rakmyat-core' ),
                'placeholder' => __( 'Enter text before span', 'rakmyat-core' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'span_text',
            [
                'label' => __( 'Highlighted Text (Span)', 'rakmyat-core' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Our Store', 'rakmyat-core' ),
                'placeholder' => __( 'Enter highlighted text', 'rakmyat-core' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'text_after',
            [
                'label' => __( 'Text After Span', 'rakmyat-core' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __( 'Enter text after span (optional)', 'rakmyat-core' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_control(
            'span_position',
            [
                'label' => __( 'Span Display', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __( 'Inline (Same Line)', 'rakmyat-core' ),
                    'block' => __( 'Block (New Line)', 'rakmyat-core' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'rakmyat-core' ),
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
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'rakmyat-core' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'rakmyat-core' ),
                'dynamic' => [ 'active' => true ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - MAIN TEXT
        // =====================
        $this->start_controls_section(
            'section_style_main',
            [
                'label' => __( 'Main Text', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'main_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#0A0A0A',
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'main_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .rmt-advanced-heading',
            ]
        );

        $this->add_control(
            'main_text_shadow_heading',
            [
                'label' => __( 'Text Shadow', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'main_text_shadow',
            [
                'label' => __( 'Enable Shadow', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'main_shadow_color',
            [
                'label' => __( 'Shadow Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.3)',
                'condition' => [
                    'main_text_shadow' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'main_shadow_blur',
            [
                'label' => __( 'Shadow Blur', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'condition' => [
                    'main_text_shadow' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading' => 'text-shadow: 2px 2px {{SIZE}}{{UNIT}} {{main_shadow_color.VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - SPAN TEXT
        // =====================
        $this->start_controls_section(
            'section_style_span',
            [
                'label' => __( 'Highlighted Text (Span)', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'span_color',
            [
                'label' => __( 'Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'default' => '#3A5F79',
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'span_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span',
            ]
        );

        $this->add_control(
            'span_background_heading',
            [
                'label' => __( 'Background', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'span_background',
            [
                'label' => __( 'Background Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'span_padding',
            [
                'label' => __( 'Padding', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'span_border_radius',
            [
                'label' => __( 'Border Radius', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'span_text_shadow_heading',
            [
                'label' => __( 'Text Shadow', 'rakmyat-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'span_text_shadow',
            [
                'label' => __( 'Enable Shadow', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'span_shadow_color',
            [
                'label' => __( 'Shadow Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.3)',
                'condition' => [
                    'span_text_shadow' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'span_shadow_blur',
            [
                'label' => __( 'Shadow Blur', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'condition' => [
                    'span_text_shadow' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'text-shadow: 2px 2px {{SIZE}}{{UNIT}} {{span_shadow_color.VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - SPACING
        // =====================
        $this->start_controls_section(
            'section_style_spacing',
            [
                'label' => __( 'Spacing', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gap_before_span',
            [
                'label' => __( 'Gap Before Span', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rmt-advanced-heading.rmt-span-block .rmt-heading-span' => 'margin-left: 0; margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap_after_span',
            [
                'label' => __( 'Gap After Span', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rmt-advanced-heading.rmt-span-block .rmt-heading-span' => 'margin-right: 0; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => __( 'Heading Margin', 'rakmyat-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================
        // STYLE - DECORATIONS
        // =====================
        $this->start_controls_section(
            'section_style_decorations',
            [
                'label' => __( 'Decorations', 'rakmyat-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'span_underline',
            [
                'label' => __( 'Span Underline', 'rakmyat-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'underline_color',
            [
                'label' => __( 'Underline Color', 'rakmyat-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#3A5F79',
                'condition' => [
                    'span_underline' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'text-decoration: underline; text-decoration-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'underline_style',
            [
                'label' => __( 'Underline Style', 'rakmyat-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid' => __( 'Solid', 'rakmyat-core' ),
                    'double' => __( 'Double', 'rakmyat-core' ),
                    'dotted' => __( 'Dotted', 'rakmyat-core' ),
                    'dashed' => __( 'Dashed', 'rakmyat-core' ),
                    'wavy' => __( 'Wavy', 'rakmyat-core' ),
                ],
                'condition' => [
                    'span_underline' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'text-decoration-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'underline_thickness',
            [
                'label' => __( 'Underline Thickness', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 2,
                ],
                'condition' => [
                    'span_underline' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'underline_offset',
            [
                'label' => __( 'Underline Offset', 'rakmyat-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'size' => 4,
                ],
                'condition' => [
                    'span_underline' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rmt-advanced-heading .rmt-heading-span' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $text_before = $settings['text_before'];
        $span_text = $settings['span_text'];
        $text_after = $settings['text_after'];
        $tag = $settings['html_tag'];
        $span_position = $settings['span_position'];

        // Validate HTML tag
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p' ];
        if ( ! in_array( $tag, $allowed_tags, true ) ) {
            $tag = 'h2';
        }

        // Build class
        $heading_class = 'rmt-advanced-heading';
        if ( $span_position === 'block' ) {
            $heading_class .= ' rmt-span-block';
        }

        // Check if link exists
        $has_link = ! empty( $settings['link']['url'] );
        $link_attributes = '';
        if ( $has_link ) {
            $this->add_link_attributes( 'heading_link', $settings['link'] );
            $link_attributes = $this->get_render_attribute_string( 'heading_link' );
        }

        // Build span element
        $span_html = '';
        if ( ! empty( $span_text ) ) {
            $span_display = $span_position === 'block' ? 'display: block;' : 'display: inline;';
            $span_html = '<span class="rmt-heading-span" style="' . esc_attr( $span_display ) . '">' . esc_html( $span_text ) . '</span>';
        }

        // Build heading content
        $heading_content = '';
        if ( ! empty( $text_before ) ) {
            $heading_content .= '<span class="rmt-heading-before">' . esc_html( $text_before ) . '</span>';
        }
        $heading_content .= $span_html;
        if ( ! empty( $text_after ) ) {
            $heading_content .= '<span class="rmt-heading-after">' . esc_html( $text_after ) . '</span>';
        }

        // Wrap with link if needed
        if ( $has_link ) {
            $heading_content = '<a ' . $link_attributes . ' class="rmt-heading-link">' . $heading_content . '</a>';
        }

        // Output
        printf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            esc_attr( $tag ),
            esc_attr( $heading_class ),
            $heading_content
        );
    }

    protected function content_template() {
        ?>
        <#
        var tag = settings.html_tag || 'h2';
        var allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p'];
        if (allowedTags.indexOf(tag) === -1) {
            tag = 'h2';
        }

        var headingClass = 'rmt-advanced-heading';
        if (settings.span_position === 'block') {
            headingClass += ' rmt-span-block';
        }

        var spanDisplay = settings.span_position === 'block' ? 'display: block;' : 'display: inline;';
        var spanHtml = '';
        if (settings.span_text) {
            spanHtml = '<span class="rmt-heading-span" style="' + spanDisplay + '">' + _.escape(settings.span_text) + '</span>';
        }

        var content = '';
        if (settings.text_before) {
            content += '<span class="rmt-heading-before">' + _.escape(settings.text_before) + '</span>';
        }
        content += spanHtml;
        if (settings.text_after) {
            content += '<span class="rmt-heading-after">' + _.escape(settings.text_after) + '</span>';
        }

        if (settings.link && settings.link.url) {
            content = '<a href="' + _.escape(settings.link.url) + '" class="rmt-heading-link">' + content + '</a>';
        }
        #>
        <{{{ tag }}} class="{{{ headingClass }}}">{{{ content }}}</{{{ tag }}}>
        <?php
    }
}
