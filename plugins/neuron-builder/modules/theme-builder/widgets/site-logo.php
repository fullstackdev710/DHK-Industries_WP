<?php
/**
 * Site Logo
 * 
 * Prints the logo that is uploaded
 * in Appearance > Customize > Site Identity.
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

class Site_Logo extends Widget_Image {

	// use Base_Widget_Trait;

	public function get_name() {
		return 'neuron-site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-site-logo neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-site' ];
	}

	public function get_keywords() {
		return [ 'site', 'logo', 'branding' ];
	}

	public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'image',
			[
				'dynamic' => [
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'site-logo' ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'image_size',
			[
				'default' => 'full',
			]
		);

		$this->update_control(
			'link_to',
			[
				'default' => 'custom',
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

		$this->update_responsive_control(
			'width',
			[
				'size_units' => [ '%', 'px', 'vw', 'rem' ],
			]
		);

		$this->remove_control( 'caption' );
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-widget-' . parent::get_name();
	}
}
