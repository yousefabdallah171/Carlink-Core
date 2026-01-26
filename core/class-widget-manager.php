<?php
namespace RakmyatCore\Core;

if (!defined('ABSPATH')) exit;

class Widget_Manager {
    private $widgets = [];
    private static $instance = null;
    
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Updated to the modern 'register' hook
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories']);
        add_action('wp_enqueue_scripts', [$this, 'register_widget_assets']);
    }
    
    public function register_widgets($widgets_manager) {
        $this->load_widgets();
        foreach ($this->widgets as $widget_class) {
            if (class_exists($widget_class)) {
                $widgets_manager->register(new $widget_class());
            }
        }
    }
    
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'rakmyat-elements',
            [
                'title' => __('RakMyat Elements', 'rakmyat-core'),
                'icon' => 'fa fa-star',
            ]
        );
    }
    
    private function load_widgets() {
        $widgets_dir = RMT_PATH . 'elements/widgets/';
        if (!is_dir($widgets_dir)) return;
        
        $widget_files = glob($widgets_dir . '*.php');
        foreach ($widget_files as $widget_file) {
            require_once $widget_file;
            $widget_class = $this->get_widget_class_from_file($widget_file);
            if ($widget_class && class_exists($widget_class)) {
                $this->widgets[] = $widget_class;
            }
        }
    }
    
    private function get_widget_class_from_file($file_path) {
        $file_name = basename($file_path, '.php');
        // Transforms 'woo-category-search' to 'RMT_Woo_Category_Search_Widget'
        $class_name = 'RMT_' . str_replace('-', '_', ucwords($file_name, '-')) . '_Widget';
        return $class_name;
    }

    public function register_widget_assets() {
        $widgets_dir = RMT_PATH . 'elements/widgets/';
        $css_dir     = RMT_PATH . 'elements/widgets/assets/css/';
        $js_dir      = RMT_PATH . 'elements/widgets/assets/js/';
        $css_url     = RMT_URL . 'elements/widgets/assets/css/';
        $js_url      = RMT_URL . 'elements/widgets/assets/js/';

        $widget_files = glob($widgets_dir . '*.php');
        foreach ($widget_files as $widget_file) {
            $widget_name = basename($widget_file, '.php');

            // CSS Registration
            if (file_exists($css_dir . $widget_name . '.css')) {
                wp_register_style(
                    'rmt-' . $widget_name . '-css',
                    $css_url . $widget_name . '.css',
                    [],
                    filemtime($css_dir . $widget_name . '.css')
                );
            }
            // JS Registration
            if (file_exists($js_dir . $widget_name . '.js')) {
                wp_register_script(
                    'rmt-' . $widget_name,
                    $js_url . $widget_name . '.js',
                    ['jquery'],
                    filemtime($js_dir . $widget_name . '.js'),
                    true
                );
                // Enqueue wishlist script on all pages
                if ($widget_name === 'wishlist-icon') {
                    wp_enqueue_script('rmt-' . $widget_name);
                    if (file_exists($css_dir . $widget_name . '.css')) {
                        wp_enqueue_style('rmt-' . $widget_name . '-css');
                    }
                }
            }
        }
    }
}