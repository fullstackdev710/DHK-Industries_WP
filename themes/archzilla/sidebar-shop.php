<?php
/**
 * Shop Sidebar
 * 
 * @since 1.0.0
 */

if ( class_exists( 'WooCommerce' ) && is_active_sidebar( 'shop-sidebar' ) ) {
	dynamic_sidebar( 'shop-sidebar' );
}
