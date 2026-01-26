<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Global_Assets {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Enqueue global plugin assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_global_assets' ] );
    }

    /**
     * Enqueue global assets that apply to all pages
     */
    public function enqueue_global_assets() {
        // Enqueue global breadcrumb CSS
        wp_enqueue_style(
            'rmt-breadcrumbs',
            RMT_URL . 'assets/css/breadcrumbs.css',
            [],
            filemtime( RMT_PATH . 'assets/css/breadcrumbs.css' )
        );
    }
}
