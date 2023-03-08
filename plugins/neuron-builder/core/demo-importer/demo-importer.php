<?php
/**
 * Demo Importer
 * 
 * @since 1.0.0
 */

// namespace Neuron\Core\Demo_Importer;

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Main plugin class with initialization tasks.
 */
class Neuron_OCDI_Plugin {

	/**
	 * Constructor for this class.
	 */
	public function __construct() {

		require_once NEURON_PATH . 'core/demo-importer/vendor/autoload.php';

		// @TODO: Add them properly without autoloader

		// Instantiate the main plugin class *Singleton*.
		$pt_one_click_demo_import = OCDI\OneClickDemoImport::get_instance();
	}
}

// Instantiate the plugin class.
$ocdi_plugin = new Neuron_OCDI_Plugin();
