<?php
/**
 * Product Upsell
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

use Neuron\Core\Helpers\Products_Related as Products_Related_Helper;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_Upsell extends Base_Widget {

    protected $query_args;

	public function get_name() {
		return 'neuron-woo-product-upsell';
	}

	public function get_title() {
		return __( 'Product Upsell', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-upsell neuron-badge';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'upsell', 'product' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'neuron-packery', 'neuron-object-fit' ];
	}

	protected function register_controls() {
		new Products_Related_Helper( $this );
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			];
		}
		return [];
	}

	/**
	 * Product Thumbnail
	 * 
	 * Displays the image on the top
	 * of article if the image position
	 * is not set at none and show image.
	 * 
	 * @since 1.0.0
	 */
	protected function get_product_thumbnail() {
		$output = '';

		if ( $this->get_settings( 'image_size_size' ) == 'custom' && !empty ( $this->get_settings( 'image_size_custom_dimension' )['width'] ) && ! empty ( $this->get_settings( 'image_size_custom_dimension' )['height'] ) ) {
			$image_size = [
				$this->get_settings( 'image_size_custom_dimension' )['width'],
				$this->get_settings( 'image_size_custom_dimension' )['height']
			];
		} else {
			$image_size = $this->get_settings( 'image_size_size' );
		}

		global $product;
		$attachment_ids = $product->get_gallery_image_ids();
		
		// Metro Image Calculation
		$isMetroMasonry = $this->get_settings( 'layout' ) == 'masonry' || $this->get_settings( 'layout' ) == 'metro';
		$padding_bottom = '';

		if ( $isMetroMasonry && has_post_thumbnail() ) {
			$attachment_id = get_post_thumbnail_id( get_the_ID() ); 
			$metadata = wp_get_attachment_metadata( $attachment_id );
			$height =  $metadata['height'];
			$width =  $metadata['width'];
			$padding_bottom = 'style="padding-bottom: ' . round( ( $height / $width) * 100 ) . '%;"';
		}

		if ( has_post_thumbnail() ) {
			$output .= '<div class="m-neuron-post__thumbnail--link m-neuron-product__thumbnail--link" ' . $padding_bottom . '>';
				$output .= $this->get_product_overlay();
				$output .= '<a class="m-neuron-product__permalink" href="' . get_the_permalink() . '">';
					$output .= '<div class="m-neuron-post__thumbnail m-neuron-product__thumbnail">';
						$output .= get_the_post_thumbnail( get_the_ID(), $image_size );
						
						// Secondary Image
						if ( $this->get_settings( 'image_hover_secondary' ) == 'yes' && ! empty( $attachment_ids ) ) {
							$output .= '<div class="m-neuron-post__thumbnail--secondary" style="background-image: url('. wp_get_attachment_url( $attachment_ids[0] ) .');"></div>';
						}

					$output .= '</div>';
				$output .= '</a>';
			$output .= '</div>';

			return $output;
		} else {
			return '<div class="m-neuron-post__thumbnail--link m-neuron-product__thumbnail--link">'. $this->get_product_overlay() .'<a class="m-neuron-product__permalink" href="' . get_the_permalink() . '"><div class="m-neuron-post__thumbnail m-neuron-product__thumbnail"><img src="'. NEURON_BUILDER_PLACEHOLDER .'" alt="'. esc_html__( 'Placeholder', 'neuron-builder' ) .'"/></div></a></div>';
		}
	}

	/**
	 * Product Overlay
	 * 
	 * @since 1.0.0
	 */
	protected function get_product_overlay() {
		$output = [];

		if ( $this->get_settings( 'add_to_cart_position' ) == 'inside' ) {
			$output[] = $this->add_to_cart();
		}

		if ( $this->get_settings( 'quick_view' ) == 'yes' ) {
			$output[] = $this->quick_view();
		}

		if ( defined( 'YITH_WCWL' ) && $this->get_settings( 'wishlist' ) == 'yes' ) {
			$output[] = $this->wishlist();
		}

		if ( empty( $output ) ) {
			return;
		}

		return '<div class="m-neuron-product__overlay">' . implode( ' ', $output ) . '</div>';
	}

	/**
	 * Product Cart
	 * 
	 * Print outside
	 * the cart.
	 * 
	 * @since 1.0.0
	 */
	protected function get_product_cart() {
		if ( $this->get_settings( 'add_to_cart_position' ) != 'outside' ) {
			return;
		}

		return $this->add_to_cart();
	}

	/**
	 * Product Title
	 * 
	 * Displays the product title.
	 * 
	 * @since 1.0.0
	 */
	protected function get_product_title() {
		if ( ! get_the_title() || $this->get_settings( 'title' ) != 'yes' ) {
			return;
		}

		return '<'. $this->get_settings( 'title_html_tag' ) .' class="m-neuron-product__title"><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></'. $this->get_settings( 'title_html_tag' ) .'>';
	}

	/**
	 * Get Rating
	 * 
	 * Display product rating.
	 * 
	 * @since 1.0.0
	 */
	protected function get_rating() {
		$product = wc_get_product( get_the_ID() );
		$rating_count = $product->get_rating_count();

		if ( $this->get_settings( 'rating' ) != 'yes' || $rating_count == 0 ) {
			return;
		}
		?>
			<div class="m-neuron-product__rating">
				<div class="woocommerce"><?php woocommerce_template_single_rating(); ?></div>
			</div>
		<?php
	}

	/**
	 * Get Price
	 * 
	 * Get price if the source
	 * is product.
	 * 
	 * @since 1.0.0
	 */
	protected function get_price() {
		if ( $this->get_settings( 'price' ) != 'yes' ) {
			return;
		}

		global $product;

		$price_html = $product->get_price_html();

		// Price Switch
		if ( $this->get_settings('price_switch') == 'yes' ) {
			global $product;

			if ( ( ! $product->is_in_stock() || ! $product->is_purchasable() ) && ! is_single() ) {
				$add_to_cart = sprintf(
					'<a class="product_type_%s" href="%s">
						<span class="loading">'. esc_html__( 'Loading...', 'neuron-builder' ) .'</span>
						%s
					</a>',
					esc_attr( $product->get_type() ),
					$product->is_type( 'external' ) ? esc_url( $product->add_to_cart_url() ) : esc_url( get_the_permalink() ),
					$product->is_type( 'external' ) ? esc_html__( 'Buy Product', 'neuron-builder' ) : esc_html__( 'Read More', 'neuron-builder' )
				);
			} else if ( $product->is_purchasable() && $product->is_in_stock() ) {
				$add_to_cart = apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( 
					'<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="ajax_add_to_cart add_to_cart_button product_type_%s">
						<span class="loading">'. esc_html__( 'Loading...', 'neuron-builder' ) .'</span>
						%s
					</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					esc_attr( $product->get_type() ),
					'<span>'. __( 'Add to Cart', 'neuron-builder' ) .'</span>'
				), $product );
			}
			
			if ( $price_html ) {
				return '<span class="m-neuron-product__price m-neuron-product__price--switch"><span class="m-neuron-product__price--inner">'. wp_kses_post( $price_html ) .'</span>' . $add_to_cart .'</span>';
			}
		}

		if ( $price_html ) {
			return '<span class="m-neuron-product__price">'. wp_kses_post( $price_html ) .'</span>';
		}
	}

	/**
	 * Add to Cart
	 * 
	 * Display add to cart
	 * button which user is able
	 * via ajax to add the product
	 * to his/her mini cart.
	 * 
	 * @since 1.0.0
	 */
	protected function add_to_cart() {
		if ( $this->get_settings( 'add_to_cart' ) != 'yes' ) {
			return;
		}

		$add_to_cart_text = $this->get_settings( 'add_to_cart_text' );

		ob_start();

		Icons_Manager::render_icon( $this->get_settings( 'add_to_cart_selected_icon' ) );

		$add_to_cart_icon = ob_get_contents();

		ob_end_clean();

		global $product;

		if ( ( ! $product->is_in_stock() || ! $product->is_purchasable() ) && ! is_single() ) {
			$output = sprintf(
				'<a class="product_type_%s button" href="%s">
					%s
				</a>',
				esc_attr( $product->get_type() ),
				$product->is_type( 'external' ) ? esc_url( $product->add_to_cart_url() ) : esc_url( get_the_permalink() ),
				$product->is_type( 'external' ) ? esc_html__( 'Buy Product', 'neuron-builder' ) : esc_html__( 'Read More', 'neuron-builder' )
			);
		} else if ( $product->is_purchasable() && $product->is_in_stock() ) {
			$output = apply_filters( 'woocommerce_loop_add_to_cart_link',
			   sprintf( 
				   '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button ajax_add_to_cart add_to_cart_button product_type_%s">
						%s
						%s
					</a>',
				   esc_url( $product->add_to_cart_url() ),
				   esc_attr( $product->get_id() ),
				   esc_attr( $product->get_sku() ),
				   esc_attr( $product->get_type() ),
				   $add_to_cart_text ? '<span>'. $add_to_cart_text .'</span>' : '',
				   ! empty( $add_to_cart_icon ) ? $add_to_cart_icon : ''
			   ),
		   $product );
		}


		if ( ! empty ( $output ) ) {
			return '<span class="m-neuron-product__add-to-cart">'. $output .'</span>';
		}
	}

	/**
	 * Quick View
	 * 
	 * Opens the quick view in products.
	 * 
	 * @since 1.0.2
	 */
	protected function quick_view() {
		global $product;

		ob_start();

		Icons_Manager::render_icon( $this->get_settings( 'quick_view_selected_icon' ) );

		$quick_view_icon = ob_get_contents();

		ob_end_clean();

		if ( ! empty( $quick_view_icon ) ) {
			$output = $quick_view_icon;
		} else {
			$output = '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.88982 8.83733C1.59207 8.31873 1.59207 7.68128 1.88981 7.16267C3.45648 4.43394 5.96236 2.66663 8.78637 2.66663C11.6104 2.66663 14.1162 4.43392 15.6829 7.16262C15.9806 7.68122 15.9806 8.31867 15.6829 8.83727C14.1162 11.566 11.6103 13.3333 8.78634 13.3333C5.96235 13.3333 3.45648 11.566 1.88982 8.83733Z" stroke="#121212" stroke-width="1.5"/>
						<ellipse cx="8.78662" cy="8" rx="2" ry="2" stroke="#121212" stroke-width="1.5"/>
					</svg>';
		}

		return '<span class="m-neuron-product__quick-view"><a data-product_id="'. esc_attr( $product->get_id() ) .'" href="#">'. $output .'</a></span>';
	}

	/**
	 * Wishlist
	 * 
	 * Add wishlist via Yith.
	 * 
	 * @since 1.0.2
	 */
	protected function wishlist() {
		return '<span class="m-neuron-product__wishlist">'. do_shortcode( '[yith_wcwl_add_to_wishlist]' ) .'</span>';
	}

	/**
	 * Flash Sale
	 * 
	 * A flash sale badge which
	 * is displayed on the top
	 * of product when there's
	 * a sale onto it.
	 * 
	 * @since 1.0.0
	 */
	protected function sale_flash() {
		if ( $this->get_settings( 'sale_flash' ) != 'yes' || $this->get_settings( 'carousel' ) == 'yes' ) {
			return;
		}

		global $product;

		if ( $product->is_on_sale() ) {
			return '<span class="m-neuron-product__sale-flash m-neuron-portfolio__sale-flash">Sale</span>';
		}
	}

	/**
	 * Paged
	 * 
	 * @since 1.0.0
	 */
	protected function get_paged() {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		return $paged;
	}


	/**
	 * Query
	 * 
	 * Loads the query with
	 * different sources and
	 * works with include &
	 * exclude option on it.
	 * 
	 * @since 1.0.0
	 */
	protected function get_query() {
		global $product;

		if ( $product ) {
			$upsells = array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' );

			if ( $upsells ) {
				$this->query_args['post__in'] = $upsells;
			}
		}
        
		$this->query_args = [
			'post_type' => 'product',
			'posts_per_page' => $this->get_settings('posts_per_page') ? $this->get_settings('posts_per_page') : 4,
			'paged' => $this->get_paged(),
        ];
        
		$query = new \WP_Query( $this->query_args );

		return $query;
	}

	/**
	 * Repeater Item
	 * 
	 * Add Repeater Item when metro or
	 * carousel is activated.
	 * 
	 * @since 1.0.0
	 */
	protected function repeater_item( $post_class ) {
		if ( $this->get_settings( 'layout' ) != 'metro' && $this->get_settings( 'carousel' ) != 'yes' ) {
			return;
		}

		if ( $this->get_settings('neuron_metro') ) {
			foreach ( $this->get_settings('neuron_metro') as $metro ) {
				if ( $metro['id'] == get_the_ID() ) {
					return $post_class = ' elementor-repeater-item-' . $metro['_id'];
				}
			}
		}
	}

	/**
	 * Packery
	 * 
	 * Loads Packery in 
	 * Masonry & Metro.
	 * 
	 * @since 1.0.0
	 */
	protected function load_packery() {
		$isMetroMasonry = ( $this->get_settings( 'layout' ) == 'masonry' || $this->get_settings( 'layout' ) == 'metro' );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() == true && $isMetroMasonry ) {
		?>
		<script>
			var grid = new Packery( '.l-neuron-grid[data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) )  ?>"]', {
				itemSelector: '.l-neuron-grid__item'
			} );

			window.document.onload = function () {
				grid.layout();
			}	
		</script>
		<?php
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$query = $this->get_query();

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );

		if ( $settings['carousel'] == 'yes' ) {
			$gridClass = 'swiper-wrapper neuron-slides';
			$articleClass = 'swiper-slide';
		} else {
			$gridClass = 'l-neuron-grid';
			$articleClass = 'l-neuron-grid__item';
		}

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$articleClass .= ' h-neuron-animation--wow';
		}
		?>
		<?php if ( $settings['carousel'] == 'yes' ) : ?>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
			<?php endif; // End Carousel ?>
				<div class="<?php echo esc_attr( $gridClass ) ?>" data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
					<?php 
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$post_class = '';

							if ( $this->repeater_item( $post_class ) ) {
								$post_class = $this->repeater_item( $post_class );
							}
							?>
							<article <?php post_class( $articleClass . ' m-neuron-product m-neuron-post' . $post_class ) ?> data-id="<?php the_id() ?>">
								<div class="m-neuron-post__inner m-neuron-product__inner">
									<?php echo $this->get_product_thumbnail() ?>
									<div class="m-neuron-post__text m-neuron-product__content">
										<?php echo $this->get_product_title() ?>
										<?php echo $this->get_rating() ?>
										<?php echo $this->get_price() ?>
										<?php echo $this->get_product_cart() ?>
									</div>
									<?php echo $this->sale_flash() ?>
								</div>
							</article>
							<?php
						}
					}
					wp_reset_postdata();
					?>
				</div>

			<?php if ( $settings['carousel'] == 'yes' ) : ?>
				</div>

			<?php if ( $settings['dots_style'] == 'scrollbar' ) : ?>
				<div class="swiper-scrollbar"></div>
			<?php endif; ?>

			<?php if ( $carousel_dots ) : ?>
				<div class="swiper-pagination neuron-swiper-dots"></div>
			<?php endif; ?>
			
			<?php if ( $carousel_arrows ) : ?>
				<div class="neuron-swiper-button neuron-swiper-button--prev">
					<?php if ( $settings['arrows_icon'] ) : ?>
						<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<?php endif; ?>
					<span class="neuron-swiper-button--hidden"><?php _e( 'Previous', 'neuron-builder' ); ?></span>
				</div>
				<div class="neuron-swiper-button neuron-swiper-button--next">
					<?php if ( $settings['arrows_icon'] ) : ?>
						<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<?php endif; ?>
					<span class="neuron-swiper-button--hidden"><?php _e( 'Next', 'neuron-builder' ); ?></span>
				</div>
			<?php endif; ?>
			</div>
		<?php endif; // End Carousel
		$this->load_packery();
	}

	protected function content_template() {}
}
