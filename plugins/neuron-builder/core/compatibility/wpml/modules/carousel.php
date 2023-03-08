<?php

namespace Neuron\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Carousel extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'slides';
	}

	public function get_fields() {
		return array( 'content', 'name', 'title' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'content':
				return esc_html__( 'Testimonial Carousel: Content', 'neuron-builder' );

			case 'name':
				return esc_html__( 'Testimonial Carousel: Name', 'neuron-builder' );

			case 'title':
				return esc_html__( 'Testimonial Carousel: Title', 'neuron-builder' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'name':
				return 'LINE';

			case 'title':
				return 'LINE';

			case 'content':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
