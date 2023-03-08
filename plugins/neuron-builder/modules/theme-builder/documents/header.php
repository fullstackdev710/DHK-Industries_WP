<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Header extends Header_Footer_Base {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'header';

		return $properties;
	}

	protected static function get_site_editor_type() {
		return 'header';
	}

	public static function get_title() {
		return __( 'Header', 'neuron-builder' );
	}
}
