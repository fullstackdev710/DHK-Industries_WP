<?php
namespace Neuron\Modules\AssetsManager\AssetTypes\Fonts;

use Neuron\Modules\AssetsManager\Classes\Font_Base;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Typekit_Fonts extends Font_Base {

	const TYPEKIT_KIT_ID_OPTION_NAME = 'typekit_api_key';

	const TYPEKIT_FONTS_OPTION_NAME = 'typekit-kit-id';

	const TYPEKIT_FONTS_LINK = 'https://use.typekit.net/%s.css';

	protected $kit_enqueued = false;

	protected $error = '';

	private $api_base = 'https://typekit.com/api/v1/json/kits';

	private function get_typekit_fonts() {
		return get_option( self::TYPEKIT_FONTS_OPTION_NAME, false );
	}

	private function get_typekit_kit_id() {
		return get_option( 'neuron_' . self::TYPEKIT_KIT_ID_OPTION_NAME, false );
	}

	public function get_name() {
		return __( 'Adobe Fonts (TypeKit)', 'neuron-builder' );
	}

	public function get_type() {
		return 'typekit';
	}

	private function fetch_typekit_data() {
		$kit_id = $this->get_typekit_kit_id();
		if ( ! $kit_id ) {
			return false;
		}

		$response = wp_remote_get( $this->api_base . '/' . $kit_id . '/published' );

		// Response is a WP_Error object
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// Response code is not success
		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		if ( 200 !== $response_code ) {
			switch ( $response_code ) {
				case 404:
					$this->error = __( 'Project not found.', 'neuron-builder' );
					break;
				default:
					$this->error = $response_code;
					if ( isset( $response_body->errors ) ) {
						$this->error .= ': ' . implode( ', ', $response_body->errors );
					}
					break;
			}

			return false;
		}

		if ( ! $response_body ) {
			$this->error = __( 'No project data was returned.', 'neuron-builder' );

			return false;
		}

		if ( ! is_object( $response_body ) || ! isset( $response_body->kit ) || ! isset( $response_body->kit->families ) || ! is_array( $response_body->kit->families ) ) {
			return false;
		}

		$families = [];
		foreach ( $response_body->kit->families as $font_family ) {
			$font_css = isset( $font_family->css_names[0] ) ? $font_family->css_names[0] : $font_family->slug;
			$families[ $font_css ] = $this->get_type();
		}
		update_option( self::TYPEKIT_FONTS_OPTION_NAME, $families );

		return $families;
	}

	private function get_kit_fonts() {
		$typekit_fonts = $this->get_typekit_fonts();

		if ( ! $typekit_fonts ) {
			$typekit_fonts = $this->fetch_typekit_data();
		}

		return $typekit_fonts;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function handle_panel_request( array $data ) {
		$font_family = sanitize_text_field( $data['font'] );

		$typekit_fonts = $this->get_kit_fonts();

		if ( ! $typekit_fonts || ! is_array( $typekit_fonts ) ) {
			throw new \Exception( __( 'Error with TypeKit fonts', 'neuron-builder' ) );
		}

		if ( ! in_array( $font_family, array_keys( $typekit_fonts ) ) ) {
			throw new \Exception( __( 'Font missing in Project', 'neuron-builder' ) );
		}

		$kit_id = $this->get_typekit_kit_id();

		return [ 'font_url' => sprintf( self::TYPEKIT_FONTS_LINK, $kit_id ) ];
	}

	public function sanitize_kit_id_settings( $input ) {
		if ( empty( $input ) ) {
			delete_option( self::TYPEKIT_FONTS_OPTION_NAME );
		}

		return $input;
	}


	public function register_fonts_in_control( $fonts ) {
		$typekit_fonts = $this->get_kit_fonts();

		if ( $typekit_fonts ) {
			return array_merge( $typekit_fonts, $fonts );
		}

		return $fonts;
	}

	public function print_font_link( $font ) {
		if ( $this->kit_enqueued ) {
			return;
		}
		if ( $this->is_font_in_kit( $font ) ) {
			$kit_url = sprintf( self::TYPEKIT_FONTS_LINK, $this->get_typekit_kit_id() );
			echo '<link rel="stylesheet" type="text/css" href="' . $kit_url . '">';
			$this->kit_enqueued = true;
		}
	}

	private function is_font_in_kit( $font ) {
		$kit_fonts = $this->get_kit_fonts();
		if ( ! $kit_fonts || ! is_array( $kit_fonts ) ) {
			return false;
		}

		return in_array( $font, array_keys( $kit_fonts ) );
	}

	protected function actions() {
		parent::actions();
		
		add_filter( 'elementor/fonts/additional_fonts', [ $this, 'register_fonts_in_control' ] );
		add_action( 'elementor/fonts/print_font_links/' . $this->get_type(), [ $this, 'print_font_link' ] );
	}
}
