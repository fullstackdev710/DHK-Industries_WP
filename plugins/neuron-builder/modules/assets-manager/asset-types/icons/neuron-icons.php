<?php
namespace Neuron\Modules\AssetsManager\AssetTypes\Icons;

use Neuron\Modules\AssetsManager\Classes\Assets_Base;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Neuron_Icons extends  Assets_Base {

	public function get_name() {
		return __( 'Neuron Icons', 'neuron-builder' );
	}

	public function get_type() {
		return 'neuron-icons';
	}

	private function get_kit_id() {
		return get_option( 'elementor_' . self::FA_KIT_ID_OPTION_NAME, false );
	}

	public function add_neuron_icons( $settings ) {
        $json_url = NEURON_ASSETS_URL . 'fonts/n-icons/n-icons.js';
        
		$icons['neuron-icon'] = [
			'name' => 'neuron-icon',
			'label' => __( 'Neuron Icons', 'neuron-builder' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'n-icon-',
			'displayPrefix' => 'n-icon',
			'labelIcon' => 'n-icon-neuron-icon',
			'ver' => '1.0.0',
			'fetchJson' => $json_url,
			'native' => true,
        ];

		return array_merge( $icons, $settings );
	}

	protected function actions() {
		parent::actions();

        add_filter( 'elementor/icons_manager/native', [ $this, 'add_neuron_icons' ] );
	}
}
