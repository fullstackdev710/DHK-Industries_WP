<?php
/**
 * Preview
 * 
 * @since 1.0.0
 */

namespace Neuron\Core\Preview;

use Elementor\Core\Base\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Preview extends App {

	public function __construct() {
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_styles' ] );
	}

	public function get_name() {
		return 'neuron-preview';
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			'neuron-editor-preview',
			NEURON_ASSETS_URL . 'styles/preview.css',
			[],
			NEURON_BUILDER_VERSION
		);
	}

	protected function get_assets_base_url() {
		return NEURON_URL;
	}
}
