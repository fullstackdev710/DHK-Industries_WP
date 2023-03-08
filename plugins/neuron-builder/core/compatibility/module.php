<?php
/**
 * Add Compatibility Module.
 *
 * @since 1.0.0
 */

namespace Neuron\Core\Compatibility;

use Neuron\Core\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

class Module {

	public function __construct() {

		new Wpml\Module();
	}
}
