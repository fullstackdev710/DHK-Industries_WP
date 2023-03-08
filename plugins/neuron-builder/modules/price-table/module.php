<?php
/**
 * Price Table Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\PriceTable;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'price-table';
	}

	public function get_widgets() {
		return [ 'Price_Table' ];
	}

}
