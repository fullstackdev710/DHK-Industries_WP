<?php
/**
 * Custom Attributes
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\CustomAttributes;

use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
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
		return 'custom-attributes';
	}

	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' );
	}

	private function get_black_list_attributes() {
		static $black_list = null;

		if ( null === $black_list ) {
			$black_list = [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid', 'onload', 'onclick', 'onfocus', 'onblur', 'onchange', 'onresize', 'onmouseover', 'onmouseout', 'onkeydown', 'onkeyup', 'onerror' ];

			$black_list = apply_filters( 'neuron/element/attributes/black_list', $black_list );
		}

		return $black_list;
	}

	public function replace_go_pro_custom_attributes_controls( Element_Base $element ) {
		Plugin::elementor()->controls_manager->remove_control_from_stack( $element->get_unique_name(), [ 'section_custom_attributes_pro', 'custom_attributes_pro' ] );

		$this->register_custom_attributes_controls( $element );
	}

	public function register_custom_attributes_controls( Element_Base $element ) {
		$element_name = $element->get_name();

		$element->start_controls_section(
			'_section_attributes',
			[
				'label' => __( 'Attributes', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'_attributes',
			[
				'label' => __( 'Custom Attributes', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'key|value', 'neuron-builder' ),
				'description' => sprintf( __( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using %s character.', 'neuron-builder' ), '<code>|</code>' ),
			]
		);

		$element->end_controls_section();
	}

	public function register_controls( Controls_Stack $element, $section_id ) {
		if ( ! $element instanceof Element_Base ) {
			return;
		}

		// Remove Custom CSS Banner (From free version)
		if ( 'section_custom_attributes_pro' === $section_id ) {
			$this->replace_go_pro_custom_attributes_controls( $element );
		}
	}

	public function render_attributes( Element_Base $element ) {
		$settings = $element->get_settings_for_display();

		if ( ! empty( $settings['_attributes'] ) ) {
			$attributes = explode( "\n", $settings['_attributes'] );

			$black_list = $this->get_black_list_attributes();

			foreach ( $attributes as $attribute ) {
				if ( ! empty( $attribute ) ) {
					$attr = explode( '|', $attribute, 2 );
					if ( ! isset( $attr[1] ) ) {
						$attr[1] = '';
					}

					if ( ! in_array( strtolower( $attr[0] ), $black_list ) ) {
						$element->add_render_attribute( '_wrapper', trim( $attr[0] ), trim( $attr[1] ) );
					}
				}
			}
		}
	}

	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes', [ $this, 'render_attributes' ] );
	}
}
