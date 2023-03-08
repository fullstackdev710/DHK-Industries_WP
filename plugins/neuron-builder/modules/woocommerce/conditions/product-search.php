<?php
namespace Neuron\Modules\Woocommerce\Conditions;

use Neuron\Modules\ThemeBuilder as ThemeBuilder;
use Neuron\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Search extends ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'archive';
	}

	public function get_name() {
		return 'product_search';
	}

	public static function get_priority() {
		return 40;
	}

	public function get_label() {
		return __( 'Search Results', 'neuron-builder' );
	}

	public function check( $args ) {
		return Module::is_product_search();
	}
}
