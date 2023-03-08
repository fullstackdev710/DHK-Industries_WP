<?php
/**
 * Site Title
 * 
 * Prints the site title.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Widget_Heading;

// use Neuron\Base\Base_Widget_Trait;
use Neuron\Plugin;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Title extends Widget_Heading {

	// use Base_Widget_Trait;

	public function get_name() {
		return 'neuron-site-title';
	}

	public function get_title() {
		return __( 'Site Title', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-site-title neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-site' ];
	}

	public function get_keywords() {
		return [ 'site', 'title', 'name' ];
	}

	public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'title',
			[
				'dynamic' => [
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'site-title' ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'link',
			[
				'dynamic' => [
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'site-url' ),
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
}
