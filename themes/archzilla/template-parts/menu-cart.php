<?php 
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

$cart_url = wc_get_cart_url();
?>
<div class="n-site-header__menu-cart">
   <div class="n-site-header__menu-cart-icon">
       <a href="<?php echo esc_url( $cart_url ) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="17" viewBox="0 0 15 17" fill="none">
                <path d="M5.04346 6.26226V3.75613C5.04346 2.37203 6.16549 1.25 7.54959 1.25C8.93366 1.25 10.0557 2.37203 10.0557 3.75613V6.06728" stroke="#1A1A1A" stroke-width="1.5"/>
                <rect x="1.5" y="3.5" width="12" height="12" stroke="#1A1A1A" stroke-width="1.5"/>
            </svg>
        </a>
   </div>
</div>