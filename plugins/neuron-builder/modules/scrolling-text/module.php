<?php
/**
 * Scrolling Text
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ScrollingText;

use Neuron\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'neuron-scrolling-text';
	}

	public function get_widgets() {
		return [ 'Scrolling_Text' ];
	}
}
