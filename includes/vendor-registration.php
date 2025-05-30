<?php
/**
 * Frontend Vendor Registration Form Shortcode
 */

function fcmv_vendor_registration_form() {
    if (is_user_logged_in()) {
        return '<p>You are already logged in.</p>';
    }

    ob_start();

    if (isset($_POST['fcmv_register_vendor'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        $errors = new WP_Error();

        if (empty($username) || empty($email) || empty($password)) {
            $errors->add('field', 'All fields are required.');
        }

        if (!is_email($email)) {
            $errors->add('email_invalid', 'Email is not valid.');
        }

        if (username_exists($username)) {
            $errors->add('username_exists', 'Username already exists.');
        }

        if (email_exists($email)) {
            $errors->add('email_exists', 'Email already registered.');
        }

        if (empty($errors->errors)) {
            $user_id = wp_create_user($username, $password, $email);
            wp_update_user(['ID' => $user_id, 'role' => 'fluent_vendor']);

            echo '<p>Registration successful. Please <a href="' . wp_login_url() . '">login</a>.</p>';
            return ob_get_clean();
        } else {
            foreach ($errors->get_error_messages() as $error) {
                echo '<p style="color:red;">' . esc_html($error) . '</p>';
            }
        }
    }

    ?>
    <form method="post">
        <p>
            <label for="username">Username</label><br />
            <input type="text" name="username" required />
        </p>
        <p>
            <label for="email">Email</label><br />
            <input type="email" name="email" required />
        </p>
        <p>
            <label for="password">Password</label><br />
            <input type="password" name="password" required />
        </p>
        <p>
            <input type="submit" name="fcmv_register_vendor" value="Register as Vendor" />
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('fcmv_vendor_registration', 'fcmv_vendor_registration_form');
