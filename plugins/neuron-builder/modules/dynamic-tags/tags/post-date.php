<?php
/**
 * Post Date
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Tag;
use Neuron\Modules\DynamicTags\Module;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Date extends Tag {
	public function get_name() {
		return 'post-date';
	}

	public function get_title() {
		return __( 'Post Date', 'neuron-builder' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'format',
			[
				'label' => __( 'Format', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'neuron-builder' ),
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d' => gmdate( 'Y-m-d' ),
					'm/d/Y' => gmdate( 'm/d/Y' ),
					'd/m/Y' => gmdate( 'd/m/Y' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => __( 'Custom Format', 'neuron-builder' ),
				'default' => '',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Formatting Date and Time', 'neuron-builder' ) ),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$format = $this->get_settings( 'format' );
		$date_format = $format;

		if ( $format == 'default' ) {
			$date_format = '';
		} elseif ( $format == 'custom' ) {
			$date_format = $this->get_settings( 'custom_format' );
		}

		$value = get_the_date( $date_format );

		echo wp_kses_post( $value );
	}
}
