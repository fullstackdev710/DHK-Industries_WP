<?php
/**
 * Nav Menu
 * 
 * Display a navigation menu with
 * tons of options to customize.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\NavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Responsive\Responsive;

use Neuron\Plugin;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nav_Menu extends Base_Widget {

	protected $nav_menu_index = 1;

	public function get_name() {
		return 'neuron-nav-menu';
	}

	public function get_title() {
		return __( 'Nav Menu', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-nav-menu neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements', 'neuron-elements-site' ];
	}

	public function get_keywords() {
		return [ 'menu', 'navigation', 'nav', 'nav menu' ];
	}

	public function get_style_depends() {
        return ['font-awesome'];
    } 

	public function on_export( $element ) {
		unset( $element['settings']['menu'] );

		return $element;
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	private function get_all_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'nav_menu_functionality',
			[
				'label' => __('Functionality', 'neuron-builder'),
			]
		);

		$menus = $this->get_all_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label' => __( 'Menu', 'neuron-builder' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus Screen</a> to manage your menus.', 'neuron-builder'), admin_url('nav-menus.php')),
				]
			);
		} else {
			$this->add_control(
				'nav_menu_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(__('<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Screen</a> to create one.', 'neuron-builder'), admin_url('nav-menus.php?action=edit&menu=0')),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'layout',
			[
                'label' => __( 'Layout', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'horizontal' => __( 'Horizontal', 'neuron-builder' ),
					'vertical' => __( 'Vertical', 'neuron-builder' ),
					'mobile-dropdown' => __( 'Mobile Dropdown', 'neuron-builder' ),
				],
				'default' => 'horizontal',
				'prefix_class' => 'm-neuron-nav-menu--'
			]
		);

		$this->add_responsive_control(
			'align_items',
			[
				'label' => __( 'Align', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Stretch', 'neuron-builder' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'm-neuron-nav-menu__align%s-',
				'condition' => [
					'layout!' => 'mobile-dropdown',
				],
			]
		);

		$this->add_control(
			'pointer',
			[
                'label' => __( 'Pointer', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => __( 'None', 'neuron-builder' ),
					'underline' => __( 'Underline', 'neuron-builder' ),
					'strikethrough' => __( 'Strikethrough', 'neuron-builder' ),
					'animated' => __( 'Animated', 'neuron-builder' ),
					'vertical' => __( 'Vertical', 'neuron-builder' ),
					'dot' => __( 'Dot', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-nav-menu__pointer-',
				'render_type' => 'ui',
				'default' => 'underline',
				'condition' => [
					'layout!' => 'mobile-dropdown'
				]
			]
		);

		$this->add_control(
			'pointer_animation',
			[
                'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => __( 'None', 'neuron-builder' ),
					'fade' => __( 'Fade', 'neuron-builder' ),
					'slide' => __( 'Slide', 'neuron-builder' ),
					'grow' => __( 'Grow', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-nav-menu__pointer-animation--',
				'default' => 'fade',
				'condition' => [
					'layout!' => 'mobile-dropdown',
					'pointer!' => ['dot', 'vertical', 'none'],
				]
			]
		);

		$this->add_control(
			'indicator',
			[
				'label' => __( 'Submenu Indicator', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'angle',
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'classic' => __( 'Classic', 'neuron-builder' ),
					'angle' => __( 'Angle', 'neuron-builder' ),
					'plus' => __( 'Plus', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-nav-menu--indicator-',
			]
		);

		$this->add_control(
			'active_scroll',
			[
				'label' => __( 'Active on Scroll', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value' => 'yes',
				'condition' => [
					'layout!' => 'mobile-dropdown'
				] 
			]
		);

		$this->add_control(
			'mobile_heading',
			[
				'label' => __( 'Mobile Dropdown', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout!' => 'mobile-dropdown'
				]
			]
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'breakpoint',
			[
				'label' => __( 'Breakpoint', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'neuron-builder' ), $breakpoints['md'] ),
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'neuron-builder' ), $breakpoints['lg'] ),
				],
				'prefix_class' => 'm-neuron-nav-menu--breakpoint-',
				'default' => 'mobile',
				'condition' => [
					'layout!' => 'mobile-dropdown'
				]
			]
		);

		$this->add_control(
			'full_width',
			[
				'label' => __( 'Full Width', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Stretch the dropdown of the menu to full width.', 'neuron-builder' ),
				'prefix_class' => 'm-neuron-nav-menu--',
				'return_value' => 'stretch',
				'default' => 'stretch'
			]
		);

		$this->add_control(
			'toggle_align',
			[
				'label' => __( 'Toggle Align', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'label_block' => false,
				'prefix_class' => 'm-neuron-nav-menu__toggle-align-'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'main_menu_style',
			[
				'label' => __( 'Main Menu', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'mobile-dropdown'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu .menu-item',
			]
		);

		$this->add_control( 
			'animation', [ 
				'label' => __( 'Initial Animation', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true,
				'render_type' => 'none'
			] 
		);

		$this->start_popover();

        $this->add_responsive_control( 
			'neuron_animations', [ 
				'label' => __( 'Entrance Animation', 'neuron-builder' ),
				'type' => Controls_Manager::ANIMATION,
				'custom_control' => 'add_responsive_control',
				'frontend_available' => true,
			] 
		);

		$this->add_responsive_control( 
			'neuron_animations_duration', [ 
				'label' => __( 'Animation Duration', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'animated',
				'options' => [
					'animated-slow' => __( 'Slow', 'neuron-builder' ),
					'animated' => __( 'Normal', 'neuron-builder' ),
					'animated-fast' => __( 'Fast', 'neuron-builder' ),
				],
				'frontend_available' => true,
				'condition' => [
					'neuron_animations!' => [
						'',
						'none',
						'h-neuron-animation--specialOne', 
						'h-neuron-animation--clipFromLeft', 
						'h-neuron-animation--clipFromRight', 
						'h-neuron-animation--clipUp', 
						'h-neuron-animation--clipBottom'
					], 
				],
			] 
		);

       	$this->add_responsive_control( 
			'animation_delay', [ 
				'label' => __( 'Animation Delay', 'neuron-builder' ) . ' ' . '(ms)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 100,
				'default' => 0,
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [
					'neuron_animations!' => '',
				],
			] 
		);

		$this->add_responsive_control( 
			'animation_delay_reset', [ 
				'label' => __( 'Animation Delay Reset', 'neuron-builder' ) . ' ' . '(ms)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
				'condition' => [
					'neuron_animations!' => '',
					'animation_delay!' => 0,
					'animation_delay!' => 0
				],
				'frontend_available' => true,
				'render_type' => 'UI'
			] 
		);

		$this->end_popover();

		$this->start_controls_tabs( 'menu_style_tabs' );

		$this->start_controls_tab(
			'menu_style_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'menu_text_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item > a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_style_tab_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'menu_text_color_hover',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item:hover > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_bg_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item:hover > a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_pointer_color_hover',
			[
				'label' => __( 'Pointer Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item > a:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pointer!' => 'none'
				]
			]
		);

		$this->add_control(
			'menu_pointer_color_second_hover',
			[
				'label' => __( 'Pointer Second Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item > a:after' => 'background: linear-gradient( 90deg, {{menu_pointer_color_hover.VALUE}}, {{VALUE}} )',
				],
				'of_type' => 'menu_pointer_color_hover',
				'condition' => [
					'pointer' => 'animated'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_style_tab_active',
			[
				'label' => __( 'Active', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'menu_text_color_active',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item.current-menu-item > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_bg_color_active',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item.current-menu-item > a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_pointer_color_active',
			[
				'label' => __('Pointer Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item.current-menu-item > a:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pointer!' => 'none'
				]
			]
		);

		$this->add_control(
			'menu_pointer_color_second_active',
			[
				'label' => __('Pointer Second Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item.current-menu-item > a:after' => 'background: linear-gradient( 90deg, {{menu_pointer_color_active.VALUE}}, {{VALUE}} )',
				],
				'of_type' => 'menu_pointer_color_hover',
				'condition' => [
					'pointer' => 'animated'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_responsive_control(
			'horizontal_padding',
			[
				'label' => __( 'Horizontal Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'vertical_padding',
			[
				'label' => __( 'Vertical Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'selectors' => [
					'{{WRAPPER}}.m-neuron-nav-menu--horizontal .m-neuron-nav-menu > ul > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}:not(.m-neuron-nav-menu--horizontal) .m-neuron-nav-menu > ul > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_item_border',
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item > a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_item_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'menu_item_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul > li.menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'pointer_heading',
			[
				'label' => __( 'Pointer', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'pointer!' => 'none'
				],
			]
		);

		$this->add_responsive_control(
			'pointer_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.m-neuron-nav-menu__pointer-dot) .m-neuron-nav-menu .menu-item > a::after' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.m-neuron-nav-menu__pointer-dot .m-neuron-nav-menu .menu-item > a::after' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pointer!' => 'none'
				],
			]
		);

		$this->add_responsive_control(
			'pointer_distance',
			[
				'label' => __( 'Distance', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu .menu-item > a' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pointer!' => 'none'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dropdown_section',
			[
				'label' => __( 'Dropdown', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu--mobile ul li a, {{WRAPPER}} .m-neuron-nav-menu .sub-menu li a',
			]
		);

		$this->start_controls_tabs( 'dropdown_tabs_style' );

		$this->start_controls_tab(
			'dropdown_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_responsive_control(
			'dropdown_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_tab_hover',
			[
				'label' => __('Hover', 'neuron-builder'),
			]
		);

		$this->add_responsive_control(
			'dropdown_text_color_hover',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul li:hover a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu li:hover a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'dropdown_background_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul li:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu li:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_tab_active',
			[
				'label' => __('Active', 'neuron-builder'),
			]
		);

		$this->add_responsive_control(
			'dropdown_text_color_active',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul li.current-menu-item a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu li.current-menu-item a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_background_color_active',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile ul li.current-menu-item' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-nav-menu .sub-menu li.current-menu-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs(); // dropdown_tabs_style


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_border',
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu--mobile > ul, {{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile > ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'dropdown_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu--mobile > ul, {{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu',
			]
		);

		$this->add_responsive_control(
			'padding_horizontal_dropdown_item',
			[
				'label' => __( 'Horizontal Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile > ul li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',

			]
		);

		$this->add_responsive_control(
			'padding_vertical_dropdown_item',
			[
				'label' => __( 'Vertical Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile > ul li' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu li' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_dropdown_divider',
			[
				'label' => __( 'Divider', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_divider',
				'selector' => '{{WRAPPER}} .m-neuron-nav-menu--mobile > ul li:not(:last-child), {{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu li:not(:last-child)',
				'exclude' => [ 'width' ],
			]
		);

		$this->add_control(
			'dropdown_divider_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile > ul li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'dropdown_divider_border!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_top_distance',
			[
				'label' => __( 'Distance', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu, {{WRAPPER}} .m-neuron-nav-menu--mobile > ul' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > ul .sub-menu:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-nav-menu--mega-menu__item .m-neuron-nav-menu--mega-menu' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dropdown_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu--mobile > .m-neuron-nav-menu__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-nav-menu > .m-neuron-nav-menu__list li > .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // Dropdown Section

		$this->start_controls_section(
			'toggle_button_section',
			[
				'label' => __( 'Toggle Button', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'toggle_button_tabs' );

		$this->start_controls_tab(
			'toggle_button_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger svg line' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_button_tab_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'toggle_color_hover',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger:hover svg line' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_bg_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger:hover svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_control(
			'toggle_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger svg' => 'width: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger svg' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'toggle_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'size_units' => [ 'px', '%' ],
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-nav-menu__hamburger svg' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // Toggle Butotn Section

	}

	protected function render() {
		$available_menus = $this->get_all_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		$args = [
			'echo' => false,
			'menu' => $settings['menu'],
			'menu_class' => 'm-neuron-nav-menu__list',
			'menu_id' => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
		];

		// Animations
		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$args['add_li_class'] = 'h-neuron-animation--wow';
		}

		$menu_html = wp_nav_menu( $args );

		// Mobile Menu.
		$args['menu_id'] = 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id();
		$args['menu_type'] = 'dropdown';
		$mobile_menu = wp_nav_menu( $args );

		// Remove all our custom filters.
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		
		$this->add_render_attribute( 'main-menu', 'class', [
			'm-neuron-nav-menu',
		] );

		$this->add_render_attribute( 'main-menu', 'id', [
			'm-neuron-nav-menu--id-' . $this->get_nav_menu_index() . '-' . $this->get_id()
		] );

		$this->add_render_attribute( 'mobile-menu', 'class', [
			'm-neuron-nav-menu--mobile',
		] );

		$this->add_render_attribute( 'mobile-menu', 'id', [
			'm-neuron-nav-menu--mobile--id-' . $this->get_id()
		] );

		if ( empty( $menu_html ) ) {
			return;
		}

		if ( $settings['menu'] && $menu_html ) {
			?>
				<nav <?php echo $this->get_render_attribute_string( 'main-menu' ); ?>><?php echo $menu_html ?></nav>
				<nav <?php echo $this->get_render_attribute_string( 'mobile-menu' ); ?>>
					<div class="m-neuron-nav-menu__hamburger-holder">
						<a href="#" class="m-neuron-nav-menu__hamburger">
							<svg style="enable-background:new 0 0 139 139;" version="1.1" viewBox="0 0 139 139" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><line class="st0" x1="26.5" x2="112.5" y1="46.3" y2="46.3"/><line class="st0" x1="26.5" x2="112.5" y1="92.7" y2="92.7"/><line class="st0" x1="26.5" x2="112.5" y1="69.5" y2="69.5"/></svg>
						</a>
					</div>
					<?php echo $mobile_menu ?>
				</nav>
			<?php
		}
	}
}
