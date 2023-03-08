<?php
namespace Neuron\Modules\ThemeBuilder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Not_Found404 extends Condition_Base {

	public static function get_type() {
		return 'singular';
	}

	public static function get_priority() {
		return 20;
	}

	public function get_name() {
		return 'not_found404';
	}

	public function get_label() {
		return __( '404 Page', 'neuron-builder' );
	}

	public function check( $args ) {
		return is_404();
	}
}
