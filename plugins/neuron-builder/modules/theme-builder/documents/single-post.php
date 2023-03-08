<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Single_Post extends Single_Base {

	protected static function get_site_editor_type() {
		return 'single-post';
	}

	public static function get_title() {
		return __( 'Single Post', 'neuron-builder' );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'single post';

		return $config;
	}
}
