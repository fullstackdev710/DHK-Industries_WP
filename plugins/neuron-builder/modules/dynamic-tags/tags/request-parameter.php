<?php
/**
 * Request Parameter
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

class Request_Parameter extends Tag {

	public function get_name() {
		return 'request-arg';
	}

	public function get_title() {
		return __( 'Request Parameter', 'neuron-builder' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		$settings = $this->get_settings();

		$request_type = isset( $settings['request_type'] ) ? strtoupper( $settings['request_type'] ) : false;
		$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : false;
		$value = '';

		if ( ! $param_name || ! $request_type ) {
			return '';
		}

		if ( $request_type == 'POST' ) {
			if ( ! isset( $_POST[ $param_name ] ) ) {
				return '';
			}
			$value = $_POST[ $param_name ];
		} elseif ( $request_type == 'GET' ) {
			if ( ! isset( $_GET[ $param_name ] ) ) {
				return '';
			}
			$value = $_GET[ $param_name ];
		} elseif ( $request_type == 'QUERY_VAR' ) {

			if ( $param_name == 'neuron_posts_found' ) {
				global $wp_query;

				$value = $wp_query->found_posts;
			} else {
				$value = get_query_var( $param_name );
			}
		}

		echo htmlentities( wp_kses_post( $value ) );
	}

	protected function register_controls() {
		$this->add_control(
			'request_type',
			[
				'label'   => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'get',
				'options' => [
					'get' => 'Get',
					'post' => 'Post',
					'query_var' => 'Query Var',
				],
			]
		);
		$this->add_control(
			'param_name',
			[
				'label'   => __( 'Parameter Name', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
			]
		);
	}
	
}
