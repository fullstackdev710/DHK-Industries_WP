<?php
/**
 * Plugin Name: Neuron Builder
 * Plugin URI:  https://neuronthemes.com/neuron-builder
 * Description: Neuron Builder introduces a breakthrough in design and development, giving you full control of your website including headers, footers and all parts of content. Build sites with ease and flexibility.
 * Version:     1.0.6.6
 * Author:      neuronthemes
 * Author URI:  https://neuronthemes.com/
 * Text Domain: neuron-builder
 * License: 	GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:	/languages
 * Elementor tested up to: 3.9.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Global Variables
 * 
 * Defining global variables 
 * to make usage easier.
 */
define( 'NEURON_BUILDER_VERSION', '1.0.6.6' );
define( 'NEURON_FILE', __FILE__ );
define( 'NEURON_DIR_PATH', plugin_dir_path( __DIR__ ) );
define( 'NEURON_PATH', plugin_dir_path( NEURON_FILE ) );
define( 'NEURON_ASSETS_PATH', NEURON_PATH . 'assets/' );
define( 'NEURON_BUILDER_PLACEHOLDER', get_template_directory_uri() . '/assets/images/placeholder.png' );
define( 'NEURON_URL', plugins_url( '/', NEURON_FILE ) );
define( 'NEURON_ASSETS_URL', NEURON_URL . 'assets/' );
define( 'NEURON_MODULES_URL', NEURON_URL . 'modules/' );
define( 'NEURON_BUILDER_URL', NEURON_URL . 'core/' );

Class Neuron {
	
	public static $instance;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		// Include Plugin
		include_once( NEURON_PATH . 'includes/plugin.php' );

		// Actions
		add_action( 'init', [ $this, 'post_types' ] );
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Post Types
	 * 
	 * Portfolio Post Type
	 * 
	 * @since 1.0.0
	 */
	public function post_types() {
		// Prefix
		$neuron_portfolio_prefix = apply_filters( 'neuron/admin/post_type/portfolio', __( 'Portfolio', 'neuron-builder' ) );

		// Portfolio Post Type
		neuron_portfolio_post_type( $neuron_portfolio_prefix );

		// Portfolio Categories Taxonomy
		neuron_portfolio_categories( $neuron_portfolio_prefix );

		// Portfolio Tags Taxonomy
		neuron_portfolio_tags( $neuron_portfolio_prefix );
	}

	public function plugins_loaded() {
		
		// Includes
		include( NEURON_PATH . 'functions.php' );

		if ( is_admin() ) {
			include( NEURON_PATH . 'core/demo-importer/demo-importer.php' );
		}

		// Load localization file
		load_plugin_textdomain( 'neuron-builder', false, plugin_dir_path( NEURON_FILE ) . 'languages' );
	}
}

/**
 * Returns the Neuron class.
 *
 * @since 1.0.0
 *
 * @return Neuron
 */
function Neuron() {
	return Neuron::get_instance();
}

/**
 * Initializes the Neuron class.
 *
 * @since 1.0.0
 */
Neuron();
