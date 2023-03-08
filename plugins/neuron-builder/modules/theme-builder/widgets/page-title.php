<?php
/**
 * Page Title
 * 
 * Prints the page title.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Page_Title extends Title_Widget_Base {

	protected function get_dynamic_tag_name() {
		return 'page-title';
	}

	public function get_name() {
		return 'neuron-page-title';
	}

	public function get_title() {
		return __( 'Page Title', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-archive-title neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-site' ];
	}

	public function get_keywords() {
		return [ 'title', 'heading', 'page' ];
	}
}
