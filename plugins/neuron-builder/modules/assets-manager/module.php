<?php
/**
 * Assets Manager
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\AssetsManager;

use Neuron\Base\Module_Base;
use Neuron\Modules\AssetsManager\AssetTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	private $asset_managers = [];

	public function get_name() {
		return 'neuron-assets-manager';
	}

	public function add_asset_manager( $name, $instance ) {
		$this->asset_managers[ $name ] = $instance;
	}

	public function get_assets_manager( $id = null ) {
		if ( $id ) {
			if ( ! isset( $this->asset_managers[ $id ] ) ) {
				return null;
			}

			return $this->asset_managers[ $id ];
		}

		return $this->asset_managers;
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'fontsUploadEmptyNotice' => __( 'Choose a font to publish.', 'neuron-builder' ),
				'iconsUploadEmptyNotice' => __( 'Upload an icon set to publish.', 'neuron-builder' ),
			],
		] );

		return $settings;
	}

	public function __construct() {
		parent::__construct();

		if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
			add_filter( 'neuron/admin/localize_settings', [ $this, 'localize_settings' ] );
			
			$this->add_asset_manager( 'font', new AssetTypes\Fonts_Manager() );
		}
       
		$this->add_asset_manager( 'icon', new AssetTypes\Icons_Manager() );
	}
}
