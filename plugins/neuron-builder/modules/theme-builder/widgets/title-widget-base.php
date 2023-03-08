<?php
/**
 * Title Widget Base
 * 
 * @since 1.0.0
 */
namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Widget_Heading;
use Elementor\Plugin;

use Neuron\Base\Base_Widget_Trait; 
use Neuron\Plugin as NeuronPlugin;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Title_Widget_Base extends Widget_Heading {

	use Base_Widget_Trait;

	abstract protected function get_dynamic_tag_name();

	protected function should_show_page_title() {
		$current_doc = Plugin::instance()->documents->get( get_the_ID() );
		if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
			return false;
		}

		return true;
	}

	protected function register_controls() {
		parent::register_controls();

		$dynamic_tag_name = $this->get_dynamic_tag_name();

		$this->update_control(
			'title',
			[
				'dynamic' => [
					'default' => NeuronPlugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, $dynamic_tag_name ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-page-title elementor-widget-' . parent::get_name();
	}

	public function render() {
		if ( $this->should_show_page_title() ) {
			return parent::render();
		}
	}

	public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}
}
