<?php
/**
 * Author Info
 * 
 * Return the author info
 * Bio, Email or Website.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Tag;
use Neuron\Modules\DynamicTags\Module;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Info extends Tag {

	public function get_name() {
		return 'author-info';
	}

	public function get_title() {
		return __( 'Author Info', 'neuron-builder' );
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			return;
		}

		$value = get_the_author_meta( $key );

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label' => __( 'Field', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'description',
				'options' => [
					'description' => __( 'Bio', 'neuron-builder' ),
					'email' => __( 'Email', 'neuron-builder' ),
					'url' => __( 'Website', 'neuron-builder' ),
				],
			]
		);
	}
}
