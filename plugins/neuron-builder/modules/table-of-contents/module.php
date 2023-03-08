<?php
/**
 * Table of Contents
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\TableOfContents;

use Neuron\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_filter( 'neuron/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'neuron/frontend/localize_settings', [ $this, 'localize_settings' ] );
	}

	public function get_widgets() {
		return [
			'Table_Of_Contents',
		];
	}

	public function get_name() {
		return 'neuron-table-of-contents';
	}

	public function localize_settings( array $settings ) {
		$settings['i18n']['toc_no_headings_found'] = __( 'No headings were found on this page.', 'neuron-builder' );

		return $settings;
	}
}
