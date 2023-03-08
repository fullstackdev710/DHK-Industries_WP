<?php
namespace Neuron\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Rating extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-rating-tag';
	}

	public function get_title() {
		return __( 'Product Rating', 'neuron-builder' );
	}

	protected function register_controls() {
		$this->add_control( 'field', [
			'label' => __( 'Format', 'neuron-builder' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'average_rating' => __( 'Average Rating', 'neuron-builder' ),
				'rating_count' => __( 'Rating Count', 'neuron-builder' ),
				'review_count' => __( 'Review Count', 'neuron-builder' ),
			],
			'default' => 'average_rating',
		] );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return '';
		}

		$field = $this->get_settings( 'field' );
		$value = '';
		switch ( $field ) {
			case 'average_rating':
				$value = $product->get_average_rating();
				break;
			case 'rating_count':
				$value = $product->get_rating_count();
				break;
			case 'review_count':
				$value = $product->get_review_count();
				break;
		}

		echo $value;
	}
}
