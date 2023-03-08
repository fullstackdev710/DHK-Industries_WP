<?php
/**
 * ACF File Upload
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\ACF\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Data_Tag;
use Neuron\Modules\DynamicTags\ACF\Module;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return __( 'ACF', 'neuron-builder' ) . ' ' . __( 'File Field', 'neuron-builder' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	public function get_supported_fields() {
		return [
			'file',
		];
	}
}
