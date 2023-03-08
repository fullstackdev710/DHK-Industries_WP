<?php
/**
 * Template Library
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\TemplateLibrary;

use Elementor\Plugin;

use Neuron\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

    public function get_name() {
		return 'neuron-template-library';
    }

	public function __construct() {
        parent::__construct();


        include( NEURON_PATH . 'modules/template-library/source.php' );

        $unregister_source = function($id) {
            unset( $this->_registered_sources[ $id ] );
        };

        $unregister_source->call( Plugin::instance()->templates_manager, 'remote' );

        Plugin::instance()->templates_manager->register_source( 'Neuron\Modules\TemplateLibrary\Source_Custom' );
    }
}
