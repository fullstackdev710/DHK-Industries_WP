<?php
namespace Neuron\Modules\Woocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Base_Widget extends \Neuron\Base\Base_Widget {

	public function get_categories() {
		return [ 'neuron-woo-elements-single' ];
	}
}
