<?php
/**
 * Add Base Widget.
 *
 * @since 1.0.0
 * @package Neuron Builder
 */

namespace Neuron\Base;

defined( 'ABSPATH' ) || die();

use Elementor\Widget_Base;

abstract class Base_Widget extends Widget_Base {

	use Base_Widget_Trait;

	static $docs_url = 'https://neuronthemes.com/go';

	public function get_categories() {
		return [ 'neuron-elements' ];
	}

	public function get_custom_help_url() {
		return $this::$docs_url . '/widgets-' . $this->get_name();
	}
}
