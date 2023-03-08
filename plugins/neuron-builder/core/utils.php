<?php
/**
 * Neuron Utils
 * 
 * Is a collection of useful functions and snippets 
 * that you need or could use every day. 
 * It's implemented as a class with static methods, 
 * to avoid conflicts with your existing code-base. 
 * 
 * @since 1.0.0
 */

namespace Neuron\Core;

use Neuron\Plugin;
use Neuron\Modules\Animations\Module as Animations;

defined( 'ABSPATH' ) || die();

class Utils {

	/**
	 * Get Public Post Types
	 * 
	 * @since 1.0.0
	 */
	public static function get_public_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types neuron should work on
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'neuron/utils/get_public_post_types', $post_types );
	}

    /**
     * Get Site Domain
     * 
     * @since 1.0.0
     */
    public static function get_site_domain() {
		$site = strtolower( $_SERVER['SERVER_NAME'] );

		if ( substr( $site, 0, 4 ) == 'www.' ) {
			$site = substr( $site, 4 );
		}

		return $site;
  	}
  
	/**
	 * Get Client IP
	 * 
	 * @since 1.0.0
	 */
  	public static function get_client_ip() {
		$server_ip_keys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		foreach ( $server_ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
				return $_SERVER[ $key ];
			}
		}
		
		// Fallback local ip.
		return '127.0.0.1';
	}

	/**
	 * Get Post ID
	 * 
	 * @since 1.0.0
	 */
	public static function get_current_post_id() {
		if ( isset( Plugin::elementor()->documents ) ) {
			return Plugin::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	/**
	 * Get Taxonomies by eliminating if
	 * it is placed in more than one
	 * post type.
	 *
	 * @since 1.0.0
	 */
	public static function get_taxonomies( $args = [], $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;

		$field = ( 'names' === $output ) ? 'name' : false;

		// Handle 'object_type' separately.
		if ( isset( $args['object_type'] ) ) {
			$object_type = (array) $args['object_type'];
			unset( $args['object_type'] );
		}

		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}

		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}

		return $taxonomies;
	}

	/**
	 * Get Page Title
	 * 
	 * Returns the page/post or
	 * any other taxonomy title.
	 * 
	 * @since 1.0.0
	 */
	public static function get_page_title( $include_context = true ) {
		$title = '';

		if ( is_singular() ) {
			/* translators: %s: Search term. */
			$title = get_the_title();

			if ( $include_context ) {
				$post_type_obj = get_post_type_object( get_post_type() );
				$title = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
			}
		} elseif ( is_search() ) {
			/* translators: %s: Search term. */
			if ( $include_context ) {
				$title = sprintf( __( 'Search Results for: %s', 'neuron-builder' ), get_search_query() );
			} else {
				$title = get_search_query();
			}

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s is the page number. */
				$title .= sprintf( __( '&nbsp;&ndash; Page %s', 'neuron-builder' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );

			if ( $include_context ) {
				/* translators: Category archive title. 1: Category name */
				$title = sprintf( __( 'Category: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
			if ( $include_context ) {
				/* translators: Tag archive title. 1: Tag name */
				$title = sprintf( __( 'Tag: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';

			if ( $include_context ) {
				/* translators: Author archive title. 1: Author name */
				$title = sprintf( __( 'Author: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'neuron-builder' ) );

			if ( $include_context ) {
				/* translators: Yearly archive title. 1: Year */
				$title = sprintf( __( 'Year: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'neuron-builder' ) );

			if ( $include_context ) {
				/* translators: Monthly archive title. 1: Month name and year */
				$title = sprintf( __( 'Month: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_day() ) {
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'neuron-builder' ) );

			if ( $include_context ) {
				/* translators: Daily archive title. 1: Date */
				$title = sprintf( __( 'Day: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'neuron-builder' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'neuron-builder' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );

			if ( $include_context ) {
				/* translators: Post type archive title. 1: Post type name */
				$title = sprintf( __( 'Archives: %s', 'neuron-builder' ), $title );
			}
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );

			if ( $include_context ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
				$title = sprintf( __( '%1$s: %2$s', 'neuron-builder' ), $tax->labels->singular_name, $title );
			}
		} elseif ( is_archive() ) {
			$title = __( 'Archives', 'neuron-builder' );
		} elseif ( is_404() ) {
			$title = __( 'Page Not Found', 'neuron-builder' );
		} 

		/**
		 * The archive title.
		 *
		 * Filters the archive title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Archive title to be displayed.
		 */
		$title = apply_filters( 'neuron/utils/get_the_archive_title', $title );

		return $title;
	}

	/**
	 * Set Global Author Data
	 * 
	 * @since 1.0.0
	 */
	public static function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post = get_post();
			$authordata = get_userdata( $post->post_author ); // WPCS: override ok.
		}
	}

	/**
	 * Archive URL
	 * 
	 * @since 1.0.0
	 */
	public static function get_the_archive_url() {
		$url = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$url = get_term_link( get_queried_object() );
		} elseif ( is_author() ) {
			$url = get_author_posts_url( get_queried_object_id() );
		} elseif ( is_year() ) {
			$url = get_year_link( get_query_var( 'year' ) );
		} elseif ( is_month() ) {
			$url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
		} elseif ( is_day() ) {
			$url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
		} elseif ( is_post_type_archive() ) {
			$url = get_post_type_archive_link( get_post_type() );
		}

		return $url;
	}

	/**
	 * Terms
	 * 
	 * It returns all the terms
	 * with a prefix of the custom
	 * taxonomy type before the dash.
	 * 
	 * e.g: category==hello || product_cat==fashion
	 * 
	 * @since 1.0.0
	 */
	public static function get_the_terms( $term ) {
		$output = [];

		$taxonomies = get_object_taxonomies( $term );

		$terms = get_terms( [ 'taxonomy' => $taxonomies, 'hide_empty' => false ] );

		if ( ! empty ( $terms ) ) {
			foreach ( $terms as $term ) {
				$output[$term->taxonomy . '==' . $term->slug] = ucwords( str_replace( "_"," ", $term->taxonomy ) ) . ': ' . $term->name;
			}
		}

		return $output;
	}

	/**
	 * Posts
	 * 
	 * Returns all the posts
	 * including the custom post
	 * types that are added after.
	 * 
	 * @since 1.0.0
	 */
	public static function get_the_posts( $post = [] ) {
		$output = [];

		if ( in_array( 'all', $post ) )  {
			foreach ( self::get_public_post_types() as $id => $value ) {
				$post[] = $id;
			}
		}

		if ( ! empty ( get_posts( [ 'post_type' => $post ] ) ) ) {
            foreach( get_posts( [ 'post_type' => $post, 'posts_per_page' => -1 ] ) as $value ) {
                $output[$value->post_type . '-' . $value->ID] = __( ucfirst( $value->post_type ) .  ': ', 'neuron-builder' ) . $value->post_title;
            }
		}
		
		return $output;
	}

	/**
	 * Supported Terms
	 * 
	 * @since 1.0.0
	 */
	public static function get_supported_taxonomies() {
		$supported_taxonomies = [];

		$public_types = Utils::get_public_post_types();

		foreach ( $public_types as $type => $title ) {
			$taxonomies = get_object_taxonomies( $type, 'objects' );
			foreach ( $taxonomies as $key => $tax ) {
				if ( ! array_key_exists( $key, $supported_taxonomies ) ) {
					$label = $tax->label;
					if ( in_array( $tax->label, $supported_taxonomies ) ) {
						$label = $tax->label . ' (' . $tax->name . ')';
					}
					$supported_taxonomies[ $key ] = $label;
				}
			}
		}

		return $supported_taxonomies;
	}

	/**
	 * Authors
	 * 
	 * @since 1.0.0
	 */
	public static function get_authors() {
		$output = [];
		
		if ( !empty( get_users() ) ) {
			foreach ( get_users() as $user ) {
				$output[$user->data->ID] = $user->data->display_name;
			}
		}
	}

	/**
	 * Edit Front Page
	 * 
	 * @since 1.0.0
	 */
	public static function edit_front_page_url() {
		$frontpage_id = get_option( 'page_on_front' );
		$url = add_query_arg(
			[
				'post' => $frontpage_id,
				'action' => 'elementor',
			],
			admin_url( 'post.php' )
		);
			
		return $url;
	}

	/**
	 * Breadcrumbs
	 * 
	 * @since 1.0.0
	 */
	public static function get_breadcrumbs( $args ) {
		$output = [];

		if ( ! empty( $args['home_label'] ) ) {
			$output['home_title'] = '<a href="'. esc_url( $args['home_url'] ) .'">' . $args['home_label'] . '</a>';
		}

		if ( class_exists( 'WooCommerce' ) && is_shop() ) {
			$output['current_title'] = woocommerce_page_title( false );
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata( $author );

			$output['current_title'] = $userdata->display_name;
		} elseif ( is_archive() ) {
			if ( is_date() ) {
				if ( ! empty( $args['archive_format'] ) && strpos( $args['archive_format'], '%s' ) ) {
					$output['current_title'] = str_replace( '%s', get_the_date(), $args['archive_format'] );
				} else {
					$output['current_title'] = get_the_date();
				}
			} elseif ( is_year() ) {
				if ( ! empty( $args['archive_format'] ) && strpos( $args['archive_format'], '%s' ) ) {
					$output['current_title'] = str_replace( '%s', get_the_time( 'Y' ), $args['archive_format'] );
				} else {
					$output['current_title'] = get_the_time( 'Y' );
				}
			} elseif ( is_month() ) {
				if ( ! empty( $args['archive_format'] ) && strpos( $args['archive_format'], '%s' ) ) {
					$output['current_title'] = str_replace( '%s', get_the_time( 'M' ), $args['archive_format'] );
				} else {
					$output['current_title'] = get_the_time( 'M' );
				}
			} else {
				$category = get_queried_object();
				
				// Check For All Parents
				if ( ! empty ( get_ancestors( $category->term_id, $category->taxonomy ) ) ) {
					$parents = get_ancestors( $category->term_id, $category->taxonomy );
					$parents = array_reverse( $parents );

					foreach ( $parents as $key => $parent ) {
						$output['parent_' . $key] = '<span class="m-neuron-breadcrumbs__item"><a href='.  esc_url( get_term_link( $parent, $category->taxonomy ) ) . '>' . get_term( $parent, $category->taxonomy)->name . '</a></span>';
					}
				}

				if ( ! empty ( $args['archive_format'] ) && strpos( $args['archive_format'], '%s' ) ) {
					$output['current_title'] = str_replace( '%s', single_term_title( '', false ), $args['archive_format'] );
				} else {
					$output['current_title'] = single_term_title( '', false );
				}
			}
		} elseif ( is_search() ) {
			if ( ! empty ( $args['search_results_format'] ) && strpos( $args['search_results_format'], '%s' ) !== false ) {
				$output['current_title'] = str_replace( '%s', get_search_query(), $args['search_results_format'] );
			} else {
				$output['current_title'] = get_search_query();
			}
		} elseif ( is_page() ) {
			global $post;

			if ( ! empty( get_post_ancestors( $post->term_id ) ) ) {

				$parents = get_post_ancestors( $post->term_id );
				$parents = array_reverse( $parents );

				foreach ( $parents as $parent ) {
					$output['parent_' . $parent] = '<span class="m-neuron-breadcrumbs__item"><a href='.  esc_url( get_permalink( $parent ) ) . '>' . get_the_title( $parent ) . '</a></span>';
				}
			}

			$output['current_title'] = get_the_title();
		} elseif ( is_404() ) { 
			if ( ! empty ( $args['404_format'] ) ) {
				$output['current_title'] = $args['404_format'];
			}
		}
		else {
			$output['current_title'] = get_the_title();
		}

		// Wrap
		if ( isset( $output['home_title'] ) ) {
			$output['home_title'] = '<span class="m-neuron-breadcrumbs__item">' . $output['home_title'] . '</span>';
		}

		if ( isset( $output['parent'] ) ) {
			$output['parent'] = '<span class="m-neuron-breadcrumbs__item">' . $output['parent'] . '</span>';
		}

		if ( isset( $output['current_title'] ) ) {
			$output['current_title'] = '<span class="m-neuron-breadcrumbs__item" aria-current="page">' . $output['current_title'] . '</span>';
		}
		
		return $output;
	}

	/**
	 * Initial Animations
	 * 
	 * @since 1.0.0
	 */
	public static function get_entrance_animations() {
		$animations = Animations::entrance_animations();

		return apply_filters( 'neuron/utils/entrance_animations', $animations );
	}

	/**
	 * Hover Animations
	 * 
	 * @since 1.0.0
	 */
	public static function get_hover_animations() {
		$animations = [
			'none' => __( 'None', 'neuron-builder' ),
			'translate' => __( 'Translate', 'neuron-builder' ),
			'scale' => __( 'Scale', 'neuron-builder' ),
			'tooltip' => __( 'Tooltip', 'neuron-builder' ),
			'fixed' => __( 'Fixed', 'neuron-builder' ),
		];

		return apply_filters( 'neuron/utils/hover_animations', $animations );
	}

	/**
	 * Elementor Templates
	 * 
	 * Get All Elementor Templates
	 * 
	 * @since 1.0.0
	 */
	public static function get_elementor_templates() {
		$output = [];

		$args = [
			'sort_column'    => 'post_title',
			'post_type'      => 'elementor_library',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => [
				[
					'key'   => '_elementor_template_type',
					'value' => 'section',
				],
			],
		];

		$templates = get_posts($args);
		
		if ( $templates ) {
			foreach ( $templates as $template ) {
				$output[ $template->ID ] = $template->post_title;
			}
		}

		return $output;
	}

	/**
	 * Build Elementor Template
	 * 
	 * @since 1.0.0
	 */
	public static function build_elementor_template( $id, $css_print = true ) {
		$template = '';

		// WPML Compatibility
		if ( function_exists( 'icl_object_id' ) ) {
			$id = apply_filters( 'wpml_object_id', $id, 'elementor_library', true );
		}
        
        if ( $id ) {
            $template = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $css_print );
		}
		
		return $template;
	}

	/**
	 * Calculate Reading Time
	 * 
	 * @since 1.0.0
	 */
	public static function get_reading_time() {
		global $post;

		$post_id = $post->ID;
		$words_per_minute = 200;

		$content     = get_the_content( $post_id );
		$count_words = str_word_count( strip_tags( $content ) );
		
		// Get Estimated time
		$minutes = floor( $count_words / $words_per_minute);
		
		// If less than a minute
		if( $minutes < 1 ) {
			$estimated_time = 1;
		}
		
		// If more than a minute
		if( $minutes >= 1 ) {
			$estimated_time = $minutes;
		}

		return apply_filters( 'neuron/utils/reading_time', $estimated_time );
	}

	/**
	 * Custom Position
	 * 
	 * Selectors Dictionary
	 * 
	 * @since 1.0.0
	 */
	public static function get_custom_position_selectors() {
		$selectors_dictionary = [
			'top-left' => 'top: 0; left: 0;',
			'top-center' => 'top: 0; left: 50%; transform: translateX(-50%);',
			'top-right' => 'top: 0; left: 100%; transform: translateX(-100%);',
			'center-left' => 'top: 50%; transform: translateY(-50%); left: 0;',
			'center' => 'top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);',
			'center-right' => 'top: 50%; left: 100%; transform: translateY(-50%) translateX(-100%);',
			'bottom-left' => 'top: 100%; transform: translateY(-100%); left: 0;',
			'bottom-center' => 'top: 100%; left: 50%; transform: translateX(-50%) translateY(-100%);',
			'bottom-right' => 'top: 100%; transform: translateX(-100%) translateY(-100%); left: 100%;'
		];

		return apply_filters( 'neuron/utils/custom_position_selectors', $selectors_dictionary );
	}
}
