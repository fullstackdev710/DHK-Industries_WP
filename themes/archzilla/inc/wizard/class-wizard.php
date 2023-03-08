<?php
/**
 * Wizard WP
 * Better WordPress Theme Onboarding
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wizard.
 */
class Wizard {
	/**
	 * Current theme.
	 *
	 * @var object WP_Theme
	 */
	protected $theme;

	/**
	 * Current step.
	 *
	 * @var string
	 */
	protected $step = '';

	/**
	 * Steps.
	 *
	 * @var    array
	 */
	protected $steps = array();

	/**
	 * TGMPA instance.
	 *
	 * @var    object
	 */
	protected $tgmpa;

	/**
	 * Helper.
	 *
	 * @var    array
	 */
	protected $helper;

	/**
	 * Updater.
	 *
	 * @var    array
	 */
	protected $updater;

	/**
	 * The text string array.
	 *
	 * @var array $strings
	 */
	protected $strings = null;

	/**
	 * The base path where Wizard is located.
	 *
	 * @var array $strings
	 */
	protected $base_path = null;

	/**
	 * The base url where Wizard is located.
	 *
	 * @var array $strings
	 */
	protected $base_url = null;

	/**
	 * The location where Wizard is located within the theme or plugin.
	 *
	 * @var string $directory
	 */
	protected $directory = null;

	/**
	 * Top level admin page.
	 *
	 * @var string $wizard_url
	 */
	protected $wizard_url = null;

	/**
	 * The wp-admin parent page slug for the admin menu item.
	 *
	 * @var string $parent_slug
	 */
	protected $parent_slug = null;

	/**
	 * The capability required for this menu to be displayed to the user.
	 *
	 * @var string $capability
	 */
	protected $capability = null;

	/**
	 * The URL for the "Learn more about child themes" link.
	 *
	 * @var string $child_action_btn_url
	 */
	protected $child_action_btn_url = null;

	/**
	 * Turn on dev mode if you're developing.
	 *
	 * @var string $dev_mode
	 */
	protected $dev_mode = false;

	/**
	 * Ignore.
	 *
	 * @var string $ignore
	 */
	public $ignore = null;


	/**
	 * Setup plugin version.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function version() {

		if ( ! defined( 'NEURON_WIZARD_VERSION' ) ) {
			define( 'NEURON_WIZARD_VERSION', '1.0.0' );
		}
	}

	/**
	 * Class Constructor.
	 *
	 * @param array $config Package-specific configuration args.
	 * @param array $strings Text for the different elements.
	 */
	function __construct( $config = array(), $strings = array() ) {

		$this->version();

		$config = wp_parse_args(
			$config, array(
				'base_path'            => get_parent_theme_file_path(),
				'base_url'             => get_parent_theme_file_uri(),
				'directory'            => 'wizard',
				'wizard_url'           => 'wizard',
				'parent_slug'          => 'themes.php',
				'capability'           => 'manage_options',
				'child_action_btn_url' => '',
				'dev_mode'             => ''
			)
		);

		// Set config arguments.
		$this->base_path              = $config['base_path'];
		$this->base_url               = $config['base_url'];
		$this->directory              = $config['directory'];
		$this->wizard_url             = $config['wizard_url'];
		$this->parent_slug            = $config['parent_slug'];
		$this->capability             = $config['capability'];
		$this->child_action_btn_url   = $config['child_action_btn_url'];
		$this->dev_mode               = $config['dev_mode'];

		if ( class_exists( 'Neuron' ) ) {
			$this->ready_big_button_url = admin_url( 'admin.php' ) . '?page=neuron';
		} else {
			$this->ready_big_button_url = get_dashboard_url();
		}

		// Strings passed in from the config file.
		$this->strings = $strings;

		// Retrieve a WP_Theme object.
		$this->theme = wp_get_theme();
		$this->slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $this->theme->template ) );

		// Set the ignore option.
		$this->ignore = $this->slug . '_ignore';

		// Is Dev Mode turned on?
		if ( true !== $this->dev_mode ) {

			// Has this theme been setup yet?
			$already_setup = get_option( 'wizard_' . $this->slug . '_completed' );

			// Return if Wizard has already completed it's setup.
			if ( $already_setup ) {
				return;
			}
		}

		// Get TGMPA.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}

		add_action( 'admin_init', array( $this, 'redirect' ), 30 );
		add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		add_action( 'admin_init', array( $this, 'steps' ), 30, 0 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_page' ), 30, 0 );
		add_action( 'admin_init', array( $this, 'ignore' ), 5 );
		add_filter( 'tgmpa_load', array( $this, 'load_tgmpa' ), 10, 1 );
		add_action( 'wp_ajax_wizard_plugins', array( $this, '_ajax_plugins' ), 10, 0 );
		add_action( 'wp_ajax_wizard_child_theme', array( $this, 'generate_child' ), 10, 0 );
	}

	/**
	 * Set redirection transient on theme switch.
	 */
	public function switch_theme() {
		if ( ! is_child_theme() ) {
			set_transient( $this->theme->template . '_wizard_redirect', 1 );
		}
	}

	/**
	 * Redirection transient.
	 */
	public function redirect() {

		if ( ! get_transient( $this->theme->template . '_wizard_redirect' ) ) {
			return;
		}

		delete_transient( $this->theme->template . '_wizard_redirect' );

		wp_safe_redirect( menu_page_url( $this->wizard_url ) );

		exit;
	}

	/**
	 * Give the user the ability to ignore Wizard WP.
	 */
	public function ignore() {

		// Bail out if not on correct page.
		if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( $_GET['_wpnonce'], 'wizardwp-ignore-nounce' ) || ! is_admin() || ! isset( $_GET[ $this->ignore ] ) || ! current_user_can( 'manage_options' ) ) ) {
			return;
		}

		update_option( 'wizard_' . $this->slug . '_completed', 'ignored' );
	}

	/**
	 * Conditionally load TGMPA
	 *
	 * @param string $status User's manage capabilities.
	 */
	public function load_tgmpa( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Determine if the user already has theme content installed.
	 * This can happen if swapping from a previous theme or updated the current theme.
	 * We change the UI a bit when updating / swapping to a new theme.
	 *
	 * @access public
	 */
	protected function is_possible_upgrade() {
		return false;
	}

	/**
	 * Add the admin menu item, under Appearance.
	 */
	public function add_admin_menu() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		$this->hook_suffix = add_submenu_page(
			esc_html( $this->parent_slug ), esc_html( $strings['admin-menu'] ), esc_html( $strings['admin-menu'] ), sanitize_key( $this->capability ), sanitize_key( $this->wizard_url ), array( $this, 'admin_page' )
		);
	}

	/**
	 * Add the admin page.
	 */
	public function admin_page() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->wizard_url !== $_GET['page'] ) {
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		// Use minified libraries if dev mode is turned on.
		$suffix = ( ( true === $this->dev_mode ) ) ? '' : '.min';

		// Enqueue styles.
		wp_enqueue_style( 'wizard', trailingslashit( $this->base_url ) . $this->directory . '/assets/css/wizard.css', array( 'wp-admin' ), NEURON_WIZARD_VERSION );

		// Enqueue javascript.
		wp_enqueue_script( 'wizard', trailingslashit( $this->base_url ) . $this->directory . '/assets/js/wizard.js', array( 'jquery-core' ), NEURON_WIZARD_VERSION );

		$texts = array(
			'something_went_wrong' => esc_html__( 'Something went wrong. Please refresh the page and try again!', 'archzilla' ),
		);

		// Localize the javascript.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			// Check first if TMGPA is included.
			wp_localize_script(
				'wizard', 'wizard_params', array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => $this->tgmpa->get_tgmpa_url(),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'wizard_nonce' ),
					'texts'            => $texts,
				)
			);
		} else {
			// If TMGPA is not included.
			wp_localize_script(
				'wizard', 'wizard_params', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wpnonce' => wp_create_nonce( 'wizard_nonce' ),
					'texts'   => $texts,
				)
			);
		}

		ob_start();

		/**
		 * Start the actual page content.
		 */
		$this->wizard_header(); ?>

		<div class="wizard__wrapper">

			<div class="wizard__content wizard__content--<?php echo esc_attr( strtolower( $this->steps[ $this->step ]['name'] ) ); ?>">

				<?php
				// Content Handlers.
				$show_content = true;

				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}

				if ( $show_content ) {
					$this->body();
				}
				?>

			</div>

			<?php if ( $this->step == 'welcome' ) : ?>

				<?php $ignore_url = wp_nonce_url( admin_url( '?' . $this->ignore . '=true' ), 'wizardwp-ignore-nounce' ); ?>

				<div class="wizard__return-to-dashboard-wrapper">
					<?php 
						echo sprintf( 
							'<a class="wizard__return-to-dashboard ignore" href="%s">%s</a>', 
							esc_url( $ignore_url ), 
							esc_html( $strings['ignore'] )
						); 
					?>

					<span class="open-tooltip">
						<span>Manual setup is only recommended for <br /> experienced WordPress users or developers.</span>

						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18px" height="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
						
					</span>
				</div>

			<?php endif; ?>
		</div>

		<?php $this->wizard_footer(); ?>

		<?php
		exit;
	}

	/**
	 * Output the header.
	 */
	protected function wizard_header() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Get the current step.
		$current_step = strtolower( $this->steps[ $this->step ]['name'] );
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php printf( esc_html( $strings['title%s%s%s%s'] ), '<ti', 'tle>', esc_html( $this->theme->name ), '</title>' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="wizard__body wizard__body--<?php echo esc_attr( $current_step ); ?>">
		<?php $this->step_output(); ?>
		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	protected function body() {
		isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
	}

	/**
	 * Output the footer.
	 */
	protected function wizard_footer() {
		?>
		</body>
		<?php do_action( 'admin_footer' ); ?>
		<?php do_action( 'admin_print_footer_scripts' ); ?>
		</html>
		<?php
	}

	/**
	 * Loading wizard-spinner.
	 */
	public function loading_spinner() {

		// Define the spinner file.
		$spinner = $this->directory . '/assets/images/spinner';

		// Retrieve the spinner.
		get_template_part( apply_filters( 'wizard_loading_spinner', $spinner ) );
	}

	/**
	 * Allowed HTML for the loading spinner.
	 */
	public function loading_spinner_allowed_html() {

		$array = array(
			'span' => array(
				'class' => array(),
			),
			'cite' => array(
				'class' => array(),
			),
		);

		return apply_filters( 'wizard_loading_spinner_allowed_html', $array );
	}

	/**
	 * Setup steps.
	 */
	public function steps() {

		$this->steps['welcome'] = array(
			'name' => esc_html__( 'Site Identity', 'archzilla' ),
			'view' => array( $this, 'welcome' ),
			'number' => 1
		);


		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->steps['plugins'] = array(
				'name' => esc_html__( 'Installation', 'archzilla' ),
				'view' => array( $this, 'plugins' ),
				'number' => 2
			);
		}

		$this->steps['license'] = array(
			'name' => esc_html__( 'License', 'archzilla' ),
			'view' => array( $this, 'license' ),
			'number' => 3
		);

		$this->steps = apply_filters( $this->theme->template . '_wizard_steps', $this->steps );
	}

	/**
	 * Output the steps
	 */
	protected function step_output() {
		$ouput_steps  = $this->steps;
		$array_keys   = array_keys( $this->steps );
		$current_step = array_search( $this->step, $array_keys, true );

		?>

		<div class="dots">

			<?php
			foreach ( $ouput_steps as $step_key => $step ) :

				$class_attr = 'dots-step';
				$show_link  = false;

				if ( $step_key === $this->step ) {
					$class_attr = 'dots-step active';
				} elseif ( $current_step > array_search( $step_key, $array_keys, true ) ) {
					$class_attr = 'dots-step done';
					$show_link  = true;
				}
				?>
				<div class="<?php echo esc_attr( $class_attr ); ?>">
					<a href="<?php echo esc_url( $this->step_link( $step_key ) ); ?>" title="<?php echo esc_attr( $step['name'] ); ?>">
						<div class="dots__step-icon">
							<span class="dots__step-number"><?php echo esc_attr( $step['number'] ) ?></span>
							<svg role="img" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="2" y="3" width="14" height="12"><path d="M6.59631 11.9062L3.46881 8.77875L2.40381 9.83625L6.59631 14.0287L15.5963 5.02875L14.5388 3.97125L6.59631 11.9062Z" fill="white"></path></mask><g mask="url(#mask0)"><rect width="18" height="18" fill="white"></rect></g></svg>
						</div>
						<span><?php echo esc_attr( $step['name'] ); ?></span>
					</a>
				</div>
				<div class="dots__step-divider"></div>
			<?php endforeach; ?>

		</div>

		<?php
	}

	/**
	 * Get the step URL.
	 *
	 * @param string $step Name of the step, appended to the URL.
	 */
	protected function step_link( $step ) {
		return add_query_arg( 'step', $step );
	}

	/**
	 * Get the next step link.
	 */
	protected function step_next_link() {
		$keys = array_keys( $this->steps );
		$step = array_search( $this->step, $keys, true ) + 1;

		return add_query_arg( 'step', $keys[ $step ] );
	}

	/**
	 * Handles save button from welcome page.
	 * This is to perform tasks when the setup wizard has already been run.
	 */
	protected function welcome_handler() {

		check_admin_referer( 'wizard' );

		return false;
	}

	/**
	 * Child theme generator.
	 */
	protected function welcome() {

		// Variables.
		$is_child_theme     = is_child_theme();
		$child_theme_option = get_option( 'wizard_' . $this->slug . '_child' );
		$theme              = $child_theme_option ? wp_get_theme( $child_theme_option )->name : $this->theme . ' Child';
		$action_url         = $this->child_action_btn_url;

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = ! $is_child_theme ? $strings['child-header'] : $strings['child-header-success'];
		$action    = $strings['child-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$paragraph = ! $is_child_theme ? $strings['child'] : $strings['child-success%s'];
		$install   = $strings['btn-child-install'];
		?>

		<div class="wizard__content--transition">

			<h1><?php echo esc_html( 'Welcome to Neuron' ); ?></h1>

			<p>This wizard will set up your theme, install plugins. <br /> It is optional & should take only a few seconds.</p>

		</div>

		<div class="wizard__content--body wizard__content--body-welcome">

			<form class="wizard-form" enctype="multipart/form-data">

				<ul class="wizard__drawer wizard__drawer--install-plugins">
					
					<li>
						<label for="file-upload" class="custom-file-upload">
							<svg class="gridicon gridicons-cloud-upload" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M18 9c-.01 0-.017.002-.025.003C17.72 5.646 14.922 3 11.5 3 7.91 3 5 5.91 5 9.5c0 .524.07 1.03.186 1.52C5.123 11.015 5.064 11 5 11c-2.21 0-4 1.79-4 4 0 1.202.54 2.267 1.38 3h18.593C22.196 17.09 23 15.643 23 14c0-2.76-2.24-5-5-5zm-5 4v3h-2v-3H8l4-5 4 5h-3z"></path></g></svg>
							<span>My WordPress Site Logo (Optional)</span>
						</label>
						<input class="site-logo" id="file-upload" type="file"  accept="image/*"/>
					</li>

					<li>
						<input class="site-title" type="text" placeholder="<?php echo esc_html__( 'My WordPress Site Title', 'archzilla' ) ?>">
					</li>

					<li>
						<input class="site-tagline" type="text" placeholder="<?php echo esc_html__( 'My WordPress Site Tagline (Optional)', 'archzilla' ) ?>">
					</li>

					<li>
						<input type="checkbox" name="install-child-theme" class="checkbox" id="install-child-theme" value="1">

						<label for="install-child-theme">
							<i></i>

							<span>Install Child Theme</span>

							<span class="badge">
								<span class="hint--top" aria-label="<?php esc_html_e( 'Recommended', 'archzilla' ); ?>">
									<?php esc_html_e( 'rec', 'archzilla' ); ?>
								</span>
							</span>
						</label>
					</li>

				</ul>

				<footer class="wizard__content__footer ">
					<input type="submit" value="Continue" data-callback="install_child" data-url="<?php echo esc_url( $this->step_next_link() ); ?>">
					<?php wp_nonce_field( 'wizard' ); ?>
				</footer>
				
			</form>

			
		</div>	
	<?php
	}

	/**
	 * Theme plugins
	 */
	protected function plugins() {

		// Variables.
		$url    = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'wizard' );
		$method = '';
		$fields = array_keys( $_POST );
		$creds  = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields );

		tgmpa_load_bulk_installer();

		if ( false === $creds ) {
			return true;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}

		// Are there plugins that need installing/activating?
		$plugins          = $this->get_tgmpa_plugins();
		$required_plugins = $recommended_plugins = array();
		$count            = count( $plugins['all'] );
		$class            = $count ? null : 'no-plugins';

		// Split the plugins into required and recommended.
		foreach ( $plugins['all'] as $slug => $plugin ) {
			if ( ! empty( $plugin['required'] ) ) {
				$required_plugins[ $slug ] = $plugin;
			} else {
				$recommended_plugins[ $slug ] = $plugin;
			}
		}

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $count ? $strings['plugins-header'] : $strings['plugins-header-success'];
		$paragraph = $count ? $strings['plugins'] : $strings['plugins-success%s'];
		$action    = $strings['plugins-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$install   = $strings['btn-plugins-install'];
		?>

		<div class="wizard__content--transition">

			<h1><?php echo esc_html( $header ); ?></h1>

			<p>Let's install some essential WordPress <br /> plugins to get your site up to speed.</p>

		</div>

		<div class="wizard__content--body">
			<form action="" method="post">
				<?php if ( $count ) : ?>

					<ul class="wizard__drawer wizard__drawer--install-plugins">
					<?php if ( ! empty( $required_plugins ) ) : ?>

					<?php $required_plugins = array_reverse( $required_plugins ) ?>
					
						<?php foreach ( $required_plugins as $slug => $plugin ) : ?>
							<li data-slug="<?php echo esc_attr( $slug ); ?>">
								<input type="checkbox" name="default_plugins[<?php echo esc_attr( $slug ); ?>]" class="checkbox" id="default_plugins_<?php echo esc_attr( $slug ); ?>" value="1" checked>

								<label for="default_plugins_<?php echo esc_attr( $slug ); ?>">
									<i></i>

									<span><?php echo esc_html( $plugin['name'] ); ?></span>

									<span class="badge">
										<span class="hint--top" aria-label="<?php esc_html_e( 'Required', 'archzilla' ); ?>">
											<?php esc_html_e( 'req', 'archzilla' ); ?>
										</span>
									</span>
								</label>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $recommended_plugins ) ) : ?>
						<?php foreach ( $recommended_plugins as $slug => $plugin ) : ?>
							<li data-slug="<?php echo esc_attr( $slug ); ?>">
								<input type="checkbox" name="default_plugins[<?php echo esc_attr( $slug ); ?>]" class="checkbox" id="default_plugins_<?php echo esc_attr( $slug ); ?>">

								<label for="default_plugins_<?php echo esc_attr( $slug ); ?>">
									<i></i><span><?php echo esc_html( $plugin['name'] ); ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>

					</ul>

				<?php else : ?>
					<h3>All plugins are installed & activated.</h3>
				<?php endif; ?>

				<footer class="wizard__content__footer <?php echo esc_attr( $class ); ?>">
					<?php if ( $count ) : ?>
						<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next button-next" data-callback="install_plugins">
							<span class="wizard__button--loading__text"><?php echo esc_html( $install ); ?></span>
							<?php echo wp_kses( $this->loading_spinner(), $this->loading_spinner_allowed_html() ); ?>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next wizard__button--proceed wizard__button--colorchange"><?php echo esc_html( $next ); ?></a>
					<?php endif; ?>
					<?php wp_nonce_field( 'wizard' ); ?>
				</footer>
			</form>
		</div>

	<?php
	}

	/**
	 * Final step
	 */
	protected function license() {

		update_option( 'wizard_' . $this->slug . '_completed', time() );
		?>

		<div class="wizard__content--transition">

			<h1>License Verification</h1>

			<p>Enable the Neuron Builder License Verfication through Envato, <br /> the verification will unlock various features of the theme.</p>

		</div>

		<?php if ( class_exists( 'Neuron' ) && class_exists( 'Envato_Market' ) ) { ?>
			<div class="wizard__content--body wizard__content--body-ready wizard__content--license-activate-<?php echo Neuron\License\Admin::is_license_activated() ? 'yes' : 'no' ?>">
					<div class="neuron-admin">
						<div class="neuron-admin__content">
							<div class="neuron-admin__card neuron-admin__card-wizard neuron-admin__card--license">
								<form method="POST" action="<?php echo esc_url( ENVATO_MARKET_NETWORK_ACTIVATED ? network_admin_url( 'edit.php?action=envato_market_network_settings' ) : admin_url( 'options.php' ) ); ?>">
									<?php Envato_Market_Admin::render_settings_theme_panel_partial(); ?>

									<?php if ( ! Neuron\License\Admin::is_license_activated() ) { ?>
										<input type="submit" name="submit" id="submit" class="button button-primary" value="Verify Token">
									<?php } ?>
								</form>
							</div>
						</div>
					</div>

					<?php if ( Neuron\License\Admin::is_license_activated() ) { ?>
						<a class="wizard__button button invert" href="<?php echo esc_url( admin_url('admin.php?page=neuron') ) ?>">Start from Scratch</a>
						<a class="wizard__button button" href="<?php echo esc_url( admin_url('admin.php?page=demo-importer') ) ?>">Start with pre-built Demos</a>
					<?php } ?>
			</div>
		<?php } ?>
	<?php
	}

	/**
	 * Get registered TGMPA plugins
	 *
	 * @return    array
	 */
	protected function get_tgmpa_plugins() {
		$plugins = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);

		$predefined_plugins = [
			'neuron-builder' => 'Neuron',
			'elementor' => 'Elementor',
			'revslider' => 'RevSliderFront',
			'woocommerce' => 'WooCommerce',
		];

		foreach ( $this->tgmpa->plugins as $slug => $plugin ) {
			if ( class_exists( $predefined_plugins[$slug] ) && false === $this->tgmpa->does_plugin_have_update( $slug ) ) {
				continue;
			} else if ( $slug == 'elementor' && did_action( 'elementor/loaded' ) ) { 
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}
					if ( $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}

		return $plugins;
	}

	/**
	 * Generate the child theme via AJAX.
	 */
	public function generate_child() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$success = $strings['child-json-success%s'];
		$already = $strings['child-json-already%s'];

		$name = $this->theme . ' Child';
		$slug = sanitize_title( $name );

		$path = get_theme_root() . '/' . $slug;

		if ( ! file_exists( $path ) ) {

			WP_Filesystem();

			global $wp_filesystem;

			wp_mkdir_p( $path );
			$wp_filesystem->put_contents( $path . '/style.css', $this->generate_child_style_css( $this->theme->template, $this->theme->name, $this->theme->author, $this->theme->version ) );
			$wp_filesystem->put_contents( $path . '/functions.php', $this->generate_child_functions_php( $this->theme->template ) );

			$this->generate_child_screenshot( $path );

			$allowed_themes          = get_option( 'allowedthemes' );
			$allowed_themes[ $slug ] = true;
			update_option( 'allowedthemes', $allowed_themes );

		} else {

			if ( $this->theme->template !== $slug ) :
				update_option( 'wizard_' . $this->slug . '_child', $name );
				switch_theme( $slug );
			endif;

			wp_send_json(
				array(
					'done'    => 1,
					'message' => sprintf(
						esc_html( $success ), $slug
					),
				)
			);
		}

		if ( $this->theme->template !== $slug ) :
			update_option( 'wizard_' . $this->slug . '_child', $name );
			switch_theme( $slug );
		endif;

		wp_send_json(
			array(
				'done'    => 1,
				'message' => sprintf(
					esc_html( $already ), $name
				),
			)
		);
	}


	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/688327dd103b1aa826ebae47e99a0fbe
	 *
	 * @param string $slug Parent theme slug.
	 */
	public function generate_child_functions_php( $slug ) {

		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

		$output = "
			<?php
			/**
			 * Theme functions and definitions.
			 *
			 * @link https://developer.wordpress.org/themes/basics/theme-functions/
			 */

			/*
			 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
			 * you will have to make sure to maintain all of the parent theme dependencies.
			 *
			 * Make sure you're using the correct handle for loading the parent theme's styles.
			 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
			 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
			 *
			 * @link https://codex.wordpress.org/Child_Themes
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
		return apply_filters( 'wizard_generate_child_functions_php', $output, $slug );
	}

	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/7d88d279706fc3093911e958fd1fd791
	 *
	 * @param string $slug    Parent theme slug.
	 * @param string $parent  Parent theme name.
	 * @param string $author  Parent theme author.
	 * @param string $version Parent theme version.
	 */
	public function generate_child_style_css( $slug, $parent, $author, $version ) {

		$output = "
			/**
			* Theme Name: {$parent} Child
			* Description: This is a child theme of {$parent}.
			* Author: {$author}
			* Template: {$slug}
			* Version: {$version}
			*/\n
		";

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		return apply_filters( 'wizard_generate_child_style_css', $output, $slug, $parent, $version );
	}

	/**
	 * Generate child theme screenshot file.
	 *
	 * @param string $path    Child theme path.
	 */
	public function generate_child_screenshot( $path ) {

		$screenshot = apply_filters( 'wizard_generate_child_screenshot', '' );

		if ( ! empty( $screenshot ) ) {
			// Get custom screenshot file extension
			if ( '.png' === substr( $screenshot, -4 ) ) {
				$screenshot_ext = 'png';
			} else {
				$screenshot_ext = 'jpg';
			}
		} else {
			if ( file_exists( $this->base_path . '/screenshot.png' ) ) {
				$screenshot     = $this->base_path . '/screenshot.png';
				$screenshot_ext = 'png';
			} elseif ( file_exists( $this->base_path . '/screenshot.jpg' ) ) {
				$screenshot     = $this->base_path . '/screenshot.jpg';
				$screenshot_ext = 'jpg';
			}
		}

		if ( ! empty( $screenshot ) && file_exists( $screenshot ) ) {
			$copied = copy( $screenshot, $path . '/screenshot.' . $screenshot_ext );
		} else {
		}
	}

	/**
	 * Do plugins' AJAX
	 *
	 * @internal    Used as a calback.
	 */
	function _ajax_plugins() {

		if ( ! check_ajax_referer( 'wizard_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			exit( 0 );
		}

		$json      = array();
		$tgmpa_url = $this->tgmpa->get_tgmpa_url();
		$plugins   = $this->get_tgmpa_plugins();

		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating', 'archzilla' ),
				);
				break;
			}
		}

		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating', 'archzilla' ),
				);
				break;
			}
		}

		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing', 'archzilla' ),
				);
				break;
			}
		}

		if ( $json ) {

			$json['hash']    = md5( serialize( $json ) );
			$json['message'] = esc_html__( 'Installing', 'archzilla' );
			wp_send_json( $json );
		} else {

			wp_send_json(
				array(
					'done'    => 1,
					'message' => esc_html__( 'Success', 'archzilla' ),
				)
			);
		}

		exit;
	}

}
