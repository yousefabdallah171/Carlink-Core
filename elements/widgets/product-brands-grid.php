<?php
/**
 * Product Brands Grid Widget
 * Displays WooCommerce product brands in a customizable grid layout.
 * Supports two layout styles: Simple (Name + Count) and Detailed (Name + Country + Count).
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Product_Brands_Grid_Widget extends Widget_Base {

    public function get_name() { return 'product-brands-grid'; }

    public function get_title() { return __( 'Product Brands Grid', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-gallery-grid'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-product-brands-grid-css' ]; }

    public function get_keywords() { return [ 'brand', 'brands', 'product', 'grid', 'woocommerce', 'taxonomy', 'car' ]; }

    /**
     * Get all brand terms for Select2 controls
     */
    private function get_brand_options() {
        $terms = get_terms( [
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
        ] );
        $options = [];
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }
        return $options;
    }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- Content Settings ----
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ] );

        $this->add_control( 'layout_style', [
            'label'   => __( 'Layout Style', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => [
                'style-1' => __( 'Style 1 — Simple (Name + Count)', 'rakmyat-core' ),
                'style-2' => __( 'Style 2 — Detailed (Name + Country + Count)', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'thumbnail_source', [
            'label'   => __( 'Thumbnail Source', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'brand_thumbnail_id',
            'options' => [
                'brand_thumbnail_id' => __( 'Second Thumbnail (Martfury)', 'rakmyat-core' ),
                'thumbnail_id'       => __( 'First Thumbnail (WooCommerce)', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'count_suffix', [
            'label'       => __( 'Count Suffix Text', 'rakmyat-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'parts',
            'placeholder' => __( 'e.g., parts, products, items', 'rakmyat-core' ),
        ] );

        $this->add_control( 'number_format', [
            'label'   => __( 'Format Numbers with Commas', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_count', [
            'label'   => __( 'Show Product Count', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'show_image', [
            'label'   => __( 'Show Brand Image', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'name_html_tag', [
            'label'   => __( 'Name HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
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

        $this->end_controls_section();

        // ---- Query Settings ----
        $this->start_controls_section( 'section_query', [
            'label' => __( 'Query', 'rakmyat-core' ),
        ] );

        $this->add_control( 'brands_include', [
            'label'       => __( 'Include Brands', 'rakmyat-core' ),
            'description' => __( 'Select specific brands to show. Leave empty to show all.', 'rakmyat-core' ),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'options'     => $this->get_brand_options(),
            'label_block' => true,
        ] );

        $this->add_control( 'brands_exclude', [
            'label'       => __( 'Exclude Brands', 'rakmyat-core' ),
            'description' => __( 'Select brands to exclude.', 'rakmyat-core' ),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'options'     => $this->get_brand_options(),
            'label_block' => true,
        ] );

        $this->add_control( 'orderby', [
            'label'   => __( 'Order By', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'name',
            'options' => [
                'name'    => __( 'Name', 'rakmyat-core' ),
                'count'   => __( 'Product Count', 'rakmyat-core' ),
                'term_id' => __( 'Term ID', 'rakmyat-core' ),
                'slug'    => __( 'Slug', 'rakmyat-core' ),
                'none'    => __( 'None (Custom Order)', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'order', [
            'label'   => __( 'Order Direction', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'ASC',
            'options' => [
                'ASC'  => __( 'Ascending', 'rakmyat-core' ),
                'DESC' => __( 'Descending', 'rakmyat-core' ),
            ],
        ] );

        $this->add_control( 'limit', [
            'label'   => __( 'Number of Brands', 'rakmyat-core' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'hide_empty', [
            'label'   => __( 'Hide Empty Brands', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->end_controls_section();

        // ---- Layout Settings ----
        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
        ] );

        $this->add_responsive_control( 'columns', [
            'label'          => __( 'Columns', 'rakmyat-core' ),
            'type'           => Controls_Manager::SELECT,
            'default'        => '6',
            'tablet_default' => '3',
            'mobile_default' => '2',
            'options'        => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brands-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ] );

        $this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Grid Gap', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 80, 'step' => 1 ],
                'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
            ],
            'default' => [ 'size' => 24, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brands-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
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
                '{{WRAPPER}} .rmt-brand-card__info' => 'text-align: {{VALUE}};',
            ],
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
                '{{WRAPPER}} .rmt-brand-card' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .rmt-brand-card',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width'  => [ 'default' => [
                    'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true,
                ] ],
                'color' => [ 'default' => '#E8E8E8' ],
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
                '{{WRAPPER}} .rmt-brand-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_box_shadow',
            'selector' => '{{WRAPPER}} .rmt-brand-card',
            'fields_options' => [
                'box_shadow_type' => [ 'default' => 'yes' ],
                'box_shadow' => [
                    'default' => [
                        'horizontal' => 0,
                        'vertical'   => 2,
                        'blur'       => 8,
                        'spread'     => 0,
                        'color'      => 'rgba(0,0,0,0.08)',
                    ],
                ],
            ],
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Content Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '12', 'right' => '12', 'bottom' => '16', 'left' => '12',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'card_hover_heading', [
            'label'     => __( 'Hover', 'rakmyat-core' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'card_hover_bg_color', [
            'label'     => __( 'Background Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_hover_box_shadow',
            'selector' => '{{WRAPPER}} .rmt-brand-card:hover',
        ] );

        $this->add_control( 'card_hover_border_color', [
            'label'     => __( 'Border Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card:hover' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_transition_duration', [
            'label'     => __( 'Transition Duration (ms)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ] ],
            'default'   => [ 'size' => 300 ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card' => 'transition-duration: {{SIZE}}ms;',
            ],
        ] );

        $this->end_controls_section();

        // ---- Image Style ----
        $this->start_controls_section( 'section_image_style', [
            'label'     => __( 'Image', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_image' => 'yes' ],
        ] );

        $this->add_responsive_control( 'image_height', [
            'label'      => __( 'Height', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [
                'px' => [ 'min' => 60, 'max' => 500, 'step' => 1 ],
                'vh' => [ 'min' => 5, 'max' => 50 ],
            ],
            'default'   => [ 'size' => 180, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__image' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'image_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [
                'top' => '8', 'right' => '8', 'bottom' => '0', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
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
                '{{WRAPPER}} .rmt-brand-card__image img' => 'object-fit: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'image_hover_zoom', [
            'label'   => __( 'Hover Zoom Effect', 'rakmyat-core' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'image_hover_zoom_scale', [
            'label'     => __( 'Zoom Scale', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 1, 'max' => 1.5, 'step' => 0.01 ] ],
            'default'   => [ 'size' => 1.05 ],
            'condition' => [ 'image_hover_zoom' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card:hover .rmt-brand-card__image img' => 'transform: scale({{SIZE}});',
            ],
        ] );

        $this->end_controls_section();

        // ---- Brand Name Style ----
        $this->start_controls_section( 'section_name_style', [
            'label' => __( 'Brand Name', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'name_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__name' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'name_hover_color', [
            'label'     => __( 'Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card:hover .rmt-brand-card__name' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'name_typography',
            'selector' => '{{WRAPPER}} .rmt-brand-card__name',
        ] );

        $this->add_responsive_control( 'name_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-brand-card__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Country Style (Style 2 only) ----
        $this->start_controls_section( 'section_country_style', [
            'label'     => __( 'Country', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'layout_style' => 'style-2' ],
        ] );

        $this->add_control( 'country_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__country' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'country_typography',
            'selector' => '{{WRAPPER}} .rmt-brand-card__country',
        ] );

        $this->add_responsive_control( 'country_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-brand-card__country' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ---- Product Count Style ----
        $this->start_controls_section( 'section_count_style', [
            'label'     => __( 'Product Count', 'rakmyat-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_count' => 'yes' ],
        ] );

        $this->add_control( 'count_color', [
            'label'     => __( 'Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6B7280',
            'selectors' => [
                '{{WRAPPER}} .rmt-brand-card__count' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'count_typography',
            'selector' => '{{WRAPPER}} .rmt-brand-card__count',
        ] );

        $this->add_responsive_control( 'count_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-brand-card__count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Build taxonomy query args
        $args = [
            'taxonomy'   => 'product_brand',
            'hide_empty' => $settings['hide_empty'] === 'yes',
            'number'     => $settings['limit'] ? intval( $settings['limit'] ) : 6,
            'orderby'    => $settings['orderby'],
            'order'      => $settings['order'],
        ];

        if ( ! empty( $settings['brands_include'] ) ) {
            $args['include'] = array_map( 'intval', $settings['brands_include'] );
        }

        if ( ! empty( $settings['brands_exclude'] ) ) {
            $args['exclude'] = array_map( 'intval', $settings['brands_exclude'] );
        }

        $brands = get_terms( $args );

        if ( is_wp_error( $brands ) || empty( $brands ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="rmt-brands-empty">';
                esc_html_e( 'No brands found. Add brands in Products > Brands.', 'rakmyat-core' );
                echo '</div>';
            }
            return;
        }

        $layout     = $settings['layout_style'];
        $thumb_key  = $settings['thumbnail_source'];
        $suffix     = $settings['count_suffix'];
        $use_format = $settings['number_format'] === 'yes';
        $show_count = $settings['show_count'] === 'yes';
        $show_image = $settings['show_image'] === 'yes';
        $name_tag   = in_array( $settings['name_html_tag'], [ 'h1','h2','h3','h4','h5','h6','p','span','div' ], true )
                      ? $settings['name_html_tag'] : 'h3';
        $hover_zoom = $settings['image_hover_zoom'] === 'yes';

        $grid_classes = 'rmt-brands-grid rmt-brands-' . esc_attr( $layout );
        if ( $hover_zoom ) {
            $grid_classes .= ' rmt-brands-hover-zoom';
        }
        ?>

        <div class="<?php echo esc_attr( $grid_classes ); ?>">
            <?php foreach ( $brands as $brand ) :
                // Get thumbnail
                $thumbnail_id = get_term_meta( $brand->term_id, $thumb_key, true );
                if ( ! $thumbnail_id ) {
                    $fallback_key = $thumb_key === 'brand_thumbnail_id' ? 'thumbnail_id' : 'brand_thumbnail_id';
                    $thumbnail_id = get_term_meta( $brand->term_id, $fallback_key, true );
                }

                $image_url = $thumbnail_id ? wp_get_attachment_image_url( intval( $thumbnail_id ), 'medium_large' ) : '';
                $image_alt = $thumbnail_id ? get_post_meta( intval( $thumbnail_id ), '_wp_attachment_image_alt', true ) : '';
                if ( ! $image_alt ) {
                    $image_alt = $brand->name;
                }

                $brand_link    = get_term_link( $brand, 'product_brand' );
                $count         = $brand->count;
                $count_display = $use_format ? number_format( $count ) : $count;
                $country       = get_term_meta( $brand->term_id, 'brand_country', true );
            ?>
            <div class="rmt-brands-grid__item">
                <a href="<?php echo esc_url( $brand_link ); ?>" class="rmt-brand-card">
                    <?php if ( $show_image ) : ?>
                    <div class="rmt-brand-card__image">
                        <?php if ( $image_url ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>"
                                 alt="<?php echo esc_attr( $image_alt ); ?>"
                                 loading="lazy">
                        <?php else : ?>
                            <div class="rmt-brand-card__placeholder">
                                <span><?php echo esc_html( mb_substr( $brand->name, 0, 1 ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="rmt-brand-card__info">
                        <<?php echo esc_html( $name_tag ); ?> class="rmt-brand-card__name">
                            <?php echo esc_html( $brand->name ); ?>
                        </<?php echo esc_html( $name_tag ); ?>>

                        <?php if ( $layout === 'style-2' && $country ) : ?>
                            <span class="rmt-brand-card__country"><?php echo esc_html( $country ); ?></span>
                        <?php endif; ?>

                        <?php if ( $show_count ) : ?>
                            <span class="rmt-brand-card__count">
                                <?php echo esc_html( $count_display ); ?>
                                <?php if ( $suffix ) : ?>
                                    <?php echo esc_html( $suffix ); ?>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php
    }
}
