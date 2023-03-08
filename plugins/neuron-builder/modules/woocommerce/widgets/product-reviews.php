<?php
/**
 * Product Reviews
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Reviews extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-product-reviews';
	}

	public function get_title() {
		return __( 'Product Reviews', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-review neuron-badge';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'product review', 'review', 'ratings' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_review_general',
			[
				'label' => __( 'General', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .woocommerce-Reviews-title',
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Reviews-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_review_content',
			[
				'label' => __( 'Content', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_heading',
			[
				'label' => __( 'Image', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ol.commentlist li img.avatar' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .comment-text' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ol.commentlist .comment-text' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} li img.avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'stars_heading',
			[
				'label' => __( 'Stars', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'stars_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 40,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} li .star-rating' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'stars_filled',
			[
				'label' => __( 'Stars Filled', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating span::before' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'stars_unfilled',
			[
				'label' => __( 'Stars Unfilled', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating::before' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'stars_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'name_heading',
			[
				'label' => __( 'Name', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '.woocommerce {{WRAPPER}} #reviews #comments ol.commentlist li .comment-text .meta .woocommerce-review__author',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review__author' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'name_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review__author' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		// Date
		$this->add_control(
			'date_heading',
			[
				'label' => __( 'Date', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '.woocommerce {{WRAPPER}} #reviews #comments ol.commentlist li .comment-text .meta .woocommerce-review__published-date',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review__published-date' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'date_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review__published-date' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		// Review
		$this->add_control(
			'review_heading',
			[
				'label' => __( 'Review', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'review_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '.woocommerce {{WRAPPER}} #reviews #comments ol.commentlist li .comment-text p',
			]
		);

		$this->add_control(
			'review_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .description' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_product_review_form',
			[
				'label' => __( 'Form', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Row Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} form > p:not(.form-submit)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .comment-form-rating' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'label_heading',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} label',
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} label' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} label' => 'color: {{VALUE}} !important',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_review_field',
			[
				'label' => __( 'Field', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_review_field_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'color: {{VALUE}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_review_field_typography',
				'selector' => '.woocommerce {{WRAPPER}} #commentform input:not(.submit), woocommerce {{WRAPPER}} #review_form #respond textarea',
			]
		);

		$this->add_control(
			'product_review_field_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'background-color: {{VALUE}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_review_field_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'border-color: {{VALUE}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_review_field_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_review_field_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_review_field_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} #commentform input:not(.submit)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.woocommerce {{WRAPPER}} #review_form #respond textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_review_button',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'product_review_button_tabs'
		);

		$this->start_controls_tab(
			'product_review_button_tab_normal',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'product_review_button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_review_button_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_review_button_typography',
				'selector' => '.woocommerce {{WRAPPER}} #respond input#submit',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'product_review_button_border',
				'selector' => '.woocommerce {{WRAPPER}} #respond input#submit',
			]
		);

		$this->add_responsive_control(
			'product_review_button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_review_button_text_padding',
			[
				'label' => __( 'Text Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'product_review_button_tab_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);


		$this->add_control(
			'product_review_button_background_hover_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_review_button_hover_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_review_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} #respond input#submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		comments_template();
	}

	public function render_plain_content() {}
}
