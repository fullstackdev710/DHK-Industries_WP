<?php
/**
 * Admin
 * 
 * @since 1.0.0
 */

namespace Neuron\Core\Admin;

use Elementor\Core\Base\App;
use Elementor\Rollback;
use Elementor\Settings;
use Elementor\Tools;
use Elementor\Utils as ElementorUtils;
use Elementor\User as ElementorUser;

use Neuron\Core\Utils;
use Neuron\Modules\TemplateLibrary\Source_Custom as Api;

use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Admin extends App {

	public function get_name() {
		return 'neuron-admin';
	}
	

	public function enqueue_styles() {
		// $suffix = ElementorUtils::is_script_debug() ? '' : '.min'; @TODO

		wp_register_style(
			'neuron-admin',
			NEURON_ASSETS_URL . 'styles/admin.css',
			[],
			NEURON_BUILDER_VERSION
		);

		wp_enqueue_style( 'neuron-admin' );
	}

	public function enqueue_scripts() {
		// $suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'neuron-admin',
			NEURON_ASSETS_URL . 'js/admin.js',
			[
				'elementor-common',
			],
			NEURON_BUILDER_VERSION,
			true
		);

		$locale_settings = [
			'frontpage_edit_url' => Utils::edit_front_page_url()
		];

		/**
		 * Localize admin settings.
		 *
		 * Filters the admin localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'neuron/admin/localize_settings', $locale_settings );

		ElementorUtils::print_js_config(
			'neuron-admin',
			'NeuronConfig',
			$locale_settings
		);
	}

	function neuron_hero_image_add_metabox () {
		$supported_fields = apply_filters( 'neuron/admin/image/hero-image', [ 'post', 'portfolio', 'product' ] );
		
		add_meta_box( 'neuron_hero_image_box', __( 'Hero Image', 'neuron-builder' ), [ $this, 'neuron_hero_metabox' ], $supported_fields, 'side', 'low' );
	}

	function neuron_hero_metabox ( $post ) {
		global $content_width, $_wp_additional_image_sizes;

		$image_id = get_post_meta( $post->ID, '_neuron_hero_image_id', true );

		$old_content_width = $content_width;
		$content_width = 254;

		if ( $image_id && get_post( $image_id ) ) {

			if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
				$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
			} else {
				$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
			}

			if ( ! empty( $thumbnail_html ) ) {
				$content = $thumbnail_html;
				$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_neuron_hero_image_button" >' . esc_html__( 'Remove hero image', 'neuron-builder' ) . '</a></p>';
				$content .= '<input type="hidden" id="upload_neuron_hero_image" name="_neuron_hero_image" value="' . esc_attr( $image_id ) . '" />';
			}

			$content_width = $old_content_width;
		} else {
			$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
			$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set Hero image', 'neuron-builder' ) . '" href="javascript:;" id="upload_neuron_hero_image_button" id="set-hero-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'neuron-builder' ) . '" data-uploader_button_text="' . esc_attr__( 'Set Hero image', 'neuron-builder' ) . '">' . esc_html__( 'Set Hero image', 'neuron-builder' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_neuron_hero_image" name="_neuron_hero_image" value="" />';
		}

		echo $content;
	}

	function neuron_hero_image_save ( $post_id ) {
		if ( isset( $_POST['_neuron_hero_image'] ) ) {
			$image_id = (int) $_POST['_neuron_hero_image'];
			update_post_meta( $post_id, '_neuron_hero_image_id', $image_id );
		}
	}


	public function register_neuron_menu_pages() {
		add_menu_page(
			__( 'Neuron', 'neuron-builder' ),
			__( 'Neuron', 'neuron-builder' ),
			'manage_options',
			'neuron',
			[ $this, 'neuron_getting_started_page' ],
			NEURON_BUILDER_URL . 'admin/assets/logo-menu.svg' ,
			2
		);
	}

	public function register_neuron_menu_subpages() {
		add_submenu_page( 'neuron', 'Settings', 'Settings', 'administrator', 'settings', [ $this, 'settings_page' ], 10 );
		add_submenu_page( 'neuron', 'System Info', 'System Info', 'administrator', 'system-info', [ $this, 'system_info_page' ], 11 );
		add_submenu_page( 'neuron', 'Get Help', 'Get Help', 'administrator', 'get-help', [ $this, 'get_help_page' ], 12 );
	}

	public function register_neuron_integrations() {

		add_settings_section(
			'neuron_integrations_recaptcha_section',
			'reCAPTCHA',
			[ $this, 'neuron_recaptcha_description' ],
			'neuron_integrations_recaptcha_section'
		);

		add_settings_field(
			'neuron_recaptcha_site_key',
			null,
			[ $this, 'integrations_recaptcha_site_key_markup' ],
			'neuron_integrations_recaptcha_section',
			'neuron_integrations_recaptcha_section'
		);

		add_settings_field(
			'neuron_recaptcha_secret_key',
			null,
			[ $this, 'integrations_recaptcha_secret_key_markup' ],
			'neuron_integrations_recaptcha_section',
			'neuron_integrations_recaptcha_section'
		);

		add_settings_section(
			'neuron_integrations_recaptcha_v3_section',
			'reCAPTCHA v3',
			[ $this, 'neuron_recaptcha_v3_description' ],
			'neuron_integrations_recaptcha_v3_section'
		);

		add_settings_field(
			'neuron_recaptcha_v3_site_key',
			null,
			[ $this, 'integrations_recaptcha_v3_site_key_markup' ],
			'neuron_integrations_recaptcha_v3_section',
			'neuron_integrations_recaptcha_v3_section'
		);

		add_settings_field(
			'neuron_recaptcha_v3_secret_key',
			null,
			[ $this, 'integrations_recaptcha_v3_secret_key_markup' ],
			'neuron_integrations_recaptcha_v3_section',
			'neuron_integrations_recaptcha_v3_section'
		);

		register_setting( 'neuron-integrations', 'neuron_recaptcha_site_key' );
		register_setting( 'neuron-integrations', 'neuron_recaptcha_secret_key' );
		register_setting( 'neuron-integrations', 'neuron_recaptcha_v3_site_key' );
		register_setting( 'neuron-integrations', 'neuron_recaptcha_v3_secret_key' );
	}

	public function neuron_recaptcha_description() {
		?>
			<div class="neuron-admin__card--description"><a href="https://www.google.com/recaptcha/intro/v3.html" target="_BLANK">reCAPTCHA</a> is a free service that protects your website from spam and abuse.</div>
		<?php
	}

	public function neuron_recaptcha_v3_description() {
		?>
			<div class="neuron-admin__card--description"><a href="https://www.google.com/recaptcha/intro/v3.html" target="_BLANK">reCAPTCHA V3</a> is a free service that protects your website from spam and abuse.</div>
		<?php
	}

	public function integrations_recaptcha_site_key_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Site Key', 'neuron-builder' ) ?>" type="text" id="neuron_recaptcha_site_key" name="neuron_recaptcha_site_key" value="<?php echo get_option( 'neuron_recaptcha_site_key' ); ?>">
		<?php
	}

	public function integrations_recaptcha_secret_key_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Secret Key', 'neuron-builder' ) ?>" type="text" id="neuron_recaptcha_secret_key" name="neuron_recaptcha_secret_key" value="<?php echo get_option( 'neuron_recaptcha_secret_key' ); ?>">
		<?php
	}

	public function integrations_recaptcha_v3_site_key_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Site Key', 'neuron-builder' ) ?>" type="text" id="neuron_recaptcha_v3_site_key" name="neuron_recaptcha_v3_site_key" value="<?php echo get_option( 'neuron_recaptcha_v3_site_key' ); ?>">
		<?php
	}

	public function integrations_recaptcha_v3_secret_key_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Secret Key', 'neuron-builder' ) ?>" type="text" id="neuron_recaptcha_v3_secret_key" name="neuron_recaptcha_v3_secret_key" value="<?php echo get_option( 'neuron_recaptcha_v3_secret_key' ); ?>">
		<?php
	}

	public function register_neuron_google_map() {

		add_settings_section(
			'neuron_google_map_section',
			'Google Maps',
			[ $this, 'neuron_google_map_description' ],
			'neuron_google_map_section'
		);

		add_settings_field(
			'neuron_google_map_api_key',
			null,
			[ $this, 'integrations_google_map_site_key_markup' ],
			'neuron_google_map_section',
			'neuron_google_map_section'
		);

		register_setting( 'neuron-integrations', 'neuron_google_map_api_key' );
	}

	public function neuron_google_map_description() {
		?>
			<div class="neuron-admin__card--description">To use the Maps JavaScript API you must have an <a target="_BLANK" href="https://developers.google.com/maps/documentation/javascript/get-api-key">API key</a>. The API key is a unique identifier that is used to authenticate requests associated with your project for usage and billing purposes.</div>
		<?php
	}

	public function integrations_google_map_site_key_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter API Key', 'neuron-builder' ) ?>" type="text" id="neuron_google_map_api_key" name="neuron_google_map_api_key" value="<?php echo get_option( 'neuron_google_map_api_key' ); ?>">
		<?php
	}

	public function register_neuron_instagram() {

		add_settings_section(
			'neuron_instagram_section',
			'Instagram',
			[ $this, 'neuron_instagram_description' ],
			'neuron_instagram_section'
		);

		add_settings_field(
			'neuron_instagram_access_token',
			null,
			[ $this, 'integrations_instagram_access_markup' ],
			'neuron_instagram_section',
			'neuron_instagram_section'
		);

		register_setting( 'neuron-integrations', 'neuron_instagram_access_token' );
	}

	public function neuron_instagram_description() {
		?>
			<div class="neuron-admin__card--description"><a href="https://neuronthemes.ticksy.com/article/15734/">Access Tokens</a> are used in token-based authentication to allow an application to access an API.</div>
		<?php
	}

	public function integrations_instagram_access_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Access Token', 'neuron-builder' ) ?>" type="text" id="neuron_instagram_access_token" name="neuron_instagram_access_token" value="<?php echo get_option( 'neuron_instagram_access_token' ); ?>">
		<?php
	}

	public function register_neuron_mailchimp() {

		add_settings_section(
			'neuron_mailchimp_section',
			'Mailchimp',
			[ $this, 'integrations_mailchimp_description' ],
			'neuron_mailchimp_section'
		);

		add_settings_field(
			'neuron_mailchimp_api_key',
			null,
			[ $this, 'integrations_mailchimp_markup' ],
			'neuron_mailchimp_section',
			'neuron_mailchimp_section'
		);

		register_setting( 'neuron-integrations', 'neuron_mailchimp_api_key' );
	}

	public function integrations_mailchimp_description() {
		?>
			<div class="neuron-admin__card--description">Integrate your <a href="https://mailchimp.com/help/about-api-keys/">mailchimp</a> account to fetch the groups and other stuff.</div>
		<?php
	}

	public function integrations_mailchimp_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter API Key', 'neuron-builder' ) ?>" type="text" id="neuron_mailchimp_api_key" name="neuron_mailchimp_api_key" value="<?php echo get_option( 'neuron_mailchimp_api_key' ); ?>">
		<?php
	}

	public function register_neuron_typekit() {

		add_settings_section(
			'neuron_typekit_section',
			'Adobe Fonts (TypeKit)',
			[ $this, 'integrations_typekit_description' ],
			'neuron_typekit_section'
		);

		add_settings_field(
			'neuron_typekit_api_key',
			null,
			[ $this, 'integrations_typekit_markup' ],
			'neuron_typekit_section',
			'neuron_typekit_section'
		);

		register_setting( 'neuron-integrations', 'neuron_typekit_api_key' );
	}

	public function integrations_typekit_description() {
		?>
			<div class="neuron-admin__card--description">Enter Your <a href="https://fonts.adobe.com/typekit" target="_blank">TypeKit Project ID</a></div>
		<?php
	}

	public function integrations_typekit_markup() {
		?>
		<input placeholder="<?php echo esc_attr__( 'Enter Project ID', 'neuron-builder' ) ?>" type="text" id="neuron_typekit_api_key" name="neuron_typekit_api_key" value="<?php echo get_option( 'neuron_typekit_api_key' ); ?>">
		<?php
	}

	public function neuron_admin_header( $active = 'settings', $categories = false ) {
		include 'parts/header.php';
	}

	public function settings_page() {
		include 'parts/settings.php';
	}

	public function demo_importer_page( $categories ) {
		$this->neuron_admin_header( 'demo-importer', $categories );
	}

	public function license_page() {
		$this->neuron_admin_header( 'license' );
	}

	public function custom_fonts_page() {
		$this->neuron_admin_header( 'custom-fonts' );
	}

	public function system_info_page() {
		include 'parts/system-info.php';
	}

	public function neuron_getting_started_page() {
		include 'parts/getting-started.php';
	}

	public function get_help_page() {
		?>
			<script type="text/javascript">
				window.open('https://neuronthemes.com/support','_blank');

				window.location = "<?php echo esc_url( admin_url( 'admin.php' ) . '?page=neuron' ) ?>";
			</script>
		<?php
	}

	public function remove_elementor_pro_menu() {
		remove_action( 'admin_menu', [ Plugin::elementor()->settings, 'register_pro_menu' ], Settings::MENU_PRIORITY_GO_PRO );
	}

	public function remove_elementor_knowledge_menu() {
		remove_action( 'admin_menu', [ Plugin::elementor()->settings, 'register_knowledge_base_menu' ], 501 );
	}

	// public function remove_elementor_system_info_menu() {
	// 	remove_action( 'admin_menu', [ Plugin::elementor()->settings, 'register_knowledge_base_menu' ], 501 );
	// }

	public function plugin_action_links( $links ) {
		unset( $links['go_pro'] );

		return $links;
	}

	private function dashboard_footer_actions() {
		$actions = [
			'blog' => [
				'title' => __( 'Blog', 'neuron-builder' ),
				'link' => 'https://neuronthemes.com/go/dashboard-feed-blog/',
			],
			'help' => [
				'title' => __( 'Help', 'neuron-builder' ),
				'link' => 'https://neuronthemes.com/go/dashboard-feed-help/',
			],
		];

		return $actions;
	}


	/**
	 * Neuron Dashboard Widget.
	 *
	 * Fired by `wp_add_dashboard_widget` function.
	 *
	 * @since 1.0.0
	 */
	public function neuron_dashboard_overview_widget() {
		$feed = Api::get_feed_data();

		// $recently_edited_query_args = [
		// 	'post_type' => 'any',
		// 	'post_status' => [ 'publish', 'draft' ],
		// 	'posts_per_page' => '3',
		// 	'meta_key' => '_elementor_edit_mode',
		// 	'meta_value' => 'builder',
		// 	'orderby' => 'modified',
		// ];

		// $recently_edited_query = new \WP_Query( $recently_edited_query_args );

		if ( ElementorUser::is_current_user_can_edit_post_type( 'page' ) ) {
			$create_new_label = __( 'Create New Page', 'neuron-builder' );
			$create_new_post_type = 'page';
		} elseif ( ElementorUser::is_current_user_can_edit_post_type( 'post' ) ) {
			$create_new_label = __( 'Create New Post', 'neuron-builder' );
			$create_new_post_type = 'post';
		}

		?>
		<div class="neuron-dashboard">
			<div class="neuron-dashboard__header">
				<div class="neuron-dashboard__logo">
					<a href="<?php echo esc_url( admin_url( 'admin.php' ) . '?page=neuron' ) ?>">
						<img src="<?php echo NEURON_BUILDER_URL . 'admin/assets/logo.svg' ?>" alt="<?php echo esc_attr( 'Neuron Logo' ) ?>">
					</a>
				</div>
				<div class="neuron-dashboard__version">
					<?php echo esc_html__( 'Neuron Builder', 'neuron-builder' ); ?> v<?php echo NEURON_BUILDER_VERSION; ?>
				</div>
				<?php if ( ! empty( $create_new_post_type ) ) : ?>
					<div class="neuron-dashboard__create">
						<a href="<?php echo esc_url( \Elementor\Plugin::$instance->documents->get_create_new_post_url( $create_new_post_type ) ); ?>" class="button"><span aria-hidden="true" class="dashicons dashicons-plus"></span> <?php echo esc_html( $create_new_label ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $feed ) ) : ?>
				<div class="neuron-dashboard__feed">
					<h3 class="neuron-dashboard__heading"><?php echo __( 'News & Updates', 'neuron-builder' ); ?></h3>
					<ul class="neuron-dashboard__posts">
						<?php foreach ( $feed as $feed_post ) : ?>
							<li class="neuron-dashboard__post">
								<a href="<?php echo esc_url( $feed_post['url'] ); ?>" class="neuron-dashboard__link" target="_blank">
									<?php echo esc_html( $feed_post['title'] ); ?>
								</a>
								<p class="neuron-dashboard__description"><?php echo esc_html( $feed_post['excerpt'] ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			
			<div class="neuron-dashboard__footer">
				<ul>
					<?php foreach ( $this->dashboard_footer_actions() as $action_id => $action ) : ?>
						<li class="e-overview__<?php echo esc_attr( $action_id ); ?>"><a href="<?php echo esc_attr( $action['link'] ); ?>" target="_blank"><?php echo esc_html( $action['title'] ); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Register Neuron dashboard widgets.
	 *
	 * Fired by `wp_dashboard_setup` action.
	 *
	 * @since 1.0.0
	 */
	public function register_dashboard_widgets() {
		wp_add_dashboard_widget( 'neuron-dashboard-overview', __( 'Neuron Overview', 'neuron-builder' ), [ $this, 'neuron_dashboard_overview_widget' ] );

		global $wp_meta_boxes;

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

		$neuron = ['neuron-dashboard-overview' => $dashboard['neuron-dashboard-overview'] ];

		if ( isset( $dashboard['e-dashboard-overview'] ) ) {
			unset( $dashboard['e-dashboard-overview'] );
		}

		$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $neuron, $dashboard ); 
	}

	/**
	 * Change Neuron to Getting Started
	 *
	 * @since 1.0.0
	 */
	public function admin_menu_change_name() {
		global $submenu;

		if ( isset( $submenu['neuron'] ) ) {
			$submenu['neuron'][0][0] = __( 'Getting Started', 'neuron-builder' );
		}
	}

	public function maybe_redirect_to_getting_started() {
		if ( ! get_transient( 'elementor_activation_redirect' ) ) {
			return;
		}

		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'elementor_activation_redirect' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		global $wpdb;

		$has_elementor_page = ! ! $wpdb->get_var( "SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_elementor_edit_mode' LIMIT 1;" );

		if ( $has_elementor_page ) {
			return;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=neuron' ) );

		exit;
	}

	public function admin_footer_text() {
		$name = wp_get_theme()->Name;

		$admin_footer_text = sprintf(
			__( 'Enjoying %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'neuron-builder' ),
				'<strong>' . $name . '</strong>',
				'<a href="https://themeforest.net/downloads" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
		);

		return $admin_footer_text;
	}

	/**
	 * Admin constructor.
	 */
	public function __construct() {
        // $this->add_component( 'canary-deployment', new Canary_Deployment() );
        // @TODO: fix completely the admin.php
		
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		// add_action( 'admin_menu', [ $this, 'remove_elementor_system_info_menu' ], 0 );

		add_action( 'admin_init', [ $this, 'register_neuron_integrations' ] );
		add_action( 'admin_init', [ $this, 'register_neuron_google_map' ] );
		add_action( 'admin_init', [ $this, 'register_neuron_instagram' ] );
		add_action( 'admin_init', [ $this, 'register_neuron_mailchimp' ] );
		add_action( 'admin_init', [ $this, 'register_neuron_typekit' ] );

		add_action( 'admin_menu', [ $this, 'remove_elementor_pro_menu' ], 0 );
		add_action( 'admin_menu', [ $this, 'remove_elementor_knowledge_menu' ], 0 );
		add_action( 'admin_menu', [ $this, 'register_neuron_menu_pages' ], 0 );
		add_action( 'admin_menu', [ $this, 'register_neuron_menu_subpages' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu_change_name' ], 200 );

		add_filter( 'plugin_action_links_' . ELEMENTOR_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );

		// Custom Header Insert
		add_action( 'neuron/admin/demo-importer/header', [ $this, 'demo_importer_page' ], 10, 1 );
		add_action( 'neuron/admin/license/header', [ $this, 'license_page' ] );
		add_action( 'neuron/admin/custom_fonts/header', [ $this, 'custom_fonts_page' ] );

		// Register Dashboard Widgets.
		add_action( 'wp_dashboard_setup', [ $this, 'register_dashboard_widgets' ], 20 );

		add_action( 'admin_init', [ $this, 'maybe_redirect_to_getting_started' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );

		// Custom Hero Image
		add_action( 'add_meta_boxes', [ $this, 'neuron_hero_image_add_metabox' ] );
		add_action( 'save_post', [ $this, 'neuron_hero_image_save' ], 10, 1 );
	}
}
