<?php

namespace Neuron\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Form extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'form_fields';
	}

	public function get_fields() {
		return array( 'field_label', 'placeholder', 'field_html', 'acceptance_text', 'field_options' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'field_label':
				return esc_html__( 'Form: Field label', 'neuron-builder' );

			case 'placeholder':
				return esc_html__( 'Form: Field placeholder', 'neuron-builder' );

			case 'field_html':
				return esc_html__( 'Form: Field HTML', 'neuron-builder' );
				
            case 'acceptance_text':
                return esc_html__( 'Form: Acceptance Text', 'neuron-builder' );

            case 'field_options':
                return esc_html__( 'Form: Checkbox Options', 'neuron-builder' );
				
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'field_label':
			case 'placeholder':
            case 'acceptance_text':			
				return 'LINE';

			case 'field_html':
				return 'VISUAL';	
				
            case 'field_options':
                return 'AREA';				

			default:
				return '';
		}
	}

}
