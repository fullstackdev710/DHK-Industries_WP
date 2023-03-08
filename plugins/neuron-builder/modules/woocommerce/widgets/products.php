<?php
/**
 * Products
 * 
 * Powerful element to showcase
 * your products or categories.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

use Neuron\Core\Helpers\Products as Products_Helper;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Products extends Base_Widget {

	protected $query_args;

	protected $catalog_orderby_options;

	protected $orderby;

	protected $show_default_orderby;

	protected $terms = [];

	public function get_name() {
		return 'neuron-woo-products';
	}

	public function get_title() {
		return __( 'Products', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-products neuron-badge';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'neuron-packery', 'neuron-object-fit' ];
	}

	public function get_categories() {
		return [ 'neuron-woo-elements' ];
	}

	protected function register_controls() {
		new Products_Helper( $this );
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
						global $product;

						if ( $this->get_settings( 'image_hover_secondary' ) == 'yes' && ! empty( $product->get_gallery_image_ids() ) ) {
							$output .= '<div class="m-neuron-post__thumbnail--secondary" style="background-image: url('. wp_get_attachment_url( $product->get_gallery_image_ids()[0] ) .');"></div>';
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

		if  ( empty ( $product ) ) {
			return;
		}

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
		global $product;


		if ( $this->get_settings( 'price' ) != 'yes' || empty( $product ) ) {
			return;
		}

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

		if ( ! empty ( $product ) ) {
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
	 * Query by Source
	 * 
	 * @since 1.0.0
	 */
	protected function query_by_source() {
		$source = $this->get_settings( 'source' );

		if ( $source == 'sale' ) {
			$this->query_args['meta_query'] = [
				'relation' => 'OR',
				array( // Simple products type
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'
				),
				array( // Variable products type
					'key'     => '_min_variation_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'
				)
			];
		} elseif ( $source == 'featured' ) {
			$this->query_args['tax_query'] = [
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN'
				],
			];			
		}
	}

	/**
	 * Include By Term
	 * 
	 * Include post that contains
	 * the following term.
	 * 
	 * @since 1.0.0
	 */
	protected function include_by_term() {
		$include = $this->get_settings( 'include_by' );
		$include_term = $this->get_settings( 'include_term' );

		if ( empty ( $include ) || ! in_array( 'term', $include ) || empty( $include_term  ) ) {
			return;
		}

		$this->query_args['tax_query']['relation'] = 'OR';

		foreach ( $include_term as $term ) {
			$termSingle = strstr( $term, '==', false );
			$termType = strstr( $term, '==', true );
			$termObj = str_replace( '==', '', $termSingle );

			$this->query_args['tax_query'][] = [
				'taxonomy' => $termType,
				'terms' => $termObj,
				'field' => 'slug'
			];
		}
	}

	/**
	 * Exclude By Term
	 * 
	 * Include post that contains
	 * the following term.
	 * 
	 * @since 1.0.0
	 */
	protected function exclude_by_term() {
		$exclude = $this->get_settings( 'exclude_by' );
		$exclude_term = $this->get_settings( 'exclude_term' );

		if ( empty ( $exclude ) || ! in_array( 'term', $exclude ) || empty( $exclude_term  ) ) {
			return;
		}

		foreach ( $exclude_term as $term ) {
			$termSingle = strstr( $term, '==', false );
			$termType = strstr( $term, '==', true );
			$termObj = str_replace( '==', '', $termSingle );

			$this->query_args['tax_query'][] = [
				'taxonomy' => $termType,
				'terms' => $termObj,
				'field' => 'slug',
				'operator' => 'NOT IN'
			];
		}

		// Handle Relation
		if ( ! empty ( $this->get_settings( 'include_by' ) ) || in_array( 'term', $this->get_settings( 'include_by' ) ) ) {
			$this->query_args['tax_query']['relation'] = 'AND';
		}
	}

	/**
	 * Include Manually
	 * 
	 * Include manually any kind
	 * of page or post to the query.
	 * 
	 * @since 1.0.0
	 */
	protected function include_manually() {
		if ( $this->get_settings( 'source' ) != 'manual-selection' || empty ( $this->get_settings('search_select') ) ) {
			return;
		}

		foreach ( $this->get_settings('search_select') as $term ) {
			$termSingle = strstr( $term, '-', false );
			$termType = strstr( $term, '-', true );
			$termObj = str_replace( '-', '', $termSingle );

			$this->query_args['post__in'][] = $termObj;
		}
	}

	/**
	 * Exclude Manually
	 * 
	 * Exclude manually any kind
	 * of page or post to the query.
	 * 
	 * @since 1.0.0
	 */
	protected function exclude_manually() {
		$exclude = $this->get_settings( 'exclude_by' );
		$exclude_term = $this->get_settings( 'exclude_term' );

		if ( ( empty ( $exclude ) || ! in_array( 'term', $exclude ) || empty( $exclude_term  ) ) && empty( $this->get_settings( 'exclude_manual' ) ) ) {
			return;
		}

		foreach ( $this->get_settings( 'exclude_manual' ) as $term ) {
			$termSingle = strstr( $term, '-', false );
			$termType = strstr( $term, '-', true );
			$termObj = str_replace( '-', '', $termSingle );

			$this->query_args['post__not_in'][] = $termObj;
		}
	}

	/**
	 * Query By Author
	 * 
	 * Include or Exclude posts
	 * by a certain author.
	 * 
	 * @since 1.0.0
	 */
	protected function query_by_author() {
		// Include
		if ( ! empty ( $this->get_settings( 'include_by' ) ) && in_array( 'author', $this->get_settings( 'include_by' ) ) && ! empty( $this->get_settings( 'include_author' ) ) ) {
			$this->query_args['author'] = implode(', ', $this->get_settings( 'include_author' ) );
		}

		// Exclude
		if ( ! empty ( $this->get_settings( 'exclude_by' ) ) &&  in_array( 'author', $this->get_settings( 'exclude_by' ) ) && ! empty( $this->get_settings( 'exclude_author' ) )  ) {
			$this->query_args['author__not_in'] = implode(', ', $this->get_settings( 'exclude_author' ) );
		}
	}

	/**
	 * Exclude Curent Post
	 * 
	 * @since 1.0.0
	 */
	protected function query_exclude_current_post() {
		if ( ! empty ( $this->get_settings( 'exclude_by' ) ) && ( in_array( 'current-post', $this->get_settings( 'exclude_by' ) ) ) ) {
			if ( ! empty ( $this->query_args['post__not_in'] ) ) {
				$this->query_args['post__not_in'] = array_push( $this->query_args['post__not_in'], get_the_ID() );
			} else {
				$this->query_args['post__not_in'] = [get_the_ID()];
			}
		}
	}

	/**
	 * Query By Date
	 * 
	 * Include posts that have
	 * a certain time creation.
	 * 
	 * @since 1.0.0
	 */
	function query_by_date() {
		if ( $this->get_settings( 'source' ) == 'manual-selection' || $this->get_settings( 'date_order' ) == 'all' ) {
			return;
		}

		switch ( $this->get_settings( 'date_order' ) ) {
			case 'past-day':
				$this->query_args['date_query'] = ['after' => '-1 day'];
				break;
			case 'past-week':
				$this->query_args['date_query'] = ['after' => '-1 week'];
				break;
			case 'past-month':
				$this->query_args['date_query'] = ['after' => '-1 month'];
				break;
			case 'past-quarter':
				$this->query_args['date_query'] = ['after' => '-3 month'];
				break;
			case 'past-year':
				$this->query_args['date_query'] = ['after' => '-1 year'];
				break;
		}

		if ( $this->get_settings( 'date_order' ) == 'custom' ) {
			$this->query_args['date_query']['inclusive'] = 1;
			
			if ( $this->get_settings( 'date_before' ) ) {
				$this->query_args['date_query']['before'] = $this->get_settings( 'date_before' );
			}

			if ( $this->get_settings( 'date_after' ) ) {
				$this->query_args['date_query']['after'] = $this->get_settings( 'date_after' );
			}
		}
	}

	/**
	 * Query Products
	 * 
	 * Different properties to the wp_query
	 * to extend the query with includes
	 * and excludes.
	 * 
	 * @since 1.0.0
	 */
	protected function query_posts() {
		$this->query_by_source();

		$this->query_by_author();
		$this->include_by_term();
		$this->include_manually();

		$this->exclude_by_term();
		$this->exclude_manually();
		$this->query_exclude_current_post();

		$this->query_by_date();

		// WooCommerce
		$this->query_by_woo_orderby();
	}

	/**
	 * Show More Pagination
	 * 
	 * Includes and Excludes
	 * posts to display them
	 * via the ajax pagination.
	 * 
	 * @since 1.0.0
	 */
	protected function show_more_pagination() {
		if ( $this->get_settings( 'pagination' ) != 'show-more' ) {
			return;
		}

		$filter = isset( $_GET['filter'] ) ? $_GET['filter'] : '';
		$taxonomy = isset( $_GET['termType'] ) ? $_GET['termType'] : '';
		$exclude = isset( $_GET['exclude'] ) ? $_GET['exclude'] : '';

		if ( $filter ) {
			$this->query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy ? $taxonomy : 'category',
					'field' => 'slug',
					'terms' => $filter
				)
			);
		}

		if ( $exclude ) {
			$this->query_args['post__not_in'] = $exclude;
		}
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
	protected function get_query( $filters = false ) {
		$this->query_args = [
			'post_type' => 'product',
			'posts_per_page' => $this->get_settings('posts_per_page') ? $this->get_settings('posts_per_page') : 6,
			'paged' => $this->get_paged(),
			'orderby' => $this->get_settings( 'orderby' ),
			'order' => $this->get_settings( 'order' ),
			'ignore_sticky_posts' => $this->get_settings( 'ignore_sticky' ) == 'yes' ? 1 : '',
			'offset' => $this->get_settings( 'query_offset' ),
		];

		if ( $this->get_settings('source') == 'current_query' ) {
			$this->query_args = $GLOBALS['wp_query']->query_vars;
		}

		$this->query_posts();

		// Filters
		if ( ! $filters ) {
			$this->show_more_pagination();
		}

		$product_visibility_term_ids = wc_get_product_visibility_term_ids();

		// // Show/Hide out of stock
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$this->query_args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['outofstock'],
					'operator' => 'NOT IN',
				),
			); // WPCS: slow query ok.
		}

		$query = new \WP_Query( $this->query_args );

		return $query;
	}

	/**
	 * Get Current Page
	 * 
	 * @since 1.0.0
	 */
	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Get WP Link Page
	 * 
	 * @since 1.0.0
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		global $wp_rewrite;
		$post = get_post();
		$query_args = [];
		$url = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id'] = wp_unslash( $_GET['preview_id'] );
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = [];

		$paged = $this->get_current_page();

		$link_template = '<a class="page-numbers %s page-label" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s page-label">%s</span>';

		ob_start();

		Icons_Manager::render_icon( $this->get_settings( 'label_icon' ) );

		$label_icon = '<span class="m-neuron-pagination--icon">' . ob_get_contents() . '</span>';

		ob_end_clean();

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $label_icon . $this->get_settings( 'previous_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $label_icon . $this->get_settings( 'previous_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'next_label' ) . $label_icon );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'next_label' ) . $label_icon );
		}

		return $return;
	}

	/**
	 * Normal Pagination
	 * 
	 * Includes the numbers pagination
	 * with or without arrows.
	 * 
	 * @since 1.0.0
	 */
	public function normal_pagination( $query = '' ) {
		$settings = $this->get_settings();

		if ( 'none' === $settings['pagination'] ) {
			return;
		}

		global $paged;

		$page_limit = $query->max_num_pages;
		
		if ( '' !== $settings['page_limit'] ) {
			$page_limit = min( $settings['page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$total_pages = $query->max_num_pages;

		$has_numbers = in_array( $settings['pagination'], [ 'numbers', 'numbers-previous-next' ] );
		$has_prev_next = in_array( $settings['pagination'], [ 'previous-next', 'numbers-previous-next' ] );

		$links = [];

		if ( $has_numbers ) {
			$paginate_args = [
				'type' => 'array',
				'current' => $this->get_current_page(),
				'total' => $page_limit,
				'prev_next' => false,
				'show_all' => 'yes' !== $settings['shorten']
			];

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					if ( ! is_multisite() ) {
						$paginate_args['base'] = trailingslashit( get_permalink() ) . '%_%';
						$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
					}
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		echo '<nav class="woocommerce-pagination m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'">'. implode( PHP_EOL, $links ) .'</nav>';
	}

	/**
	 * Ajax Pagination
	 * 
	 * Load more Products via
	 * a button which request
	 * for ajax call and returns
	 * on the DOM.
	 * 
	 * @since 1.0.0
	 */
	protected function ajax_pagination() {
		if ( $this->get_settings( 'show_more_text' ) ) {
			$show_more = $this->get_settings( 'show_more_text' );
		} else {
			$show_more = __( 'Show More', 'neuron-builder' );
		}

		$button_class = ['page-numbers', 'a-button'];
		if ( ! empty( $this->get_settings( 'pagination_hover_animation' ) ) ) {
			$button_class[] = 'elementor-animation-' . $this->get_settings('pagination_hover_animation');
		}

		$exclude = '';

		if ( isset( $this->query_args['post__not_in'] ) && ! empty( $this->query_args['post__not_in'] ) ) {
			$exclude = $this->query_args['post__not_in'];

			if ( ! empty ( $exclude ) ) {
				$exclude = implode( ', ', $exclude );
			}
		}

		$loading = $this->get_settings( 'show_more_loading_text' ) ? $this->get_settings( 'show_more_loading_text' ) : $show_more;

		echo '<div class="m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'"><button id="load-more-posts" class="'. implode(' ', $button_class) .'" data-text="'. $show_more .'" data-exclude="'. $exclude . '" data-loading="'. $loading .'">'. $show_more .'</button></div>';
	}

	/**
	 * Pagination
	 * 
	 * Displays a different pagination
	 * type which can make user able
	 * to navigate through pages or load
	 * more posts via show more button.
	 * 
	 * @since 1.0.0
	 */
	protected function get_pagination( $query ) {
		if ( $this->get_settings( 'pagination' ) == 'none' || $this->get_settings( 'carousel' ) == 'yes' ) {
			return;
		}

		if ( $this->get_settings( 'pagination' ) == 'show-more' && $query->max_num_pages > $this->get_paged() ) {
			$html = $this->ajax_pagination();
		} else {
			$html = $this->normal_pagination( $query );
		}

		return '<div class="m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'">' . $html . '</div>';
	}

	/**
	 * Prepare Term
	 * 
	 * Accepts the term and returns
	 * with the correct taxonomy;
	 * 
	 * @since 1.0.0
	 */
	protected function prepare_term( $term ) {
		$term = $term[0];

		// Taxonomy
		switch ( $term->taxonomy ) {
			case 'post_tag':
				$term->taxonomy = 'tag';
				break;
		}

		return $term;
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
	 * Filters
	 * 
	 * Display sortable filters which
	 * are able to sort different posts
	 * due a certain taxonomy,
	 * 
	 * @since 1.0.0
	 */
	protected function get_filters( $query ) {
		if ( $this->get_settings( 'filters' ) != 'yes' || $this->get_settings( 'carousel' ) == 'yes' ) {
			return;
		}

		// Query
		$query = $this->get_query( true );

		// Filters in Manual Selection
		$filters_tax = $this->get_settings( 'filters_tax' );

		// Save Terms for Filters
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				if ( get_the_terms( get_the_ID(), $filters_tax ) ) {
					foreach ( get_the_terms( get_the_ID(), $filters_tax ) as $post_term ) {
						if ( ! in_array( $post_term, $this->terms ) ) {
							$this->terms[] = $post_term;
						}
					}
				}
			}
		}

		if ( ! empty( $this->terms ) ) {
		?>
		<div class="m-neuron-filters" aria-label="<?php echo esc_html__( 'Filters', 'neuron-builder' ) ?>">
			<ul <?php echo $this->get_render_attribute_string( 'filters' ) ?>>
				<?php if ( $this->get_settings( 'filter_all' ) == 'yes' ) : ?>
					<li class="m-neuron-filters__item active" data-filter="*"><?php echo $this->get_settings( 'filter_all_string' ) ? $this->get_settings( 'filter_all_string' ) : esc_html__( 'All', 'neuron-builder' ) ?></li>
				<?php endif; ?>

				<?php foreach( $this->terms as $term ) : ?>
					<li class="m-neuron-filters__item" data-filter="<?php echo esc_attr( $term->taxonomy ) ?>-<?php echo esc_attr( $term->slug ) ?>"><?php echo esc_attr( $term->name ) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		}
	}

	/**
	 * Orderby Sorting
	 * 
	 * Sort by WooCommerce options
	 * 
	 * @since 1.0.0
	 */
	protected function query_by_woo_orderby() {
		if ( $this->get_settings( 'allow_order' ) != 'yes' && $this->get_settings( 'results_count' ) != 'yes' ) {
			return;
		}

		/**
		 * Catalog Orderby
		 */
		$orderby_options = apply_filters(
			'woocommerce_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'neuron-builder' ),
				'popularity' => __( 'Sort by popularity', 'neuron-builder' ),
				'rating'     => __( 'Sort by average rating', 'neuron-builder' ),
				'date'       => __( 'Sort by newness', 'neuron-builder' ),
				'price'      => __( 'Sort by price: low to high', 'neuron-builder' ),
				'price-desc' => __( 'Sort by price: high to low', 'neuron-builder' )
			)
		);

		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );

		$this->catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', $orderby_options );

		/**
		 * Change default order
		 */
		if ( isset( $_GET['orderby'] ) ) {
			$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;
			$show_default_orderby = 'default';
		} else {
			switch ( $this->get_settings( 'posts_meta_ordering_default' ) ) {
				default:
					$show_default_orderby = $orderby = 'menu_order';
					break;
				case 'popularity':
					$show_default_orderby = $orderby = 'popularity';
					break;
				case 'rating':
					$show_default_orderby = $orderby = 'rating';
					break;
				case 'date':
					$show_default_orderby = $orderby = 'date';
					break;
				case 'price':
					$show_default_orderby = $orderby = 'price';
					break;
				case 'price-desc':
					$show_default_orderby = $orderby = 'price-desc';
					break;
			}
		}

		$this->orderby = $orderby;
		$this->show_default_orderby = $show_default_orderby;

		/**
		 * Modify Query for Orderby
		 */
		switch ( $this->orderby ) {
			case 'default':
				$this->query_args['orderby'] = 'menu_order';
				$this->query_args['order'] = 'asc';
				break;
			case 'popularity':
				$this->query_args['orderby'] = 'meta_value_num';
				$this->query_args['meta_key'] = 'total_sales';
				$this->query_args['order'] = 'desc';
				break;
			case 'rating':
				$this->query_args['orderby'] = 'meta_value_num';
				$this->query_args['meta_key'] = '_wc_average_rating';
				$this->query_args['order'] = 'desc';
				break;
			case 'date':
				$this->query_args['orderby'] = 'date';
				$this->query_args['meta_key'] = '';
				$this->query_args['order'] = 'desc';
				break;
			case 'price':
				$this->query_args['orderby'] = 'meta_value_num';
				$this->query_args['meta_key'] = '_price';
				$this->query_args['order'] = 'asc';
				break;
			case 'price-desc':
				$this->query_args['orderby'] = 'meta_value_num';
				$this->query_args['meta_key'] = '_price';
				$this->query_args['order'] = 'desc';
				break;
		}
	}

	/**
	 * Top Bar
	 * 
	 * Includes WooCommerce orderby
	 * and results count of woocommerce.
	 * 
	 * @since 1.0.0
	 */
	protected function woo_top_bar( $query ) {
		if ( $this->get_settings( 'allow_order' ) != 'yes' && $this->get_settings( 'results_count' ) != 'yes' ) {
			return;
		}

		/**
		 * Result Count
		 */
		$neuron_result_count_args = [
			'total'    => $query->found_posts,
			'per_page' => $this->query_args['posts_per_page'] ? $this->query_args['posts_per_page'] : 10,
			'current'  => $this->get_paged(),
		];

		?>
		<div class="m-neuron-product__woo-bar">
			<?php if ( $this->get_settings( 'results_count' ) == 'yes' ) : ?>
				<?php wc_get_template('loop/result-count.php', $neuron_result_count_args) ?>
			<?php endif; ?>
			<?php if ( $this->get_settings( 'allow_order' ) == 'yes' ) : ?>
				<?php 
					wc_get_template('loop/orderby.php', array(
						'catalog_orderby_options' => $this->catalog_orderby_options,
						'orderby' => $this->orderby,
						'show_default_orderby' => $this->show_default_orderby,
					));
				?>
			<?php endif; ?>
		</div>
		<?php
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

		$this->get_filters( $query );

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
				<?php echo $this->woo_top_bar( $query ); ?>
				<div class="<?php echo esc_attr( $gridClass ) ?>" data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
					<?php 
					if ( $query->have_posts() ) {

						woocommerce_product_loop_start( false );

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
		<?php endif; // End Carousel ?>

		<?php 
		$this->get_pagination( $query );
		$this->load_packery();
	}

	protected function content_template() {}
}
