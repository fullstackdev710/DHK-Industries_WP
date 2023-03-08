<?php
/**
 * Price List Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\PriceList;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'price-list';
	}

	public function get_widgets() {
		return [ 'Price_List' ];
	}

}
