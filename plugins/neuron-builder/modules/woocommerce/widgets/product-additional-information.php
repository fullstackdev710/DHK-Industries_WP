<?php
/**
 * Product Additional Information
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Additional_Information extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-product-additional-information';
	}

	public function get_title() {
		return __( 'Additional Information', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-info neuron-badge';
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_additional_info_style', [
			'label' => __( 'General', 'neuron-builder' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control(
			'hide_heading',
			[
				'label' => __( 'Hide Heading', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'render_type' => 'ui',
				'return_value' => 'hide',
				'default' => 'hide',
                'prefix_class' => 'woo-additional-title-',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'hide_heading!' => 'hide',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '.woocommerce {{WRAPPER}} h2',
				'condition' => [
					'hide_heading!' => 'hide',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .shop_attributes p' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( 'single-product/tabs/additional-information.php' );
	}

	public function render_plain_content() {}
}
