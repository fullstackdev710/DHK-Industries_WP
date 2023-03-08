<?php
/**
 * Advanced Google Maps Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Maps;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'maps';
	}

	public function get_widgets() {
		return [ 'Maps' ];
	}
	
}
