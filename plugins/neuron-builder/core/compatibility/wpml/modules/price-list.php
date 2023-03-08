<?php

namespace Neuron\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class PriceList extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'price_lists';
	}

	public function get_fields() {
		return array( 'title', 'description', 'link' => array( 'link' ) );
	}

	protected function get_title( $field ) {
		if ( 'title' === $field ) {
			return esc_html__( 'Price list: title', 'neuron-builder' );
		}

		if ( 'description' === $field ) {
			return esc_html__( 'Pricing list: description', 'neuron-builder' );
		}

		if ( 'link' === $field ) {
			return esc_html__( 'Pricing list: link', 'neuron-builder' );
		}

		return '';
	}
	
	protected function get_editor_type( $field ) {
		if ( 'title' === $field ) {
			return 'LINE';
		}

		if ( 'link' === $field ) {
			return 'LINK';
		}

		if ( 'description' === $field ) {
			return 'AREA';
		}

		return '';
    }
}
