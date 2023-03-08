<?php
/**
 * Archive title
 *
 * Prints the archive title. 
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Title extends Title_Widget_Base {

	protected function get_dynamic_tag_name() {
		return 'archive-title';
	}

	public function get_name() {
		return 'neuron-archive-title';
	}

	public function get_title() {
		return __( 'Archive Title', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-archive-title neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-archive' ];
	}

	public function get_keywords() {
		return [ 'title', 'heading', 'archive' ];
	}
}
