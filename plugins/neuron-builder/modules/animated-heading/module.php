<?php
/**
 * Animated Heading Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\AnimatedHeading;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {
	
	public function get_name() {
		return 'animated-heading';
	}

	public function get_widgets() {
		return [ 'Animated_Heading' ];
	}

}
