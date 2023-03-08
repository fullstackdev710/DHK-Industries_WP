<?php
namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Menu_Cart extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-menu-cart';
	}

	public function get_title() {
		return __( 'Menu Cart', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-cart-light neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-site', 'neuron-woo-elements' ];
	}

	public function get_style_depends() {
        return ['font-awesome'];
	} 

	protected function register_controls() {

		$this->start_controls_section(
			'section_menu_cart_content',
			[
				'label' => __( 'Menu Cart', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'sidebar' => __( 'Sidebar', 'neuron-builder' ),
					'hover' => __( 'Hover', 'neuron-builder' ),
				],
				'default' => 'sidebar',
				'prefix_class' => 'm-neuron-menu-cart--style-',
				'frontend_available' => true,
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cart-neuron' => __( 'Default', 'neuron-builder' ),
					'cart-light' => __( 'Cart', 'neuron-builder' ) . ' ' . __( 'Light', 'neuron-builder' ),
					'cart-medium' => __( 'Cart', 'neuron-builder' ) . ' ' . __( 'Medium', 'neuron-builder' ),
					'cart-solid' => __( 'Cart', 'neuron-builder' ) . ' ' . __( 'Solid', 'neuron-builder' ),
					'basket-light' => __( 'Basket', 'neuron-builder' ) . ' ' . __( 'Light', 'neuron-builder' ),
					'basket-medium' => __( 'Basket', 'neuron-builder' ) . ' ' . __( 'Medium', 'neuron-builder' ),
					'basket-solid' => __( 'Basket', 'neuron-builder' ) . ' ' . __( 'Solid', 'neuron-builder' ),
					'bag-light' => __( 'Bag', 'neuron-builder' ) . ' ' . __( 'Light', 'neuron-builder' ),
					'bag-medium' => __( 'Bag', 'neuron-builder' ) . ' ' . __( 'Medium', 'neuron-builder' ),
					'bag-solid' => __( 'Bag', 'neuron-builder' ) . ' ' . __( 'Solid', 'neuron-builder' ),
				],
				'default' => 'cart-neuron',
				'prefix_class' => 'm-neuron-menu-cart__toggle-icon--',
			]
		);

		$this->add_control(
			'items_indicator',
			[
				'label' => __( 'Items Indicator', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'bubble' => __( 'Bubble', 'neuron-builder' ),
					'plain' => __( 'Plain', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-menu-cart--items-indicator-',
				'default' => 'bubble',
			]
		);

		$this->add_control(
			'hide_empty_indicator',
			[
				'label' => __( 'Hide Empty', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'hide',
				'prefix_class' => 'm-neuron-menu-cart--empty-indicator-',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
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
					'{{WRAPPER}} .m-neuron-menu-cart' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => __( 'Menu Cart', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => __( 'Normal', 'neuron-builder' ) ] );

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => __( 'Icon Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => __( 'Hover', 'neuron-builder' ) ] );

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'items_indicator_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Items Indicator', 'neuron-builder' ),
				'separator' => 'before',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);
		$this->add_control(
			'items_indicator_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle[data-counter]:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'items_indicator_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle[data-counter]:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->add_control(
			'items_indicator_distance',
			[
				'label' => __( 'Distance', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__toggle[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Cart', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Divider', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'm-neuron-menu-cart--show-divider-',
			]
		);

		$this->add_control(
			'show_remove_icon',
			[
				'label' => __( 'Remove Item Icon', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'm-neuron-menu-cart--show-remove-button-',
			]
		);

		$this->add_control(
			'heading_subtotal_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Subtotal', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtotal_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__subtotal',
			]
		);


		$this->add_control(
			'heading_close_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Close Button', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__close-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'close_button_typography',
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__close-button',
			]
		);

		$this->add_control(
			'close_button_position',
			[
				'label' => __( 'Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__close-button' => 'right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => __( 'Products', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_product_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Title', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product-name, {{WRAPPER}} .m-neuron-menu-cart__product-name a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__product-name, {{WRAPPER}} .m-neuron-menu-cart__product-name a',
			]
		);

		$this->add_control(
			'heading_product_price_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Price', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product-price' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__product-price',
			]
		);

		$this->add_control(
			'heading_product_remove_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Remove', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_remove_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product-remove:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_remove_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product-remove' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_remove_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%' ],
				'default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product-remove' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_product_divider_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Divider', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'neuron-builder' ),
					'solid' => __( 'Solid', 'neuron-builder' ),
					'double' => __( 'Double', 'neuron-builder' ),
					'dotted' => __( 'Dotted', 'neuron-builder' ),
					'dashed' => __( 'Dashed', 'neuron-builder' ),
					'groove' => __( 'Groove', 'neuron-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product, {{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'border-top-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product, {{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label' => __( 'Weight', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product, {{WRAPPER}} .m-neuron-menu-cart__products, {{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-menu-cart__product:first-child, {{WRAPPER}} .m-neuron-menu-cart__subtotal' => 'border-top-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_gap',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__product' => 'padding-bottom: {{SIZE}}{{UNIT}}; padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => __( 'Buttons', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_layout',
			[
				'label' => __( 'Layout', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline' => __( 'Inline', 'neuron-builder' ),
					'stacked' => __( 'Stacked', 'neuron-builder' ),
				],
				'default' => 'inline',
				'prefix_class' => 'm-neuron-menu-cart--buttons-',
			]
		);

		$this->add_control(
			'space_between_buttons',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_buttons_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__footer-buttons .a-neuron-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__footer-buttons .a-neuron-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_cart_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'View Cart', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_button_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .a-neuron-button--view-cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .a-neuron-button--view-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .a-neuron-button--view-cart',
			]
		);

		$this->add_control(
			'heading_checkout_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Checkout', 'neuron-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .a-neuron-button--checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .a-neuron-button--checkout' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .a-neuron-button--checkout',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_sidebar',
			[
				'label' => __( 'Sidebar', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'sidebar'
				] 
			] 
		);

		$this->add_control(
			'sidebar_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__sidebar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-menu-cart__bottom' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'overlay_bg_color',
			[
				'label' => __( 'Overlay Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__overlay' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sidebar_box_shadow',
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-menu-cart__sidebar',
			]
		);

		$this->add_control(
			'sidebar_height',
			[
				'label' => __( 'Height', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'full' => __( 'Fit To Screen', 'neuron-builder' ),
					'min-height' => __( 'Min Height', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-menu-cart--style-sidebar__height-',
			]
		);

		$this->add_responsive_control(
			'sidebar_custom_height',
			[
				'label' => __( 'Min Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => 'vh'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-menu-cart__sidebar' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sidebar_height' => [ 'min-height' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		Module::render_menu_cart();
	}

	public function render_plain_content() {}
}
