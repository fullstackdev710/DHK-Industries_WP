<?php

namespace Neuron\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Timing extends Base {

	public function get_name() {
		return 'popup_timing';
	}

	protected function register_controls() {
		$this->start_controls_section( 'timing' );

		$this->start_settings_group( 'page_views', __( 'Show after X page views', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'views',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Page Views', 'neuron-builder' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sessions', __( 'Show after X sessions', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'sessions',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Sessions', 'neuron-builder' ),
				'default' => 2,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'times', __( 'Show up to X times', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'times',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Times', 'neuron-builder' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->add_settings_group_control(
			'count',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Count', 'neuron-builder' ),
				'options' => [
					'' => __( 'On Open', 'neuron-builder' ),
					'close' => __( 'On Close', 'neuron-builder' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'url', __( 'When arriving from specific URL', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'action',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __( 'Show', 'neuron-builder' ),
					'hide' => __( 'Hide', 'neuron-builder' ),
					'regex' => __( 'Regex', 'neuron-builder' ),
				],
			]
		);

		$this->add_settings_group_control(
			'url',
			[
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'URL', 'neuron-builder' ),
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sources', __( 'Show when arriving from', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'sources',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'search', 'external', 'internal' ],
				'options' => [
					'search' => __( 'Search Engines', 'neuron-builder' ),
					'external' => __( 'External Links', 'neuron-builder' ),
					'internal' => __( 'Internal Links', 'neuron-builder' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'logged_in', __( 'Hide for logged in users', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'users',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => __( 'All Users', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
			]
		);

		global $wp_roles;

		$roles = array_map( function( $role ) {
			return $role['name'];
		}, $wp_roles->roles );

		$this->add_settings_group_control(
			'roles',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $roles,
				'select2options' => [
					'placeholder' => __( 'Select Roles', 'neuron-builder' ),
				],
				'condition' => [
					'users' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'devices', __( 'Show on devices', 'neuron-builder' ) );

		$this->add_settings_group_control(
			'devices',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'neuron-builder' ),
					'tablet' => __( 'Tablet', 'neuron-builder' ),
					'mobile' => __( 'Mobile', 'neuron-builder' ),
				],
			]
		);

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
