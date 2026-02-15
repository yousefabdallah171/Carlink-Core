<?php
/**
 * Icon Category Grid Widget
 * Displays WooCommerce product categories with icons dynamically
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Icon_Category_Grid_Widget extends Widget_Base {

    public function get_name() { return 'icon-category-grid'; }

    public function get_title() { return __( 'Icon Category Grid', 'rakmyat-core' ); }

    public function get_icon() { return 'eicon-apps'; }

    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-icon-category-grid-css' ]; }

    public function get_keywords() { return [ 'icon', 'category', 'grid', 'neumorphic', 'woocommerce', 'product' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB
         * ============================================================ */

        // ---- Query Controls ----
        $this->start_controls_section( 'section_query', [
            'label' => __( 'Query', 'rakmyat-core' ),
        ] );

        $this->add_control( 'data_source', [
            'label'   => __( 'Data Source', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'dynamic',
            'options' => [
                'dynamic' => __( 'WooCommerce Categories', 'rakmyat-core' ),
                'manual'  => __( 'Manual (Custom Items)', 'rakmyat-core' ),
            ],
        ] );

        // ---- Dynamic Categories ----
        $this->add_control( 'hide_empty', [
            'label'     => __( 'Hide Empty Categories', 'rakmyat-core' ),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'yes',
            'condition' => [ 'data_source' => 'dynamic' ],
        ] );

        $this->add_control( 'category_limit', [
            'label'       => __( 'Limit Categories', 'rakmyat-core' ),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 10,
            'min'         => 1,
            'max'         => 100,
            'condition'   => [ 'data_source' => 'dynamic' ],
        ] );

        $this->add_control( 'category_orderby', [
            'label'     => __( 'Order By', 'rakmyat-core' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'name',
            'options'   => [
                'name'  => __( 'Name', 'rakmyat-core' ),
                'count' => __( 'Product Count', 'rakmyat-core' ),
                'id'    => __( 'Term ID', 'rakmyat-core' ),
                'slug'  => __( 'Slug', 'rakmyat-core' ),
            ],
            'condition' => [ 'data_source' => 'dynamic' ],
        ] );

        $this->add_control( 'category_order', [
            'label'     => __( 'Order Direction', 'rakmyat-core' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'asc',
            'options'   => [
                'asc'  => __( 'Ascending', 'rakmyat-core' ),
                'desc' => __( 'Descending', 'rakmyat-core' ),
            ],
            'condition' => [ 'data_source' => 'dynamic' ],
        ] );

        // Get all categories for include/exclude controls
        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'number'     => 100,
        ] );

        $cat_options = [];
        if ( ! is_wp_error( $categories ) ) {
            foreach ( $categories as $cat ) {
                $cat_options[ $cat->term_id ] = $cat->name;
            }
        }

        $this->add_control( 'include_categories', [
            'label'       => __( 'Include Categories', 'rakmyat-core' ),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $cat_options,
            'multiple'    => true,
            'condition'   => [ 'data_source' => 'dynamic' ],
            'description' => __( 'Leave empty to include all categories.', 'rakmyat-core' ),
        ] );

        $this->add_control( 'exclude_categories', [
            'label'     => __( 'Exclude Categories', 'rakmyat-core' ),
            'type'      => Controls_Manager::SELECT2,
            'options'   => $cat_options,
            'multiple'  => true,
            'condition' => [ 'data_source' => 'dynamic' ],
        ] );

        $this->end_controls_section();

        // ---- Layout ----
        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
        ] );

        $this->add_responsive_control( 'columns', [
            'label'          => __( 'Columns', 'rakmyat-core' ),
            'type'           => Controls_Manager::SELECT,
            'default'        => '5',
            'tablet_default' => '3',
            'mobile_default' => '2',
            'options'        => [
                '1' => '1', '2' => '2', '3' => '3',
                '4' => '4', '5' => '5', '6' => '6',
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ] );

        $this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Grid Gap', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 60 ],
            ],
            'default'   => [ 'size' => 20, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'content_alignment', [
            'label'   => __( 'Alignment', 'rakmyat-core' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'rakmyat-core' ), 'icon' => 'eicon-text-align-left' ],
                'center'     => [ 'title' => __( 'Center', 'rakmyat-core' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => __( 'Right', 'rakmyat-core' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'align-items: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'title_html_tag', [
            'label'   => __( 'Title HTML Tag', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
                'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4',
                'h5' => 'H5', 'h6' => 'H6', 'p' => 'p',
                'span' => 'span', 'div' => 'div',
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
            'default'   => '#F2F2F2',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Padding', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [
                'top' => '24', 'right' => '16', 'bottom' => '20', 'left' => '16',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'card_border_radius', [
            'label'      => __( 'Border Radius', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [
                'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16',
                'unit' => 'px', 'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'card_min_height', [
            'label'      => __( 'Min Height', 'rakmyat-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'neumorphic_style', [
            'label'        => __( 'Enable Neumorphic Shadow', 'rakmyat-core' ),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'separator'    => 'before',
            'prefix_class' => 'rmt-neumorphic-',
            'return_value' => 'yes',
        ] );

        $this->add_control( 'neumorphic_blur_amount', [
            'label'     => __( 'Backdrop Blur (px)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'   => [ 'size' => 45, 'unit' => 'px' ],
            'condition' => [ 'neumorphic_style' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
            ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'      => 'card_custom_shadow',
            'selector'  => '{{WRAPPER}} .rmt-icon-cat-card__inner',
            'condition' => [ 'neumorphic_style' => '' ],
        ] );

        $this->add_control( 'card_hover_bg_color', [
            'label'     => __( 'Hover Background', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_hover_transform', [
            'label'     => __( 'Hover Lift (px)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 10, 'step' => 1 ] ],
            'default'   => [ 'size' => 2 ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
            ],
        ] );

        $this->add_control( 'card_transition', [
            'label'     => __( 'Transition Duration (ms)', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ] ],
            'default'   => [ 'size' => 300 ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner' => 'transition-duration: {{SIZE}}ms;',
            ],
        ] );

        $this->end_controls_section();

        // ---- Icon Style ----
        $this->start_controls_section( 'section_icon_style', [
            'label' => __( 'Icon / Image', 'rakmyat-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label'     => __( 'Icon/Image Size', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 30, 'max' => 120 ] ],
            'default'   => [ 'size' => 64, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_spacing', [
            'label'     => __( 'Spacing Below', 'rakmyat-core' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'   => [ 'size' => 14, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'image_object_fit', [
            'label'   => __( 'Image Fit', 'rakmyat-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover'   => __( 'Cover', 'rakmyat-core' ),
                'contain' => __( 'Contain', 'rakmyat-core' ),
                'fill'    => __( 'Fill', 'rakmyat-core' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__icon-wrap img' => 'object-fit: {{VALUE}};',
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
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'title_hover_color', [
            'label'     => __( 'Hover Color', 'rakmyat-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rmt-icon-cat-card__inner:hover .rmt-icon-cat-card__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .rmt-icon-cat-card__title',
            'fields_options' => [
                'font_size'   => [ 'default' => [ 'size' => 14, 'unit' => 'px' ] ],
                'font_weight' => [ 'default' => '600' ],
            ],
        ] );

        $this->add_responsive_control( 'title_margin', [
            'label'      => __( 'Margin', 'rakmyat-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .rmt-icon-cat-card__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $title_tag = in_array( $settings['title_html_tag'], [ 'h2','h3','h4','h5','h6','p','span','div' ], true )
                     ? $settings['title_html_tag'] : 'h3';

        // Get categories
        if ( $settings['data_source'] === 'dynamic' ) {
            $categories = $this->get_dynamic_categories( $settings );
        } else {
            $categories = [];
        }

        if ( empty( $categories ) ) {
            echo '<div class="rmt-icon-cat-empty">' . esc_html__( 'No categories found.', 'rakmyat-core' ) . '</div>';
            return;
        }

        ?>
        <div class="rmt-icon-cat-grid">
            <?php foreach ( $categories as $category ) :
                $cat_link = get_term_link( $category->term_id, 'product_cat' );
                if ( is_wp_error( $cat_link ) ) $cat_link = '';

                $icon_id = get_term_meta( $category->term_id, 'product_cat_icon_id', true );
                $icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, 'medium' ) : '';
            ?>
            <div class="rmt-icon-cat-grid__item">
                <a href="<?php echo esc_url( $cat_link ); ?>" class="rmt-icon-cat-card__inner">

                    <div class="rmt-icon-cat-card__icon-wrap">
                        <?php if ( $icon_url ) : ?>
                            <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
                        <?php else : ?>
                            <div class="rmt-icon-cat-card__placeholder">
                                <span><?php echo esc_html( substr( $category->name, 0, 1 ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <<?php echo esc_html( $title_tag ); ?> class="rmt-icon-cat-card__title">
                        <?php echo esc_html( $category->name ); ?>
                    </<?php echo esc_html( $title_tag ); ?>>

                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Get dynamic WooCommerce categories
     */
    private function get_dynamic_categories( $settings ) {
        $args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => $settings['hide_empty'] === 'yes',
            'number'     => intval( $settings['category_limit'] ),
            'orderby'    => $settings['category_orderby'],
            'order'      => strtoupper( $settings['category_order'] ),
        ];

        // Include specific categories
        if ( ! empty( $settings['include_categories'] ) ) {
            $args['include'] = $settings['include_categories'];
        }

        // Exclude specific categories
        if ( ! empty( $settings['exclude_categories'] ) ) {
            $args['exclude'] = $settings['exclude_categories'];
        }

        $categories = get_terms( $args );

        return is_wp_error( $categories ) ? [] : $categories;
    }
}
