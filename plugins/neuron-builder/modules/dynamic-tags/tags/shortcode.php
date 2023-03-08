<?php
/**
 * Shortcode
 * 
 * Turns the options to
 * a shortcode, via the
 * do_shortcode function.
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

class Shortcode extends Tag {

	public function get_name() {
		return 'shortcode';
	}

	public function get_title() {
		return __( 'Shortcode', 'neuron-builder' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
			Module::URL_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'shortcode',
			[
				'label' => __( 'Shortcode', 'neuron-builder' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['shortcode'] ) ) {
			return;
		}

		$shortcode_string = $settings['shortcode'];

		$value = do_shortcode( $shortcode_string );

		/**
		 * Should Escape.
		 *
		 * Used to allow 3rd party to avoid shortcode dynamic from escaping
		 *
		 * @since 1.0.0
		 *
		 * @param bool defaults to true
		 */
		$should_escape = apply_filters( 'neuron/dynamic_tags/shortcode/should_escape', true );

		if ( $should_escape ) {
			$value = wp_kses_post( $value );
		}

		echo $value;
	}
	
}
