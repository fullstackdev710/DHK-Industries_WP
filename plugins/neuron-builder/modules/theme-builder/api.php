<?php
/**
 * Check template if exists
 * 
 * @since 1.0.0
 */

use Neuron\Modules\ThemeBuilder\Module as Theme_Builder_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'neuron_theme_do_location' ) ) {

	function neuron_theme_do_location( $location ) {
		$theme_builder_module = Theme_Builder_Module::instance();

		return $theme_builder_module->get_locations_manager()->do_location( $location );
	}
}

if ( ! function_exists( 'neuron_location_exits' ) ) {
	
	function neuron_location_exits( $location, $check_match = false ) {
		$theme_builder_module = Theme_Builder_Module::instance();

		return $theme_builder_module->get_locations_manager()->location_exits( $location, $check_match );
	}
	
}
