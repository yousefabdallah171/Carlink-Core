<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Woo_Category_Search_Widget extends Widget_Base {

    public function get_name() { return 'woo-category-search'; }
    public function get_title() { return __( 'Woo Category Search', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-search'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    // Tell Elementor to load the registered CSS/JS handle for this widget
    public function get_style_depends() { return [ 'rmt-woo-category-search-css' ]; }
    public function get_script_depends() { return [ 'rmt-woo-category-search' ]; }

    protected function register_controls() {
        // CONTENT SECTION
        $this->start_controls_section('content_section', [
            'label' => __( 'Search Settings', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('placeholder', [
            'label' => __( 'Placeholder', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Search for auto parts...', 'rakmyat-core' ),
        ]);

        $this->add_control('search_icon', [
            'label' => __( 'Button Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-search', 'library' => 'fa-solid' ],
        ]);

        $this->add_control('all_text', [
            'label' => __( 'All Categories Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'ALL', 'rakmyat-core' ),
        ]);

        $this->end_controls_section();

        // STYLE SECTION: CONTAINER
        $this->start_controls_section('style_container', [
            'label' => __( 'Container', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('border_radius', [
            'label' => __( 'Border Radius', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default' => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-amazon-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}} .rmt-amazon-search',
        ]);

        $this->end_controls_section();

        // STYLE SECTION: DROPDOWN
        $this->start_controls_section('style_dropdown', [
            'label' => __( 'Category Dropdown', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('drop_bg', [
            'label' => __( 'Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E9ECEF',
            'selectors' => [ '{{WRAPPER}} .rmt-cat-col' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('dropdown_width', [
            'label' => __( 'Dropdown Width', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [
                'px' => [ 'min' => 50, 'max' => 300 ],
                '%' => [ 'min' => 10, 'max' => 100 ],
            ],
            'default' => [ 'size' => 90, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-cat-col' => 'width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('dropdown_icon', [
            'label' => __( 'Dropdown Icon', 'rakmyat-core' ),
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-chevron-down', 'library' => 'fa-solid' ],
        ]);

        $this->add_control('dropdown_icon_color', [
            'label' => __( 'Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#333',
            'selectors' => [ '{{WRAPPER}} .rmt-dropdown-icon i, {{WRAPPER}} .rmt-dropdown-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('dropdown_icon_size', [
            'label' => __( 'Icon Size', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 10, 'max' => 30 ] ],
            'default' => [ 'size' => 14, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-dropdown-icon i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .rmt-dropdown-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;' ],
        ]);

        $this->add_responsive_control('dropdown_padding', [
            'label' => __( 'Dropdown Padding', 'rakmyat-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default' => [ 'top' => '0', 'right' => '15', 'bottom' => '0', 'left' => '20', 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .rmt-cat-col' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_control('drop_color', [
            'label' => __( 'Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#1A202C',
            'selectors' => [ 
                '{{WRAPPER}} .rmt-cat-col select' => 'color: {{VALUE}};',
                '{{WRAPPER}} .rmt-cat-col::after' => 'border-top-color: {{VALUE}};' 
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'drop_typography',
            'selector' => '{{WRAPPER}} .rmt-cat-col select',
        ]);

        $this->end_controls_section();

        // STYLE SECTION: BUTTON
        $this->start_controls_section('style_button', [
            'label' => __( 'Search Button', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_bg', [
            'label' => __( 'Background Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#1A202C',
            'selectors' => [ '{{WRAPPER}} .rmt-btn-col' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_icon_color', [
            'label' => __( 'Icon Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => [ '{{WRAPPER}} .rmt-btn-col i, {{WRAPPER}} .rmt-btn-col svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
        ?>
        <form role="search" method="get" class="rmt-amazon-search" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="hidden" name="post_type" value="product">
            
            <div class="rmt-cat-col">
                <select name="product_cat">
                    <option value="" selected="selected"><?php echo esc_html($settings['all_text']); ?></option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="rmt-dropdown-icon">
                    <?php Icons_Manager::render_icon( $settings['dropdown_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            </div>

            <input type="text" name="s" class="rmt-input-col" 
                placeholder="<?php echo esc_attr($settings['placeholder']); ?>" 
                value="<?php echo get_search_query(); ?>">

            <button type="submit" class="rmt-btn-col">
                <?php Icons_Manager::render_icon( $settings['search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </button>
        </form>
        <?php
    }
}