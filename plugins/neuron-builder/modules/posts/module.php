<?php
/**
 * Posts Module
 * 
 * @since 1.0.0
 */
namespace Neuron\Modules\Posts;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Core\Utils\Exceptions;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {
	
	public function get_name() {
		return 'posts';
	}

	public function get_widgets() {
		return [ 'Posts' ];
	}
	
}
