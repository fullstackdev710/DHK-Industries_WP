<?php

namespace Neuron\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class PriceTable extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'list_items';
	}

	public function get_fields() {
		return array( 'text' );
	}

	protected function get_title( $field ) {
		if ( 'text' === $field ) {
			return esc_html__( 'Price table: text', 'neuron-builder' );
		}

		return '';
	}

	protected function get_editor_type( $field ) {
		if ( 'text' === $field ) {
			return 'LINE';
		}

		return '';
	}
}
