<?php
/**
 * Plugin Core
 * 
 * @package Neuron Builder
 * @since 1.0.0
 */

namespace Neuron;

defined('ABSPATH') || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Core\Responsive\Files\Frontend as FrontendFile;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\DocumentTypes\PageBase as PageBase;
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;

use Neuron\Core\Admin\Admin;
use Neuron\Core\Editor\Editor;
use Neuron\Core\Modules_Manager;
use Neuron\Core\Preview\Preview;
use Neuron\Core\Helpers;
use Neuron\Core\Helpers\Attachments;
use Neuron\Widgets as Widgets;

// Notice if the Elementor is not active
if ( ! did_action( 'elementor/loaded' ) ) {
	return;
}

/**
 * Plugin class.
 *
 * @since 1.0.0
 */
class Plugin {

	public static $_instance;

	public $modules_manager;

	public static $plugin_path;

	public $editor;

	public $preview;

	public $presets;

	public $admin;

	public $attachments;

	public $license_admin;

	public $demo_importer;

	/**
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Autoload classes based on namespace.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $class Name of class.
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		if ( ! class_exists( $class ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class
				)
			);
			$filename = NEURON_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}

	public function add_neuron_categories() {
		// Add this category, after basic category.
		Elementor::$instance->elements_manager->add_category(
			'neuron-elements',
			[
				'title' => __( 'Neuron Builder', 'neuron-builder' ),
			],
			1
		);

		Elementor::$instance->elements_manager->add_category(
			'neuron-elements-site',
			[
				'title' => __( 'Site', 'neuron-builder' ),
				'active' => false,
			],
			2
		);
		
		Elementor::$instance->elements_manager->add_category(
			'neuron-elements-single',
			[
				'title' => __( 'Single', 'neuron-builder' ),
				'active' => false,
			],
			3
		);

		if ( class_exists( 'WooCommerce' ) ) {
			Elementor::$instance->elements_manager->add_category(
				'neuron-woo-elements',
				[
					'title' => __( 'WooCommerce', 'neuron-builder' ),
					'active' => false,
				],
				4
			);
		}
	}

	/**
	 * Register Scripts After Elementor Scripts
	 * 
	 * @since 1.0.0
	 */
	public function register_frontend_scripts() {

		// TODO SUFFIX
		wp_register_script(
			'neuron-sticky',
			NEURON_ASSETS_URL . 'js/sticky.js',
			[
				'elementor-frontend',
				'jquery'
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		wp_register_script(
			'neuron-packery',
			NEURON_ASSETS_URL . 'scripts/packery.js',
			[
				'neuron-frontend',
				'jquery'
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		wp_register_script(
			'neuron-typed',
			NEURON_ASSETS_URL . 'scripts/typed.js',
			[
				'elementor-frontend',
				'jquery'
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		wp_register_script(
			'neuron-object-fit',
			NEURON_ASSETS_URL . 'scripts/object-fit.js',
			[
				'elementor-frontend',
				'jquery'
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		wp_register_script(
			'neuron-social-share',
			NEURON_ASSETS_URL . 'js/social-share.js',
			[
				'jquery'
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		if ( get_option( 'neuron_google_map_api_key' ) ) {
			wp_register_script(
				'neuron-google-maps',
				'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( get_option( 'neuron_google_map_api_key' ) ),
				[
					'elementor-frontend',
					'jquery'
				],
				NEURON_BUILDER_VERSION,
				true // in_footer
			);
		}

		wp_register_script(
			'neuron-frontend',
			NEURON_ASSETS_URL . 'js/frontend.js',
			[
				'elementor-frontend',
			],
			NEURON_BUILDER_VERSION,
			true // in_footer
		);

		wp_register_script(
			'neuron-elements-handlers',
			NEURON_ASSETS_URL . 'js/elements.js',
			[
				'elementor-frontend',
			],
			NEURON_BUILDER_VERSION,
			true
		);
	}

	/**
	 * Register Preview Scripts
	 * 
	 * @since 1.0.0
	 */
	public function register_preview_scripts() {
		// $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script(
			'neuron-preview',
			plugin_dir_url( __FILE__ ) . '../assets/js/preview.js',
			[
				'elementor-frontend',
			],
			NEURON_BUILDER_VERSION,
			true
		);
	}

	public function get_responsive_stylesheet_templates( $templates ) {
		$templates_paths = glob( self::get_responsive_templates_path() . '*.css' );

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'neuron-custom-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return $templates;
	}

	/**
	 * Enqeueue Scripts After Elementor Scripts
	 * 
	 * @since 1.0.0
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script( 'neuron-sticky' );
		wp_enqueue_script( 'neuron-frontend' );

		wp_enqueue_script( 'neuron-elements-handlers' );

		$is_preview_mode = Elementor::$instance->preview->is_preview_mode( Elementor::$instance->preview->get_post_id() );

		$locale_settings = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'neuron-frontend' ),
			'environmentMode' => [
				'edit' => $is_preview_mode,
				'wpPreview' => is_preview(),
			],
		];

		$locale_settings = apply_filters( 'neuron/frontend/localize_settings', $locale_settings );

		ElementorUtils::print_js_config(
			'neuron-frontend',
			'NeuronFrontendConfig',
			$locale_settings
		);
	}

	/**
	 * Enqueue Frontend Styles
	 * 
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$frontend_file_name = 'frontend.css';
		// $frontend_file_name = 'frontend' . $suffix . '.css';
		$has_custom_file = Responsive::has_custom_breakpoints();

		if ( $has_custom_file ) {
			$frontend_file = new FrontendFile( 'neuron-' . $frontend_file_name, self::get_responsive_templates_path() . $frontend_file_name );

			$time = $frontend_file->get_meta( 'time' );

			if ( ! $time ) {
				$frontend_file->update();
			}

			$frontend_file_url = $frontend_file->get_url();
		} else {
			$frontend_file_url = NEURON_ASSETS_URL . 'styles/static/' . $frontend_file_name;
		}

		wp_enqueue_style(
			'neuron-frontend',
			$frontend_file_url,
			[],
			$has_custom_file ? null : NEURON_BUILDER_VERSION
		);

		wp_enqueue_style(
			'neuron-icons',
			NEURON_ASSETS_URL . 'fonts/n-icons/n-icons.css',
			[ 'neuron-frontend' ],
			NEURON_BUILDER_VERSION
		);

		/**
		 * Yith wishlist
		 * 
		 * @since 1.0.2
		 */
		if ( defined( 'YITH_WCWL' ) ) {
			wp_enqueue_style(
				'neuron-wishlist',
				NEURON_ASSETS_URL . 'styles/wishlist.css',
				[ 'neuron-frontend' ],
				NEURON_BUILDER_VERSION
			);
		}
	}

	/**
	 * Update Lightbox Settings
	 * 
	 * @since 1.0.0
	 */
	public function update_elementor_lightbox_options( Controls_Stack $controls_stack ) {
		$controls_stack->update_control(
			'lightbox_icons_size',
			[
				'size_units' => [ 'px', 'rem', 'vw' ]
			]
		);

		$controls_stack->update_control(
			'lightbox_slider_icons_size',
			[
				'size_units' => [ 'px', 'em', 'rem' ],
			]
		);
	}

	/**
	 * Update Image Width Units
	 *
	 * @since 1.0.0
	 * @param Controls_Stack $controls_stack.
	 */
	public function update_image_width_options( Controls_Stack $controls_stack ) {
		$controls_stack->update_control(
			'width',
			[
				'size_units' => [ '%', 'px', 'vw', 'rem' ],
			]
		);
	}

	/**
	 * Update Icon Settins
	 * 
	 * Hooks: elementor/element/icon/section_style_icon/after_section_end
	 * 
	 * @since 1.0.0
	 * @param Controls_Stack $controls_stack.
	 */
	public function update_icon_options( Controls_Stack $controls_stack ) {
		$controls_stack->update_responsive_control(
			'size',
			[
				'size_units' => [ 'px', 'em', 'rem' ],
			]
		);
	}

	/**
	 * Tabs Settings
	 * 
	 * Add settings to tab
	 * Elementor Element.
	 * 
	 * @since 1.0.0
	 */
	public function update_tabs_options( $element, $section_id, $args ) {

		if ( 'toggle' === $element->get_name() ) {
			$element->update_control(
				'border_width', [
					'selectors' => [
						'{{WRAPPER}} .elementor-toggle .elementor-toggle-item' => 'border-bottom: {{SIZE}}{{UNIT}} solid;',
					]
				]
			);
			$element->update_control(
				'border_color', [
					'default' => 'D4D4D4',
					'selectors' => [
						'{{WRAPPER}} .elementor-toggle .elementor-toggle-item' => 'border-bottom-color: {{VALUE}};',
					]
				]
			);
		}

		if ( 'tabs' === $element->get_name() && 'section_tabs_style' === $section_id ) {

			$element->start_controls_section(
				'tabs_content_section',
				[
					'tab' => Controls_Manager::TAB_STYLE,
					'label' => __( 'Extra Options', 'neuron-builder' ), // @TODO: Name it better
				]
			);

			$element->add_control(
				'tabs_title_heading',
				[
					'label' => __( 'Title', 'neuron-builder' ),
					'type' => Controls_Manager::HEADING,
				]
			);


			$element->add_control(
				'tabs_title_alignment',
				[
					'label' => __( 'Alignment', 'neuron-builder' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'start' => [
							'title' => __( 'Left', 'neuron-builder' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'neuron-builder' ),
							'icon' => 'eicon-h-align-center',
						],
						'end' => [
							'title' => __( 'Right', 'neuron-builder' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'selectors_dictionary' => [
						'start' => 'flex-start',
						'end' => 'flex-end',
					],
					
					'selectors' => [
						'(desktop){{WRAPPER}} .elementor-tabs-wrapper' => 'display: flex; justify-content: {{VALUE}}',
						'(tablet){{WRAPPER}} .elementor-tabs-wrapper' => 'display: flex; justify-content: {{VALUE}}',
						'(mobile){{WRAPPER}} .elementor-tabs-wrapper' => 'display: none;',
					],
				]
			);

			$element->add_control(
				'tabs_animation',
				[
					'label' => __('Animation', 'neuron-builder'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => __('None', 'neuron-builder'),
						'slideUp' => __('Slide Up', 'neuron-builder'),
					],
					'default' => '',
					'prefix_class' => 'elementor-tabs--animation-'
				]
			);

			$element->add_responsive_control(
				'tabs_padding',
				[
					'label' => __( 'Padding', 'neuron-builder' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$element->start_controls_tabs(
				'tabs_title_tabs'
			);

			$element->start_controls_tab(
				'tabs_title_tab_normal',
				[
					'label' => __( 'Normal', 'neuron-builder' ),
				]
			);

			$element->add_control(
				'tabs_title_color',
				[
					'label' => __( 'Color', 'neuron-builder' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title a' => 'color: {{VALUE}};'
					],
				]
			);

			$element->add_control(
				'tabs_title_bg_color',
				[
					'label' => __( 'Background Color', 'neuron-builder' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title' => 'background-color: {{VALUE}};'
					],
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'tabs_title_border',
					'label' => __( 'Border', 'neuron-builder' ),
					'selector' => '{{WRAPPER}} .elementor-tab-title:not(.elementor-active)',
				]
			);

			$element->end_controls_tab();

			$element->start_controls_tab(
				'tabs_title_tab_hover',
				[
					'label' => __( 'Hover & Active', 'neuron-builder' ),
				]
			);

			$element->add_control(
				'tabs_title_color_hover',
				[
					'label' => __( 'Color', 'neuron-builder' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title:hover a, {{WRAPPER}} .elementor-tab-title.elementor-active a' => 'color: {{VALUE}};'
					],
				]
			);

			$element->add_control(
				'tabs_title_bg_color_hover',
				[
					'label' => __( 'Background Color', 'neuron-builder' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title:hover, {{WRAPPER}} .elementor-tab-title.elementor-active' => 'background-color: {{VALUE}};'
					],
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'tabs_title_border_hover',
					'label' => __( 'Border', 'neuron-builder' ),
					'selector' => '{{WRAPPER}} .elementor-tab-title:hover, {{WRAPPER}} .elementor-tab-title.elementor-active',
					'separator' => 'before'
				]
			);

			$element->end_controls_tab();

			$element->end_controls_tabs();

			$element->end_controls_section();
		}
	}

	/**
	 * Stroke Options
	 * 
	 * @since 1.0.0
	 */
	public function update_heading_settings( $element, $section_id, $args ) {
		if ( 'heading' === $element->get_name() && 'section_title_style' === $section_id ) {

			$element->add_control(
				'heading_stroke',
				[
					'label' => __( 'Stroke', 'neuron-builder' ),
					'type' => Controls_Manager::POPOVER_TOGGLE,
					'label_off' => __( 'Default', 'neuron-builder' ),
					'label_on' => __( 'Custom', 'neuron-builder' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

			$element->start_popover();

			$element->add_control(
				'heading_stroke_width',
				[
					'label' => __( 'Width', 'neuron-builder' ),
					'type' => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .elementor-heading-title' => '-webkit-text-stroke: {{SIZE}}{{UNIT}} {{heading_stroke_color.VALUE}};'
					],
					'of_type' => 'heading_stroke',
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 10,
							'step' => 1,
						],
					],
					'condition' => [
						'heading_stroke' => 'yes'
					]
				]
			);

			$element->add_control(
				'heading_stroke_color',
				[
					'label' => __( 'Color', 'neuron-builder' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-heading-title' => '-webkit-text-stroke: {{heading_stroke_width.SIZE}}{{heading_stroke_width.UNIT}} {{VALUE}};'
					],
					'of_type' => 'heading_stroke',
					'condition' => [
						'heading_stroke' => 'yes'
					]
				]
			);

			$element->end_popover();

		}
	}

	/**
	 * Redirect Option
	 * 
	 * @since 1.0.0
	 */
	public function register_redirect_option( $document ) {
		if ( $document instanceof PageBase || $document instanceof LibraryPageDocument ) {
			$document->start_injection( [
				'of' => 'post_status',
				'fallback' => [
					'of' => 'post_title',
				],
			] );

			$document->add_control(
				'doc_redirect_url',
				[
					'label' => __( 'Redirect URL', 'neuron-builder' ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'neuron-builder' ),
				]
			);

			$document->end_injection();
		}
	}

	/**
	 * Show Redirect Option
	 * 
	 * @since 1.0.0
	 */
	public function enable_redirect_option() {
		$post_id = get_the_ID();

		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
		$page_settings_model = $page_settings_manager->get_model( $post_id );
		$redirect = $page_settings_model->get_settings( 'doc_redirect_url' );

		if ( isset( $redirect['url'] ) && ! empty( $redirect['url'] ) && ! is_user_logged_in() ) {
			wp_redirect( $redirect['url'] );
			die();
		}
	}

	/**
	 * Unregister Widgets
	 * 
	 * @since 1.0.0
	 */
	public function unregister_widgets( $widgetManager ) {
		$neuron_unregister_widgets = [
			'wp-widget-pages',
			'wp-widget-nav_menu',
			'wp-widget-calendar',
			'wp-widget-media_audio',
			'wp-widget-media_image',
			'wp-widget-media_gallery',
			'wp-widget-media_video',
			'wp-widget-rss',
			'wp-widget-recent-comments',
			'wp-widget-tag_cloud',
			'wp-widget-search',
			'wp-widget-categories',
			'wp-widget-text',
			'wp-widget-meta',
			'wp-widget-archives',
			'wp-widget-recent-posts',
			'wp-widget-woocommerce_product_search',
			'wp-widget-woocommerce_price_filter',
			'wp-widget-woocommerce_layered_nav',
			'wp-widget-woocommerce_layered_nav_filters',
			'wp-widget-woocommerce_widget_cart',
			'wp-widget-woocommerce_product_categories',
			'wp-widget-woocommerce_product_tag_cloud',
			'wp-widget-woocommerce_recently_viewed_products',
			'wp-widget-woocommerce_recent_reviews',
			'wp-widget-woocommerce_top_rated_products',
			'wp-widget-woocommerce_rating_filter',
			'wp-widget-woocommerce_products',
			'wp-widget-rev-slider-widget',
			'wp-widget-custom_html'
		];

		foreach($neuron_unregister_widgets as $widget) {
			$widgetManager->unregister_widget_type($widget);
		}
	}

	public function register_controls( $controls_manager ) {

		$controls = preg_grep( '/^((?!index.php).)*$/', glob( NEURON_PATH . 'controls/*.php' ) );

		foreach ( $controls as $control ) {

			// Prepare control name.
			$control_name = basename( $control, '.php' );
			$control_name = str_replace( '-', '_', $control_name );


			// Prepare class name.
			$class_name = str_replace( '-', '_', $control_name );
			$class_name = __NAMESPACE__ . '\Controls\\' . $class_name;


			// Register now.
			$controls_manager->register_control( $control_name, new $class_name() );
		}

	}

	public function on_elementor_init() {
		$this->modules_manager = new Modules_Manager();

		$this->add_neuron_categories();

		/**
		 * Neuron init.
		 *
		 * Fires on Neuron init, after Elementor has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'neuron/init' );
	}

	/**
	 * Sync libraries.
	 *
	 * @since 1.0.0
	 */
	public function sync_libraries() {
		if ( empty( $_POST['library'] ) ) {
			wp_send_json_error( __( 'Library field is missing', 'neuron-builder' ) );
		}

		$library = sanitize_text_field( wp_unslash( $_POST['library'] ) );

		if ( 'presets' === $library && isset( $this->core_modules['preset'] ) ) {
			$cached_elements = get_transient( 'neuron_presets_elements_cached' );

			delete_transient( 'neuron_presets_elements' );
			delete_transient( 'neuron_presets_elements_cached' );

			if ( false === $cached_elements ) {
				wp_send_json_success();
			}

			foreach ( $cached_elements as $element ) {
				delete_transient( 'neuron_preset_' . $element );
			}

			wp_send_json_success();
		}

		wp_send_json_error( __( 'Invalid library value received', 'neuron-builder' ) );
	}

	private function get_responsive_templates_path() {
		return NEURON_ASSETS_PATH . 'styles/';
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ], 15 );
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'register_preview_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		add_filter( 'elementor/core/responsive/get_stylesheet_templates', [ $this, 'get_responsive_stylesheet_templates' ] );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'unregister_widgets' ], 10, 3 );


		// add_action( 'wp_ajax_neuron_sync_libraries', [ $this, 'sync_libraries' ] ); // @TODO: Presets

		if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
			add_filter( 'elementor/editor/localize_settings', function( $settings ) {
				if ( ! empty( $settings['promotionWidgets'] ) ) {
					$settings['promotionWidgets'] = [];
				}

				if ( isset( $settings['dynamicPromotionURL'] ) ) {
					$settings['dynamicPromotionURL'] = 'https://neuronthemes.com/features/dynamic-content';
				}

				if ( isset( $settings['elementPromotionURL'] ) ) {
					$settings['elementPromotionURL'] = 'https://neuronthemes.com/neuron-builder';
				}

				return $settings;
			}, 20 );

			// APP iframe
			add_action( 'elementor/frontend/after_enqueue_styles', function() {
				$css = '.elementor-app-iframe { display: none !important; } .e-route-app { overflow: scroll !important; } ';

				wp_add_inline_style( 'neuron-frontend', $css );
			} );
		}

		add_action( 'elementor/element/before_section_end', [ $this, 'update_heading_settings' ], 10, 3 );
		add_action( 'elementor/element/after_section_end', [ $this, 'update_tabs_options' ], 10, 3 );
		add_action( 'elementor/element/after_section_end', [ $this, 'update_elementor_lightbox_options' ], 10, 2 );
		add_action( 'elementor/element/image/section_style_image/after_section_end', [ $this, 'update_image_width_options' ], 10, 2 );
		add_action( 'elementor/element/icon/section_style_icon/after_section_end', [ $this, 'update_icon_options' ], 10, 2 );

		// Redirect Settings
		add_action( 'elementor/documents/register_controls', [ $this, 'register_redirect_option' ]  );
		add_action( 'template_redirect', [ $this, 'enable_redirect_option' ] );
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->setup_hooks();

		$this->editor = new Editor();
		$this->preview = new Preview();
		$this->attachments = new Attachments();
		
		if ( is_admin() ) {
			$this->admin = new Admin();
			$this->license_admin = new License\Admin();
		}
	}
}

/**
 * Returns the Plugin application instance.
 *
 * @since 1.0.0
 *
 * @return Plugin
 */
function NeuronPlugin() {
	return Plugin::instance();
}

/**
 * Initializes the Plugin application.
 *
 * @since 1.0.0
 */
NeuronPlugin();