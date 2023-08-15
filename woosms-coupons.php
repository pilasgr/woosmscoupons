<?php
/*
Plugin Name: WooSMS Coupon
Description: Αυτόματη δημιουργία κουπονιών και αποστολή με sms στον πελάτη
Version: 1.0
Author: Pilas.Gr - Go Brand Yourself
*/

        // Include necessary WooCommerce files
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // Add the settings page to the admin menu
        function loyalty_coupons_add_settings_page() {
            add_menu_page(
                'WooSMS Coupon', 
                'WooSMS Coupon', 
                'manage_options',
                'loyalty-coupons',
                'loyalty_coupons_settings_page',
                'dashicons-tickets-alt'
    );
}
add_action( 'admin_menu', 'loyalty_coupons_add_settings_page' );


    // Display the plugin settings page
    function loyalty_coupons_settings_page() {
        // Check if the user has the required capability
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Save the settings if the form is submitted
        if ( isset( $_POST['loyalty_coupons_submit'] ) ) {
            update_option( 'loyalty_coupons_discount', sanitize_text_field( $_POST['loyalty_coupons_discount'] ) );
            update_option( 'loyalty_coupons_min_order_amount', sanitize_text_field( $_POST['loyalty_coupons_min_order_amount'] ) );
            update_option( 'loyalty_coupons_min_completed_order_amount', sanitize_text_field( $_POST['loyalty_coupons_min_completed_order_amount'] ) ); // New field
            update_option( 'loyalty_coupons_expiry_days', sanitize_text_field( $_POST['loyalty_coupons_expiry_days'] ) );
            update_option( 'loyalty_coupons_clicksend_username', sanitize_text_field( $_POST['loyalty_coupons_clicksend_username'] ) );
            update_option( 'loyalty_coupons_clicksend_api_key', sanitize_text_field( $_POST['loyalty_coupons_clicksend_api_key'] ) );
            update_option( 'loyalty_coupons_sender_number', sanitize_text_field( $_POST['loyalty_coupons_sender_number'] ) );
        }

        // Get the current settings
        $discount = get_option( 'loyalty_coupons_discount', '' );
        $min_order_amount = get_option( 'loyalty_coupons_min_order_amount', '' );
        $min_completed_order_amount = get_option( 'loyalty_coupons_min_completed_order_amount', '' ); // New field
        $expiry_days = get_option( 'loyalty_coupons_expiry_days', '' );
        $clicksend_username = get_option( 'loyalty_coupons_clicksend_username', '' );
        $clicksend_api_key = get_option( 'loyalty_coupons_clicksend_api_key', '' );
        $sender_number = get_option( 'loyalty_coupons_sender_number', '' );

        // Display the settings form
        ?>
        <div class="wrap">
    <h1 class="wp-heading-inline">Ρυθμίσεις WooSMS</h1>

    <?php if ( isset( $_POST['loyalty_coupons_submit'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Οι ρυθμίσεις σας αποθηκεύτηκαν!</strong></p>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <p>Καλώς ήλθατε στις ρυθμίσεις του WooSMS Coupon plugin. Εδώ μπορείτε να προσαρμόσετε τις επιλογές σας για τη δημιουργία και αποστολή κουπονιών με SMS στους πελάτες σας. Παρακαλούμε συμπληρώστε τα απαιτούμενα πεδία και κάντε κλικ στο κουμπί 'Αποθήκευση' για να αποθηκεύσετε τις ρυθμίσεις σας.</p>
        <form method="post" action="">
            <h2>Λογαριασμός clicksend</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="loyalty_coupons_clicksend_username">Όνομα χρήστη</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_clicksend_username" id="loyalty_coupons_clicksend_username" value="<?php echo esc_attr( $clicksend_username ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="loyalty_coupons_clicksend_api_key">Κλειδί API</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_clicksend_api_key" id="loyalty_coupons_clicksend_api_key" value="<?php echo esc_attr( $clicksend_api_key ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="loyalty_coupons_sender_number">Αποστολέας SMS</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_sender_number" id="loyalty_coupons_sender_number" value="<?php echo esc_attr( $sender_number ); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
    
            <h2>Επιλογές κουπονιού Woocommerce</h2>
    
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="loyalty_coupons_min_completed_order_amount">Ποσό ολοκληρωμένης παραγγελίας για έκδοση κουπονιού</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_min_completed_order_amount" id="loyalty_coupons_min_completed_order_amount" value="<?php echo esc_attr( $min_completed_order_amount ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="loyalty_coupons_discount">Ποσό έκπτωσης κουπονιού (σε ευρώ)</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_discount" id="loyalty_coupons_discount" value="<?php echo esc_attr( $discount ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="loyalty_coupons_min_order_amount">Ελάχιστο ποσό αγοράς για χρήση κουπονιού</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_min_order_amount" id="loyalty_coupons_min_order_amount" value="<?php echo esc_attr( $min_order_amount ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="loyalty_coupons_expiry_days">Σε πόσες ημέρες να λήγει το κουπόνι;</label></th>
                    <td>
                        <input type="text" name="loyalty_coupons_expiry_days" id="loyalty_coupons_expiry_days" value="<?php echo esc_attr( $expiry_days ); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
            <p class="submit">
            <input type="submit" name="loyalty_coupons_submit" id="loyalty_coupons_submit" class="button button-primary" value="Αποθήκευση ρυθμίσεων">
            </p>
            <p><img width="150px" src="https://www.pilas.gr/sign/logo.png" alt="Pilas.Gr Logo"></p>

        </form>
    </div>

        <?php
    }

    // Function to send SMS using ClickSend API
    function loyalty_coupons_send_sms( $recipient_phone, $message, $clicksend_username, $clicksend_api_key, $sender_number ) {
        // Remove HTML tags from the message
        $plain_message = strip_tags( $message );

        // Construct the API request
        $api_url = 'https://rest.clicksend.com/v3/sms/send';
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode( $clicksend_username . ':' . $clicksend_api_key ),
            'Content-Type' => 'application/json',
        );
        $sms_data = array(
            'messages' => array(
                array(
                    'from' => $sender_number,
                    'to' => $recipient_phone,
                    'body' => $plain_message,
                ),
            ),
        );
        $args = array(
            'headers' => $headers,
            'body' => json_encode( $sms_data ),
        );

        // Send the API request
        $response = wp_safe_remote_post( $api_url, $args );
    }

    // Function to generate unique coupon code
    function loyalty_coupons_generate_unique_coupon_code() {
        $coupon_code = substr( md5( uniqid() ), 0, 5 );
        return $coupon_code;
    }

// Function to create unique coupon
function loyalty_coupons_create_unique_coupon( $discount_amount, $expiry_days, $min_order_amount ) {
    // Coupon creation code goes here
    $coupon_code = loyalty_coupons_generate_unique_coupon_code();
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon',
    );
    $new_coupon_id = wp_insert_post( $coupon );
    update_post_meta( $new_coupon_id, 'discount_type', 'fixed_cart' );
    update_post_meta( $new_coupon_id, 'coupon_amount', $discount_amount );
    update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', strtotime( '+' . $expiry_days . ' days' ) );
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
    update_post_meta( $new_coupon_id, 'minimum_amount', $min_order_amount ); // Set the minimum order amount for the coupon

    // Return the generated coupon code
    return $coupon_code;
}


// Hook to send SMS and email on high value order
function loyalty_coupons_send_sms_and_email_on_high_value_order( $order_id ) {
    // Get order details
    $order = wc_get_order( $order_id );

    // Get the recipient phone number
    $recipient_phone = $order->get_billing_phone();

    // Check if the order is completed and total is greater than or equal to the minimum order amount
    $discount_amount = get_option( 'loyalty_coupons_discount', '' );
    $min_order_amount = get_option( 'loyalty_coupons_min_order_amount', '' );
    $min_completed_order_amount = get_option( 'loyalty_coupons_min_completed_order_amount', '' ); // New field
    $expiry_days = get_option( 'loyalty_coupons_expiry_days', '' );
    $clicksend_username = get_option( 'loyalty_coupons_clicksend_username', '' );
    $clicksend_api_key = get_option( 'loyalty_coupons_clicksend_api_key', '' );
    $sender_number = get_option( 'loyalty_coupons_sender_number', '' );

    // Here's the correction for the minimum completed order amount
    $order_total = $order->get_total(); // Get the total amount of the order
    if ( $order->is_paid() && $order->get_total() >= $min_order_amount && $order->get_total() >= $min_completed_order_amount ) {
        // Generate and create a unique coupon
        $coupon_code = loyalty_coupons_create_unique_coupon( $discount_amount, $min_order_amount, $expiry_days ); // Use min_order_amount here

        // Get the newly created coupon code
        $new_coupon = new WC_Coupon( $coupon_code );

        // Get the expiration date of the coupon
        $expiration_date = date_i18n( get_option( 'date_format' ), strtotime( '+' . $expiry_days . ' days' ) );

        // Generate the coupon code and replace &euro; with €
        $discount_amount_text = str_replace( '&euro;', '€', wc_price( $discount_amount ) );
        $min_order_amount_text = str_replace( '&euro;', '€', wc_price( $min_order_amount ) );

        // Construct SMS message
        $sms_message = 'Ευχαριστούμε για την αγορά σας! Χρησιμοποιήστε το κουπόνι "' . $coupon_code . '" για να πάρετε έκπτωση ' . $discount_amount_text . ' στην επόμενη παραγγελία σας με αξία από ' . $min_order_amount_text . ' και άνω. Το κουπόνι λήγει στις ' . $expiration_date . '.';

        // Send SMS
        loyalty_coupons_send_sms( $recipient_phone, $sms_message, $clicksend_username, $clicksend_api_key, $sender_number );

    }
}
add_action( 'woocommerce_order_status_completed', 'loyalty_coupons_send_sms_and_email_on_high_value_order' );


}

function custom_plugin_footer_text($footer_text) {
    $new_footer_text = 'Δημιουργήθηκε από το Pilas.Gr με τη βοήθεια τεχνητής νοημοσύνης - AI (ChatGPT)';
    return $new_footer_text;
}
add_filter('admin_footer_text', 'custom_plugin_footer_text');

?>
