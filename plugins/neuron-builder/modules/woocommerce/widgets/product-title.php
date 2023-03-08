<?php
/**
 * Product Title
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Widget_Heading;

use Neuron\Base\Base_Widget_Trait;
use Neuron\Plugin;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Title extends Widget_Heading {

	use Base_Widget_Trait; 

	public function get_name() {
		return 'neuron-woo-product-title';
	}

	public function get_title() {
		return __( 'Product Title', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-title neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-woo-elements-single' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' ];
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
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'woocommerce-product-title-tag' ),
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

	protected function render() {
		$this->add_render_attribute( 'title', 'class', [ 'product_title', 'entry-title' ] );

		parent::render();
	}

	protected function content_template() {
		?>
		<# view.addRenderAttribute( 'title', 'class', [ 'product_title', 'entry-title' ] ); #>
		<?php
		parent::content_template();
	}

	public function render_plain_content() {}
}
