<?php
/**
 * Neuron Functions
 * 
 * Extra functions which enhance
 * the theme by adding custom
 * styles & scrips and features.
 * 
 * @since 1.0.0
 */

/**
 * Portfolio Post Type
 */
if ( ! function_exists( 'neuron_portfolio_post_type' ) ) {
    function neuron_portfolio_post_type( $custom_post = '' ) {
    
        $post_name = $custom_post ? $custom_post : __( 'Portfolio', 'neuron-builder' );
    
        // Post Type
        $labels = array(
            'name'                  => __( $post_name, 'neuron-builder' ),
            'singular_name'         => __( $post_name . ' Item', 'neuron-builder' ),
            'menu_name'             => _x( $post_name, 'admin menu', 'neuron-builder' ),
            'name_admin_bar'        => _x( $post_name . ' Item', 'add new on admin bar', 'neuron-builder' ),
            'add_new'               => __( 'Add New Item', 'neuron-builder' ),
            'add_new_item'          => __( 'Add New ' . $post_name . ' Item', 'neuron-builder' ),
            'new_item'              => __( 'Add New ' . $post_name . ' Item', 'neuron-builder' ),
            'edit_item'             => __( 'Edit ' . $post_name . ' Item', 'neuron-builder' ),
            'view_item'             => __( 'View Item', 'neuron-builder' ),
            'all_items'             => __( 'All ' . $post_name . ' Items', 'neuron-builder' ),
            'search_items'          => __( 'Search ' . $post_name . '', 'neuron-builder' ),
            'parent_item_colon'     => __( 'Parent ' . $post_name . ' Item:', 'neuron-builder' ),
            'not_found'             => __( 'No ' . strtolower( $post_name ) . ' items found', 'neuron-builder' ),
            'not_found_in_trash'    => __( 'No ' . strtolower( $post_name ) . ' items found in trash', 'neuron-builder' ),
            'filter_items_list'     => __( 'Filter ' . strtolower( $post_name ) . ' items list', 'neuron-builder' ),
            'items_list_navigation' => __( $post_name . ' items list navigation', 'neuron-builder' ),
            'items_list'            => __( $post_name . ' items list', 'neuron-builder ' )
        );
    
        $supports = array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'comments',
            'author',
            'custom-fields',
            'revisions',
            'elementor'
        );
    
        $args = array(
            'labels'          => $labels,
            'supports'        => $supports,
            'public'          => true,
            'capability_type' => 'post',
            'rewrite'         => array('slug' => strtolower( $post_name )), // Permalinks format
            'menu_position'   => 5,
            'menu_icon'       => (version_compare( $GLOBALS['wp_version'], '3.8', '>=' )) ? 'dashicons-portfolio' : false,
            'has_archive'     => true,
            'show_in_rest'    => true
        );
        
        register_post_type('portfolio', $args);
    }
}

if ( ! function_exists( 'neuron_portfolio_categories' ) ) {
    /**
     * Portfolio Categories
     * 
     * @since 1.0.0
     */
    function neuron_portfolio_categories($custom_post = '' ) {

        $post_name = $custom_post ? $custom_post : __( 'Portfolio', 'neuron-builder' );

        $labels = array(
            'name'                       => __( $post_name . ' Categories', 'neuron-builder' ),
            'singular_name'              => __( $post_name . ' Category', 'neuron-builder' ),
            'menu_name'                  => __( $post_name . ' Categories', 'neuron-builder' ),
            'edit_item'                  => __( 'Edit ' . $post_name . ' Category', 'neuron-builder' ),
            'update_item'                => __( 'Update ' . $post_name . ' Category', 'neuron-builder' ),
            'add_new_item'               => __( 'Add New ' . $post_name . ' Category', 'neuron-builder' ),
            'new_item_name'              => __( 'New ' . $post_name . ' Category Name', 'neuron-builder' ),
            'parent_item'                => __( 'Parent ' . $post_name . ' Category', 'neuron-builder' ),
            'parent_item_colon'          => __( 'Parent ' . $post_name . ' Category:', 'neuron-builder' ),
            'all_items'                  => __( 'All ' . $post_name . ' Categories', 'neuron-builder' ),
            'search_items'               => __( 'Search ' . $post_name . ' Categories', 'neuron-builder' ),
            'popular_items'              => __( 'Popular ' . $post_name . ' Categories', 'neuron-builder' ),
            'separate_items_with_commas' => __( 'Separate ' . strtolower( $post_name ) . ' categories with commas', 'neuron-builder' ),
            'add_or_remove_items'        => __( 'Add or remove ' . strtolower( $post_name ) . ' categories', 'neuron-builder' ),
            'choose_from_most_used'      => __( 'Choose from the most used ' . strtolower( $post_name ) . ' categories', 'neuron-builder' ),
            'not_found'                  => __( 'No ' . strtolower( $post_name ) . ' categories found.', 'neuron-builder' ),
            'items_list_navigation'      => __( $post_name . ' categories list navigation', 'neuron-builder' ),
            'items_list'                 => __( $post_name . ' categories list', 'neuron-builder' ),
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_tagcloud'     => true,
            'hierarchical'      => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => strtolower( $post_name ) . '_category' ),
            'show_admin_column' => true,
            'query_var'         => true,
        );

        register_taxonomy( 'portfolio_category', 'portfolio', $args );
    }
}

if ( ! function_exists( 'neuron_portfolio_tags' ) ) {
    /**
     * Portfolio Tags
     * 
     * @since 1.0.0
     */
    function neuron_portfolio_tags( $custom_post = '' ) {

        $post_name = $custom_post ? $custom_post : __( 'Portfolio', 'neuron-builder' );

        $labels = array(
            'name'                       => __( $post_name . ' Tags', 'neuron-builder' ),
            'singular_name'              => __( $post_name . ' Tag', 'neuron-builder' ),
            'menu_name'                  => __( $post_name . ' Tags', 'neuron-builder' ),
            'edit_item'                  => __( 'Edit ' . $post_name . ' Tag', 'neuron-builder' ),
            'update_item'                => __( 'Update ' . $post_name . ' Tag', 'neuron-builder' ),
            'add_new_item'               => __( 'Add New ' . $post_name . ' Tag', 'neuron-builder' ),
            'new_item_name'              => __( 'New ' . $post_name . ' Tag Name', 'neuron-builder' ),
            'parent_item'                => __( 'Parent ' . $post_name . ' Tag', 'neuron-builder' ),
            'parent_item_colon'          => __( 'Parent ' . $post_name . ' Tag:', 'neuron-builder' ),
            'all_items'                  => __( 'All ' . $post_name . ' Tags', 'neuron-builder' ),
            'search_items'               => __( 'Search ' . $post_name . ' Tags', 'neuron-builder' ),
            'popular_items'              => __( 'Popular ' . $post_name . ' Tags', 'neuron-builder' ),
            'separate_items_with_commas' => __( 'Separate ' . strtolower( $post_name ) . ' tags with commas', 'neuron-builder' ),
            'add_or_remove_items'        => __( 'Add or remove ' . strtolower( $post_name ) . ' tags', 'neuron-builder' ),
            'choose_from_most_used'      => __( 'Choose from the most used ' . strtolower( $post_name ) . ' tags', 'neuron-builder' ),
            'not_found'                  => __( 'No ' . strtolower( $post_name ) . ' tags found.', 'neuron-builder' ),
            'items_list_navigation'      => __( $post_name . ' tags list navigation', 'neuron-builder' ),
            'items_list'                 => __( $post_name . ' tags list', 'neuron-builder' ),
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_tagcloud'     => true,
            'hierarchical'      => true,
            'show_in_rest'    => true,
            'rewrite'           => array( 'slug' => strtolower( $post_name ) . '_tags' ),
            'show_admin_column' => true,
            'query_var'         => true,
        );

        register_taxonomy( 'portfolio_tag', 'portfolio', $args );
    }
}


if ( ! function_exists( 'neuron_after_switch_theme_elementor' ) ) {
    /**
     * Custom Post Types
     * 
     * Enable post types by default.
     * 
     * @since 1.0.0
     */
    function neuron_after_switch_theme_elementor() {

        // CPT Support
        $neuron_cpt_support = get_option( 'elementor_cpt_support' );
        
        if ( ! $neuron_cpt_support ) {
            $neuron_cpt_support = ['page', 'post', 'portfolio'];
            if ( class_exists( 'WooCommerce' ) ) {
                $neuron_cpt_support[] = 'product';
            }
            update_option( 'elementor_cpt_support', $neuron_cpt_support );
        } elseif ( ! in_array( 'portfolio', $neuron_cpt_support ) ) {
            $neuron_cpt_support[] = 'portfolio';
            update_option( 'elementor_cpt_support', $neuron_cpt_support );
        }

        // Remove elementor tracker
        if ( ! get_option( 'elementor_tracker_notice' ) ) {
            update_option( 'elementor_tracker_notice', 1 );
        }
    }
}
add_action( 'after_switch_theme', 'neuron_after_switch_theme_elementor' );


if ( ! function_exists( 'neuron_comments_open' ) ) {
    function neuron_comments_open( $comment, $comments_args, $comment_depth ) {
        switch ( $comment_depth ) {
            case 1:
                $comment_class = "col-md-12";
                break;
            case 2:
                $comment_class = "col-md-11 offset-md-1";
                break;
            case 3:
                $comment_class = "col-md-10 offset-md-2";
                break;
            case 4:
            default:
                $comment_class = "col-md-9 offset-md-3";
                break;
        }

        if ( $comment->comment_type == 'pingback' ) {
            $comment_class .= " o-comment--no-avatar";
        }
    ?>
    <div class="o-comment <?php echo esc_attr( $comment_class ) ?>" id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
        <?php if( $comment->comment_type != 'pingback' ) : ?>
            <div class="o-comment__avatar">
                <?php echo get_avatar( $comment, 70 ) ?>
            </div>
        <?php endif; ?>
        <div class="o-comment__details">
            <div class="o-comment__author-meta d-flex align-items-center">
                <h5 class="o-comment__author-meta-title">
                    <?php echo esc_html( $comment->comment_author ) ?>
                </h5>

                <div class="ml-auto">
                    <?php
                        /**
                         * Reply Link
                         */
                        comment_reply_link(
                            array_merge(
                                $comments_args,
                                array(
                                    'reply_text' => esc_attr__( 'reply', 'neuron-builder' ),
                                    'depth' => $comment_depth,
                                    'max_depth' => $comments_args['max_depth'],
                                )
                            ),
                            $comment
                        );
                    ?>
                </div>
            </div>
            <div class="o-comment__date">
                <?php comment_date( get_option( 'date_format' ) ) ?>
                <?php comment_date( get_option( 'time_format' ) ) ?>
            </div>
            <div class="o-comment__content">
                <?php comment_text(); ?>
            </div>
        </div>
    </div>
    <?php
    }
}

if ( ! function_exists( 'neuron_comments_close' ) ) {
    function neuron_comments_close() {}
}

if ( ! function_exists( 'neuron_menu_custom_fields' ) ) {
    /**
     * Custom Nav Menu Fields
     * 
     * Adds the mega menu 
     * options for the items.
     * 
     * @since 1.0.0
     */
    function neuron_menu_custom_fields( $item_id, $item, $depth ) {
    
        wp_nonce_field( 'neuron_mega_menu_nonce', '_neuron_mega_menu_nonce_name' );
        $neuron_mega_menu = get_post_meta( $item_id, '_neuron_mega_menu', true ) ? get_post_meta( $item_id, '_neuron_mega_menu', true ) : 'default-value';
        
        $options = \Neuron\Core\Utils::get_elementor_templates();

        if ( $options && $depth == 0 ) :
        ?>
        <div class="field-neuron_mega_menu description-wide" style="margin: 5px 0;">
            <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />

            <?php if ( $neuron_mega_menu != 'default-value' && $depth != 0 ) : ?>
                <p><?php _e( 'Mega Menu works only in the parent item.', 'neuron-builder' ) ?></p>
            <?php endif; ?>
    
            <div class="logged-input-holder">
                <label for="custom-menu-meta-for-<?php echo $item_id ;?>">
                    <?php _e( 'Mega Menu', 'custom-menu-meta'); ?>
                </label>
                <select name="neuron_mega_menu[<?php echo $item_id ;?>]" id="custom-menu-meta-for-<?php echo $item_id ;?>" value="<?php echo esc_attr( $neuron_mega_menu ); ?>">
                    <option value="" <?php echo $neuron_mega_menu == 'default-value' ? 'selected' : '' ?> ><?php echo esc_attr__( 'Select Template', 'neuron-builder' ) ?></option>
                    <?php foreach ( $options as $key => $value ) : ?>
                        <option <?php echo $key == $neuron_mega_menu ? 'selected' : '' ?> value="<?php echo esc_attr( $key ) ?>"><?php echo esc_attr( $value ) ?></option>
                    <?php endforeach; ?>
                </select>
                
            </div>
    
        </div>
        <?php
        endif;
    }
}
add_action( 'wp_nav_menu_item_custom_fields', 'neuron_menu_custom_fields', 10, 3 );

if ( ! function_exists( 'neuron_menu_update' ) ) {
    /**
     * Update Custom Nav Options
     * 
     * @since 1.0.0
     */
    function neuron_menu_update( $menu_id, $menu_item_db_id ) {
    
        // Verify this came from our screen and with proper authorization.
        if ( ! isset( $_POST['_neuron_mega_menu_nonce_name'] ) || ! wp_verify_nonce( $_POST['_neuron_mega_menu_nonce_name'], 'neuron_mega_menu_nonce' ) ) {
            return $menu_id;
        }
    
        if ( isset( $_POST['neuron_mega_menu'][$menu_item_db_id]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST['neuron_mega_menu'][$menu_item_db_id] );
            update_post_meta( $menu_item_db_id, '_neuron_mega_menu', $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_neuron_mega_menu' );
        }
    }
}
add_action( 'wp_update_nav_menu_item', 'neuron_menu_update', 10, 2 );

if ( ! function_exists( 'neuron_mega_menu_class' ) ) {
    /**
     * Mega Menu Class
     * 
     * @since 1.0.0
     */
    function neuron_mega_menu_class( $classes, $item, $depth ) {

        if ( is_object( $item ) && isset( $item->ID ) ) {

            $mega_menu = get_post_meta( $item->ID, '_neuron_mega_menu', true );
            $parent = $depth !== 0;
            $has_child = ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes );

    
            if ( ! empty( $mega_menu ) && $parent && ! $has_child ) {
                $classes[] = 'm-neuron-nav-menu--mega-menu__item';
            }
        }

        return $classes;
    }
}
add_filter( 'nav_menu_css_class', 'neuron_mega_menu_class', 10, 3 );

if ( ! function_exists( 'neuron_mega_menu_item' ) ) {
    function neuron_mega_menu_item( $item_output, $item, $depth, $args ) {
        if ( is_object( $item ) && isset( $item->ID ) ) {

            $mega_menu = get_post_meta( $item->ID, '_neuron_mega_menu', true );
            $parent = $depth !== 0;
            $has_child = ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes );

            if ( ! empty( $mega_menu ) && ! $parent && ! $has_child ) {
                $item_output .= ' <div class="m-neuron-nav-menu--mega-menu" data-id="'. $item->ID .'">' . \Neuron\Core\Utils::build_elementor_template( $mega_menu, false )  . '</div>';
            }
        }

        return $item_output;
    }
}
add_filter( 'walker_nav_menu_start_el', 'neuron_mega_menu_item', 10, 4 );


if ( ! function_exists( 'neuron_add_li_class' ) ) {
    /**
     * Add class in <li>
     * of menu.
     * 
     * @since 1.0.0
     */
    function neuron_add_li_class( $classes, $item, $args, $depth ) {
        if ( isset( $args->add_li_class ) && $depth === 0 ) {
            $classes[] = $args->add_li_class;
        }

        return $classes;
    }
}
add_filter('nav_menu_css_class', 'neuron_add_li_class', 10, 4);


if ( ! function_exists( 'neuron_woocommerce_single_product_image_thumbnail_html' ) ) {
    /**
     * Product Images
     * 
     * Override links of gallery image
     * html.
     * 
     * @since 1.0.0
     */
    function neuron_woocommerce_single_product_image_thumbnail_html( $html, $post_thumbnail_id ) {
        global $product;

        if ( $product->get_image_id() ) {
			$html = neuron_wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'neuron_woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); 
    }
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'neuron_woocommerce_single_product_image_thumbnail_html', 10, 2 );

if ( ! function_exists( 'neuron_wc_get_gallery_image_html' ) ) {
    function neuron_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
        $flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
        $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
        $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
        $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
        $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
        $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
        $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
        $alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
        $image             = wp_get_attachment_image(
            $attachment_id,
            $image_size,
            false,
            apply_filters(
                'woocommerce_gallery_image_html_attachment_image_params',
                array(
                    'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                    'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                    'data-src'                => esc_url( $full_src[0] ),
                    'data-large_image'        => esc_url( $full_src[0] ),
                    'data-large_image_width'  => esc_attr( $full_src[1] ),
                    'data-large_image_height' => esc_attr( $full_src[2] ),
                    'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
                ),
                $attachment_id,
                $image_size,
                $main_image
            )
        );

        return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="product-slideshow-'. 123 .'" href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
    }
}

/**
 * Allow unfiltered uploads only before 
 * import, disable it then after import.
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'neuron_import_svg' ) ) {
    function neuron_import_svg( $mimes ) {
        if ( apply_filters( 'neuron_disable_svg_support', true ) ) {
            $mimes['svg']   = 'image/svg+xml';
            $mimes['woff']  = 'application/font-woff';
            $mimes['woff2'] = 'font/woff2';
            $mimes['ttf']   = 'application/x-font-ttf';
            $mimes['eot']   = 'application/vnd.ms-fontobject';
        }

        return $mimes;
    }
}

if ( ! function_exists( 'neuron_update_mime_types' ) ) {
    function neuron_update_mime_types() {
        add_filter('upload_mimes', 'neuron_import_svg');
    }
}