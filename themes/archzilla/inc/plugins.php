<?php
if ( ! function_exists( 'archzilla_plugins' ) ) {
	/**
	 * TGMPA
	 * 
	 * An addon which helps theme to install
	 * and activate different plugins.
	 */
	function archzilla_plugins() {

		$neuron_api = 'https://neuronthemes.com/api';
		$neuron_builder_url = $neuron_api . '/plugins/neuron-builder.zip';
		$revslider_url = $neuron_api . '/plugins/revslider.zip';

		$revslider_transient = 'archzilla';
		$neuron_transient = 'archzilla_neuron';

		$free_plugins = [
			[
				'name'      => esc_html__( 'Elementor', 'archzilla' ),
				'slug'      => 'elementor',
				'required'  => true
			],
		];

		// Neuron Builder
		if ( ! class_exists( 'Neuron' ) ) {

			if ( ! get_transient( $neuron_transient ) ) {
				$neuron_builder = archzilla_fetch_remote_file( $neuron_builder_url );
				set_transient( $neuron_transient, $neuron_builder );
			}

			if ( ! empty( get_transient( $neuron_transient ) ) && ! is_wp_error( get_transient( $neuron_transient ) ) ) {
					$free_plugins[] = [
						'name'      		=> esc_html__( 'Neuron Builder', 'archzilla' ),
						'slug'      		=> 'neuron-builder',
						'required'  		=> true,
						'source' 			=> get_transient( $neuron_transient )['url']
					];
			}
		}

		$free_plugins[] = [
			'name'      => esc_html__( 'WooCommerce', 'archzilla' ),
			'slug'      => 'woocommerce',
			'required'  => true
		];

	
		// Revslider
		if ( ! class_exists( 'RevSlider' ) ) {

			if ( ! get_transient( $revslider_transient ) ) {
				$revslider = archzilla_fetch_remote_file( $revslider_url );
				set_transient( $revslider_transient, $revslider );
			}

			if ( ! empty( get_transient( $revslider_transient ) ) && ! is_wp_error( get_transient( $revslider_transient ) ) ) {
					$free_plugins[] = [
						'name'      		=> esc_html__( 'Revolution Slider', 'archzilla' ),
						'slug'      		=> 'revslider',
						'required'  		=> true,
						'source' 			=> get_transient( $revslider_transient )['url']
					];
			}
		}

		$plugins = apply_filters( 'neuron_tgmpa_plugins', $free_plugins );
		
		$config = array(
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => false,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => ''
		);
		
		tgmpa( $plugins, $config );
    }
    
    add_action( 'tgmpa_register', 'archzilla_plugins' );

}

