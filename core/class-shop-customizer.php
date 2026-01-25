<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shop_Customizer {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'customize_register', [ $this, 'register_customizer_settings' ] );
    }

    /**
     * Register Customizer settings for shop sidebar
     */
    public function register_customizer_settings( $wp_customize ) {

        // Add Section
        $wp_customize->add_section( 'rmt_shop_sidebar', [
            'title'    => __( 'Shop Sidebar Filters', 'rakmyat-core' ),
            'priority' => 30,
        ]);

        // Enable Custom Sidebar
        $wp_customize->add_setting( 'rmt_enable_custom_sidebar', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_custom_sidebar', [
            'label'   => __( 'Enable Custom Shop Sidebar', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Filters Title
        $wp_customize->add_setting( 'rmt_filters_title', [
            'default'           => __( 'Filters', 'rakmyat-core' ),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control( 'rmt_filters_title', [
            'label'   => __( 'Filters Title', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'text',
        ]);

        // Enable Category Filter
        $wp_customize->add_setting( 'rmt_enable_category_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_category_filter', [
            'label'   => __( 'Enable Category Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Enable Brand Filter
        $wp_customize->add_setting( 'rmt_enable_brand_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_brand_filter', [
            'label'   => __( 'Enable Brand Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Enable Price Filter
        $wp_customize->add_setting( 'rmt_enable_price_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_price_filter', [
            'label'   => __( 'Enable Price Range Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Enable Availability Filter
        $wp_customize->add_setting( 'rmt_enable_availability_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_availability_filter', [
            'label'   => __( 'Enable Availability Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Enable Collections Filter
        $wp_customize->add_setting( 'rmt_enable_collections_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_collections_filter', [
            'label'   => __( 'Enable Collections Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);

        // Collections Category Selection
        $wp_customize->add_setting( 'rmt_collections_category', [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'rmt_collections_category', [
            'label'       => __( 'Collections Parent Category', 'rakmyat-core' ),
            'description' => __( 'Select a parent category to show as Collections', 'rakmyat-core' ),
            'section'     => 'rmt_shop_sidebar',
            'type'        => 'select',
            'choices'     => $this->get_product_categories(),
        ]));

        // Enable Ratings Filter
        $wp_customize->add_setting( 'rmt_enable_ratings_filter', [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);

        $wp_customize->add_control( 'rmt_enable_ratings_filter', [
            'label'   => __( 'Enable Ratings Filter', 'rakmyat-core' ),
            'section' => 'rmt_shop_sidebar',
            'type'    => 'checkbox',
        ]);
    }

    /**
     * Get product categories for customizer dropdown
     */
    private function get_product_categories() {
        $categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => 0,
        ]);

        $options = [ '' => __( '-- Select Category --', 'rakmyat-core' ) ];

        if ( ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $options[ $category->term_id ] = $category->name;
            }
        }

        return $options;
    }
}
