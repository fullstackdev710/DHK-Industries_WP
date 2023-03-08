<?php
/**
 * Post Title
 *
 * Prints the post title.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Title extends Title_Widget_Base {

	public function get_name() {
		return 'neuron-post-title';
	}

	public function get_title() {
		return __( 'Post Title', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-post-title neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	public function get_keywords() {
		return [ 'title', 'heading', 'post' ];
	}

	protected function get_dynamic_tag_name() {
		return 'post-title';
	}

	public function get_common_args() {
		return [
			'_css_classes' => [
				'default' => 'entry-title',
			],
		];
	}
}
