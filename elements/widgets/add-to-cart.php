<?php
/**
 * Add to Cart Widget
 * Custom product add to cart with quantity, wishlist, and buy now
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Add_To_Cart_Widget extends Widget_Base {

    public function get_name() { return 'add-to-cart'; }
    public function get_title() { return __( 'Add to Cart', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-cart'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-add-to-cart-css' ]; }
    public function get_script_depends() { return [ 'rmt-add-to-cart' ]; }

    protected function register_controls() {

        // ============================================
        // CONTENT TAB
        // ============================================
        $this->start_controls_section('section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ]);

        $this->add_control('show_quantity', [
            'label' => __( 'Show Quantity Selector', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_add_to_cart', [
            'label' => __( 'Show Add to Cart', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('add_to_cart_text', [
            'label' => __( 'Add to Cart Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Add to Cart', 'rakmyat-core' ),
            'condition' => [ 'show_add_to_cart' => 'yes' ],
        ]);

        $this->add_control('show_wishlist', [
            'label' => __( 'Show Wishlist Button', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('wishlist_icon', [
            'label' => __( 'Wishlist Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'far fa-heart',
                'library' => 'fa-regular',
            ],
            'condition' => [ 'show_wishlist' => 'yes' ],
        ]);

        $this->add_control('wishlist_icon_active', [
            'label' => __( 'Wishlist Icon (Active)', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-heart',
                'library' => 'fa-solid',
            ],
            'condition' => [ 'show_wishlist' => 'yes' ],
        ]);

        $this->add_control('show_buy_now', [
            'label' => __( 'Show Buy Now', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('buy_now_text', [
            'label' => __( 'Buy Now Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Buy Now', 'rakmyat-core' ),
            'condition' => [ 'show_buy_now' => 'yes' ],
        ]);

        $this->add_control('default_quantity', [
            'label' => __( 'Default Quantity', 'rakmyat-core' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
        ]);

        $this->end_controls_section();

        // ============================================
        // STYLE TAB: LAYOUT
        // ============================================
        $this->start_controls_section('section_style_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('top_row_gap', [
            'label' => __( 'Top Row Gap', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 10 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-atc-top-row' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->add_control('rows_gap', [
            'label' => __( 'Gap Between Rows', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 10 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-widget' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // ============================================
        // STYLE TAB: QUANTITY SELECTOR
        // ============================================
        $this->start_controls_section('section_style_quantity', [
            'label' => __( 'Quantity Selector', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('qty_bg_color', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-selector' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('qty_border_color', [
            'label' => __( 'Border Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-selector' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('qty_border_width', [
            'label' => __( 'Border Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 1 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 5 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-qty-selector' => 'border-width: {{SIZE}}px;' ],
        ]);

        $this->add_control('qty_border_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 4 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-qty-selector' => 'border-radius: {{SIZE}}px;' ],
        ]);

        $this->add_control('qty_btn_bg_color', [
            'label' => __( 'Button Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#F3F4F6',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-btn' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('qty_btn_color', [
            'label' => __( 'Button Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('qty_btn_hover_bg', [
            'label' => __( 'Button Hover Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-btn:hover' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('qty_input_width', [
            'label' => __( 'Input Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 50 ],
            'range' => [ 'px' => [ 'min' => 30, 'max' => 100 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-qty-input' => 'width: {{SIZE}}px;' ],
        ]);

        $this->add_control('qty_btn_size', [
            'label' => __( 'Button Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 40 ],
            'range' => [ 'px' => [ 'min' => 30, 'max' => 60 ] ],
            'selectors' => [
                '{{WRAPPER}} .rmt-qty-btn' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('qty_height', [
            'label' => __( 'Selector Height', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 44 ],
            'range' => [ 'px' => [ 'min' => 30, 'max' => 60 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-qty-selector' => 'height: {{SIZE}}px;' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'qty_input_typo',
            'label' => __( 'Input Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-qty-input',
        ]);

        $this->add_control('qty_input_color', [
            'label' => __( 'Input Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-qty-input' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // ============================================
        // STYLE TAB: ADD TO CART BUTTON
        // ============================================
        $this->start_controls_section('section_style_add_to_cart', [
            'label' => __( 'Add to Cart Button', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('atc_bg_color', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#8A8F91',
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-btn' => 'background: {{VALUE}};' ],
        ]);

        $this->add_control('atc_use_gradient', [
            'label' => __( 'Use Gradient Background', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('atc_gradient_start', [
            'label' => __( 'Gradient Start Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(138, 143, 145, 0.3)',
            'condition' => [ 'atc_use_gradient' => 'yes' ],
        ]);

        $this->add_control('atc_gradient_end', [
            'label' => __( 'Gradient End Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#8A8F91',
            'condition' => [ 'atc_use_gradient' => 'yes' ],
        ]);

        $this->add_control('atc_text_color', [
            'label' => __( 'Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('atc_border_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 4 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-btn' => 'border-radius: {{SIZE}}px;' ],
        ]);

        $this->add_control('atc_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => 10, 'right' => 20, 'bottom' => 10, 'left' => 20, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'atc_typo',
            'label' => __( 'Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-add-to-cart-btn',
        ]);

        $this->add_control('atc_hover_bg', [
            'label' => __( 'Hover Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => [ '{{WRAPPER}} .rmt-add-to-cart-btn:hover' => 'background: {{VALUE}} !important;' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'atc_box_shadow',
            'label' => __( 'Box Shadow', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-add-to-cart-btn',
        ]);

        $this->end_controls_section();

        // ============================================
        // STYLE TAB: WISHLIST BUTTON
        // ============================================
        $this->start_controls_section('section_style_wishlist', [
            'label' => __( 'Wishlist Button', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('wishlist_bg_color', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('wishlist_border_color', [
            'label' => __( 'Border Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('wishlist_border_width', [
            'label' => __( 'Border Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 1 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 5 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn' => 'border-width: {{SIZE}}px;' ],
        ]);

        $this->add_control('wishlist_border_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 4 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn' => 'border-radius: {{SIZE}}px;' ],
        ]);

        $this->add_control('wishlist_icon_color', [
            'label' => __( 'Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('wishlist_icon_active_color', [
            'label' => __( 'Icon Active Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#EF4444',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn.active' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('wishlist_hover_bg', [
            'label' => __( 'Hover Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#F3F4F6',
            'selectors' => [ '{{WRAPPER}} .rmt-wishlist-btn:hover' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('wishlist_size', [
            'label' => __( 'Button Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 44 ],
            'range' => [ 'px' => [ 'min' => 30, 'max' => 60 ] ],
            'selectors' => [
                '{{WRAPPER}} .rmt-wishlist-btn' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('wishlist_icon_size', [
            'label' => __( 'Icon Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 20 ],
            'range' => [ 'px' => [ 'min' => 12, 'max' => 40 ] ],
            'selectors' => [
                '{{WRAPPER}} .rmt-wishlist-btn i' => 'font-size: {{SIZE}}px;',
                '{{WRAPPER}} .rmt-wishlist-btn svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        // ============================================
        // STYLE TAB: BUY NOW BUTTON
        // ============================================
        $this->start_controls_section('section_style_buy_now', [
            'label' => __( 'Buy Now Button', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('bn_bg_color', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-buy-now-btn' => 'background: {{VALUE}};' ],
        ]);

        $this->add_control('bn_use_gradient', [
            'label' => __( 'Use Gradient Background', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('bn_gradient_start', [
            'label' => __( 'Gradient Start Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'condition' => [ 'bn_use_gradient' => 'yes' ],
        ]);

        $this->add_control('bn_gradient_end', [
            'label' => __( 'Gradient End Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(58, 95, 121, 0.4)',
            'condition' => [ 'bn_use_gradient' => 'yes' ],
        ]);

        $this->add_control('bn_text_color', [
            'label' => __( 'Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-buy-now-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('bn_border_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 4 ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-buy-now-btn' => 'border-radius: {{SIZE}}px;' ],
        ]);

        $this->add_control('bn_padding', [
            'label' => __( 'Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-buy-now-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'bn_typo',
            'label' => __( 'Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-buy-now-btn',
        ]);

        $this->add_control('bn_hover_bg', [
            'label' => __( 'Hover Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#2C4A5C',
            'selectors' => [ '{{WRAPPER}} .rmt-buy-now-btn:hover' => 'background: {{VALUE}} !important;' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'bn_box_shadow',
            'label' => __( 'Box Shadow', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-buy-now-btn',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get current product
        global $product;

        if ( ! $product ) {
            $product = wc_get_product( get_the_ID() );
        }

        if ( ! $product ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<p style="padding: 20px; background: #f0f0f0; text-align: center;">Add to Cart Widget - Works on product pages</p>';
            }
            return;
        }

        $product_id = $product->get_id();
        $product_type = $product->get_type();
        $is_purchasable = $product->is_purchasable();
        $is_in_stock = $product->is_in_stock();

        // Check if in wishlist (WCBoost)
        $in_wishlist = false;
        if ( class_exists( '\WCBoost\Wishlist\Helper' ) ) {
            try {
                $wishlist = \WCBoost\Wishlist\Helper::get_wishlist();
                if ( $wishlist ) {
                    $items = $wishlist->get_items();
                    foreach ( $items as $item ) {
                        if ( $item->get_product_id() == $product_id ) {
                            $in_wishlist = true;
                            break;
                        }
                    }
                }
            } catch ( \Exception $e ) {
                $in_wishlist = false;
            }
        }

        // Build gradient styles
        $atc_style = '';
        if ( $settings['atc_use_gradient'] === 'yes' ) {
            $atc_style = 'background: linear-gradient(270deg, ' . $settings['atc_gradient_start'] . ' 0%, ' . $settings['atc_gradient_end'] . ' 100%);';
        }

        $bn_style = '';
        if ( $settings['bn_use_gradient'] === 'yes' ) {
            $bn_style = 'background: linear-gradient(90deg, ' . $settings['bn_gradient_start'] . ' 0%, ' . $settings['bn_gradient_end'] . ' 100%);';
        }

        ?>
        <div class="rmt-add-to-cart-widget" data-product-id="<?php echo esc_attr( $product_id ); ?>">

            <!-- Top Row: Quantity + Add to Cart + Wishlist -->
            <div class="rmt-atc-top-row">

                <?php if ( $settings['show_quantity'] === 'yes' && $is_purchasable && $is_in_stock ) : ?>
                <div class="rmt-qty-selector">
                    <button type="button" class="rmt-qty-btn rmt-qty-minus" aria-label="Decrease quantity">
                        <svg width="12" height="2" viewBox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <input type="number" class="rmt-qty-input" value="<?php echo esc_attr( $settings['default_quantity'] ); ?>" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : 999 ); ?>" step="1" inputmode="numeric">
                    <button type="button" class="rmt-qty-btn rmt-qty-plus" aria-label="Increase quantity">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <?php endif; ?>

                <?php if ( $settings['show_add_to_cart'] === 'yes' && $is_purchasable && $is_in_stock ) : ?>
                <button type="button" class="rmt-add-to-cart-btn" style="<?php echo esc_attr( $atc_style ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                    <span class="rmt-btn-text"><?php echo esc_html( $settings['add_to_cart_text'] ); ?></span>
                    <span class="rmt-btn-loading" style="display:none;">
                        <svg class="rmt-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v4m0 12v4m10-10h-4M6 12H2m15.07-5.07l-2.83 2.83M9.76 14.24l-2.83 2.83m11.14 0l-2.83-2.83M9.76 9.76L6.93 6.93"/>
                        </svg>
                    </span>
                </button>
                <?php endif; ?>

                <?php if ( $settings['show_wishlist'] === 'yes' ) : ?>
                <button type="button" class="rmt-wishlist-btn <?php echo $in_wishlist ? 'active' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php echo $in_wishlist ? 'Remove from wishlist' : 'Add to wishlist'; ?>">
                    <span class="rmt-wishlist-icon-default" <?php echo $in_wishlist ? 'style="display:none;"' : ''; ?>>
                        <?php Icons_Manager::render_icon( $settings['wishlist_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                    <span class="rmt-wishlist-icon-active" <?php echo !$in_wishlist ? 'style="display:none;"' : ''; ?>>
                        <?php Icons_Manager::render_icon( $settings['wishlist_icon_active'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                </button>
                <?php endif; ?>

            </div>

            <!-- Bottom Row: Buy Now -->
            <?php if ( $settings['show_buy_now'] === 'yes' && $is_purchasable && $is_in_stock ) : ?>
            <button type="button" class="rmt-buy-now-btn" style="<?php echo esc_attr( $bn_style ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                <span class="rmt-btn-text"><?php echo esc_html( $settings['buy_now_text'] ); ?></span>
            </button>
            <?php endif; ?>

            <?php if ( ! $is_in_stock ) : ?>
            <p class="rmt-out-of-stock-notice"><?php esc_html_e( 'Out of Stock', 'rakmyat-core' ); ?></p>
            <?php endif; ?>

        </div>
        <?php
    }
}
