<?php
/**
 * Pods Numeric
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Pods\Tags;

use Neuron\Modules\DynamicTags\Pods\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Numeric extends Pods_Base {

	public function get_name() {
		return 'pods-numeric';
	}

	public function get_title() {
		return __( 'Pods', 'neuron-builder' ) . ' ' . __( 'Numeric', 'neuron-builder' ) . ' ' . __( 'Field', 'neuron-builder' );
	}

	public function get_categories() {
		return [
			Module::NUMBER_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		$field_data = $this->get_field();
		$value = ! empty( $field_data['value'] ) && is_numeric( $field_data['value'] ) ? $field_data['value'] : '';

		echo wp_kses_post( $value );
	}

	protected function get_supported_fields() {
		return [
			'numeric',
		];
	}
}
