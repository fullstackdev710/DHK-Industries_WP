<?php
namespace Neuron\Modules\Woocommerce\Tags;

use Neuron\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Image extends Base_Data_Tag {
	public function get_name() {
		return 'woocommerce-product-image-tag';
	}

	public function get_title() {
		return __( 'Product Image', 'neuron-builder' );
	}

	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		$product = wc_get_product();
		if ( ! $product ) {
			return [];
		}

		$image_id = $product->get_image_id();
		$src = wp_get_attachment_image_src( $image_id, 'full' );

		return [
			'id' => $image_id,
			'url' => $src[0],
		];
	}
}
