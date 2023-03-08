<?php
/**
 * Neuron Functions
 * 
 * Bunch of functions which improve & help the theme
 * to work better and keep the code more organised.
 * 
 * @since 1.0.0
 */

/**
 * Open and close container tags
 * 
 * Simply opens and closes the container tags,
 * theme uses it and finds helpful on files
 * that does not contain html tags.
 * 
 * @since 1.0.0
 */
add_action( 'n-theme/open/container', 'archzilla_open_container' );
add_action( 'n-theme/close/container', 'archzilla_close_container' );

if ( ! function_exists( 'archzilla_open_container' ) ) {
	function archzilla_open_container( $custom_class = '' ) {
		if ( $custom_class ) {
			$class = $custom_class;
		} else {
			$class = 'n-container';
		}
	?>
		<div class="<?php echo esc_attr( $class ) ?>">
	<?php
	}
}

if ( ! function_exists( 'archzilla_close_container' ) ) {
	function archzilla_close_container() {
	?>
		</div>
	<?php
	}
}

if ( ! function_exists( 'archzilla_pagination' ) ) {
	/**
	 * Default Pagination
	 * 
	 * Well organised pagination with numbers and arrows,
	 * theme uses it on blogs and portfolios.
	 */
	function archzilla_pagination( $query = '' ) {
		global $paged;

		$archzilla_range = 10;
		$archzilla_pages = '';
		$archzilla_showitems = ( $archzilla_range * 2) + 1;

		if ( empty( $paged ) ) {
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}
		}

		if ( $archzilla_pages == '' ) {
			global $wp_query;
			if ( $query ) {
				$archzilla_pages = $query->max_num_pages;
			} else {
				$archzilla_pages = $wp_query->max_num_pages;
			}

			if ( ! $archzilla_pages ) {
				$archzilla_pages = 1;
			}
		}

		if ( 1 != $archzilla_pages ) {
			echo "<div class='n-site-pagination'>";

			$archzilla_prev_class = 'n-site-pagination__arrow';
			$archzilla_next_class = 'n-site-pagination__arrow n-site-pagination__arrow--right';
			if ( $paged <= 1 ) {
				$archzilla_prev_class = 'n-site-pagination__arrow n-site-pagination__arrow--disabled';
			} 

			$link = "href=". esc_url( get_pagenum_link( $paged - 1 ) ) ."";

			if ( $paged == 1 ) {
				$link = '';
			}

			echo "<div class='". $archzilla_prev_class ."'><a class=\"neurontheme-link\" ". $link ."><svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' viewBox='0 0 50 50' fill='none'><path d='M22 30L22.6667 29.3333L18.8067 25.4733L32.5267 25.4733L32.5267 24.5266L18.8067 24.5267L22.6667 20.6666L22 20L17 25L22 30Z' fill='black'></path><rect x='49.5' y='49.5' width='49' height='49' rx='24.5' transform='rotate(-180 49.5 49.5)' stroke='black'></rect></svg></a></div>";

			echo "<ul class='n-site-pagination__numbers'>";
			for ( $i = 1; $i <= $archzilla_pages; $i++ ) {
				if ( 1 != $archzilla_pages && (!( $i >= $paged + $archzilla_range + 1 || $i <= $paged - $archzilla_range - 1) || $archzilla_pages <= $archzilla_showitems ) ) {
					if ( $paged == $i ) {
						echo "<li class=\"active\"><a>". $i ."</a></li>";
					} else {
						echo "<li><a href='". get_pagenum_link( $i) ."' class=\"inactive neurontheme-link\">". $i ."</a></li>";
					}
				}
			}

			$archzilla_pages_float = intval( $archzilla_pages );
			echo "</ul>";


			if ( $paged == $archzilla_pages_float ) {
				$archzilla_next_class = 'n-site-pagination__arrow n-site-pagination__arrow--disabled';
			}

			echo "<div class='". esc_attr( $archzilla_next_class ) ."'><a class=\"neurontheme-link\" href='". esc_url( get_pagenum_link( $paged + 1) ) ."'><svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' viewBox='0 0 50 50' fill='none'><path d='M22 30L22.6667 29.3333L18.8067 25.4733L32.5267 25.4733L32.5267 24.5266L18.8067 24.5267L22.6667 20.6666L22 20L17 25L22 30Z' fill='black'></path><rect x='49.5' y='49.5' width='49' height='49' rx='24.5' transform='rotate(-180 49.5 49.5)' stroke='black'></rect></svg></a></div>";

			echo "</div>\n";
		}
	}
}

/**
 * Modify wp_link_pages to show 
 */
add_filter( 'wp_link_pages_args', 'archzilla_wp_link_pages_args_prevnext_add' );

if ( ! function_exists( 'archzilla_wp_link_pages_args_prevnext_add' ) ) {
	function archzilla_wp_link_pages_args_prevnext_add( $args ) {
		global $page, $numpages, $more, $pagenow;
		
		if ( ! $args['next_or_number'] == 'next_and_number' ) {
			return $args; 
		} 

		$args['next_or_number'] = 'number'; 

		if ( ! $more ) {
			return $args; 
		}
		return $args;
	}
}

add_action( 'wp_ajax_update_wordpress_option', 'archzilla_update_wordpress_option' );
add_action( 'wp_ajax_nopriv_update_wordpress_option', 'archzilla_update_wordpress_option' );

function archzilla_update_wordpress_option() {
    $option = $_POST['option'];

	if ( ! empty( $option ) ) {

		if ( isset( $option['siteLogo'] ) && ! empty( $option['siteLogo'] ) ) {
			set_theme_mod( 'custom_logo', esc_url( $option['siteLogo'] ) );
			update_option( 'custom_logo', esc_url( $option['siteLogo'] ) );
			set_theme_mod( 'site_logo', esc_url( $option['siteLogo'] ) );
		}

		if ( isset( $option['siteTitle'] ) && ! empty( $option['siteTitle'] ) ) {
			update_option( 'blogname', esc_attr( $option['siteTitle'] )  );
		}

		if ( isset( $option['siteTagline'] ) && ! empty( $option['siteTagline'] ) ) {
			update_option( 'blogdescription', esc_attr( $option['siteTagline'] )  );
		}
	}

    die();
}

add_action( 'wp_ajax_wizard_file_upload', 'archzilla_file_upload_callback' );
add_action( 'wp_ajax_nopriv_wizard_file_upload', 'archzilla_file_upload_callback' );

function archzilla_file_upload_callback() {
	$arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

    if ( in_array( $_FILES['file']['type'], $arr_img_ext ) ) {
		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
			
		// Let WordPress handle the upload.
		// Remember, 'my_image_upload' is the name of our file input in our form above.
		$attachment_id = media_handle_upload( 'file', $_POST['post_id'] );
			
		if ( ! is_wp_error( $attachment_id ) ) {
			wp_send_json( $attachment_id );
		} 
    }

    wp_die();
}

/**
 * Fetch Remote URL
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'archzilla_fetch_remote_file' ) ) {
	function archzilla_fetch_remote_file( $url ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '', get_the_date() );

		if ( $upload['error'] ) {
			return new WP_Error( 'upload_dir_error', $upload['error'] );
		}

		// fetch the remote url and write it to the placeholder file
		$response = wp_remote_get( $url, array(
			'stream' => true,
			'filename' => $upload['file'],
		) );

		// request failed
		if ( is_wp_error( $response ) ) {
			unlink( $upload['file'] );
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		// make sure the fetch was successful
		if ( $code !== 200 ) {
			unlink( $upload['file'] );
			return new WP_Error(
				'import_file_error',
				sprintf(
					__( 'Remote server returned %1$d %2$s for %3$s', 'archzilla' ),
					$code,
					get_status_header_desc( $code ),
					$url
				)
			);
		}

		return $upload;
	}
}
