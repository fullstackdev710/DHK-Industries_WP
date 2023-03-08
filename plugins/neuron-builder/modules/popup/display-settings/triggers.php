<?php
/**
 * Popup Triggers
 * 
 * Opens popup in different actions
 * of the users.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Triggers extends Base {

	public function get_name() {
		return 'popup_triggers';
	}

	protected function register_controls() {
		$this->start_controls_section( 'triggers' );

		$this->start_settings_group( 'page_load', __( 'On Page Load', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'delay',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'neuron-builder' ) . ' (sec)',
				'default' => 0,
				'min' => 0,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling', __( 'On Scroll', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'direction',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Direction', 'neuron-builder' ),
				'default' => 'down',
				'options' => [
					'down' => __( 'Down', 'neuron-builder' ),
					'up' => __( 'Up', 'neuron-builder' ),
				],
			]
		);

		$this->add_settings_group_control(
			'offset',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'neuron-builder' ) . ' (%)',
				'default' => 50,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'direction' => 'down',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling_to', __( 'On Scroll To Element', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'selector',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Selector', 'neuron-builder' ),
				'placeholder' => '.my-class',
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'click', __( 'On Click', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'times',
			[
				'label' => __( 'Clicks', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'inactivity', __( 'After Inactivity', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'time',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'neuron-builder' ) . ' (sec)',
				'default' => 30,
				'min' => 1,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'exit_intent', __( 'On Page Exit Intent', 'neuron-builder' ) );

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
