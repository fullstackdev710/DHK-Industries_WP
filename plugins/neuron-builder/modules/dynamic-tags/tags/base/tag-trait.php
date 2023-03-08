<?php

namespace Neuron\Modules\DynamicTags\Tags\Base;

use Neuron\License\Admin as License_API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait Tag_Trait {

	public function is_editable() {
		return License_API::is_license_activated();
	}
}
