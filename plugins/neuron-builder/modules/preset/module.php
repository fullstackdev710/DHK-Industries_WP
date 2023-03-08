<?php
/**
 * Add Template Library Module.
 *
 * @package Neuron
 * @since 1.0.0
 */

namespace Neuron\Modules\Preset;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'neuron-preset';
	}

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_preset_control' ], 10, 2 );
		add_action( 'wp_ajax_neuron_element_presets', [ $this, 'get_element_presets' ] );
	}

	public function register_preset_control( $element, $section_id ) {
		if ( 'widget' !== $element->get_type() ) {
			return;
		}

		$elements = $this->get_elements();

		if ( ! in_array( $element->get_name(), $elements, true ) ) {
			return;
		}

		if ( 'section_neuron_presets' === $section_id ) {
			return;
		}

		if ( ! empty( $element->get_controls( 'section_neuron_presets' ) ) ) {
			$element->remove_control( 'section_neuron_presets' );
		}

		if ( ! empty( $element->get_controls( 'neuron_presets' ) ) ) {
			$element->remove_control( 'neuron_presets' );
		}

		$element->start_controls_section(
			'section_neuron_presets',
			[
				'label' => 'Presets',
				'tab' => 'content',
			]
		);

		$element->add_control(
			'neuron_presets',
			[
				'type' => 'neuron_presets',
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Fetch neuron element presets.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return void
	 */
	public function get_element_presets() {
		// phpcs:ignore WordPress.Security
		if ( empty( $_POST['neuron_element'] ) ) {
			wp_send_json_error( 'neuron_element field is missing' );
		}

		// phpcs:ignore WordPress.Security
		$neuron_element = sanitize_text_field( wp_unslash( $_POST['neuron_element'] ) );

		// $url           = 'https://neuronthemes.com/library/wp-json/neuron/v1/presets/' . $neuron_element;
		$presets       = get_transient( 'neuron_preset_' . $neuron_element );

		if ( ! empty( $presets ) ) {
			return is_array( $presets ) ? wp_send_json_success( $presets ) : wp_send_json_success( [] );
		}

		$response = wp_remote_get( $url, [
			'timeout' => 40,
		] );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'Unable to fetch presets.' );
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			wp_send_json_error( 'Unable to fetch presets.' );
		}

		$presets = json_decode( wp_remote_retrieve_body( $response ), true );

		set_transient( 'neuron_preset_' . $neuron_element, $presets, 24 * HOUR_IN_SECONDS );

		wp_send_json_success( $presets );
	}

	/**
	 * Get active preset elements.
	 *
	 * @access public
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_elements() {
		$transient_key = 'neuron_presets_elements';

		$preset_elements = get_transient( $transient_key );

		if ( false !== $preset_elements ) {
			return $preset_elements;
		}

		$preset_elements = [];

		$url = 'https://neuronthemes.com/library/wp-json/neuron/v1/presets-elements';

		$response = wp_remote_get( $url, [
			'timeout' => 60,
		] );

		if ( is_wp_error( $response ) ) {
			set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
			set_transient( $transient_key . '_cached', $preset_elements );

			return $preset_elements;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
			set_transient( $transient_key . '_cached', $preset_elements );

			return $preset_elements;
		}

		$body = wp_remote_retrieve_body( $response );

		$preset_elements = json_decode( $body, true );

		set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
		set_transient( $transient_key . '_cached', $preset_elements );

		return $preset_elements;
	}
}
