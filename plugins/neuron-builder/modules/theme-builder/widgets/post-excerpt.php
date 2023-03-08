<?php
/**
 * Post Excerpt
 *
 * Prints the posts excerpt.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Neuron\Base\Base_Widget;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Excerpt extends Base_Widget {

	public function get_name() {
		return 'neuron-post-excerpt';
	}

	public function get_title() {
		return __( 'Post Excerpt', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-post-excerpt neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	public function get_keywords() {
		return [ 'post', 'excerpt', 'description' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'excerpt',
			[
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'post-excerpt' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .elementor-widget-container',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		echo $this->get_settings_for_display( 'excerpt' );
	}
}
