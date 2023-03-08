<?php
/**
 * Archive Products
 * 
 * Extends the Products.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

use Neuron\Core\Helpers\Products as Products_Helper;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Products extends Products {

	public function get_name() {
		return 'neuron-woo-archive-products';
	}

	public function get_title() {
		return __( 'Archive Products', 'neuron-builder' );
	}

	public function get_categories() {
		return [
			'neuron-woo-elements-archive',
		];
    }

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'query_section',
			[
				'type' => 'hidden',
			]
        );
        
        $this->update_control(
			'filters_section',
			[
				'type' => 'hidden',
			]
        );

        $this->remove_control( 'posts_per_page' );
	}
	
	protected function woo_top_bar_archive() {
		if ( $this->get_settings( 'allow_order' ) != 'yes' && $this->get_settings( 'results_count' ) != 'yes' ) {
			return;
		}

		/**
		 * Result Count
		 */
		$neuron_result_count_args = array(
			'total'    => wc_get_loop_prop( 'total' ),
			'per_page' => wc_get_loop_prop( 'per_page' ),
			'current'  => wc_get_loop_prop( 'current_page' ),
		);

		// Orderby
		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
		$catalog_orderby_options = apply_filters(
			'woocommerce_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'neuron-builder' ),
				'popularity' => __( 'Sort by popularity', 'neuron-builder' ),
				'rating'     => __( 'Sort by average rating', 'neuron-builder' ),
				'date'       => __( 'Sort by latest', 'neuron-builder' ),
				'price'      => __( 'Sort by price: low to high', 'neuron-builder' ),
				'price-desc' => __( 'Sort by price: high to low', 'neuron-builder' ),
			)
		);

		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;

		if ( wc_get_loop_prop( 'is_search' ) ) {
			$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'neuron-builder' ) ), $catalog_orderby_options );

			unset( $catalog_orderby_options['menu_order'] );
		}

		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}

		if ( ! wc_review_ratings_enabled() ) {
			unset( $catalog_orderby_options['rating'] );
		}

		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}

		?>
        <div class="m-neuron-product__woo-bar">
			<?php if ( $this->get_settings( 'results_count' ) == 'yes' ) : ?>
				<?php wc_get_template( 'loop/result-count.php', $neuron_result_count_args ) ?>
			<?php endif; ?>
			<?php if ( $this->get_settings( 'allow_order' ) == 'yes' ) : ?>
				<?php 
					wc_get_template(
						'loop/orderby.php', 
						array(
							'catalog_orderby_options' => $catalog_orderby_options,
							'orderby'                 => $orderby,
							'show_default_orderby'    => $show_default_orderby,
						)
					);
				?>
			<?php endif; ?>
		</div>
		<?php
	}
    
    protected function render() {
		$settings = $this->get_settings_for_display();

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );

		if ( $settings['carousel'] == 'yes' ) {
			$gridClass = 'swiper-wrapper neuron-slides';
			$articleClass = 'swiper-slide';
		} else {
			$gridClass = 'l-neuron-grid products';
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
				<?php echo $this->woo_top_bar_archive(); ?>
				<div class="<?php echo esc_attr( $gridClass ) ?>" data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
					<?php 
					if ( have_posts() ) {

						woocommerce_product_loop_start( false );

						while ( have_posts() ) {
							the_post();
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

						woocommerce_product_loop_end( false );

						woocommerce_reset_loop();
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
		
		$this->get_pagination( $GLOBALS['wp_query'] );
		$this->load_packery();
	}
}
