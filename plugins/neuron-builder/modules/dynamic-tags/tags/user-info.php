<?php
/**
 * User Info
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

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return __( 'User Info', 'neuron-builder' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'neuron-builder' ),
					'id' => __( 'ID', 'neuron-builder' ),
					'display_name' => __( 'Display Name', 'neuron-builder' ),
					'login' => __( 'Username', 'neuron-builder' ),
					'first_name' => __( 'First Name', 'neuron-builder' ),
					'last_name' => __( 'Last Name', 'neuron-builder' ),
					'description' => __( 'Bio', 'neuron-builder' ),
					'email' => __( 'Email', 'neuron-builder' ),
					'url' => __( 'Website', 'neuron-builder' ),
					'meta' => __( 'User Meta', 'neuron-builder' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'neuron-builder' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

}
