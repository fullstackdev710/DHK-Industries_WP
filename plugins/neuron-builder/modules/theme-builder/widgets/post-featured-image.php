<?php
/**
 * Post Featured Image
 *
 * Prints the post featured image.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Widget_Image;
// use Neuron\Base\Base_Widget_Trait;
use Neuron\Plugin;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Featured_Image extends Widget_Image {

	// use Base_Widget_Trait;

	public function get_name() {
		return 'neuron-post-featured-image';
	}

	public function get_title() {
		return __( 'Featured Image', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-featured-image neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	public function get_keywords() {
		return [ 'image', 'featured', 'thumbnail' ];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'image',
			[
				'dynamic' => [
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'post-featured-image' ),
				],
			],
			[
				'recursive' => true,
			]
		);
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-widget-' . parent::get_name();
	}

	public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}
}
