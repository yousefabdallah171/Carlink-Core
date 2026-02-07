<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Order_Tracking {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register custom order statuses
        \add_action( 'init', [ $this, 'register_custom_order_statuses' ] );
        \add_filter( 'wc_order_statuses', [ $this, 'add_custom_order_statuses' ] );

        // Override the tracking template
        \add_filter( 'woocommerce_locate_template', [ $this, 'override_tracking_template' ], 10, 3 );

        // Add estimated delivery settings to WooCommerce > Settings > General
        \add_filter( 'woocommerce_general_settings', [ $this, 'add_estimated_delivery_settings' ] );
    }

    /**
     * Register custom order statuses: Shipped, Out For Delivery
     */
    public function register_custom_order_statuses() {
        \register_post_status( 'wc-shipped', [
            'label'                     => _x( 'Shipped', 'Order status', 'rakmyat-core' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>', 'rakmyat-core' ),
        ] );

        \register_post_status( 'wc-out-for-delivery', [
            'label'                     => _x( 'Out For Delivery', 'Order status', 'rakmyat-core' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Out For Delivery <span class="count">(%s)</span>', 'Out For Delivery <span class="count">(%s)</span>', 'rakmyat-core' ),
        ] );
    }

    /**
     * Add custom statuses to WooCommerce status list
     */
    public function add_custom_order_statuses( $order_statuses ) {
        $new_statuses = [];

        foreach ( $order_statuses as $key => $status ) {
            $new_statuses[ $key ] = $status;

            // Insert custom statuses after "Processing"
            if ( $key === 'wc-processing' ) {
                $new_statuses['wc-shipped']          = _x( 'Shipped', 'Order status', 'rakmyat-core' );
                $new_statuses['wc-out-for-delivery'] = _x( 'Out For Delivery', 'Order status', 'rakmyat-core' );
            }
        }

        return $new_statuses;
    }

    /**
     * Override the WooCommerce order tracking template
     */
    public function override_tracking_template( $template, $template_name, $template_path ) {
        if ( $template_name === 'order/tracking.php' ) {
            $plugin_template = RMT_PATH . 'templates/order/tracking.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Add estimated delivery settings to WooCommerce General settings
     */
    public function add_estimated_delivery_settings( $settings ) {
        $delivery_settings = [
            [
                'title' => __( 'Estimated Delivery', 'rakmyat-core' ),
                'type'  => 'title',
                'desc'  => __( 'Configure estimated delivery date display on order tracking page.', 'rakmyat-core' ),
                'id'    => 'rmt_estimated_delivery_options',
            ],
            [
                'title'   => __( 'Enable Estimated Delivery', 'rakmyat-core' ),
                'desc'    => __( 'Show estimated delivery date on order tracking page', 'rakmyat-core' ),
                'id'      => 'rmt_estimated_delivery_enabled',
                'default' => 'yes',
                'type'    => 'checkbox',
            ],
            [
                'title'             => __( 'Estimated Delivery Days', 'rakmyat-core' ),
                'desc'              => __( 'Number of days from order date to estimated delivery.', 'rakmyat-core' ),
                'id'                => 'rmt_estimated_delivery_days',
                'default'           => '14',
                'type'              => 'number',
                'custom_attributes' => [
                    'min'  => '1',
                    'step' => '1',
                ],
                'css'               => 'width: 80px;',
            ],
            [
                'type' => 'sectionend',
                'id'   => 'rmt_estimated_delivery_options',
            ],
        ];

        return array_merge( $settings, $delivery_settings );
    }

    /**
     * Get the current progress step (1-4) based on order status
     *
     * @param string $status Order status (without wc- prefix)
     * @return int Step number: 0 = pre-confirmation, 1-4 = steps, -1 = cancelled/refunded/failed
     */
    public static function get_order_progress_step( $status ) {
        $status_map = [
            'pending'          => 0,
            'on-hold'          => 0,
            'processing'       => 1,
            'shipped'          => 2,
            'out-for-delivery' => 3,
            'completed'        => 4,
            'cancelled'        => -1,
            'refunded'         => -1,
            'failed'           => -1,
        ];

        return isset( $status_map[ $status ] ) ? $status_map[ $status ] : 0;
    }
}
