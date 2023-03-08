<?php
/**
 * Class for the import actions used in the One Click Demo Import plugin.
 * Register default WP actions for OCDI plugin.
 *
 * @package ocdi
 */

namespace OCDI;

class ImportActions {
	/**
	 * Register all action hooks for this class.
	 */
	public function register_hooks() {
		// Before content import.
		add_action( 'pt-ocdi/before_content_import_execution', array( $this, 'before_content_import_action' ), 10, 3 );

		// After content import.
		add_action( 'pt-ocdi/after_content_import_execution', array( $this, 'before_widget_import_action' ), 10, 3 );
		add_action( 'pt-ocdi/after_content_import_execution', array( $this, 'widgets_import' ), 20, 3 );

		// Customizer import.
		add_action( 'pt-ocdi/customizer_import_execution', array( $this, 'customizer_import' ), 10, 1 );

		// After full import action.
		add_action( 'pt-ocdi/after_all_import_execution', array( $this, 'after_import_action' ), 10, 3 );

		// Special widget import cases.
		if ( apply_filters( 'pt_ocdi/enable_custom_menu_widget_ids_fix', true ) ) {
			add_action( 'pt-ocdi/widget_settings_array', array( $this, 'fix_custom_menu_widget_ids' ) );
		}
	}


	/**
	 * Change the menu IDs in the custom menu widgets in the widget import data.
	 * This solves the issue with custom menu widgets not having the correct (new) menu ID, because they
	 * have the old menu ID from the export site.
	 *
	 * @param array $widget The widget settings array.
	 */
	public function fix_custom_menu_widget_ids( $widget ) {
		// Skip (no changes needed), if this is not a custom menu widget.
		if ( ! array_key_exists( 'nav_menu', $widget ) || empty( $widget['nav_menu'] ) || ! is_int( $widget['nav_menu'] ) ) {
			return $widget;
		}

		// Get import data, with new menu IDs.
		$ocdi                = OneClickDemoImport::get_instance();
		$content_import_data = $ocdi->importer->get_importer_data();
		$term_ids            = $content_import_data['mapping']['term_id'];

		// Set the new menu ID for the widget.
		$widget['nav_menu'] = $term_ids[ $widget['nav_menu'] ];

		return $widget;
	}

	/**
	 * Execute the widgets import.
	 *
	 * @param array $selected_import_files Actual selected import files (content, widgets, customizer, redux).
	 * @param array $import_files          The filtered import files defined in `ocdi/import_files` filter.
	 * @param int   $selected_index        Selected index of import.
	 */
	public function widgets_import( $selected_import_files, $import_files, $selected_index ) {
		if ( ! empty( $selected_import_files['widgets'] ) ) {
			WidgetImporter::import( $selected_import_files['widgets'] );
		}
	}

	/**
	 * Execute the customizer import.
	 *
	 * @param array $selected_import_files Actual selected import files (content, widgets, customizer, redux).
	 * @param array $import_files          The filtered import files defined in `pt-ocdi/import_files` filter.
	 * @param int   $selected_index        Selected index of import.
	 */
	public function customizer_import( $selected_import_files ) {
		if ( ! empty( $selected_import_files['customizer'] ) ) {
			CustomizerImporter::import( $selected_import_files['customizer'] );
		}
	}

	/**
	 * Execute the action: 'pt-ocdi/before_content_import'.
	 *
	 * @param array $selected_import_files Actual selected import files (content, widgets, customizer).
	 * @param array $import_files          The filtered import files defined in `pt-ocdi/import_files` filter.
	 * @param int   $selected_index        Selected index of import.
	 */
	public function before_content_import_action( $selected_import_files, $import_files, $selected_index ) {
		// Enable SVG Upload before importing
		// neuron_update_mime_types();

		$this->do_import_action( 'pt-ocdi/before_content_import', $import_files[ $selected_index ] );
	}


	/**
	 * Execute the action: 'pt-ocdi/before_widgets_import'.
	 *
	 * @param array $selected_import_files Actual selected import files (content, widgets, customizer).
	 * @param array $import_files          The filtered import files defined in `pt-ocdi/import_files` filter.
	 * @param int   $selected_index        Selected index of import.
	 */
	public function before_widget_import_action( $selected_import_files, $import_files, $selected_index ) {
		$this->do_import_action( 'pt-ocdi/before_widgets_import', $import_files[ $selected_index ] );
	}


	public function after_import_action( $selected_import_files, $import_files, $selected_index ) {
		if ( did_action( 'elementor/loaded' ) ) {
			// Disable Color Scheme
			if ( ! get_option( 'elementor_disable_color_schemes' ) ) {
				update_option( 'elementor_disable_color_schemes', 'yes' );
			}

			// Disable Default Typography
			if ( ! get_option( 'elementor_disable_typography_schemes' ) ) {
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}

			// Enable Unfiltered uploads
			if ( ! get_option( 'elementor_unfiltered_files_upload' ) ) {
				update_option( 'elementor_unfiltered_files_upload', 1 );
			}

			// Remove elementor tracker
			if ( ! get_option( 'elementor_tracker_notice' ) ) {
				update_option( 'elementor_tracker_notice', 1 );
			}

			// Update Elementor Theme Kit Option.
			$args = array(
				'post_type'   => 'elementor_library',
				'meta_query'  => array(
					array(
						'key'   => '_elementor_template_type',
						'value' => 'kit',
					),
				),
			);

			$query = get_posts( $args );

			if ( ! empty( $query ) && isset( $query[1] ) && isset( $query[1]->ID ) ) {
				update_option( 'elementor_active_kit', $query[1]->ID );
			}

			// Disable SVG Support
			add_filter( 'neuron_disable_svg_support', false );

			// neuron_update_mime_types();

			// Regenerate Conditions
			$theme_builder_module = \Neuron\Modules\ThemeBuilder\Module::instance();
			$theme_builder_module->get_conditions_manager()->get_cache()->regenerate();

			// Update 
			if ( ! get_option( 'permalink_structure' ) ) {
				global $wp_rewrite;

				$wp_rewrite->set_permalink_structure('/%postname%/');
				$wp_rewrite->flush_rules();
			}

			// Update Fonts
			$fonts = new \WP_Query([
				'post_type' => 'neuron_font',
				'posts_per_page' => -1,
			]);

			if ( $fonts->have_posts() ) {
				while ( $fonts->have_posts() ) {
					$fonts->the_post();

					$elementor_font_files = get_post_meta( get_the_ID(), 'elementor_font_files', true );
					$updated_font_files = $elementor_font_files;

					foreach ( $updated_font_files as $key => $meta ) {
						$updated_font_files[$key]['svg']['url'] = wp_get_attachment_url( $meta['svg']['id'] ) ? wp_get_attachment_url( $meta['svg']['id'] ) : '';
						$updated_font_files[$key]['woff']['url'] = wp_get_attachment_url( $meta['woff']['id'] ) ? wp_get_attachment_url( $meta['woff']['id']) : '';
						$updated_font_files[$key]['woff2']['url'] = wp_get_attachment_url( $meta['woff2']['id'] ) ? wp_get_attachment_url( $meta['woff2']['id']) : '';
						$updated_font_files[$key]['ttf']['url'] = wp_get_attachment_url( $meta['ttf']['id'] ) ? wp_get_attachment_url( $meta['ttf']['id']) : '';
						$updated_font_files[$key]['eot']['url'] = wp_get_attachment_url( $meta['eot']['id'] ) ? wp_get_attachment_url( $meta['eot']['id']) : '';
					}

					if ( ! empty( $updated_font_files ) ) {
						update_post_meta( get_the_ID(), 'elementor_font_files', $updated_font_files );
					}
				}

				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}
		}

		$this->do_import_action( 'neuron/admin/demo-import/after_import', $import_files[ $selected_index ] );
	}


	/**
	 * Register the do_action hook, so users can hook to these during import.
	 *
	 * @param string $action          The action name to be executed.
	 * @param array  $selected_import The data of selected import from `pt-ocdi/import_files` filter.
	 */
	private function do_import_action( $action, $selected_import ) {
		if ( false !== has_action( $action ) ) {
			$ocdi          = OneClickDemoImport::get_instance();
			$log_file_path = $ocdi->get_log_file_path();

			ob_start();
				do_action( $action, $selected_import );
			$message = ob_get_clean();

			// Add this message to log file.
			$log_added = Helpers::append_to_file(
				$message,
				$log_file_path,
				$action
			);
		}
	}
}
