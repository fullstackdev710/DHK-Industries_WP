<?php
/**
 * Post Comments
 * 
 * Display the comments template
 * on your single pages or posts.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Comments extends Base_Widget {

	public function get_name() {
		return 'neuron-post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-comments neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'post_comments_section',
			[
				'label' => __( 'Post Comments', 'neuron-builder' )
			]
		);

		$this->add_control(
			'post_comments',
			[
				'raw' => __( '<small>This element simply displays the comments of this post/page.</small>', 'neuron-builder' ),
				'type' => Controls_Manager::RAW_HTML,
				'field_type' => 'html'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		comments_template();
	}

	protected function content_template() {}
}
