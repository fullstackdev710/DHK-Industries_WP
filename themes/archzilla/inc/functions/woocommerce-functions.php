<?php
/**
 * Cart Title
 * 
 * Hooks: woocommerce_before_cart_table
 * 
 * @since 1.0.0
 */
add_action( 'woocommerce_before_cart_table', 'archzilla_cart_title' );

if ( ! function_exists( 'archzilla_cart_title' ) ) {
    function archzilla_cart_title() {
        echo '<h2>'. esc_html__( 'Cart', 'archzilla' ) .'</h2>';
    }
}

/**
 * Wrap Checkout Totals
 * 
 * Hooks: woocommerce_checkout_before_order_review_heading, woocommerce_checkout_after_order_review
 * 
 * @since 1.0.0
 */
add_action( 'woocommerce_checkout_before_order_review_heading', 'archzilla_checkout_before_totals' );
add_action( 'woocommerce_checkout_after_order_review', 'archzilla_checkout_after_totals' );

if ( ! function_exists( 'archzilla_checkout_before_totals' ) ) {
    function archzilla_checkout_before_totals() {
        echo '<div class="n-checkout-totals">';
    }
}

if ( ! function_exists( 'archzilla_checkout_after_totals' ) ) {
    function archzilla_checkout_after_totals() {
        echo '</div>';
    }
}

/**
 * Product Thumbnail Sizes
 * 
 * Hooks: woocommerce_gallery_thumbnail_size
 * 
 * @since 1.0.0
 */
add_action( 'woocommerce_gallery_thumbnail_size', 'archzilla_product_thumbnail_sizes' );

if ( ! function_exists( 'archzilla_product_thumbnail_sizes' )  ) {
    function archzilla_product_thumbnail_sizes() {
        return [ 300, 300 ];
    }
}


/**
 * Thumbnails in Grouped Product
 * 
 * Hooks: woocommerce_grouped_product_list_before_quantity
 * 
 * @since 1.0.0
 */
add_action( 'woocommerce_grouped_product_list_before_quantity', 'archzilla_product_grouped_thumbnails' );

if ( ! function_exists( 'archzilla_product_grouped_thumbnails' ) ) {
    function archzilla_product_grouped_thumbnails( $product ) {
        $image_size = array( 120, 120 );
        $attachment_id = get_post_meta( $product->get_id(), '_thumbnail_id', true );
        $link = get_the_permalink( $product->get_id() );
        ?>
        <td class="woocommerce-grouped-product-list-item__thumb">
            <a href="<?php echo esc_url( $link ); ?>" > <?php echo wp_get_attachment_image( $attachment_id, $image_size ); ?> </a>
        </td>
        <?php
    }
}

/**
 * Checkout Fields
 * 
 * @since 1.0.0
 */
add_filter( 'woocommerce_checkout_fields', 'archzilla_change_woo_placeholders' );
if ( ! function_exists( 'archzilla_change_woo_placeholders' ) ) {
    function archzilla_change_woo_placeholders( $fields ) {

        // Add placeholders
        $fields['billing']['billing_first_name']['placeholder'] = esc_html__( 'First Name', 'archzilla' );
        $fields['billing']['billing_last_name']['placeholder'] = esc_html__( 'Last Name', 'archzilla' );
        $fields['billing']['billing_company']['placeholder'] = esc_html__( 'Company name', 'archzilla' );
        $fields['billing']['billing_country']['placeholder'] = esc_html__( 'Country / Region', 'archzilla' );
        $fields['billing']['billing_city']['placeholder'] = esc_html__( 'Town / City', 'archzilla' );
        $fields['billing']['billing_state']['placeholder'] = esc_html__( 'County', 'archzilla' );
        $fields['billing']['billing_postcode']['placeholder'] = esc_html__( 'Postcode', 'archzilla' );
        $fields['billing']['billing_phone']['placeholder'] = esc_html__( 'Phone', 'archzilla' );
        $fields['billing']['billing_email']['placeholder'] = esc_html__( 'Email address', 'archzilla' );

        $fields['shipping']['shipping_first_name']['placeholder'] = esc_html__( 'First Name', 'archzilla' );
        $fields['shipping']['shipping_last_name']['placeholder'] = esc_html__( 'Last Name', 'archzilla' );
        $fields['shipping']['shipping_company']['placeholder'] = esc_html__( 'Company name', 'archzilla' );

        // Remove Labels
        unset($fields['billing']['billing_first_name']['label']);
        unset($fields['billing']['billing_last_name']['label']);
        unset($fields['billing']['billing_company']['label']);
        unset($fields['billing']['billing_country']['label']);
        unset($fields['billing']['billing_address_1']['label']);
        unset($fields['billing']['billing_city']['label']);
        unset($fields['billing']['billing_state']['label']);
        unset($fields['billing']['billing_postcode']['label']);
        unset($fields['billing']['billing_phone']['label']);
        unset($fields['billing']['billing_email']['label']);  

        // Shipping Labels
        unset($fields['shipping']['shipping_first_name']['label']);  
        unset($fields['shipping']['shipping_last_name']['label']);  
        unset($fields['shipping']['shipping_company']['label']);  
        unset($fields['shipping']['shipping_address_1']['label']);  
        unset($fields['shipping']['shipping_city']['label']);  
        unset($fields['shipping']['shipping_state']['label']);  
        unset($fields['shipping']['shipping_postcode']['label']);  
        unset($fields['shipping']['shipping_country']['label']);  

        return $fields;
    }
}