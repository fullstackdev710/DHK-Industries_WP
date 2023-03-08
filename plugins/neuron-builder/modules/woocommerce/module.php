<?php
namespace Neuron\Modules\Woocommerce;

use Elementor\Core\Documents_Manager;
use Elementor\Settings;
use Neuron\Base\Module_Base;
use Neuron\Modules\ThemeBuilder\Classes\Conditions_Manager;
use Neuron\Modules\Woocommerce\Conditions\Woocommerce;
use Neuron\Modules\Woocommerce\Documents\Product;
use Neuron\Modules\Woocommerce\Documents\Product_Post;
use Neuron\Modules\Woocommerce\Documents\Product_Archive;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {

	const WOOCOMMERCE_GROUP = 'woocommerce';
	const TEMPLATE_MINI_CART = 'cart/mini-cart.php';

	protected $docs_types = [];

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public static function is_product_search() {
		return is_search() && 'product' === get_query_var( 'post_type' );
	}

	public function get_name() {
		return 'woocommerce';
	}

	public function get_widgets() {
		return [
			'Archive_Products',
			'Archive_Description',
			'Products',

			'Breadcrumb',
			'Add_To_Cart',
			'Elements',
			'Categories',
			'Menu_Cart',

			'Product_Title',
			'Product_Images',
			'Product_Price',
			'Product_Add_To_Cart',
			'Product_Rating',
			'Product_Stock',
			'Product_Meta',
			'Product_Short_Description',
			'Product_Content',
			'Product_Data_Tabs',
			'Product_Additional_Information',
			'Product_Reviews',
			'Product_Related',
			'Product_Upsell',
		];
	}

	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	public function add_products_post_class_filter() {
		add_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function remove_products_post_class_filter() {
		remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function register_tags() {
		$tags = [
			'Product_Gallery',
			'Product_Image',
			'Product_Price',
			'Product_Rating',
			'Product_Sale',
			'Product_Short_Description',
			'Product_SKU',
			'Product_Stock',
			'Product_Terms',
			'Product_Title',
			'Category_Image',
		];

		$module = Plugin::elementor()->dynamic_tags;

		$module->register_group( self::WOOCOMMERCE_GROUP, [
			'title' => __( 'WooCommerce', 'neuron-builder' ),
		] );

		foreach ( $tags as $tag ) {
			$module->register_tag( 'Neuron\\Modules\\Woocommerce\\tags\\' . $tag );
		}
	}

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	public function register_conditions( $conditions_manager ) {
		$woocommerce_condition = new Woocommerce();

		$conditions_manager->get_condition( 'general' )->register_sub_condition( $woocommerce_condition );
	}

	public function register_documents( $documents_manager ) {
		$this->docs_types = [
			'product-post' => Product_Post::get_class_full_name(),
			'product' => Product::get_class_full_name(),
			'product-archive' => Product_Archive::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			$documents_manager->register_document_type( $type, $class_name );
		}
	}

	public static function render_menu_cart_toggle_button() {
		if ( null === WC()->cart ) {
			return;
		}
		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		?>
		<a href="#" class="m-neuron-menu-cart__toggle" <?php echo $counter_attr; ?>>
			<i class="eicon" aria-hidden="true"></i>
			<span class="elementor-screen-only"><?php esc_html_e( 'Cart', 'neuron-builder' ); ?></span>
		</a>
		<?php
	}

	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		?>
		<div class="m-neuron-menu-cart">
			<div class="m-neuron-menu-cart__icon">

				<?php self::render_menu_cart_toggle_button(); ?>

				<?php if ( ! $widget_cart_is_hidden ) : ?>
					<div class="m-neuron-menu-cart__sidebar m-neuron-menu-cart__sidebar--hidden">
						<div class="m-neuron-menu-cart__main">
							<div class="m-neuron-menu-cart__close-button">
								<?php echo esc_html__( 'Close', 'neuron-builder' ) ?>
							</div>
							<div class="widget_shopping_cart_content"></div>
						</div>
					</div>
					<div class="m-neuron-menu-cart__overlay"></div>
				<?php endif; ?>
			</div>
		</div> 
		<?php
	}

	public function menu_cart_fragments( $fragments ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			return $fragments;
		}

		ob_start();
		
		self::render_menu_cart_toggle_button();

		$menu_cart_toggle_button_html = ob_get_clean();

		if ( ! empty( $menu_cart_toggle_button_html ) ) {
			$fragments['body:not(.elementor-editor-active) div.elementor-element.elementor-widget.elementor-widget-neuron-woo-menu-cart .m-neuron-menu-cart__toggle'] = $menu_cart_toggle_button_html;
		}

		return $fragments;
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	public function localized_settings_frontend( $settings ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( $has_cart ) {
			$settings['menu_cart'] = [
				'cart_page_url' => wc_get_cart_url(),
				'checkout_page_url' => wc_get_checkout_url(),
			];
		}
		return $settings;
	}

	public function theme_template_include( $need_override_location, $location ) {
		if ( is_product() && 'single' === $location ) {
			$need_override_location = true;
		}

		return $need_override_location;
	}

	public function woocommerce_locate_template( $template, $template_name, $template_path ) {

		if ( self::TEMPLATE_MINI_CART !== $template_name ) {
			return $template;
		}
		
		$plugin_path = plugin_dir_path( __DIR__ ) . 'woocommerce/wc-templates/';

		if ( file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		return $template;
	}

	public function woocommerce_wordpress_widget_css_class( $default_widget_args, $widget ) {
		$widget_instance = $widget->get_widget_instance();

		if ( ! empty( $widget_instance->widget_cssclass ) ) {
			$default_widget_args['before_widget'] .= '<div class="' . $widget_instance->widget_cssclass . '">';
			$default_widget_args['after_widget'] .= '</div>';
		}

		return $default_widget_args;
	}

	public function remove_item_from_cart() {
		$cart = WC()->instance()->cart;
		$id = $_POST['product_id'];

		$cart_id = $cart->generate_cart_id( $id );
		$cart_item_id = $cart->find_product_in_cart( $cart_id );

		if ($cart_item_id) {
			$cart->set_quantity( $cart_item_id,  0);
			return true;
		} 

		return false;
	}

	public function overide_search_form( $form ) {
		$form = '<form role="search" class="m-neuron-search-form" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search...', 'neuron-builder' ) . '" />
				<label class="m-neuron-search-form__icon">
					<input type="submit" />
					<input type="hidden" name="post_type" value="product" />
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					</span>
				</label>
			</form>';
			
		return $form;
	}

	public function product_ajax_add_to_cart() {  

		$product_id = apply_filters( 'ql_woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );

		$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

		$variation_id = absint( $_POST['variation_id'] );

		$passed_validation = apply_filters( 'ql_woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		$product_status = get_post_status( $product_id ); 

    	if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) { 

        	do_action( 'ql_woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'ql_woocommerce_cart_redirect_after_add' ) ) { 
				wc_add_to_cart_message( array( $product_id => $quantity ), true ); 
			} 

			\WC_AJAX :: get_refreshed_fragments(); 

		} else { 
			$data = array( 
				'error' => true,
				'product_url' => apply_filters( 'ql_woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ) 
			);
				
			echo wp_send_json( $data );
		}

		wp_die();
	}

	/**
	 * Quick View Add to Cart
	 * 
	 * @since 1.0.1
	 */
	protected function quick_view_add_to_cart( $product )  {
		if ( ! $product->is_purchasable() ) {
			return;
		}

		if ( $product->get_type() == 'simple' ) {
			echo wc_get_stock_html( $product ); // WPCS: XSS ok.

			if ( $product->is_in_stock() ) : ?>

				<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

				<form class="cart elementor-product-simple" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
					<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

					<?php
					do_action( 'woocommerce_before_add_to_cart_quantity' );

					woocommerce_quantity_input(
						array(
							'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
							'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
							'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
						)
					);

					do_action( 'woocommerce_after_add_to_cart_quantity' );
					?>

					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt ajax_add_to_cart product_type_simple"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

					<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
				</form>

				<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

			<?php endif; 
		} else if ( $product->get_type() == 'external' ) {
			do_action( 'woocommerce_before_add_to_cart_form' ); ?>

			<form class="cart" action="<?php echo esc_url( $product_url ); ?>" method="get">
				<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

				<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $button_text ); ?></button>

				<?php wc_query_string_form_fields( $product_url ); ?>

				<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
			</form>

			<?php do_action( 'woocommerce_after_add_to_cart_form' ); 
		} else if ( $product->get_type() == 'variable' ) {
			$attribute_keys  = array_keys( $attributes );
			$variations_json = wp_json_encode( $available_variations );
			$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

			do_action( 'woocommerce_before_add_to_cart_form' ); ?>

			<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
				<?php do_action( 'woocommerce_before_variations_form' ); ?>

				<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
					<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
				<?php else : ?>
					<table class="variations" cellspacing="0">
						<tbody>
							<?php foreach ( $attributes as $attribute_name => $options ) : ?>
								<tr>
									<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
									<td class="value">
										<?php
											wc_dropdown_variation_attribute_options(
												array(
													'options'   => $options,
													'attribute' => $attribute_name,
													'product'   => $product,
												)
											);
											echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<div class="single_variation_wrap">
						<?php
							do_action( 'woocommerce_before_single_variation' );

							do_action( 'woocommerce_single_variation' );

							do_action( 'woocommerce_after_single_variation' );
						?>
					</div>
				<?php endif; ?>

				<?php do_action( 'woocommerce_after_variations_form' ); ?>
			</form>

			<?php
			do_action( 'woocommerce_after_add_to_cart_form' );
		}
		
	}

	/**
	 * Product Quick View
	 * 
	 * @since 1.0.1
	 */
	public function product_quick_view() {
		$product_id = $_POST['product_id'];
		
		if ( isset( $product_id ) && ! empty( $product_id ) ) {
			$product = wc_get_product( $product_id );
			?>
				<div class="m-neuron__quick-view--product">
					<div class="m-neuron__quick-view--product-thumbnail">
						<?php 
							if ( ! empty ( $product->get_gallery_image_ids() ) ) {

								echo '<div class="neuron-swiper">';
									echo '<div class="neuron-slides-wrapper neuron-main-swiper swiper-container">';

										echo '<div class="swiper-wrapper swiper-wrapper">';

											echo '<div class="m-neuron__quick-view--slide swiper-slide">' . wp_get_attachment_image( $product->get_image_id(), 'full' ) . '</div>';

											foreach ( $product->get_gallery_image_ids() as $product_id => $product_image ) {
												echo '<div class="m-neuron__quick-view--slide swiper-slide">' . wp_get_attachment_image( $product_image, 'full' ) . '</div>';
											}

										echo '</div>';

									echo '</div>';

									echo '<div class="swiper-pagination swiper-pagination--quick-view neuron-swiper-dots"></div>';

								echo '</div>';

							} else {
								echo wp_get_attachment_image( $product->get_image_id(), 'full' );
							}
						?>
					</div>
					<div class="m-neuron__quick-view--product-summary">
						<div class="m-neuron__quick-view--product-title"><h2><?php echo esc_attr( $product->get_name() ) ?></h2></div>
						<div class="m-neuron__quick-view--product-price"><h4><?php echo $product->get_price_html() ?></h4></div>
						<div class="m-neuron__quick-view--product-description"><?php echo esc_attr( $product->get_short_description() ) ?></div>
						<div class="m-neuron__quick-view--product-add-to-cart">
							<?php $this->quick_view_add_to_cart( $product ) ?>
						</div>
						<div class="m-neuron__quick-view--product-meta">
							<?php if ( ! empty( $product->get_categories() ) ) : ?>
								<h6><strong><?php echo esc_html__( 'Categories', 'neuron-builder' ) ?></strong>: <?php echo $product->get_categories() ?></h6>
							<?php endif; ?>
							<?php if ( ! empty( wc_get_product_tag_list( $product->get_id() ) ) ) : ?>
								<h6><strong><?php echo esc_html__( 'Tags', 'neuron-builder' ) ?></strong>: <?php echo wc_get_product_tag_list( $product->get_id() ) ?></h6>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php
		}

		wp_die();
	}

	public function __construct() {
		parent::__construct();

		add_filter( 'get_product_search_form', [ $this, 'overide_search_form' ], 10, 1 );

		add_action( 'wp_ajax_remove_item_from_cart', [ $this, 'remove_item_from_cart'] );
		add_action( 'wp_ajax_nopriv_remove_item_from_cart', [ $this, 'remove_item_from_cart'] );

		add_action('wp_ajax_neuron_woocommerce_ajax_add_to_cart', [ $this, 'product_ajax_add_to_cart' ] ); 
		add_action('wp_ajax_nopriv_neuron_woocommerce_ajax_add_to_cart', [ $this, 'product_ajax_add_to_cart' ] );    

		add_action('wp_ajax_neuron_woocommerce_quick_view', [ $this, 'product_quick_view' ] ); 
		add_action('wp_ajax_nopriv_neuron_woocommerce_quick_view', [ $this, 'product_quick_view' ] );    

		if ( class_exists( 'ElementorPro\Plugin' ) ) {
			return;
		}

		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );
		add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_tags' ] );
		add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
		add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );

		add_filter( 'neuron/theme/need_override_location', [ $this, 'theme_template_include' ], 10, 2 );

		add_filter( 'neuron/frontend/localize_settings', [ $this, 'localized_settings_frontend' ] );

		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
			add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		}

		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'menu_cart_fragments' ] );
		add_filter( 'woocommerce_locate_template', [ $this, 'woocommerce_locate_template' ], 10, 3 );

		add_filter( 'elementor/widgets/wordpress/widget_args', [ $this, 'woocommerce_wordpress_widget_css_class' ], 10, 2 );
	}
}
