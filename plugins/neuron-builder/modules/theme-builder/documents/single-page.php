<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Single_Page extends Single_Base {
    protected static function get_site_editor_type() {
		return 'single-page';
	}

	public static function get_sub_type() {
		return 'page';
	}

	public static function get_title() {
		return __( 'Single Page', 'neuron-builder' );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'single page';

		return $config;
	}
}
