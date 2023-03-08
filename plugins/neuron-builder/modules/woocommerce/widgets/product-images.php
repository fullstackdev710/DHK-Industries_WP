<?php
/**
 * Product Images
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Images extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-product-images';
	}

	public function get_title() {
		return __( 'Product Images', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-images neuron-badge';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'image', 'product', 'gallery', 'lightbox' ];
	}

	public function get_script_depends() {
        return [ 'imagesloaded' ];
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_gallery_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sale_flash',
			[
				'label' => __( 'Sale Flash', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'render_type' => 'template',
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_thumbs_style',
			[
				'label' => __( 'Thumbnails', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'width_thumbs',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'spacing_thumbs',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'thumbs_border',
				'selector' => '.woocommerce {{WRAPPER}} .flex-control-thumbs img, .woocommerce {{WRAPPER}} div.product div.images .flex-control-thumbs li img.flex-active',
			]
		);

		$this->add_responsive_control(
			'thumbs_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		if ( 'yes' === $settings['sale_flash'] ) {
			wc_get_template( 'loop/sale-flash.php' );
		}

		wc_get_template( 'single-product/product-image.php' );

		// On render widget from Editor - trigger the init manually.
		$editor = \Neuron\Plugin::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( $is_edit_mode ) {
		?>
		<script>
			jQuery( '.woocommerce-product-gallery' ).imagesLoaded(function() {
				jQuery( '.woocommerce-product-gallery' ).each( function() {
					jQuery( this ).wc_product_gallery();
				} );
			});
		</script>
		<?php
		}
	}
}
