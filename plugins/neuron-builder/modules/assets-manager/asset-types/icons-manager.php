<?php
namespace Neuron\Modules\AssetsManager\AssetTypes;

use Neuron\Modules\AssetsManager\Classes;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Icons_Manager {

	private $enqueued_fonts = [];

	protected $icon_types = [];

	public function get_icon_type_object( $type = null ) {
		if ( null === $type ) {
			return $this->icon_types;
		}

		if ( isset( $this->icon_types[ $type ] ) ) {
			return $this->icon_types[ $type ];
		}

		return false;
	}

	public function add_icon_type( $icon_type, $instance ) {
		$this->icon_types[ $icon_type ] = $instance;
	}

	protected function actions() {
		do_action( 'neuron/icons_manager_loaded', $this );
	}

	public function __construct() {
        $this->actions();
        
		$this->add_icon_type( 'neuron-icons', new Icons\Neuron_Icons() );
	}
}
