<?php
/**
 * Countdown Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Countdown;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'countdown';
	}

	public function get_widgets() {
		return [ 'Countdown' ];
	}

}
