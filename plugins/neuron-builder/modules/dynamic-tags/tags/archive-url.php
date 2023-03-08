<?php
/**
 * Archive URL
 * 
 * Return the archive URL.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Data_Tag;
use Neuron\Modules\DynamicTags\Module;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_URL extends Data_Tag {

	public function get_name() {
		return 'archive-url';
	}

	public function get_group() {
		return Module::ARCHIVE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_title() {
		return __( 'Archive URL', 'neuron-builder' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	public function get_value( array $options = [] ) {
		return Utils::get_the_archive_url();
	}
}

