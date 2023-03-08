<?php
namespace Neuron\Modules\Woocommerce\Documents;

use Neuron\Modules\ThemeBuilder\Documents\Archive;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Archive extends Archive {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'archive';
		$properties['condition_type'] = 'product_archive';

		return $properties;
	}

	public function get_name() {
		return 'product-archive';
	}

	public static function get_title() {
		return __( 'Product Archive', 'neuron-builder' );
	}

	public function enqueue_scripts() {
		if ( Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {
			wp_enqueue_script( 'woocommerce' );
		}
	}

	public function get_container_attributes() {
		$attributes = parent::get_container_attributes();

		$attributes['class'] .= ' product';

		return $attributes;
	}

	public function filter_body_classes( $body_classes ) {
		$body_classes = parent::filter_body_classes( $body_classes );

		if ( get_the_ID() === $this->get_main_id() || Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {
			$body_classes[] = 'woocommerce';
		}

		return $body_classes;
	}

	public static function get_preview_as_default() {
		return 'post_type_archive/product';
	}

	public static function get_preview_as_options() {
		$post_type_archives = [];
		$taxonomies = [];
		$post_type = 'product';

		$post_type_object = get_post_type_object( $post_type );

		$post_type_archives[ 'post_type_archive/' . $post_type ] = $post_type_object->label . ' ' . __( 'Archive', 'neuron-builder' );

		$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$post_type_taxonomies = wp_filter_object_list( $post_type_taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );

		foreach ( $post_type_taxonomies as $slug => $object ) {
			$taxonomies[ 'taxonomy/' . $slug ] = $object->label . ' ' . __( 'Archive', 'neuron-builder' );
		}

		$options = [
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

	public function __construct( array $data = [] ) {
		parent::__construct( $data );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 11 );
	}

	protected static function get_editor_panel_categories() {
		$categories = [
			'neuron-woo-elements-archive' => [
				'title' => __( 'Product Archive', 'neuron-builder' ),
			],
			// Move to top as active.
			'neuron-woo-elements' => [
				'title' => __( 'WooCommerce', 'neuron-builder' ),
				'active' => true,
			],
		];

		$categories += parent::get_editor_panel_categories();

		unset( $categories['neuron-elements-archive'] );

		return $categories;
	}

	public static function get_editor_panel_config() {
		$config = parent::get_editor_panel_config();

		$config['widgets_settings']['neuron-archive-title']['categories'][] = 'neuron-woo-elements-archive';

		return $config;
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'preview_type',
			[
				'default' => 'post_type_archive/product',
			]
		);
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'product archive';

		return $config;
	}
}
