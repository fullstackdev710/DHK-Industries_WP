<?php
/**
 * Portfolio Module
 * 
 * @since 1.0.0
 */
namespace Neuron\Modules\Portfolio;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {
	
	public function get_name() {
		return 'portfolio';
	}

	public function get_widgets() {
		return [ 'Portfolio' ];
	}
	
}
