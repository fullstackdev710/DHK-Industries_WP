<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Debug output control. */
define( 'ENVATO_MARKET_DEBUG_OUTPUT', 0 );

/* Set constant path to the plugin directory. */
define( 'ENVATO_MARKET_SLUG', basename( plugin_dir_path( __FILE__ ) ) );

/* Set constant path to the plugin directory. */
define( 'ENVATO_MARKET_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'ENVATO_MARKET_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	// Makes sure the plugin functions are defined before trying to use them.
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
define( 'ENVATO_MARKET_NETWORK_ACTIVATED', is_plugin_active_for_network( ENVATO_MARKET_SLUG . '/envato-market.php' ) );

/* Envato_Market Class */
require_once ENVATO_MARKET_PATH . 'inc/class-envato-market.php';

if ( ! function_exists( 'envato_market' ) ) :
	/**
	 * The main function responsible for returning the one true
	 * Envato_Market Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except
	 * without needing to declare the global.
	 *
	 * Example: <?php $envato_market = envato_market(); ?>
	 *
	 * @since 1.0.0
	 * @return Envato_Market The one true Envato_Market Instance
	 */
	function envato_market() {
		return Envato_Market::instance();
	}
endif;


/**
 * Loads the main instance of Envato_Market to prevent
 * the need to use globals.
 *
 * This doesn't fire the activation hook correctly if done in 'after_setup_theme' hook.
 *
 * @since 1.0.0
 * @return object Envato_Market
 */
envato_market();

