<?php
/**
 * Sticky Options
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Sticky;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Element_Section;
use Elementor\Widget_Base;
use Neuron\Base\Module_Base;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'neuron-sticky';
	}

	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' );
	}

	private function is_instance_of( $element, array $types ) {
		foreach ( $types as $type ) {
			if ( $element instanceof $type ) {
				return true;
			}
		}

		return false;
	}

	public function register_controls( Element_Base $element ) {
		$element->add_control(
			'sticky',
			[
				'label' => __( 'Sticky', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'neuron-builder' ),
					'top' => __( 'Top', 'neuron-builder' ),
					'bottom' => __( 'Bottom', 'neuron-builder' ),
				],
				'separator' => 'before',
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_on',
			[
				'label' => __( 'Sticky On', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'neuron-builder' ),
					'tablet' => __( 'Tablet', 'neuron-builder' ),
					'mobile' => __( 'Mobile', 'neuron-builder' ),
				],
				'condition' => [
					'sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_offset',
			[
				'label' => __( 'Offset', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'required' => true,
				'condition' => [
					'sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_effects_offset',
			[
				'label' => __( 'Effects Offset', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 1000,
				'required' => true,
				'condition' => [
					'sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		// Add `Stay In Column` only to the following types:
		$types = [
			Element_Section::class,
			Widget_Base::class,
		];

		if ( $this->is_instance_of( $element, $types ) ) {
			$conditions = [
				'sticky!' => '',
			];

			if ( $element instanceof Element_Section && Plugin::elementor()->editor->is_edit_mode() ) {
				$conditions['isInner'] = true;
			}

			$element->add_control(
				'sticky_parent',
				[
					'label' => __( 'Stay In Column', 'neuron-builder' ),
					'type' => Controls_Manager::SWITCHER,
					'condition' => $conditions,
					'render_type' => 'none',
					'frontend_available' => true,
				]
			);
		}

		$element->add_control(
			'sticky_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_effects/after_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/section_effects/after_section_start', [ $this, 'register_controls' ] );
	}
}
