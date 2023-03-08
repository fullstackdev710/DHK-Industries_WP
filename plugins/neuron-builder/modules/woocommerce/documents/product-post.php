<?php
namespace Neuron\Modules\Woocommerce\Documents;

use Elementor\Core\DocumentTypes\Post;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Post extends Post {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['cpt'] = [
			'product',
		];

		return $properties;
	}

	public function get_name() {
		return 'product-post';
	}

	public static function get_title() {
		return __( 'Product Post', 'neuron-builder' );
	}

	protected static function get_editor_panel_categories() {
		$categories = parent::get_editor_panel_categories();

		unset( $categories['neuron-elements-single'] );

		$categories = Utils::array_inject(
			$categories,
			'neuron-elements-site',
			[
				'neuron-woo-elements-single' => [
					'title' => __( 'Product', 'neuron-builder' ),
					'active' => false,
				],
			]
		);

		return $categories;
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'single product';

		return $config;
	}
}
