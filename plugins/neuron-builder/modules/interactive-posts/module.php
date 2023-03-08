<?php
/**
 * Interactive Posts Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\InteractivePosts;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'interactive-posts';
	}

	public function get_widgets() {
		return [ 'Interactive_Posts' ];
	}
	
}
