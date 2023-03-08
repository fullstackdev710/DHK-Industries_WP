<?php
/**
 * Theme Builder
 * 
 * Adds the ability to override part of 
 * the theme via templates and dynamic
 * elements.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder;

use Elementor\Core\Base\Document;
use Elementor\Elements_Manager;
use Elementor\TemplateLibrary\Source_Local;

use Neuron\Base\Module_Base;
use Neuron\Core\Utils;
use Neuron\Modules\ThemeBuilder\Classes;
use Neuron\Modules\ThemeBuilder\Documents\Single;
use Neuron\Modules\ThemeBuilder\Documents\Theme_Document;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public static function is_preview() {
		return Plugin::elementor()->preview->is_preview_mode() || is_preview();
	}

	public static function get_public_post_types( $args = [] ) {
		$post_types = Utils::get_public_post_types( $args );

		if ( class_exists( 'woocommerce' ) ) {
			unset( $post_types['product'] );
		}

		return $post_types;
	}

	public function get_name() {
		return 'neuron-theme-builder';
	}

	public function get_widgets() {
		$widgets = [
			'Site_Logo',
			'Site_Title',
			'Page_Title',
			'Post_Title',
			'Post_Excerpt',
			'Post_Content',
			'Post_Featured_Image',
			'Archive_Title',
		];

		if ( class_exists( '\Neuron\Modules\Posts\Widgets\Posts' ) ) {
			$widgets[] = 'Archive_Posts';
		}

		return $widgets;
	}

	public function get_conditions_manager() {
		return $this->get_component( 'conditions' );
	}

	public function get_locations_manager() {
		return $this->get_component( 'locations' );
	}

	public function get_preview_manager() {
		return $this->get_component( 'preview' );
	}

	public function get_types_manager() {
		return $this->get_component( 'templates_types' );
	}

	public function get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::elementor()->documents->get( $post_id );
		} catch ( \Exception $e ) {
			// Do nothing.
			unset( $e );
		}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'publish_settings' => __( 'Publish Settings', 'neuron-builder' ),
				'conditions' => __( 'Conditions', 'neuron-builder' ),
				'display_conditions' => __( 'Display Conditions', 'neuron-builder' ),
				'choose' => __( 'Choose', 'neuron-builder' ),
				'add_condition' => __( 'Add Condition', 'neuron-builder' ),
				'conditions_title' => __( 'Where Do You Want to Display Your %s?', 'neuron-builder' ),
				'conditions_description' => __( 'Set the conditions that determine where your %s is used throughout your site.', 'neuron-builder' ) . '<br>' . __( 'For example, choose \'Entire Site\' to display the template across your site.', 'neuron-builder' ),
				'conditions_publish_screen_description' => __( 'Set the conditions for this template, you can include or exclude it on certain pages.', 'neuron-builder' ),
				'save_and_close' => __( 'Save & Close', 'neuron-builder' ),
				'neuron_theme_builder' => __( 'Theme Builder', 'neuron-builder' ),
			],
			'theme_builder_url' => admin_url( '/edit.php?post_type=elementor_library&tabs_group=theme' )
		] );

		return $settings;
	}

	public function document_config( $config, $post_id ) {
		$document = $this->get_document( $post_id );

		if ( ! $document ) {
			return $config;
		}

		$types_manager = $this->get_types_manager();
		$conditions_manager = $this->get_conditions_manager();
		$template_type = $this->get_template_type( $post_id );

		$config = array_replace_recursive( $config, [
			'theme_builder' => [
				'types' => $types_manager->get_types_config(),
				'conditions' => $conditions_manager->get_conditions_config(),
				'template_conditions' => ( new Classes\Template_Conditions() )->get_config(),
				'is_theme_template' => $this->is_theme_template( $post_id ),
				'settings' => [
					'template_type' => $template_type,
					'location' => $document->get_location(),
					'conditions' => $conditions_manager->get_document_conditions( $document ),
				],
			],
		] );

		return $config;
	}

	public function register_controls() {
		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->register_control( Classes\Conditions_Repeater::CONTROL_TYPE, new Classes\Conditions_Repeater() );
	}

	public function create_new_dialog_types( $types ) {
		foreach ( $types as $type => $label ) {
			$document_type = Plugin::elementor()->documents->get_document_type( $type );
			$instance = new $document_type();

			if ( $instance instanceof Theme_Document && 'section' !== $type ) {
				$types[ $type ] .= $instance->get_location_label();
			}
		}

		return $types;
	}

	public function print_location_field() {
		$locations = $this->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__location__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__location" class="elementor-form-field__label">
				<?php echo __( 'Select a Location', 'neuron-builder' ); ?>
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__location" class="elementor-form-field__select" name="meta_location">
					<option value="">
						<?php echo __( 'Select...', 'neuron-builder' ); ?>
					</option>
					<?php

					foreach ( $locations as $location => $settings ) {
						echo sprintf( '<option value="%1$s">%2$s</option>', $location, $settings['label'] );
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function print_post_type_field() {
		$post_types = self::get_public_post_types( [
			'exclude_from_search' => false,
		] );

		if ( empty( $post_types ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__post-type__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__post-type" class="elementor-form-field__label">
				<?php echo __( 'Select Post Type', 'neuron-builder' ); ?>
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__post-type" class="elementor-form-field__select" name="<?php echo Single::REMOTE_CATEGORY_META_KEY; ?>">
					<option value="">
						<?php echo __( 'Select', 'neuron-builder' ); ?>...
					</option>
					<?php

					foreach ( $post_types as $post_type => $label ) {
						$doc_type = Plugin::elementor()->documents->get_document_type( $post_type );
						$doc_class = new $doc_type();

						$is_base_page = class_exists( '\Elementor\Core\DocumentTypes\PageBase' ) && $doc_class instanceof \Elementor\Core\DocumentTypes\PageBase;

						if ( ! $is_base_page ) {
							$doc_name = $doc_class->get_name();
							$is_base_page = in_array( $doc_name, [ 'post', 'page', 'wp-post', 'wp-page' ] );
						}

						if ( $is_base_page ) {
							$post_type_object = get_post_type_object( $post_type );
							echo sprintf( '<option value="%1$s">%2$s</option>', $post_type, $post_type_object->labels->singular_name );
						}
					}

					// 404.
					echo sprintf( '<option value="%1$s">%2$s</option>', 'not_found404', __( '404 Page', 'neuron-builder' ) );

					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function admin_head() {
		$current_screen = get_current_screen();
		if ( $current_screen && in_array( $current_screen->id, [ 'elementor_library', 'edit-elementor_library' ] ) ) {
			$this->get_locations_manager()->register_locations();
		}
	}

	public function admin_columns_content( $column_name, $post_id ) {
		if ( 'elementor_library_type' === $column_name ) {
			$document = Plugin::elementor()->documents->get( $post_id );

			if ( $document instanceof Theme_Document ) {
				$location_label = $document->get_location_label();

				if ( $location_label ) {
					echo ' - ' . esc_html( $location_label );
				}
			}
		}
	}

	public function get_template_type( $post_id ) {
		return Source_local::get_template_type( $post_id );
	}

	public function is_theme_template( $post_id ) {
		$document = Plugin::elementor()->documents->get( $post_id );

		return $document instanceof Theme_Document;
	}

	public function on_elementor_editor_init() {
		Plugin::elementor()->common->add_template( __DIR__ . '/views/panel-template.php' );
	}

	public function add_finder_items( array $categories ) {
		$categories['general']['items']['theme-builder'] = [
			'title' => __( 'Theme Builder', 'neuron-builder' ),
			'icon' => 'library-save',
			'url' => $this->get_admin_templates_url(),
			'keywords' => [ 'template', 'header', 'footer', 'single', 'archive', 'search', '404', 'library' ],
		];

		$categories['create']['items']['new-header-template'] = [
			'title' => __( 'Add New Header Template', 'neuron-builder' ),
			'icon' => 'plus-circle-o',
			'url' => $this->get_create_url( 'header' ),
			'keywords' => [ 'header', 'header template' ],
		];

		$categories['create']['items']['new-footer-template'] = [
			'title' => __( 'Add New Footer Template', 'neuron-builder' ),
			'icon' => 'plus-circle-o',
			'url' => $this->get_create_url( 'footer' ),
			'keywords' => [ 'footer', 'footer template' ],
		];

		$categories['create']['items']['theme-template'] = [
			'title' => __( 'Add New Theme Template', 'neuron-builder' ),
			'icon' => 'plus-circle-o',
			'url' => $this->get_admin_templates_url() . '#add_new&tabs_group=header',
			'keywords' => [ 'template', 'theme', 'new', 'create' ],
		];

		return $categories;
	}

	public function admin_menu() {
		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, '', __( 'Theme Builder', 'neuron-builder' ), 'publish_posts', $this->get_admin_templates_url( true ) );
	}

	public static function get_create_url( $type = 'header' ) {
		$base_create_url = \Elementor\Plugin::$instance->documents->get_create_new_post_url( Source_Local::CPT );

		return add_query_arg( [ 'template_type' => $type ], $base_create_url );
	}

	private function get_admin_templates_url( $relative = false ) {
		$base_url = Source_Local::ADMIN_MENU_SLUG;

		if ( ! $relative ) {
			$base_url = admin_url( $base_url );
		}

		return add_query_arg( 'tabs_group', 'theme', $base_url );
	}

	public function __construct() {
		parent::__construct();

		if ( class_exists( 'ElementorPro\Plugin' ) ) {
			return;
		}

		require __DIR__ . '/api.php';

		$this->add_component( 'theme_support', new Classes\Theme_Support() );
		$this->add_component( 'conditions', new Classes\Conditions_Manager() );
		$this->add_component( 'templates_types', new Classes\Templates_Types_Manager() );
		$this->add_component( 'preview', new Classes\Preview_Manager() );
		$this->add_component( 'locations', new Classes\Locations_Manager() );

		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		// Editor
		add_action( 'elementor/editor/init', [ $this, 'on_elementor_editor_init' ] );
		add_filter( 'neuron/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'elementor/document/config', [ $this, 'document_config' ], 10, 2 );

		// Admin
		add_action( 'admin_head', [ $this, 'admin_head' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'manage_' . Source_Local::CPT . '_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );
		add_action( 'elementor/template-library/create_new_dialog_fields', [ $this, 'print_location_field' ] );
		add_action( 'elementor/template-library/create_new_dialog_fields', [ $this, 'print_post_type_field' ] );
		add_filter( 'elementor/template-library/create_new_dialog_types', [ $this, 'create_new_dialog_types' ] );

		// Common
		add_filter( 'elementor/finder/categories', [ $this, 'add_finder_items' ] );
	}
}
