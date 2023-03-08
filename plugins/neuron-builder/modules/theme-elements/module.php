<?php
/**
 * Theme Elements Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'theme-elements';
	}

	public function get_widgets() {
		$widgets = [ 
			'Search_Form',
			'Author_Box', 
			'Post_Info', 
			'Post_Navigation', 
			'Post_Comments', 
			'Breadcrumbs', 
			'Sitemap', 
		];

		return $widgets;
	}

}
