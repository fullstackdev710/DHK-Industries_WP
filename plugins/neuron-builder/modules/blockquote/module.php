<?php
/**
 * Blockquote Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Blockquote;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'blockquote';
	}

	public function get_widgets() {
		return [ 'Blockquote' ];
	}

}
