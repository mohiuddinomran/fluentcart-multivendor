<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restrict orders list to orders containing products by the vendor
 */
function fcmv_restrict_vendor_orders_query($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $screen = get_current_screen();

    if ($screen && $screen->id === 'edit-shop_order') {
        $user = wp_get_current_user();

        if (in_array('fluent_vendor', (array) $user->roles)) {
            global $wpdb;

            // Get all product IDs authored by current vendor
            $vendor_products = get_posts([
                'post_type' => 'product',
                'author' => $user->ID,
                'fields' => 'ids',
                'posts_per_page' => -1,
            ]);

            if (empty($vendor_products)) {
                // No products, so no orders
                $query->set('post__in', [0]);
                return;
            }

            // Get order IDs containing these products
            $placeholders = implode(',', array_fill(0, count($vendor_products), '%d'));

            $sql = "
                SELECT DISTINCT order_items.order_id
                FROM {$wpdb->prefix}woocommerce_order_items AS order_items
                INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS item_meta
                    ON order_items.order_item_id = item_meta.order_item_id
                WHERE order_items.order_item_type = 'line_item'
                    AND item_meta.meta_key = '_product_id'
                    AND item_meta.meta_value IN ($placeholders)
            ";

            $prepared_sql = $wpdb->prepare($sql, $vendor_products);
            $order_ids = $wpdb->get_col($prepared_sql);

            if (empty($order_ids)) {
                // No orders found for vendor's products
                $query->set('post__in', [0]);
            } else {
                $query->set('post__in', $order_ids);
            }
        }
    }
}
add_action('pre_get_posts', 'fcmv_restrict_vendor_orders_query');
