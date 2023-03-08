<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

use Elementor\Controls_Manager;
use Neuron\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Section extends Theme_Section_Document {

	public function get_name() {
		return 'section';
	}

	public static function get_title() {
		return __( 'Section', 'neuron-builder' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'library';

		return $properties;
	}

	protected function register_controls() {
		parent::register_controls();

		Module::instance()->get_locations_manager()->register_locations();

		$locations = Module::instance()->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}

		$this->start_controls_section(
			'location_settings',
			[
				'label' => __( 'Location Settings', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);

		$options = [
			'' => __( 'Select', 'neuron-builder' ),
		];

		foreach ( $locations as $location => $settings ) {
			$options[ $location ] = $settings['label'];
		}

		$this->add_control(
			'location',
			[
				'label' => __( 'Location', 'neuron-builder' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => $this->get_location(),
				'save_default' => true,
				'options' => $options,
			]
		);

		$this->add_control(
			'apply_location',
			[
				'type' => Controls_Manager::BUTTON,
				'label' => '',
				'text' => __( 'Apply', 'neuron-builder' ),
				'separator' => 'none',
				'event' => 'neuronThemeBuilder:ApplyPreview',
			]
		);

		$this->end_controls_section();
	}

	public function save_settings( $settings ) {
		if ( isset( $settings['location'] ) ) {
			if ( empty( $settings['location'] ) ) {
				$this->delete_main_meta( '_elementor_location' );
			} else {
				$this->update_main_meta( '_elementor_location', $settings['location'] );
				unset( $settings['location'] );
			}
			Module::instance()->get_conditions_manager()->get_cache()->regenerate();
		}

		parent::save_settings( $settings );
	}
}
