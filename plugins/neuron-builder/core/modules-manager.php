<?php
/**
 * Modules Manager
 * 
 * Handles all the modules
 * via the namespacing.
 * 
 * @since 1.0.0
 */

namespace Neuron\Core;

use Neuron\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	private $core_modules = [];

	public function __construct() {
		$modules = [
			'query-control',
			'custom-attributes',
			'custom-css',
			
			// // 'preset', // TODO
			'role-manager',
			'global-widget',
			'assets-manager',
			'popup', 
			'motion-fx',
			'column-linking',

			// // With Widgets
			'theme-builder',
			'posts',
			'portfolio',
			'gallery',
			'forms',
			'slides',
			'nav-menu',
			'flip-box',
			'interactive-posts',
			'animated-heading',
			'maps',
			'testimonial-carousel',
			'table-of-contents',
			'countdown',
			'blockquote',
			'woocommerce',
			'price-list',
			'price-table',
			'share-buttons',
			'theme-elements',
			'scrolling-text',

			'library',
			'dynamic-tags',
			'sticky',
			'animations',
			'template-library',
		];

        foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\Neuron\Modules\\' . $class_name . '\Module';

			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}

		$core_modules = [
			'compatibility'
		];

		foreach ( $core_modules as $core_module_name ) {
			$class_name = str_replace( '-', ' ', $core_module_name );
			$class_name = str_replace( ' ', '_', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\' . $class_name . '\Module';

			$this->core_modules[ $core_module_name ] = new $class_name();
		}
	}

	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
