<?php

namespace Neuron\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Slides extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'slides';
	}

	public function get_fields() {
		return array( 'heading', 'description', 'button_text', 'link' => array( 'url' ) );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'heading':
				return esc_html__( 'Slides: heading', 'neuron-builder' );

			case 'description':
				return esc_html__( 'Slides: description', 'neuron-builder' );

			case 'button_text':
				return esc_html__( 'Slides: button text', 'neuron-builder' );

			case 'url':
				return esc_html__( 'Slides: link URL', 'neuron-builder' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'heading':
			case 'button_text':
				return 'LINE';

			case 'description':
				return 'VISUAL';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
