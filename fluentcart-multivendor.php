<?php
/**
 * Plugin Name: FluentCart Multivendor
 * Description: Multivendor extension for FluentCart.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL2+
 * Text Domain: fluentcart-multivendor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class FluentCart_Multivendor {
    public function __construct() {
    add_action('init', [$this, 'register_vendor_role']);
    add_action('admin_menu', [$this, 'register_admin_pages']);
    register_activation_hook(__FILE__, [$this, 'activate_plugin']);

    // Include frontend vendor registration
    require_once plugin_dir_path(__FILE__) . 'includes/vendor-registration.php';
        // Include vendor dashboard
    require_once plugin_dir_path(__FILE__) . 'includes/vendor-dashboard.php';
}


    public function activate_plugin() {
        $this->register_vendor_role();
        flush_rewrite_rules();
    }

    public function register_vendor_role() {
        add_role('fluent_vendor', 'Vendor', [
            'read' => true,
            'edit_posts' => true,
            'upload_files' => true,
        ]);
    }

    public function register_admin_pages() {
        add_menu_page(
            __('Vendors', 'fluentcart-multivendor'),
            __('Vendors', 'fluentcart-multivendor'),
            'manage_options',
            'fluentcart-vendors',
            [$this, 'render_vendor_admin_page'],
            'dashicons-groups',
            56
        );
    }

    public function render_vendor_admin_page() {
        echo '<div class="wrap"><h1>' . esc_html__('Vendors', 'fluentcart-multivendor') . '</h1>';
        echo '<p>This is where vendor management tools will appear.</p></div>';
    }
}

new FluentCart_Multivendor();
