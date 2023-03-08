<?php
/**
 * Flip Box Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\FlipBox;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {
	
	public function get_name() {
		return 'flip-box';
	}

	public function get_widgets() {
		return [ 'Flip_Box' ];
	}

}
