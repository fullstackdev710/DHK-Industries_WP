<?php
/**
 * WooCommerce Breadcrumbs
 * 
 * Displays the woocommerce
 * breadcrumb via the function
 * woocommerce_breadcrumb()
 * 
 * https://docs.woocommerce.com/document/woocommerce_breadcrumb/
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Breadcrumb extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-breadcrumb';
	}

	public function get_title() {
		return __( 'WooCommerce Breadcrumbs', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-breadcrumbs neuron-badge';
	}

	public function get_keywords() {
		return [ 'breadcrumbs', 'woocommerce', 'shop', 'store', 'internal links', 'product' ];
	}

	public function get_categories() {
		return [ 'neuron-woo-elements', 'neuron-woo-elements-single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_breadcrumb_content',
			[
				'label' => __( 'Content', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'breadcrumb_delimiter',
			[
				'label' => __( 'Delimiter', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => ' / ',
			]
		);

		$this->add_control(
			'breadcrumb_before',
			[
				'label' => __( 'Before', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'breadcrumb_front_page',
			[
				'label' => __( 'Front Page', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_breadcrumb_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .woocommerce-breadcrumb',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'wrap_before' => '<nav class="woocommerce-breadcrumb">',
			'wrap_after' => '</nav>'
		];

		if ( $settings['breadcrumb_delimiter'] ) {
			$args['delimiter'] = $settings['breadcrumb_delimiter'];
		}

		if ( $settings['breadcrumb_before'] ) {
			$args['wrap_before'] = $args['wrap_before'] . $settings['breadcrumb_before'];
		}

		if ( $settings['breadcrumb_front_page'] != 'yes' ) {
			$args['home'] = false;
		}

		woocommerce_breadcrumb( $args );
	}

	public function render_plain_content() {}
}
