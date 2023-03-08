<?php
namespace Neuron\Modules\ThemeBuilder\Documents;

use Elementor\Controls_Manager;

use Neuron\Plugin;
use Neuron\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive extends Archive_Single_Base {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'archive';
		$properties['condition_type'] = 'archive';

		return $properties;
	}

	protected static function get_site_editor_type() {
		return 'archive';
	}


	public static function get_title() {
		return __( 'Archive', 'neuron-builder' );
	}

	protected static function get_editor_panel_categories() {
		$categories = [
			'neuron-elements-archive' => [
				'title' => __( 'Archive', 'neuron-builder' ),
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}

	public static function get_preview_as_default() {
		return 'archive/recent_posts';
	}

	public static function get_preview_as_options() {
		$post_type_archives = [];

		$taxonomies = [];

		$post_types = Module::get_public_post_types();

		foreach ( $post_types as $post_type => $label ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object->has_archive ) {
				$post_type_archives[ 'post_type_archive/' . $post_type ] = sprintf( __( '%s Archive', 'neuron-builder' ), $post_type_object->label );
			}

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			$post_type_taxonomies = wp_filter_object_list( $post_type_taxonomies, [
				'public' => true,
				'show_in_nav_menus' => true,
			] );

			foreach ( $post_type_taxonomies as $slug => $object ) {
				$taxonomies[ 'taxonomy/' . $slug ] = sprintf( __( '%s Archive', 'neuron-builder' ), $object->label );
			}
		}

		$options = [
			'archive/recent_posts' => __( 'Recent Posts', 'neuron-builder' ),
			'archive/date' => __( 'Date Archive', 'neuron-builder' ),
			'archive/author' => __( 'Author Archive', 'neuron-builder' ),
			'search' => __( 'Search Results', 'neuron-builder' ),
		];

		$options += $taxonomies + $post_type_archives;

		return [
			'archive' => [
				'label' => __( 'Archive', 'neuron-builder' ),
				'options' => $options,
			],
		];
	}

	public function after_get_content() {
		parent::after_get_content();

		$settings = $this->get_settings();
		$editor = Plugin::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( ! $is_edit_mode && ! is_user_logged_in() ) {
			if ( $settings['redirect_to_url'] == 'site-url'  ) {
				echo '<script> window.location.href = "' . home_url( '/' )  . '" </script>';
			} else if ( $settings['redirect_to_url'] == 'custom' && ! empty ( $settings['custom_url'] ) ) { 
				echo '<script> window.location.href = "' . $settings['custom_url']  . '" </script>';
			}
		}
	}

	protected function register_controls() {
		parent::register_controls();

		$this->start_injection( [
			'of' => 'content_wrapper_html_tag',
			'fallback' => [
				'of' => 'content_wrapper_html_tag',
			],
		] );


		$this->add_control(
			'redirect_to_url',
			[
				'label'   => __( 'Redirect URL', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'site-url' => __( 'Site URL', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' )
				]
			]
		);
		
		$this->add_control(
			'custom_url',
			[
				'label'   => __( 'Custom URL', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => 'https://neuronthemes.com',
				'condition' => [
					'redirect_to_url' => 'custom',
				],
			]
        );

		$this->end_injection();
	}
}
