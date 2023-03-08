<?php
namespace Neuron\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Sale extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-sale-tag';
	}

	public function get_title() {
		return __( 'Product Sale', 'neuron-builder' );
	}

	protected function register_controls() {
		$this->add_control( 'text', [
			'label' => __( 'Text', 'neuron-builder' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Sale!', 'neuron-builder' ),
		] );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return;
		}

		$value = '';

		if ( $product->is_on_sale() ) {
			$value = $this->get_settings( 'text' );
		}

		echo $value;
	}
}
