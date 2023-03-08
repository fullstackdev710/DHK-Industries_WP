<?php
/**
 * Neuron Editor
 * 
 * @since 1.0.0
 */
namespace Neuron\Core\Editor;

use Elementor\Core\Base\App;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Editor extends App {

	public function get_name() {
		return 'neuron-editor';
	}

	public function add_menu_in_admin_bar( $admin_bar_config ) {

		if ( isset( $admin_bar_config['elementor_edit_page']['children'] ) ) {
			foreach ( $admin_bar_config['elementor_edit_page']['children'] as $key => $children ) {
				if ( $children['id'] == 'elementor_app_site_editor' ) {
					$admin_bar_config['elementor_edit_page']['children'][$key]['href'] = admin_url( '/edit.php?post_type=elementor_library&tabs_group=theme' );
					$admin_bar_config['elementor_edit_page']['children'][$key]['class'] = 'neuron-theme-builder-link';
				}
			}
		}

		return $admin_bar_config;
	}

	public function __construct() {
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		add_filter( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );

		add_action( 'elementor/frontend/admin_bar/settings', [ $this, 'add_menu_in_admin_bar' ], 200 );
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'neuron-editor',
			NEURON_ASSETS_URL . 'styles/editor.css',
			[
				'elementor-editor',
			],
			NEURON_BUILDER_VERSION
		);
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'neuron-editor',
			plugin_dir_url(__FILE__) . '../../assets/js/editor.js',
			[
				'backbone-marionette',
				'elementor-common',
				'elementor-editor-modules',
				'elementor-editor-document',
			],
			NEURON_BUILDER_VERSION,
			true
		);

		$locale_settings = [
			'i18n' => [
				'edit_element' => __( 'Edit %s', 'neuron-builder' ),
			],
			'urls' => [
				'modules' => NEURON_MODULES_URL,
			],
			'is_elementor_pro_active' => class_exists( 'ElementorPro\Plugin' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'neuron-editor' ),
		];

		$locale_settings = apply_filters( 'neuron/editor/localize_settings', $locale_settings );

		Utils::print_js_config(
			'neuron-editor',
			'NeuronEditorConfig',
			$locale_settings
		);

		$this->print_config( 'neuron' );

		// Inline CSS to remove Elementor Theme Builder
		if (  ! class_exists( 'ElementorPro\Plugin' ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', function() {
				$css = '.elementor-panel-menu-item-site-editor { display: none !important; }';

				wp_add_inline_style( 'neuron-editor', $css );
			} );
		}

	}

	public function localize_settings( array $settings ) {
		return $settings;
	}

	protected function get_assets_base_url() {
		return NEURON_URL;
	}
}
