<?php
namespace Neuron\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Stock extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-stock-tag';
	}

	public function get_title() {
		return __( 'Product Stock', 'neuron-builder' );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return;
		}

		if ( 'yes' === $this->get_settings( 'show_text' ) ) {
			$value = wc_get_stock_html( $product );
		} else {
			$value = $product->get_stock_quantity();
		}

		echo $value;
	}

	protected function register_controls() {
		$this->add_control(
			'show_text',
			[
				'label' => __( 'Show Text', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
			]
		);
	}
}
