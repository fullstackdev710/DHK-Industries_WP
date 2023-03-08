<?php
/**
 * Products Class
 * 
 * Extends the class Posts
 * 
 * @since 1.0.0
 */

namespace Neuron\Core\Helpers;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

use Neuron\Core\Helpers\Products as Products;

class Products_Related extends Products {

    public function __construct( $thisElement ) {
        // Content
        $this->register_section( $thisElement, 'layout' );
        $this->register_section( $thisElement, 'query_metro', [ 'layout' => 'metro' ] );

        // Style
        $this->register_section( $thisElement, 'layout_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'box_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'image_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'navigation_style', ['carousel' => 'yes', 'navigation!' => 'none'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'sale_flash_style', ['carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'add_to_cart_style', ['add_to_cart' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'quick_view_style', ['quick_view' => 'yes'], 'TAB_STYLE' );
        
        if ( defined( 'YITH_WCWL' ) ) {
            $this->register_section( $thisElement, 'wishlist_style', [ 'wishlist' => 'yes' ], 'TAB_STYLE' );
        }
    }
}
