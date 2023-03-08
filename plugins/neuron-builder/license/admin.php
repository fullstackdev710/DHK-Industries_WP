<?php
/**
 * Neuron License Admin
 * 
 * @since 1.0.0
 */

namespace Neuron\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin {

    /**
     * Themes
     * 
     * Extra check for the ID of the themes that
     * are built with Neuron Builder.
     */
    private $themes = [
        '32555666', // Kaon
        '33034718', // Merope
        '33356192', // Amina
        '33530503', // Otivar
        '33530808', // Ginea
        '34017208', // Kotona
        '34016329', // Travelosophy
        '36165360', // Manila
        '35618018', // Floona
        '35617830', // Zaragoza
        '36396770', // Arica
        '36438888', // Akko
        '36890934', // Nikaia
        '37804010', // Lilium
        '38511319', // Taranto
        '38495231', // Minimo
        '39177103', // Asteria
        '39732215', // Conlectio
        '40334815', // Ellada
        '42038980', // Archzilla
        '42570087', // Hikone
        '43734591', // Tampa
    ];

    public static function is_license_activated() {
        if ( ! empty( get_site_transient( 'neuron_license_verification' ) ) && get_site_transient( 'neuron_license_verification' ) == 'success' ) {
            return true;
        }
    }

	public function __construct() {
        include('envato-api/envato-market.php');

        if ( ! empty( get_site_transient( 'license_themes' ) ) && isset( get_site_transient( 'license_themes' )['purchased'] ) ) {
          
            foreach ( get_site_transient( 'license_themes' )['purchased'] as $theme ) {
          
                if ( isset( $theme['id'] ) && ! empty( $theme['id'] ) ) {
                    if ( in_array( $theme['id'], $this->themes ) ) {
                        if ( empty( get_site_transient( 'neuron_license_verification' ) ) ) {
                            
                            set_site_transient( 'neuron_license_verification', 'success', HOUR_IN_SECONDS );

                            // Prevent Error
                            if ( get_site_transient( 'license_error_information' ) ) {
                                delete_site_transient( 'license_error_information' );
                            }
                        }
                    }
                }
            }
        }
    }
}
