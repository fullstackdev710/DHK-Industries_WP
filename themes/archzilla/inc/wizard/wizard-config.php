<?php
/**
 * Wizard WP configuration file.
 */

if ( ! class_exists( 'Wizard' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Wizard(

	$config = array(
		'directory'            => '/inc/wizard', // Location / directory where Wizard WP is placed in your theme.
		'wizard_url'           => 'wizard', // The wp-admin page slug where Wizard WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/', // URL for the 'child-action-link'.
		'dev_mode'             => true, // Enable development mode for testing. // TODO: set to false
		'ready_big_button_url' => admin_url('admin.php?page=neuron'), // Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup', 'archzilla' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'archzilla' ),
		'ignore'                   => esc_html__( 'Skip setup details', 'archzilla' ),

		'btn-skip'                 => esc_html__( 'Skip', 'archzilla' ),
		'btn-next'                 => esc_html__( 'Next', 'archzilla' ),
		'btn-start'                => esc_html__( 'Start', 'archzilla' ),
		'btn-no'                   => esc_html__( 'Cancel', 'archzilla' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'archzilla' ),
		'btn-child-install'        => esc_html__( 'Install', 'archzilla' ),
		'btn-content-install'      => esc_html__( 'Install', 'archzilla' ),
		'btn-import'               => esc_html__( 'Import', 'archzilla' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'archzilla' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'archzilla' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins. It is optional & should take only a few minutes.', 'archzilla' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'archzilla' ),

		'child-header'             => esc_html__( 'Welcome to Neuron Wizard', 'archzilla' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'archzilla' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'archzilla' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'archzilla' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'archzilla' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'archzilla' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'archzilla' ),

		'plugins-header'           => esc_html__( 'Install Plugins', 'archzilla' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'archzilla' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'archzilla' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'archzilla' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'archzilla' ),

		'ready-header'             => esc_html__( 'All done. Have Fun!', 'archzilla' ),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by ', 'archzilla' ) . '<a href="https://neuronthemes.com" target="_BLANK">NeuronThemes</a>',
		'ready-action-link'        => esc_html__( 'Extras', 'archzilla' ),
		'ready-big-button'         => esc_html__( 'Get Started', 'archzilla' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://neuronthemes.com/support', esc_html__( 'Get Theme Support', 'archzilla' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.youtube.com/channel/UCnU6sFZmSG-Qpk9kOfPzNZw', esc_html__( 'Facebook Community', 'archzilla' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.youtube.com/channel/UCnU6sFZmSG-Qpk9kOfPzNZw', esc_html__( 'Youtube Tutorials', 'archzilla' ) ),
	)
);

function archzilla_generate_child_functions_php( $output, $slug ) {

	$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

	$output = "
		<?php
		/**
		 * Theme functions and definitions.
		 */
		function {$slug_no_hyphens}_child_enqueue_styles() {

			wp_enqueue_style( '{$slug}-style' , get_template_directory_uri() . '/style.css' );

		    wp_enqueue_style( '{$slug}-child-style',
		        get_stylesheet_directory_uri() . '/style.css',
		        array( '{$slug}-style' ),
		        wp_get_theme()->get('Version')
		    );
		}

		add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles' );\n
	";

	// Let's remove the tabs so that it displays nicely.
	$output = trim( preg_replace( '/\t+/', '', $output ) );

	// Filterable return.
	return $output;
}
add_filter( 'wizard_generate_child_functions_php', 'archzilla_generate_child_functions_php', 10, 2 );
