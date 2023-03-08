<?php
/**
 * Posts
 * 
 * Powerful element to showcase
 * your posts or any
 * custom post type.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

use Neuron\Base\Base_Widget;

use Neuron\Core\Helpers\Posts as Posts_Helper;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Posts extends Base_Widget {

	protected $query_args;

	protected $terms = [];

	public function get_name() {
		return 'neuron-posts';
	}

	public function get_title() {
		return __('Posts', 'neuron-builder');
	}

	public function get_icon() {
		return 'eicon-post-list neuron-badge';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'neuron-packery', 'neuron-object-fit' ];
	}

	public function get_keywords() {
		return [ 'posts', 'post', 'post list', 'custom post', 'portfolio', 'blog' ];
	}

	protected function register_controls() {
		new Posts_Helper( $this );
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
	 * Post Thumbnail
	 * 
	 * Displays the image on the top
	 * of article if the image position
	 * is not set at none and show image.
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_thumbnail() {
		if ( ! has_post_thumbnail() || ( $this->get_settings( 'image_position' ) == 'none'  && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) || ( ! $this->get_settings( 'show_image' ) && $this->get_settings( 'skin' ) == 'cards' ) ) {
			return;
		}

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

		if ( $isMetroMasonry ) {
			$attachment_id = get_post_thumbnail_id( get_the_ID() ); 
			$metadata = wp_get_attachment_metadata( $attachment_id );
			$height =  $metadata['height'];
			$width =  $metadata['width'];
			$padding_bottom = 'style="padding-bottom: ' . round( ( $height / $width) * 100 ) . '%;"';
		}

		return '<a class="m-neuron-post__thumbnail--link" '. $padding_bottom .' href="'. get_the_permalink() .'"><div class="m-neuron-post__thumbnail">'. get_the_post_thumbnail( get_the_ID(), $image_size ) . '</div></a>';
	}

	/**
	 * Post Title
	 * 
	 * Displays the post title.
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_title() {
		if ( ! get_the_title() || $this->get_settings( 'title' ) != 'yes' ) {
			return;
		}

		return '<'. $this->get_settings( 'title_html_tag' ) .' class="m-neuron-post__title"><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></'. $this->get_settings( 'title_html_tag' ) .'>';
	}

	/**
	 * Post Avatar
	 * 
	 * Displays the post avatar.
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_avatar() {
		if ( $this->get_settings( 'avatar' ) != 'yes' || ! $this->get_post_thumbnail() ) {
			return;
		}

		return '<div class="m-neuron-post__avatar"><img src="' . get_avatar_url( get_the_ID(), ['size' => 128] ) . '" alt="' . get_the_author()  . '"></div>';
	} 

	/**
	 * Post Badge
	 * 
	 * Displays the post badge.
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_badge() {
		if ( ! class_exists( 'WooCommerce' ) && ! $this->get_post_thumbnail() ) {
			return;
		}

		$badge_taxonomy = $this->get_settings( 'badge_taxonomy' );
		$terms = get_the_terms( get_the_ID(), $badge_taxonomy );
		
		if ( $this->get_settings( 'badge' ) == 'yes' && $terms ) {
			$firstTerm = $terms[0];

			return '<div class="m-neuron-post__badge">'. $firstTerm->name .'</div>';
		}
	} 

	/**
	 * Post Meta
	 * 
	 * Different meta of the post
	 * will be displayed via this function.
	 * Author / Date / Time / Comments
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_meta_data() {
		if ( empty ( $this->get_settings( 'meta_data' ) ) ) {
			return;
		}

		$meta_data = [];

		// Author
		if ( in_array( 'author', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['author'] = get_the_author();
		}

		// Date
		if ( in_array( 'date', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['date'] = get_the_time( get_option( 'date_format' ) );
		}

		// Time
		if ( in_array( 'time', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['time'] = get_the_time( get_option( 'time_format' ) );
		}

		// Comments
		if ( in_array( 'comments', $this->get_settings( 'meta_data' ) ) ) {
			$default_strings = [
				'no_comments' => __( 'No Comments', 'neuron-builder' ),
				'one_comment' => __( 'One Comment', 'neuron-builder' ),
				'comments' => __( '%s Comments', 'neuron-builder' ),
			];

			$meta_data['comments'] = get_comments_number_text( $default_strings['no_comments'], $default_strings['one_comment'], $default_strings['comments'] );
		}

		if ( in_array( 'terms', $this->get_settings( 'meta_data' ) ) ) {
			$taxonomy = $this->get_settings( 'meta_data_taxonomy' );
			$taxonomy_count = $this->get_settings( 'taxonomy_count' ) ? $this->get_settings( 'taxonomy_count' ) : 1;

			if ( $taxonomy ) {
				$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

				foreach ( $terms as $key => $term ) {
					$meta_data['terms'][] = '<a href="' . esc_attr( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';

					if ( $key == $taxonomy_count - 1 ) {
						break;
					}
				}

				if ( ! empty ( $meta_data['terms'] ) ) {
					$meta_data['terms'] = implode( ' ', $meta_data['terms'] );
				}
			}
		}

		if ( empty ( $meta_data ) ) {
			return;
		}

		return '<span class="m-neuron-post__meta-data"> <span>' . implode( '</span> <span> ', $meta_data ) . '</span> </span>';
	}

	/**
	 * Post Excerpt
	 * 
	 * Displays the excerpt with
	 * a custom function where you 
	 * can change the count of words.
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_excerpt() {
		if (  ! get_the_excerpt() || $this->get_settings( 'excerpt' ) != 'yes' ) {
			return;
		}

		if ( $this->get_settings( 'excerpt_length' ) ) {
			$limit = $this->get_settings( 'excerpt_length' );
		} else {
			$limit = 25;
		}

		$excerpt = explode( ' ', get_the_excerpt(), $limit );

		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$excerpt = implode( ' ', $excerpt );
		} else {
			$excerpt = implode( ' ', $excerpt);
		}	

		$excerpt = preg_replace( '`[[^]]*]`','', $excerpt);

		return '<div class="m-neuron-post__excerpt">'. $excerpt .'</div>';
	}

	/**
	 * Read More Button
	 * 
	 * Displays a read more button
	 * at the end of article.
	 * 
	 * @since 1.0.0
	 */
	protected function get_read_more_button() {
		if ( $this->get_settings( 'show_read_more' ) != 'yes' ) {
			return;
		}

		$output = [];

		if ( $this->get_settings( 'read_more_text' ) ) {
			$read_more = '<span>' . $this->get_settings( 'read_more_text' ) . '</span>';
		} else {
			$read_more = '<span>' . __( 'Read More', 'neuron-builder' ) . '</span>';
		}

		ob_start();

		Icons_Manager::render_icon( $this->get_settings( 'read_more_icon' ) );

		$read_more_icon = '<span class="m-neuron-post__read-more--icon">' . ob_get_contents() . '</span>';

		ob_end_clean();

		return '<div class="m-neuron-post__read-more"><a href="'. get_the_permalink() .'">'. $read_more . $read_more_icon .'</a></div>';
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
	 * Include By Term
	 * 
	 * Include post that contains
	 * the following term.
	 * 
	 * @since 1.0.0
	 */
	protected function include_by_term() {
		$include = $this->get_settings( 'include_by' );
		$source = $this->get_settings( 'source' );
		$include_term = $this->get_settings( 'include_term_' . $source );

		if ( $source == 'related' || empty ( $include ) || ! in_array( 'term', $include ) || empty( $include_term  ) ) {
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
		$source = $this->get_settings( 'source' );
		$exclude_term = $this->get_settings( 'exclude_term_' . $source );

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

		$post_type = [];

		foreach ( $this->get_settings('search_select') as $term ) {
			$termSingle = strstr( $term, '-', false );
			$termType = strstr( $term, '-', true );
			$termObj = str_replace( '-', '', $termSingle );

			$this->query_args['post__in'][] = $termObj;

			if ( ! in_array( $termType, $post_type ) ) {
				$post_type[] = $termType;
			}
		}

		if ( ! empty ( $post_type ) ) {
			$this->query_args['post_type'] = $post_type;
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
		$source = $this->get_settings( 'source' );
		$exclude_term = $this->get_settings( 'exclude_term_' . $source );

		if ( ( empty ( $exclude ) || ! in_array( 'term', $exclude ) || empty( $exclude_term  ) ) && empty( $this->get_settings( 'exclude_manual' ) ) ) {
			return;
		}

		$post_type = [];

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
				array_push( $this->query_args['post__not_in'], get_the_ID() );
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
		if ( $this->get_settings( 'date_order' ) == 'all' ) {
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
	 * Related Query
	 * 
	 * Shows related posts due
	 * certain terms or authors.
	 * 
	 * @since 1.0.0
	 */
	protected function include_related() {
		$include = $this->get_settings( 'include_by' );
		$source = $this->get_settings( 'source' );
		$include_term = $this->get_settings( 'include_term_related' );

		if ( $source != 'related' || empty ( $include ) || ! in_array( 'term', $include ) || empty( $include_term  ) ) {
			return;
		}

		$this->query_args['post_type'] =  get_post_type();
		$this->query_args['post__not_in'] = [get_the_ID()];

		$relatedCat = [];

		foreach ( $include_term as $term ) {
			if ( ! empty ( get_the_terms( get_the_ID(), $term  ) ) ) {
				foreach( get_the_terms( get_the_ID(), $term  ) as $category ) {
					$relatedCat[] = $category->term_id;
				}
			}

			$this->query_args['tax_query'][] = [
				'taxonomy' => $term,
				'terms' => $relatedCat,
				'field' => 'term_id'
			];
		}

		$this->query_args['post_type'] = get_post_type();
	}

	/**
	 * Related Fallback
	 * 
	 * Show different posts
	 * or recent posts if the
	 * related query fails to
	 * find posts.
	 * 
	 * @since 1.0.0
	 */
	public function include_related_fallback( $query ) {
		if ( $this->get_settings( 'source' ) != 'related' || $this->get_settings( 'fallback' ) == 'none' ) {
			return $query;
		}

		// Fallback
		$args = $this->query_args;

		// Manual Selection
		if ( $this->get_settings( 'fallback' ) == 'manual-selection' ) {
			$post_type = [];

			foreach ( $this->get_settings( 'search_select_fallback' ) as $term ) {
				$termSingle = strstr( $term, '-', false );
				$termType = strstr( $term, '-', true );
				$termObj = str_replace( '-', '', $termSingle );

				$args['post__in'][] = $termObj;

				if ( ! empty( $args['tax_query'] ) ) {
					unset( $args['tax_query'] );
				}

				if ( ! in_array( $termType, $post_type ) ) {
					$post_type[] = $termType;
				}
			}

			if ( ! empty ( $post_type ) ) {
				$args['post_type'] = $post_type;
			}
		} else {
			if ( ! empty( $args['tax_query'] ) ) {
				unset( $args['tax_query'] );
			}

			$args['post_type'] = ['post', 'portfolio', 'page', 'product'];
		}

		$query = new \WP_Query( $args );

		return $query;
	}

	/**
	 * Query Posts
	 * 
	 * Different properties to the wp_query
	 * to extend the query with includes
	 * and excludes.
	 * 
	 * @since 1.0.0
	 */
	protected function query_posts() {
		$this->query_by_author();
		$this->include_by_term();
		$this->include_manually();
		$this->include_related();

		$this->exclude_by_term();
		$this->exclude_manually();
		$this->query_exclude_current_post();

		$this->query_by_date();
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
			'post_type' => $this->get_settings('source'),
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

		if ( ! $filters ) {
			$this->show_more_pagination();
		}

		// Exclude
		$query = new \WP_Query( $this->query_args );

		$query = $this->include_related_fallback( $query );

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

		echo '<div class="m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'">'. implode( PHP_EOL, $links ) .'</div>';
	}

	/**
	 * Ajax Pagination
	 * 
	 * Load more Posts via
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
	 * Filters in Manual Selection
	 * 
	 * @since 1.0.0
	 */
	protected function filters_manual_selections( $filters_tax ) {
		if ( $this->get_settings( 'source' ) != 'manual-selection' ) {
			return $filters_tax;
		}

		switch( get_post_type() ) {
			case 'post':
				$filters_tax = 'category';
				break;
			case 'portfolio':
				$filters_tax = 'portfolio_category';
				break;
			case 'product':
				$filters_tax = 'product_cat';
				break;
		}

		return $filters_tax;
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

				<?php foreach( $this->terms as $term ) :
						$term = $this->prepare_term( $term );
					?>
					<li class="m-neuron-filters__item" data-filter="<?php echo esc_attr( $term->taxonomy ) ?>-<?php echo esc_attr( $term->slug ) ?>"><?php echo esc_attr( $term->name ) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		}
	}

	/**
	 * Prepare Term
	 * 
	 * Accepts the term and returns
	 * with the correct taxonomy;
	 * Example post_tag -> tag
	 * 
	 * @since 1.0.4
	 */
	protected function prepare_term( $term ) {
		
		if ( $term->taxonomy == 'post_tag' ) {
			$term->taxonomy = 'tag';
		}

		return $term;
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

		// Grid
		$this->add_render_attribute( 'grid', 'class', [
			$settings['carousel'] == 'yes' ? 'swiper-wrapper neuron-slides' : 'l-neuron-grid',
		] );

		$this->add_render_attribute( 'grid', 'data-masonry-id', [
			esc_attr( md5( $this->get_id() ) ) 
		] );

		// Carousel
		if ( $settings['carousel'] == 'yes' ) {
			$articleClass = 'swiper-slide';
		} else {
			$articleClass = 'l-neuron-grid__item';
		}

		// Animations
		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$articleClass .= ' h-neuron-animation--wow';
		}
		?>
		<?php if ( $settings['carousel'] == 'yes' ) : ?>
			<div class="neuron-swiper">
				<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
		<?php endif; // End Carousel ?>
				<div <?php echo $this->get_render_attribute_string( 'grid' ) ?>>
					<?php 
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$post_class = '';

							if ( $this->repeater_item( $post_class ) ) {
								$post_class = $this->repeater_item( $post_class );
							}
							?>
							<article <?php post_class( $articleClass . ' m-neuron-post' . $post_class ) ?> data-id="<?php the_id() ?>">
								<?php include(  __DIR__ . '/../skins/skin-'. $settings['skin'] .'.php' ) ?>
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
				<div class="swiper-pagination neuron-swiper-dots" data-swiper-pagination-id="<?php echo md5( $this->get_id() ) ?>"></div>
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
