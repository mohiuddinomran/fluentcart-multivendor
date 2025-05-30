<?php
// File: includes/vendor-dashboard.php

function fcmv_vendor_dashboard_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>You must be logged in as a vendor to access the dashboard.</p>';
    }

    $user = wp_get_current_user();

    if (!in_array('fluent_vendor', (array) $user->roles)) {
        return '<p>You are not authorized to access the vendor dashboard.</p>';
    }

    ob_start();
    ?>
    <div class="vendor-dashboard">
        <h2>Welcome, <?php echo esc_html($user->display_name); ?></h2>

        <ul>
            <li><a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" target="_blank">Add New Product</a></li>
            <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>" target="_blank">Manage My Products</a></li>
            <li><a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" target="_blank">View My Orders</a></li>
            <li><a href="<?php echo admin_url('profile.php'); ?>" target="_blank">Edit My Profile</a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fcmv_vendor_dashboard', 'fcmv_vendor_dashboard_shortcode');
