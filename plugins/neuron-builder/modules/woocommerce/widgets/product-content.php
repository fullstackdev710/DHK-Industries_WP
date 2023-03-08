<?php
/**
 * Product Content
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Neuron\Modules\ThemeBuilder\Widgets\Post_Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Content extends Post_Content {

	public function get_name() {
		return 'neuron-woo-product-content';
	}

	public function get_title() {
		return __( 'Product Content', 'neuron-builder' );
	}

	public function get_categories() {
		return [ 'neuron-woo-elements-single' ];
	}

	public function get_keywords() {
		return [ 'content', 'post', 'product' ];
	}
}
