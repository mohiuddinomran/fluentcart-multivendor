<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function fcmv_restrict_vendor_products_query($query) {
    if (!is_admin()) {
        return;
    }

    $screen = get_current_screen();

    // Only modify product listing page
    if ($screen && $screen->post_type === 'product' && $screen->base === 'edit') {
        $user = wp_get_current_user();

        if (in_array('fluent_vendor', (array) $user->roles)) {
            // Restrict products to those authored by current vendor
            $query->set('author', $user->ID);
        }
    }
}
add_action('pre_get_posts', 'fcmv_restrict_vendor_products_query');
