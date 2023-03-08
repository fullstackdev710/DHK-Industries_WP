<?php
/**
 * Theme Functions
 *
 * @package Kaon
 * @author NeuronThemes
 * @since 1.0.0
 */

/**
 * Global Variables
 * 
 * Defining global variables to make
 * usage easier in theme.
 */
define( 'ARCHZILLA_DIR', get_template_directory() );
define( 'ARCHZILLA_URI', get_template_directory_uri() );
define( 'ARCHZILLA_STYLESHEET', get_stylesheet_uri() );
define( 'ARCHZILLA_PLACEHOLDER', get_template_directory_uri() . '/assets/images/placeholder.png' );
define( 'ARCHZILLA_VERSION', wp_get_theme()->get( 'Version' ) );

if ( ! function_exists( 'archzilla_init' ) ) {
	/**
	 * Init
	 * 
	 * Global function which adds theme support,
	 * register nav menus and call actions for
	 * different php, js and css files.
	 */
	function archzilla_init() {
		/**
		 * Text Domain
		 * 
		 * Makes theme available for translation,
		 * translations can be found in the /languages/ directory.
		 */
		load_theme_textdomain( 'archzilla', ARCHZILLA_DIR . '/languages' );

		// Theme Support
		add_theme_support( 'custom-logo' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		/**
		 * WooCommerce Theme Support
		 * 
		 * Theme fully supports plugin WooCommerce
		 * also it's features in single product
		 * as zoom, lightbox and slider.
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		}

		// Theme actions within init function
		include_once( ARCHZILLA_DIR . '/inc/plugins.php' );

		// Enqueues the styles
		add_action( 'wp_enqueue_scripts', 'archzilla_external_css' );
		
		// Enqueues the scripts
		add_action( 'wp_enqueue_scripts', 'archzilla_external_js' );

		// Registers the sidebars
		add_action( 'widgets_init', 'archzilla_sidebars' );

		// Register Menus
		register_nav_menus( [
			'primary' => esc_html__( 'Primary Menu', 'archzilla' )
		] );

		// Wizard
		require_once( get_parent_theme_file_path( '/inc/wizard/class-wizard.php' ) );
		require_once( get_parent_theme_file_path( '/inc/wizard/wizard-config.php' ) );
	}
}
add_action( 'after_setup_theme', 'archzilla_init' );


if ( ! function_exists( 'archzilla_content_width' ) ) {
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width Content width.
	 */
	function archzilla_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'archzilla_content_width', 1300 );
	}
}
add_action( 'after_setup_theme', 'archzilla_content_width', 0 );

if ( ! function_exists( 'archzilla_external_css' ) ) {
	/**
	 * External CSS
	 */
	function archzilla_external_css() {
		wp_enqueue_style( 'archzilla-main-style', ARCHZILLA_STYLESHEET );

		wp_enqueue_style( 'archzilla-fonts', archzilla_fonts_url(), array(), ARCHZILLA_VERSION);

		/**
		 * Change Site Main Width
		 * 
		 * Only if elementor is activated.
		 * 
		 * @since 1.0.0
		 */
		if ( did_action( 'elementor/loaded' ) ) {
			$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

			// Container Width
			if ( isset( $kit->get_settings( 'container_width' )['size'] ) ) {
				$css = 'body[class*="woocommerce-"]:not([class*="elementor-page-"]) .main { max-width: 1300px; margin-left: auto; margin-right: auto; }';

				wp_add_inline_style( 'archzilla-main-style', $css );
			}
		}
	}
}

if ( ! function_exists( 'archzilla_external_js' ) ) {
	/**
	 * External Javascript
	 */
	function archzilla_external_js() {
		if ( ! is_admin() ) {
			wp_enqueue_script( 'archzilla-scripts', ARCHZILLA_URI . '/assets/scripts/archzilla.js', array( 'jquery' ), ARCHZILLA_VERSION, TRUE );

			is_singular() ? wp_enqueue_script( 'comment-reply' ) : ' ';
		}
	}
}

if ( ! function_exists( 'archzilla_fonts_url' ) ) {
	function archzilla_fonts_url() {
		$fonts_url = '';
		$fonts     = array();

		/* translators: If there are characters in your language that are not supported by this font, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== esc_html_x( 'on', 'Nimbus Sans L font: on or off', 'archzilla' ) ) {
			$fonts[] = 'Nimbus Sans L:400';
		}

		/* translators: If there are characters in your language that are not supported by this font, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== esc_html_x( 'on', 'Inter font: on or off', 'archzilla' ) ) {
			$fonts[] = 'Inter:300,400,500,600';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => implode( '|', $fonts )
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
}


if ( ! function_exists( 'archzilla_get_suffix' ) ) {
	/**
	 * Suffix
	 */
	function archzilla_get_suffix() {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}
}

if ( ! function_exists( 'archzilla_wp_body_open' ) ) {
	/**
	 * Triggered after the opening <body> tag.
	 * WordPress 5.2
	 * 
	 * @since 1.0.0 
	 */
	function archzilla_wp_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}

if ( ! function_exists( 'archzilla_hamburger' ) ) {
	function archzilla_hamburger( $class = 'a-nth-hamburger' ) {

		if ( $class ) {
			$class = 'a-nth-hamburger ' . $class;
		}
	?>
			<a href="#" class="<?php echo esc_attr( $class ); ?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="31" height="8" viewBox="0 0 31 8" fill="none"><path d="M31 4C31 5.933 29.433 7.5 27.5 7.5C25.567 7.5 24 5.933 24 4C24 2.067 25.567 0.5 27.5 0.5C29.433 0.5 31 2.067 31 4Z" fill="black"></path><path d="M19 4C19 5.933 17.433 7.5 15.5 7.5C13.567 7.5 12 5.933 12 4C12 2.067 13.567 0.5 15.5 0.5C17.433 0.5 19 2.067 19 4Z" fill="black"></path><path d="M7 4C7 5.933 5.433 7.5 3.5 7.5C1.567 7.5 0 5.933 0 4C0 2.067 1.567 0.5 3.5 0.5C5.433 0.5 7 2.067 7 4Z" fill="black"></path></svg>
            </a>
		<?php
	}
}

if ( ! function_exists( 'archzilla_sidebars' ) ) {
	function archzilla_sidebars() {
		$archzilla_sidebars = [
			[
				'name' => __( 'Main Sidebar', 'archzilla' ),
				'description' => __( 'Widgets on this sidebar are displayed in Blog Page.', 'archzilla' ),
				'id' => 'main-sidebar'
			],
			[
				'name' => __( 'Shop Sidebar', 'archzilla' ),
				'description' => __( 'Widgets on this sidebar are displayed in Shop Pages.', 'archzilla' ),
				'id' => 'shop-sidebar',
				'condition' => class_exists( 'WooCommerce' )
			],
		];
	
		foreach ( $archzilla_sidebars as $sidebar ) {
			if ( isset ( $sidebar['condition'] ) && ! $sidebar['condition'] ) {
				continue;
			}

			register_sidebar(
				[
					'name' => esc_html( $sidebar['name'] ),
					'description' => esc_html( $sidebar['description'] ),
					'id' => esc_attr( $sidebar['id'] ),
					'before_widget' => '<div id="%1$s" class="m-ntheme-widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h6 class="m-ntheme-widget__title">',
					'after_title'   => '</h6>'
				]
			);
		}
	}
}

if ( ! function_exists( 'archzilla_hamburger' ) ) {
	function archzilla_hamburger( $class = 'a-nth-hamburger' ) {

		if ( $class ) {
			$class = 'a-nth-hamburger ' . $class;
		}
	?>
			<a href="#" class="<?php echo esc_attr( $class ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="8" viewBox="0 0 20 8" fill="none"><rect width="20" height="2" fill="black"></rect><rect y="6" width="20" height="2" fill="black"></rect></svg>
            </a>
		<?php
	}
}

if ( ! function_exists( 'archzilla_load_inline_svg' ) ) {
	/**
	 * Output an inline SVG.
	 *
	 * @param string $filename The filename of the SVG you want to load.
	 * @since 1.0.0
	 * @return void
	 */
	function archzilla_load_inline_svg( $filename ) {

		ob_start();

		locate_template(
			array(
				sprintf(
					'assets/images/%1$s',
					$filename
				),
				"assets/images/{$filename}",
			),
			true,
			false
		);

		echo wp_kses(
			ob_get_clean(),
			array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'svg'  => array(
						'role'        => true,
						'width'       => true,
						'height'      => true,
						'fill'        => true,
						'xmlns'       => true,
						'viewbox'     => true,
						'aria-hidden' => true,
					),
					'path' => array(
						'd'              => true,
						'fill'           => true,
						'fill-rule'      => true,
						'stroke'         => true,
						'stroke-width'   => true,
						'stroke-linecap' => true,
					),
					'g'    => array(
						'd'    => true,
						'fill' => true,
					),
				)
			)
		);
	}
}

add_filter( 'excerpt_more', 'archzilla_excerpt_more' );
if ( ! function_exists( 'archzilla_excerpt_more' ) ) {
	function archzilla_excerpt_more( $more ) {
		return '...';
	}
}

/**
 * Neuron Functions
 * 
 * Extend the functionality
 * of the theme with extra
 * functions and actions.
 * 
 * @since 1.0.0
 */
include( ARCHZILLA_DIR . '/inc/functions/neuron-functions.php' );

/**
 * WooCommerce Functions
 * 
 * Extend the functionality
 * of the woocommerce with extra
 * functions and actions.
 * 
 * @since 1.0.0
 */
include( ARCHZILLA_DIR . '/inc/functions/woocommerce-functions.php' );

/**
 * Plugin Activation Tool
 * 
 * @since 1.0.0
 */
include_once( ARCHZILLA_DIR . '/inc/tgm/class-tgm-plugin-activation.php' );

/**
 * Demo Import
 * 
 * Hook to add demo import
 * files to the neuron core
 * demo importer.
 * 
 * @since 1.0.0
 */
include_once( ARCHZILLA_DIR . '/inc/demo-import.php' );
