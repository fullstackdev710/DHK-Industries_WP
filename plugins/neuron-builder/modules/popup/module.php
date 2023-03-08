<?php
/**
 * Popup Builder
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Popup;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Core\Documents_Manager;
use Elementor\Core\DynamicTags\Manager as DynamicTagsManager;
use Elementor\TemplateLibrary\Source_Local;

use Neuron\Base\Module_Base;
use Neuron\Modules\ThemeBuilder\Classes\Locations_Manager;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	const DOCUMENT_TYPE = 'popup';

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
		add_action( 'elementor/theme/register_locations', [ $this, 'register_location' ] );
		add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_tag' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'wp_footer', [ $this, 'print_popups' ] );
		add_action( 'neuron/init', [ $this, 'add_form_action' ] );

		add_filter( 'neuron/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'elementor/finder/categories', [ $this, 'add_finder_items' ] );
	}

	public function get_name() {
		return 'popup';
	}

	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' ); 
	}

	public function add_form_action() {
		$this->add_component( 'form-action', new Form_Action() );
	}

	public static function add_popup_to_location( $popup_id ) {
		$theme_builder = Plugin::instance()->modules_manager->get_modules( 'theme-builder' );

		$theme_builder->get_locations_manager()->add_doc_to_location( Document::get_property( 'location' ), $popup_id );
	}

	public function register_documents( Documents_Manager $documents_manager ) {
		$documents_manager->register_document_type( self::DOCUMENT_TYPE, Document::get_class_full_name() );
	}

	public function register_location( Locations_Manager $location_manager ) {
		$location_manager->register_location(
			'popup',
			[
				'label' => __( 'Popup', 'neuron-builder' ),
				'multiple' => true,
				'public' => false,
				'edit_in_content' => false,
			]
		);
	}

	public function print_popups() {
		neuron_theme_do_location( 'popup' );
	}

	public function register_tag( DynamicTagsManager $dynamic_tags ) {
		$dynamic_tags->register_tag( __NAMESPACE__ . '\Tag' );
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'neuron_popup_save_display_settings', [ $this, 'save_display_settings' ] );
	}

	public function localize_settings( array $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'popups' => __( 'Popups', 'neuron-builder' ),
				'triggers' => __( 'Triggers', 'neuron-builder' ),
				'timing' => __( 'Advanced Rules', 'neuron-builder' ),
				'popup_settings_introduction_title' => __( 'Please Note', 'neuron-builder' ),
				'popup_settings_introduction_message' => __( 'Popup settings are accessed via the settings icon in the bottom menu.', 'neuron-builder' ),
			],
		] );

		return $settings;
	}

	public function save_display_settings( $data ) {
		$popup_document = Plugin::elementor()->documents->get( $data['editor_post_id'] );

		$popup_document->save_display_settings_data( $data['settings'] );
	}

	public function admin_menu() {
		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, '', __( 'Popups', 'neuron-builder' ), 'publish_posts', $this->get_admin_url( true ) );
	}

	public function add_finder_items( array $categories ) {
		$categories['general']['items']['popups'] = [
			'title' => __( 'Popups', 'neuron-builder' ),
			'icon' => 'library-save',
			'url' => $this->get_admin_url(),
			'keywords' => [ 'template', 'popup', 'library' ],
		];

		$categories['create']['items']['popups'] = [
			'title' => __( 'Add New Popup', 'neuron-builder' ),
			'icon' => 'plus-circle-o',
			'url' => $this->get_admin_url() . '#add_new',
			'keywords' => [ 'template', 'theme', 'popup', 'new', 'create' ],
		];

		return $categories;
	}

	private function get_admin_url( $relative = false ) {
		$base_url = Source_Local::ADMIN_MENU_SLUG;
		if ( ! $relative ) {
			$base_url = admin_url( $base_url );
		}

		return add_query_arg(
			[
				'tabs_group' => 'popup',
				'elementor_library_type' => 'popup',
			],
			$base_url
		);
	}
}
