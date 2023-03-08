<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Search_Results extends Archive {

	public function get_name() {
		return 'search-results';
	}

	public static function get_sub_type() {
		return 'search';
	}

	public static function get_title() {
		return __( 'Search Results', 'neuron-builder' );
	}
	

	public static function get_preview_as_default() {
		return 'search';
	}

	public static function get_preview_as_options() {
		$options = [
			'search' => __( 'Search Results', 'neuron-builder' ),
		];

		return [
			'archive' => [
				'label' => __( 'Archive', 'neuron-builder' ),
				'options' => $options,
			],
		];
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'archive';

		return $config;
	}
}
