<?php
/**
 * Post Hero Image
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Data_Tag;
use Neuron\Modules\DynamicTags\Module;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Hero_Image extends Data_Tag {

	public function get_name() {
		return 'post-hero-image';
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_title() {
		return __( 'Hero Image', 'neuron-builder' );
	}

	public function get_value( array $options = [] ) {
		$thumbnail_id = get_post_meta( get_the_ID(), '_neuron_hero_image_id', true );

		if ( $thumbnail_id ) {
			$image_data = [
				'id' => $thumbnail_id,
				'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
			];
		} else {
			$image_data = $this->get_settings( 'fallback' );
		}

		return $image_data;
	}

	protected function register_controls() {
		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'neuron-builder' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}
}
