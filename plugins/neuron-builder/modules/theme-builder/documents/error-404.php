<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

use Elementor\Controls_Manager;

use Neuron\Modules\ThemeBuilder\Module as ThemeBuilder;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Error_404 extends Single {

	public static function get_sub_type() {
		return 'not_found404';
	}

	public static function get_title() {
		return __( 'Error 404', 'neuron-builder' );
	}

	protected static function get_site_editor_type() {
		return 'error-404';
	}

	public static function get_preview_as_options() {
		return [
			'page/404' => __( '404', 'neuron-builder' ),
		];
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = '404 page';

		return $config;
	}

	public function after_get_content() {
		parent::after_get_content();

		$settings = $this->get_settings();
		$editor = Plugin::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( ! $is_edit_mode && ! is_user_logged_in() ) {
			if ( $settings['redirect_to_url'] == 'site-url'  ) {
				echo '<script> window.location.href = "' . home_url( '/' )  . '" </script>';
			} else if ( $settings['redirect_to_url'] == 'custom' && ! empty ( $settings['custom_url'] ) ) { 
				echo '<script> window.location.href = "' . $settings['custom_url']  . '" </script>';
			}
		}
	}

	protected function register_controls() {
		parent::register_controls();

		$this->start_injection( [
			'of' => 'content_wrapper_html_tag',
			'fallback' => [
				'of' => 'content_wrapper_html_tag',
			],
		] );


		$this->add_control(
			'redirect_to_url',
			[
				'label'   => __( 'Redirect URL', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'site-url' => __( 'Site URL', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' )
				]
			]
		);
		
		$this->add_control(
			'custom_url',
			[
				'label'   => __( 'Custom URL', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => 'https://neuronthemes.com',
				'condition' => [
					'redirect_to_url' => 'custom',
				],
			]
        );

		$this->end_injection();
	}
}
