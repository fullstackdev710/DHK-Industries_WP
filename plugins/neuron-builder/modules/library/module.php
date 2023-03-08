<?php
/**
 * Library
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Library;

use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;

use Neuron\Base\Module_Base;
use Neuron\Modules\Library\Classes\Shortcode;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Template',
		];
	}

	public function __construct() {
		parent::__construct();

		$this->add_filters();

		new Shortcode();
	}

	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' );
	}

	public function get_name() {
		return 'library';
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'home_url' => home_url(),
				'edit_template' => __( 'Edit Template', 'neuron-builder' ),
			],
		] );

		return $settings;
	}

	public function add_filters() {
		add_filter( 'neuron/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'neuron/admin/localize_settings', [ $this, 'localize_settings' ] ); 
	}
}
