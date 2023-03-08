<?php
/**
 * Admin UI class.
 *
 * @package Envato_Market
 */
use Neuron\License\Admin as License_API;

if ( ! class_exists( 'Envato_Market_Admin' ) && class_exists( 'Envato_Market' ) ) :

	/**
	 * Creates an admin page to save the Envato API OAuth token.
	 *
	 * @class Envato_Market_Admin
	 * @version 1.0.0
	 * @since 1.0.0
	 */
	class Envato_Market_Admin {

		/**
		 * Action nonce.
		 *
		 * @type string
		 */
		const AJAX_ACTION = 'envato_market';

		/**
		 * The single class instance.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var object
		 */
		private static $_instance = null;

		/**
		 * Main Envato_Market_Admin Instance
		 *
		 * Ensures only one instance of this class exists in memory at any one time.
		 *
		 * @return object The one true Envato_Market_Admin.
		 * @codeCoverageIgnore
		 * @uses Envato_Market_Admin::init_actions() Setup hooks and actions.
		 *
		 * @since 1.0.0
		 * @static
		 * @see  Envato_Market_Admin()
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->init_actions();
			}

			return self::$_instance;
		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Envato_Market_Admin::instance()
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function __construct() {
			/* We do nothing here! */
		}

		/**
		 * You cannot clone this class.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'neuron-builder' ), '1.0.0' );
		}

		/**
		 * You cannot unserialize instances of this class.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'neuron-builder' ), '1.0.0' );
		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 *
		 * @since 1.0.0
		 */
		public function init_actions() {
			// @codeCoverageIgnoreStart
			if ( false === envato_market()->get_data( 'admin' ) && false === envato_market()->get_option( 'is_plugin_active' ) ) { // Turns the UI off if allowed.
				return;
			}
			// @codeCoverageIgnoreEnd
			// Deferred Download.
			add_action( 'upgrader_package_options', array( $this, 'maybe_deferred_download' ), 9 );

			// Add pre download filter to help with 3rd party plugin integration.
			add_filter( 'upgrader_pre_download', array( $this, 'upgrader_pre_download' ), 2, 4 );

			// Add item AJAX handler.
			add_action( 'wp_ajax_' . self::AJAX_ACTION . '_add_item', array( $this, 'ajax_add_item' ) );

			// Remove item AJAX handler.
			add_action( 'wp_ajax_' . self::AJAX_ACTION . '_remove_item', array( $this, 'ajax_remove_item' ) );

			// Maybe delete the site transients.
			add_action( 'init', array( $this, 'maybe_delete_transients' ), 11 );

			// Add the menu.
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

			// Register the settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// We may need to redirect after an item is enabled.
			add_action( 'current_screen', array( $this, 'maybe_redirect' ) );

			// Add authorization notices.
			add_action( 'current_screen', array( $this, 'add_notices' ) );

			// Set the API values.
			add_action( 'current_screen', array( $this, 'set_items' ) );

			// Hook to verify the API token before saving it.
			add_filter(
				'pre_update_option_' . envato_market()->get_option_name(),
				array(
					$this,
					'check_api_token_before_saving',
				),
				9,
				3
			);
			add_filter(
				'pre_update_site_option_' . envato_market()->get_option_name(),
				array(
					$this,
					'check_api_token_before_saving',
				),
				9,
				3
			);

			// When network enabled, add the network options menu.
			add_action( 'network_admin_menu', array( $this, 'add_menu_page' ) );

			// Ability to make use of the Settings API when in multisite mode.
			add_action( 'network_admin_edit_envato_market_network_settings', array( $this, 'save_network_settings' ) );
		}

		/**
		 * This runs before we save the Envato Market options array.
		 * If the token has changed then we set a transient so we can do the update check.
		 *
		 * @param array $value The option to save.
		 * @param array $old_value The old option value.
		 * @param array $option Serialized option value.
		 *
		 * @return array $value The updated option value.
		 * @since 2.0.1
		 */
		public function check_api_token_before_saving( $value, $old_value, $option ) {
			if ( ! empty( $value['token'] ) && ( empty( $old_value['token'] ) || $old_value['token'] != $value['token'] || isset( $_POST['envato_market'] ) ) ) {
				set_site_transient( envato_market()->get_option_name() . '_check_token', $value['token'], HOUR_IN_SECONDS );
			}

			return $value;
		}


		/**
		 * Defers building the API download url until the last responsible moment to limit file requests.
		 *
		 * Filter the package options before running an update.
		 *
		 * @param array $options {
		 *     Options used by the upgrader.
		 *
		 * @type string $package Package for update.
		 * @type string $destination Update location.
		 * @type bool   $clear_destination Clear the destination resource.
		 * @type bool   $clear_working Clear the working resource.
		 * @type bool   $abort_if_destination_exists Abort if the Destination directory exists.
		 * @type bool   $is_multi Whether the upgrader is running multiple times.
		 * @type array  $hook_extra Extra hook arguments.
		 * }
		 * @since 1.0.0
		 */
		public function maybe_deferred_download( $options ) {
			$package = $options['package'];

			if ( false !== strrpos( $package, 'deferred_download' ) && false !== strrpos( $package, 'item_id' ) ) {
				parse_str( parse_url( $package, PHP_URL_QUERY ), $vars );
				if ( $vars['item_id'] ) {
					$args               = $this->set_bearer_args( $vars['item_id'] );
					$options['package'] = envato_market()->api()->download( $vars['item_id'], $args );
				}
			}

			return $options;
		}

		/**
		 * We want to stop certain popular 3rd party scripts from blocking the update process by
		 * adjusting the plugin name slightly so the 3rd party plugin checks stop.
		 *
		 * Currently works for: Visual Composer.
		 *
		 * @param string $reply Package URL.
		 * @param string $package Package URL.
		 * @param object $updater Updater Object.
		 *
		 * @return string $reply    New Package URL.
		 * @since 2.0.0
		 */
		public function upgrader_pre_download( $reply, $package, $updater ) {
			if ( strpos( $package, 'marketplace.envato.com/short-dl' ) !== false ) {
				if ( isset( $updater->skin->plugin_info ) && ! empty( $updater->skin->plugin_info['Name'] ) ) {
					$updater->skin->plugin_info['Name'] = $updater->skin->plugin_info['Name'] . '.';
				} else {
					$updater->skin->plugin_info = array(
						'Name' => 'Name',
					);
				}
			}

			return $reply;
		}

		/**
		 * Returns the bearer arguments for a request with a single use API Token.
		 *
		 * @param int $id The item ID.
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function set_bearer_args( $id ) {
			$token = '';
			$args  = array();
			foreach ( envato_market()->get_option( 'items', array() ) as $item ) {
				if ( absint( $item['id'] ) === absint( $id ) ) {
					$token = $item['token'];
					break;
				}
			}
			if ( ! empty( $token ) ) {
				$args = array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $token,
					),
				);
			}

			return $args;
		}

		/**
		 * Maybe delete the site transients.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function maybe_delete_transients() {
			if ( isset( $_POST[ envato_market()->get_option_name() ] ) ) {

				// Nonce check.
				if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( $_POST['_wpnonce'], envato_market()->get_slug() . '-options' ) ) {
					wp_die( __( 'You do not have sufficient permissions to delete transients.', 'neuron-builder' ) );
				}

				self::delete_transients();
			} elseif ( ! envato_market()->get_option( 'installed_version', 0 ) || version_compare( envato_market()->get_version(), envato_market()->get_option( 'installed_version', 0 ), '<' ) ) {

				// When the plugin updates we want to delete transients.
				envato_market()->set_option( 'installed_version', envato_market()->get_version() );
				self::delete_transients();

			}
		}

		/**
		 * Delete the site transients.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function delete_transients() {
			delete_site_transient( envato_market()->get_option_name() . '_themes' );
			delete_site_transient( envato_market()->get_option_name() . '_plugins' );
			delete_site_transient( 'neuron_license_verification' );
		}

		/**
		 * Prints out all settings sections added to a particular settings page in columns.
		 *
		 * @param string $page The slug name of the page whos settings sections you want to output.
		 * @param int    $columns The number of columns in each row.
		 *
		 * @since 1.0.0
		 *
		 * @global array $wp_settings_sections Storage array of all settings sections added to admin pages
		 * @global array $wp_settings_fields Storage array of settings fields and info about their pages/sections
		 */
		public static function do_settings_sections( $page, $title = true, $callback_visibility = true, $generate = true, $extraGenerate = false ) {
			global $wp_settings_sections, $wp_settings_fields;

			// @codeCoverageIgnoreStart
			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}
			// @codeCoverageIgnoreEnd
			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
				// @codeCoverageIgnoreStart
				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}
				// @codeCoverageIgnoreEnd
				// Set the column class.
				$class = 'envato-market-block';
				?>
				<div class="<?php echo esc_attr( $class ); ?>">
				  <?php
					if ( ! empty( $section['title'] ) && $title ) {
						echo '<h3>' . esc_html( $section['title'] ) . '</h3>' . "\n";
					}
					if ( ! empty( $section['callback'] ) && $callback_visibility ) {
						call_user_func( $section['callback'], $section );
					}
					?>
					<?php if ( License_API::is_license_activated() ) : ?>
						<h3>You have successfully activated the Neuron Builder.</h3>
					<?php endif; ?>

					<table class="form-table">
						<?php do_settings_fields( $page, $section['id'] ); ?>
					</table>
					<?php if ( $generate ) { ?>
						<p class="generate-url">Generate an Envato API Personal Token by <a href="https://build.envato.com/create-token/?default=t&purchase:download=t&purchase:list=t" target="_BLANK">clicking this link</a>.</p>
							</div>			
					<?php } ?>	

					<?php if ( $extraGenerate ) { ?>
						<span class="activated">
							<svg role="img" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="2" y="3" width="14" height="12"><path d="M6.59631 11.9062L3.46881 8.77875L2.40381 9.83625L6.59631 14.0287L15.5963 5.02875L14.5388 3.97125L6.59631 11.9062Z" fill="white"></path></mask><g mask="url(#mask0)"><rect width="18" height="18" fill="white"></rect></g></svg>
						</span>
						<div class="generate-url">
							Generate an Envato API Personal Token by <a href="https://build.envato.com/create-token/?default=t&purchase:download=t&purchase:list=t" target="_BLANK">clicking this link</a>.

							<span class="open-tooltip">
								<span>
									<p>Ensure the following permissions are enabled:</p>

									<ul class="steps-required">
										<li>View and search Envato Sites</li>
										<li>Download your purchased items</li>
										<li>List purchases you've made</li>
									</ul>
								</span>
								

								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18px" height="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path></svg>
							</span>
						</div>
					<?php } ?>			
				<?php
			}
		}

		/**
		 * Adds the menu.
		 *
		 * @since 1.0.0
		 */
		public function add_menu_page() {

			if ( ENVATO_MARKET_NETWORK_ACTIVATED && ! is_super_admin() ) {
				return;
			}

			$page = add_submenu_page(
				'neuron',
				__( 'License', 'neuron-builder' ),
				__( 'License', 'neuron-builder' ),
				'administrator',
				envato_market()->get_slug(),
				array(
					$this,
					'render_admin_callback',
				)
			);

			

			// Enqueue admin JavaScript.
			add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_enqueue_script' ) );

			// Add Underscore.js templates.
			add_action( 'admin_footer-' . $page, array( $this, 'render_templates' ) );
		}

		/**
		 * Enqueue admin script.
		 *
		 * @since  1.0.0
		 */
		public function admin_enqueue_script() {
			$min        = ( WP_DEBUG ? '' : '.min' );
			$slug       = envato_market()->get_slug();
			$version    = envato_market()->get_version();
			$plugin_url = envato_market()->get_plugin_url();

			wp_enqueue_script(
				$slug,
				$plugin_url . 'js/envato-market.min.js',
				array(
					'jquery',
					'jquery-ui-dialog',
					'wp-util',
				),
				$version,
				true
			);
			wp_enqueue_script(
				$slug . '-updates',
				$plugin_url . 'js/updates.min.js',
				array(
					'jquery',
					'updates',
					'wp-a11y',
					'wp-util',
				),
				$version,
				true
			);

			// Script data array.
			$exports = array(
				'nonce'  => wp_create_nonce( self::AJAX_ACTION ),
				'action' => self::AJAX_ACTION,
				'i18n'   => array(
					'save'   => __( 'Save', 'neuron-builder' ),
					'remove' => __( 'Remove', 'neuron-builder' ),
					'cancel' => __( 'Cancel', 'neuron-builder' ),
					'error'  => __( 'An unknown error occurred. Try again.', 'neuron-builder' ),
				),
			);

			// Export data to JS.
			wp_scripts()->add_data(
				$slug,
				'data',
				sprintf( 'var _envatoMarket = %s;', wp_json_encode( $exports ) )
			);
		}

		/**
		 * Underscore (JS) templates for dialog windows.
		 *
		 * @codeCoverageIgnore
		 */
		public function render_templates() {
			?>
				<script type="text/html" id="tmpl-envato-market-auth-check-button">
					<a
						href="<?php echo esc_url( add_query_arg( array( 'authorization' => 'check' ), envato_market()->get_page_url() ) ); ?>"
						class="button button-secondary auth-check-button"
						style="margin:0 5px"><?php esc_html_e( 'Test API Connection', 'neuron-builder' ); ?></a>
				</script>

				<script type="text/html" id="tmpl-envato-market-item">
					<li data-id="{{ data.id }}">
						<span class="item-name"><?php esc_html_e( 'ID', 'neuron-builder' ); ?>
							: {{ data.id }} - {{ data.name }}</span>
						<button class="item-delete dashicons dashicons-dismiss">
							<span class="screen-reader-text"><?php esc_html_e( 'Delete', 'neuron-builder' ); ?></span>
						</button>
						<input type="hidden"
								name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[items][{{ data.key }}][name]"
								value="{{ data.name }}"/>
						<input type="hidden"
								name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[items][{{ data.key }}][token]"
								value="{{ data.token }}"/>
						<input type="hidden"
								name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[items][{{ data.key }}][id]"
								value="{{ data.id }}"/>
						<input type="hidden"
								name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[items][{{ data.key }}][type]"
								value="{{ data.type }}"/>
						<input type="hidden"
								name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[items][{{ data.key }}][authorized]"
								value="{{ data.authorized }}"/>
					</li>
				</script>

				<script type="text/html" id="tmpl-envato-market-dialog-remove">
					<div id="envato-market-dialog-remove" title="<?php esc_html_e( 'Remove Item', 'neuron-builder' ); ?>">
						<p><?php esc_html_e( 'You are about to remove the connection between the Envato Market API and this item. You cannot undo this action.', 'neuron-builder' ); ?></p>
					</div>
				</script>

				<script type="text/html" id="tmpl-envato-market-dialog-form">
					<div id="envato-market-dialog-form" title="<?php esc_html_e( 'Add Item', 'neuron-builder' ); ?>">
						<form>
							<fieldset>
								<label for="token"><?php esc_html_e( 'Token', 'neuron-builder' ); ?></label>
								<input type="text" name="token" class="widefat" value=""/>
								<p
									class="description"><?php esc_html_e( 'Enter the Envato API Personal Token.', 'neuron-builder' ); ?></p>
								<label for="id"><?php esc_html_e( 'Item ID', 'neuron-builder' ); ?></label>
								<input type="text" name="id" class="widefat" value=""/>
								<p class="description"><?php esc_html_e( 'Enter the Envato Item ID.', 'neuron-builder' ); ?></p>
								<input type="submit" tabindex="-1" style="position:absolute; top:-5000px"/>
							</fieldset>
						</form>
					</div>
				</script>

				<script type="text/html" id="tmpl-envato-market-dialog-error">
					<div class="notice notice-error">
						<p>{{ data.message }}</p>
					</div>
				</script>

				<script type="text/html" id="tmpl-envato-market-card">
					<div class="envato-market-block" data-id="{{ data.id }}">
						<div class="envato-card {{ data.type }}">
							<div class="envato-card-top">
								<a href="{{ data.url }}" class="column-icon">
									<img src="{{ data.thumbnail_url }}"/>
								</a>
								<div class="column-name">
									<h4>
										<a href="{{ data.url }}">{{ data.name }}</a>
										<span class="version"
												aria-label="<?php esc_attr_e( 'Version %s', 'neuron-builder' ); ?>"><?php esc_html_e( 'Version', 'neuron-builder' ); ?>
											{{ data.version }}</span>
									</h4>
								</div>
								<div class="column-description">
									<div class="description">
										<p>{{ data.description }}</p>
									</div>
									<p class="author">
										<cite><?php esc_html_e( 'By', 'neuron-builder' ); ?> {{ data.author }}</cite>
									</p>
								</div>
							</div>
							<div class="envato-card-bottom">
								<div class="column-actions">
									<a href="{{{ data.install }}}" class="button button-primary">
										<span aria-hidden="true"><?php esc_html_e( 'Install', 'neuron-builder' ); ?></span>
										<span class="screen-reader-text"><?php esc_html_e( 'Install', 'neuron-builder' ); ?> {{ data.name }}</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</script>
			<?php
		}

		/**
		 * Registers the settings.
		 *
		 * @since 1.0.0
		 */
		public function register_settings() {
			// Setting.
			register_setting( envato_market()->get_slug(), envato_market()->get_option_name() );

			// OAuth section.
			add_settings_section(
				envato_market()->get_option_name() . '_oauth_section',
				null,
				array( $this, 'render_oauth_section_callback' ),
				envato_market()->get_slug()
			);

			// Token setting.
			add_settings_field(
				'token',
				__( 'Token', 'neuron-builder' ),
				array( $this, 'render_token_setting_callback' ),
				envato_market()->get_slug(),
				envato_market()->get_option_name() . '_oauth_section'
			);
		}

		/**
		 * Redirect after the enable action runs.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function maybe_redirect() {
			if ( $this->are_we_on_settings_page() ) {

				if ( ! empty( $_GET['action'] ) && 'install-theme' === $_GET['action'] && ! empty( $_GET['enabled'] ) ) {
					wp_safe_redirect( esc_url( envato_market()->get_page_url() ) );
					exit;
				}
			}
		}

		/**
		 * Add authorization notices.
		 *
		 * @since 1.0.0
		 */
		public function add_notices() {

			if ( $this->are_we_on_settings_page() ) {

				// @codeCoverageIgnoreStart
				if ( get_site_transient( envato_market()->get_option_name() . '_check_token' ) || ( isset( $_GET['authorization'] ) && 'check' === $_GET['authorization'] ) ) {
					delete_site_transient( envato_market()->get_option_name() . '_check_token' );
					self::authorization_redirect();
				}
				// @codeCoverageIgnoreEnd
				// Get the option array.
				$option = envato_market()->get_options();

				// Display success/error notices.
				if ( ! empty( $option['notices'] ) ) {
					self::delete_transients();

					// Show succes notice.
					if ( isset( $option['notices']['success'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_success_notice',
							)
						);
					}

					// Show succes no-items notice.
					if ( isset( $option['notices']['success-no-items'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_success_no_items_notice',
							)
						);
					}

					// Show single-use succes notice.
					if ( isset( $option['notices']['success-single-use'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_success_single_use_notice',
							)
						);
					}

					// Show error notice.
					if ( isset( $option['notices']['error'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_error_notice',
							)
						);
					}

					// Show invalid permissions error notice.
					if ( isset( $option['notices']['error-permissions'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_error_permissions',
							)
						);
					}

					// Show single-use error notice.
					if ( isset( $option['notices']['error-single-use'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_error_single_use_notice',
							)
						);
					}

					// Show missing zip notice.
					if ( isset( $option['notices']['missing-package-zip'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_error_missing_zip',
							)
						);
					}

					// Show missing http connection error.
					if ( isset( $option['notices']['http_error'] ) ) {
						add_action(
							( ENVATO_MARKET_NETWORK_ACTIVATED ? 'network_' : '' ) . 'admin_notices',
							array(
								$this,
								'render_error_http',
							)
						);
					}

					// Update the saved data so the notice disappears on the next page load.
					unset( $option['notices'] );

					envato_market()->set_options( $option );
				}
			}
		}

		/**
		 * Set the API values.
		 *
		 * @since 1.0.0
		 */
		public function set_items() {
			if ( $this->are_we_on_settings_page() ) {
				envato_market()->items()->set_themes();
				envato_market()->items()->set_plugins();
			}
		}

		/**
		 * Check if we're on the settings page.
		 *
		 * @since 2.0.0
		 * @access private
		 */
		private function are_we_on_settings_page() {
			return true;
		}

		/**
		 * Check for authorization and redirect.
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function authorization_redirect() {
			self::authorization();
			wp_safe_redirect( esc_url( envato_market()->get_page_url() ) );
			exit;
		}

		/**
		 * Set the Envato API authorization value.
		 *
		 * @since 1.0.0
		 */
		public function authorization() {
			// Get the option array.
			$option            = envato_market()->get_options();
			$option['notices'] = array();

			// Check for global token.
			if ( envato_market()->get_option( 'token' ) || envato_market()->api()->token ) {

				$notice      = 'success';
				$scope_check = $this->authorize_token_permissions();

				if ( 'http_error' === $scope_check ) {
					$notice = 'http_error';
				} elseif ( 'error' === $this->authorize_total_items() || 'error' === $scope_check ) {
					$notice = 'error';
				} else {
					if ( 'missing-permissions' == $scope_check ) {
						$notice = 'error-permissions';
					} elseif ( 'too-many-permissions' === $scope_check ) {
						$notice = 'error-permissions';
					} else {
						$themes_notice  = $this->authorize_themes();
						$plugins_notice = $this->authorize_plugins();
						if ( 'error' === $themes_notice || 'error' === $plugins_notice ) {
							$notice = 'error';
						} elseif ( 'success-no-themes' === $themes_notice && 'success-no-plugins' === $plugins_notice ) {
							$notice = 'success-no-items';
						}
					}
				}
				$option['notices'][ $notice ] = true;
			}

			// Check for single-use token.
			if ( ! empty( $option['items'] ) ) {
				$failed = false;

				foreach ( $option['items'] as $key => $item ) {
					if ( empty( $item['name'] ) || empty( $item['token'] ) || empty( $item['id'] ) || empty( $item['type'] ) || empty( $item['authorized'] ) ) {
						continue;
					}

					$request_args = array(
						'headers' => array(
							'Authorization' => 'Bearer ' . $item['token'],
						),
					);

					// Uncached API response with single-use token.
					$response = envato_market()->api()->item( $item['id'], $request_args );

					if ( ! is_wp_error( $response ) && isset( $response['id'] ) ) {
						$option['items'][ $key ]['authorized'] = 'success';
					} else {
						if ( is_wp_error( $response ) ) {
							$this->store_additional_error_debug_information( 'Unable to query single item ID ' . $item['id'], $response->get_error_message(), $response->get_error_data() );
						}
						$failed                                = true;
						$option['items'][ $key ]['authorized'] = 'failed';
					}
				}

				if ( true === $failed ) {
					$option['notices']['error-single-use'] = true;
				} else {
					$option['notices']['success-single-use'] = true;
				}
			}

			// Set the option array.
			if ( ! empty( $option['notices'] ) ) {
				envato_market()->set_options( $option );
			}
		}

		/**
		 * Check that themes are authorized.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function authorize_total_items() {
			$response = envato_market()->api()->request( 'https://api.envato.com/v1/market/total-items.json' );
			$notice   = 'success';

			if ( is_wp_error( $response ) ) {
				$notice = 'error';
				$this->store_additional_error_debug_information( 'Failed to query total number of items in API response', $response->get_error_message(), $response->get_error_data() );
			} elseif ( ! isset( $response['total-items'] ) ) {
				$notice = 'error';
				$this->store_additional_error_debug_information( 'Incorrect response from API when querying total items' );
			}

			return $notice;
		}


		/**
		 * Get the required API permissions for this plugin to work.
		 *
		 * @single 2.0.1
		 *
		 * @return array
		 */
		public function get_required_permissions() {
			return apply_filters(
				'envato_market_required_permissions',
				array(
					'default'           => 'View and search Envato sites',
					'purchase:download' => 'Download your purchased items',
					'purchase:list'     => 'List purchases you\'ve made',
				)
			);
		}

		/**
		 * Return the URL a user needs to click to generate a personal token.
		 *
		 * @single 2.0.1
		 *
		 * @return string The full URL to request a token.
		 */
		public function get_generate_token_url() {
			return 'https://build.envato.com/create-token/?' . implode(
				'&',
				array_map(
					function ( $val ) {
							return $val . '=t';
					},
					array_keys( $this->get_required_permissions() )
				)
			);
		}

		/**
		 * Check that themes are authorized.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function authorize_token_permissions() {
			$response = envato_market()->api()->request( 'https://api.envato.com/whoami' );
			$notice   = 'success';

			if ( is_wp_error( $response ) && ( $response->get_error_code() === 'http_error' || $response->get_error_code() == 500 ) ) {
				$this->store_additional_error_debug_information( 'An error occured checking token permissions', $response->get_error_message(), $response->get_error_data() );
				$notice = 'http_error';
			} elseif ( is_wp_error( $response ) || ! isset( $response['scopes'] ) || ! is_array( $response['scopes'] ) ) {
				$this->store_additional_error_debug_information( 'No scopes found in API response message', $response->get_error_message(), $response->get_error_data() );
				$notice = 'error';
			} else {

				$minimum_scopes = $this->get_required_permissions();
				$maximum_scopes = array( 'default' => 'Default' ) + $minimum_scopes;

				foreach ( $minimum_scopes as $required_scope => $required_scope_name ) {
					if ( ! in_array( $required_scope, $response['scopes'] ) ) {
						// The scope minimum required scope doesn't exist.
						$this->store_additional_error_debug_information( 'Could not find required API permission scope in output.', $required_scope );
						$notice = 'missing-permissions';
					}
				}
				foreach ( $response['scopes'] as $scope ) {
					if ( ! isset( $maximum_scopes[ $scope ] ) ) {
						// The available scope is outside our maximum bounds.
						$this->store_additional_error_debug_information( 'Found too many permissions on token.', $scope );
						$notice = 'too-many-permissions';
					}
				}
			}

			return $notice;
		}

		/**
		 * Check that themes or plugins are authorized and downloadable.
		 *
		 * @param string $type The filter type, either 'themes' or 'plugins'. Default 'themes'.
		 *
		 * @return bool|null
		 * @since 1.0.0
		 */
		public function authorize_items( $type = 'themes' ) {
			$api_url  = 'https://api.envato.com/v2/market/buyer/list-purchases?filter_by=wordpress-' . $type;
			$response = envato_market()->api()->request( $api_url );
			$notice   = 'success';

			if ( is_wp_error( $response ) ) {
				$notice = 'error';
				$this->store_additional_error_debug_information( 'Error listing buyer purchases.', $response->get_error_message(), $response->get_error_data() );
			} elseif ( empty( $response ) ) {
				$notice = 'error';
				$this->store_additional_error_debug_information( 'Empty API result listing buyer purchases' );
			} elseif ( empty( $response['results'] ) ) {
				$notice = 'success-no-' . $type;
			} else {
				shuffle( $response['results'] );
				$item = array_shift( $response['results'] );
				if ( ! isset( $item['item']['id'] ) || ! envato_market()->api()->download( $item['item']['id'] ) ) {
					$this->store_additional_error_debug_information( 'Failed to find the correct item format in API response' );
					$notice = 'error';
				}
			}

			return $notice;
		}

		/**
		 * Check that themes are authorized.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function authorize_themes() {
			return $this->authorize_items( 'themes' );
		}

		/**
		 * Check that plugins are authorized.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function authorize_plugins() {
			return $this->authorize_items( 'plugins' );
		}

		/**
		 * Install plugin.
		 *
		 * @param string $plugin The plugin item ID.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function install_plugin( $plugin ) {
			if ( ! current_user_can( 'install_plugins' ) ) {
				$msg = '
				<div class="wrap">
					<h1>' . __( 'Installing Plugin...', 'neuron-builder' ) . '</h1>
					<p>' . __( 'You do not have sufficient permissions to install plugins on this site.', 'neuron-builder' ) . '</p>
					<a href="' . esc_url( 'admin.php?page=' . envato_market()->get_slug() . '&tab=plugins' ) . '">' . __( 'Return to Plugin Installer', 'neuron-builder' ) . '</a>
				</div>';
				wp_die( $msg );
			}

			check_admin_referer( 'install-plugin_' . $plugin );

			envato_market()->items()->set_plugins( true );
			$install = envato_market()->items()->plugins( 'install' );
			$api     = new stdClass();

			foreach ( $install as $value ) {
				if ( absint( $value['id'] ) === absint( $plugin ) ) {
					$api->name    = $value['name'];
					$api->version = $value['version'];
				}
			}

			$array_api = (array) $api;

			if ( empty( $array_api ) ) {
				$msg = '
				<div class="wrap">
					<h1>' . __( 'Installing Plugin...', 'neuron-builder' ) . '</h1>
					<p>' . __( 'An error occurred, please check that the item ID is correct.', 'neuron-builder' ) . '</p>
					<a href="' . esc_url( 'admin.php?page=' . envato_market()->get_slug() . '&tab=plugins' ) . '">' . __( 'Return to Plugin Installer', 'neuron-builder' ) . '</a>
				</div>';
				wp_die( $msg );
			}

			$title              = sprintf( __( 'Installing Plugin: %s', 'neuron-builder' ), esc_html( $api->name . ' ' . $api->version ) );
			$nonce              = 'install-plugin_' . $plugin;
			$url                = 'admin.php?page=' . envato_market()->get_slug() . '&action=install-plugin&plugin=' . urlencode( $plugin );
			$type               = 'web'; // Install plugin type, From Web or an Upload.
			$api->download_link = envato_market()->api()->download( $plugin, $this->set_bearer_args( $plugin ) );

			// Must have the upgrader & skin.
			require envato_market()->get_plugin_path() . '/inc/admin/class-envato-market-theme-upgrader.php';
			require envato_market()->get_plugin_path() . '/inc/admin/class-envato-market-theme-installer-skin.php';

			$upgrader = new Envato_Market_Plugin_Upgrader( new Envato_Market_Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
			$upgrader->install( $api->download_link );
		}

		/**
		 * Install theme.
		 *
		 * @param string $theme The theme item ID.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function install_theme( $theme ) {
			if ( ! current_user_can( 'install_themes' ) ) {
				$msg = '
				<div class="wrap">
					<h1>' . __( 'Installing Theme...', 'neuron-builder' ) . '</h1>
					<p>' . __( 'You do not have sufficient permissions to install themes on this site.', 'neuron-builder' ) . '</p>
					<a href="' . esc_url( 'admin.php?page=' . envato_market()->get_slug() . '&tab=themes' ) . '">' . __( 'Return to Theme Installer', 'neuron-builder' ) . '</a>
				</div>';
				wp_die( $msg );
			}

			check_admin_referer( 'install-theme_' . $theme );

			envato_market()->items()->set_themes( true );
			$install = envato_market()->items()->themes( 'install' );
			$api     = new stdClass();

			foreach ( $install as $value ) {
				if ( absint( $value['id'] ) === absint( $theme ) ) {
					$api->name    = $value['name'];
					$api->version = $value['version'];
				}
			}

			$array_api = (array) $api;

			if ( empty( $array_api ) ) {
				$msg = '
				<div class="wrap">
					<h1>' . __( 'Installing Theme...', 'neuron-builder' ) . '</h1>
					<p>' . __( 'An error occurred, please check that the item ID is correct.', 'neuron-builder' ) . '</p>
					<a href="' . esc_url( 'admin.php?page=' . envato_market()->get_slug() . '&tab=themes' ) . '">' . __( 'Return to Plugin Installer', 'neuron-builder' ) . '</a>
				</div>';
				wp_die( $msg );
			}

			wp_enqueue_script( 'customize-loader' );

			$title              = sprintf( __( 'Installing Theme: %s', 'neuron-builder' ), esc_html( $api->name . ' ' . $api->version ) );
			$nonce              = 'install-theme_' . $theme;
			$url                = 'admin.php?page=' . envato_market()->get_slug() . '&action=install-theme&theme=' . urlencode( $theme );
			$type               = 'web'; // Install theme type, From Web or an Upload.
			$api->download_link = envato_market()->api()->download( $theme, $this->set_bearer_args( $theme ) );

			// Must have the upgrader & skin.
			require_once envato_market()->get_plugin_path() . '/inc/admin/class-envato-market-theme-upgrader.php';
			require_once envato_market()->get_plugin_path() . '/inc/admin/class-envato-market-theme-installer-skin.php';

			$upgrader = new Envato_Market_Theme_Upgrader( new Envato_Market_Theme_Installer_Skin( compact( 'title', 'url', 'nonce', 'api' ) ) );
			$upgrader->install( $api->download_link );
		}

		/**
		 * AJAX handler for adding items that use a non global token.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function ajax_add_item() {
			if ( ! check_ajax_referer( self::AJAX_ACTION, 'nonce', false ) ) {
				status_header( 400 );
				wp_send_json_error( 'bad_nonce' );
			} elseif ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
				status_header( 405 );
				wp_send_json_error( 'bad_method' );
			} elseif ( empty( $_POST['token'] ) ) {
				wp_send_json_error( array( 'message' => __( 'The Token is missing.', 'neuron-builder' ) ) );
			} elseif ( empty( $_POST['id'] ) ) {
				wp_send_json_error( array( 'message' => __( 'The Item ID is missing.', 'neuron-builder' ) ) );
			}

			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $_POST['token'],
				),
			);

			$request = envato_market()->api()->item( $_POST['id'], $args );
			if ( false === $request ) {
				wp_send_json_error( array( 'message' => __( 'The Token or Item ID is incorrect.', 'neuron-builder' ) ) );
			}

			if ( false === envato_market()->api()->download( $_POST['id'], $args ) ) {
				wp_send_json_error( array( 'message' => __( 'The item cannot be downloaded.', 'neuron-builder' ) ) );
			}

			if ( isset( $request['number_of_sales'] ) ) {
				$type = 'plugin';
			} else {
				$type = 'theme';
			}

			if ( isset( $type ) ) {
				$response = array(
					'name'       => $request['name'],
					'token'      => $_POST['token'],
					'id'         => $_POST['id'],
					'type'       => $type,
					'authorized' => 'success',
				);

				$options = get_option( envato_market()->get_option_name(), array() );

				if ( ! empty( $options['items'] ) ) {
					$options['items'] = array_values( $options['items'] );
					$key              = count( $options['items'] );
				} else {
					$options['items'] = array();
					$key              = 0;
				}

				$options['items'][] = $response;

				envato_market()->set_options( $options );

				// Rebuild the theme cache.
				if ( 'theme' === $type ) {
					envato_market()->items()->set_themes( true, false );

					$install_link = add_query_arg(
						array(
							'page'   => envato_market()->get_slug(),
							'action' => 'install-theme',
							'id'     => $_POST['id'],
						),
						self_admin_url( 'admin.php' )
					);

					$request['install'] = wp_nonce_url( $install_link, 'install-theme_' . $_POST['id'] );
				}

				// Rebuild the plugin cache.
				if ( 'plugin' === $type ) {
					envato_market()->items()->set_plugins( true, false );

					$install_link = add_query_arg(
						array(
							'page'   => envato_market()->get_slug(),
							'action' => 'install-plugin',
							'id'     => $_POST['id'],
						),
						self_admin_url( 'admin.php' )
					);

					$request['install'] = wp_nonce_url( $install_link, 'install-plugin_' . $_POST['id'] );
				}

				$response['key']  = $key;
				$response['item'] = $request;
				wp_send_json_success( $response );
			}

			wp_send_json_error( array( 'message' => __( 'An unknown error occurred.', 'neuron-builder' ) ) );
		}

		/**
		 * AJAX handler for removing items that use a non global token.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function ajax_remove_item() {
			if ( ! check_ajax_referer( self::AJAX_ACTION, 'nonce', false ) ) {
				status_header( 400 );
				wp_send_json_error( 'bad_nonce' );
			} elseif ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
				status_header( 405 );
				wp_send_json_error( 'bad_method' );
			} elseif ( empty( $_POST['id'] ) ) {
				wp_send_json_error( array( 'message' => __( 'The Item ID is missing.', 'neuron-builder' ) ) );
			}

			$options = get_option( envato_market()->get_option_name(), array() );
			$type    = '';

			foreach ( $options['items'] as $key => $item ) {
				if ( $item['id'] === $_POST['id'] ) {
					$type = $item['type'];
					unset( $options['items'][ $key ] );
					break;
				}
			}
			$options['items'] = array_values( $options['items'] );

			envato_market()->set_options( $options );

			// Rebuild the theme cache.
			if ( 'theme' === $type ) {
				envato_market()->items()->set_themes( true, false );
			}

			// Rebuild the plugin cache.
			if ( 'plugin' === $type ) {
				envato_market()->items()->set_plugins( true, false );
			}

			wp_send_json_success();
		}

		/**
		 * Admin page callback.
		 *
		 * @since 1.0.0
		 */
		public function render_admin_callback() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/callback/admin.php' );
		}

		/**
		 * OAuth section callback.
		 *
		 * @since 1.0.0
		 */
		public function render_oauth_section_callback() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/callback/section/oauth.php' );
		}

		/**
		 * Token setting callback.
		 *
		 * @since 1.0.0
		 */
		public function render_token_setting_callback() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/callback/setting/token.php' );
		}

		/**
		 * Items setting callback.
		 *
		 * @since 1.0.0
		 */
		public function render_items_setting_callback() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/callback/setting/items.php' );
		}

		/**
		 * Tabs
		 *
		 * @since 1.0.0
		 */
		public function render_tabs_partial() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/partials/tabs.php' );
		}

		/**
		 * Settings panel
		 *
		 * @since 1.0.0
		 */
		public function render_settings_panel_partial() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/partials/settings.php' );
		}

		/**
		 * Settings Theme panel
		 *
		 * @since 1.0.0
		 */
		public function render_settings_theme_panel_partial() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/partials/settings-theme.php' );
		}

		/**
		 * Success notice.
		 *
		 * @since 1.0.0
		 */
		public function render_success_notice() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/success.php' );
		}

		/**
		 * Success no-items notice.
		 *
		 * @since 1.0.0
		 */
		public function render_success_no_items_notice() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/success-no-items.php' );
		}

		/**
		 * Success single-use notice.
		 *
		 * @since 1.0.0
		 */
		public function render_success_single_use_notice() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/success-single-use.php' );
		}

		/**
		 * Error details.
		 *
		 * @since 2.0.2
		 */
		public function render_additional_error_details() {
			$error_details = get_site_transient( envato_market()->get_option_name() . '_error_information' );
			if ( $error_details && ! empty( $error_details['title'] ) ) {
				extract( $error_details );
				require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error-details.php' );
			}
		}

		/**
		 * Error notice.
		 *
		 * @since 1.0.0
		 */
		public function render_error_notice() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error.php' );
			$this->render_additional_error_details();
		}

		/**
		 * Permission error notice.
		 *
		 * @since 2.0.1
		 */
		public function render_error_permissions() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error-permissions.php' );
			$this->render_additional_error_details();
		}

		/**
		 * Error single-use notice.
		 *
		 * @since 1.0.0
		 */
		public function render_error_single_use_notice() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error-single-use.php' );
			$this->render_additional_error_details();
		}

		/**
		 * Error missing zip.
		 *
		 * @since 2.0.1
		 */
		public function render_error_missing_zip() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error-missing-zip.php' );
			$this->render_additional_error_details();
		}

		/**
		 * Error http
		 *
		 * @since 2.0.1
		 */
		public function render_error_http() {
			require( envato_market()->get_plugin_path() . 'inc/admin/view/notice/error-http.php' );
			$this->render_additional_error_details();
		}

		/**
		 * Use the Settings API when in network mode.
		 *
		 * This allows us to make use of the same WordPress Settings API when displaying the menu item in network mode.
		 *
		 * @since 2.0.0
		 */
		public function save_network_settings() {
			check_admin_referer( envato_market()->get_slug() . '-options' );

			global $new_whitelist_options;
			$options = $new_whitelist_options[ envato_market()->get_slug() ];

			foreach ( $options as $option ) {
				if ( isset( $_POST[ $option ] ) ) {
					update_site_option( $option, $_POST[ $option ] );
				} else {
					delete_site_option( $option );
				}
			}
			wp_redirect( envato_market()->get_page_url() );
			exit;
		}

		/**
		 * Store additional error information in transient so users can self debug.
		 *
		 * @since 2.0.2
		 */
		public function store_additional_error_debug_information( $title, $message = '', $data = [] ) {
			set_site_transient(
				envato_market()->get_option_name() . '_error_information',
				[
					'title'   => $title,
					'message' => $message,
					'data'    => $data,
				],
				120
			);
		}
	}

endif;
