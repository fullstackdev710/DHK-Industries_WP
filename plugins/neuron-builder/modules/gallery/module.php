<?php
/**
 * Media Gallery Module
 * 
 * @since 1.0.0
 */
namespace Neuron\Modules\Gallery;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {
	public function get_name() {
		return 'gallery';
	}

	public function get_widgets() {
		return [ 'Gallery' ];
	}
}
