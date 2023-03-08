<?php
/**
 * Author Profile Picture
 * 
 * Return the author profile.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Core\Utils;
use Neuron\Modules\DynamicTags\Tags\Base\Data_Tag;
use Neuron\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Profile_Picture extends Data_Tag {

	public function get_name() {
		return 'author-profile-picture';
	}

	public function get_title() {
		return __( 'Author Profile Picture', 'neuron-builder' );
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		Utils::set_global_authordata();

		return [
			'id' => '',
			'url' => get_avatar_url( (int) get_the_author_meta( 'ID' ) ),
		];
	}
}
