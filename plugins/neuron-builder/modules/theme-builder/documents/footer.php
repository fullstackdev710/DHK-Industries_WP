<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Footer extends Header_Footer_Base {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'footer';

		return $properties;
	}

	protected static function get_site_editor_type() {
		return 'footer';
	}

	public static function get_title() {
		return __( 'Footer', 'neuron-builder' );
	}
}
