<?php
/**
 * Flip Box
 * 
 * Catchy flipping element which has
 * two sides and in hover shows two 
 * different contents.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\FlipBox\Widgets;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Elementor\Controls_Manager;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Flip_Box extends Base_Widget {

	public function get_name() {
		return 'neuron-flip-box';
	}

	public function get_title() {
		return __( 'Flip Box', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-flip-box neuron-badge';
	}

	public function get_keywords() {
		return [ 'flip box', 'flip', 'box', 'rotate' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'front_section', [
				'label' => __( 'Front', 'neuron-builder' )
			]
		);

		$this->start_controls_tabs( 'front_tabs' );

		$this->start_controls_tab(
			'front_content_tab',
			[
				'label' => __( 'Content', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'graphic_element',
			[
				'label' => __( 'Graphic Element', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'neuron-builder' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => __( 'Image', 'neuron-builder' ),
						'icon' => 'fa fa-picture-o',
					],
					'icon' => [
						'title' => __( 'Icon', 'neuron-builder' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'icon',
				'toggle' => true,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'neuron-builder' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'graphic_element' => 'image',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => __( 'View', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default'  => __( 'Default', 'neuron-builder' ),
					'stacked' => __( 'Stacked', 'neuron-builder' ),
					'framed' => __( 'Framed', 'neuron-builder' ),
				],
				'default' => 'default',
				'condition' => [
					'graphic_element' => 'icon',
					'selected_icon[value]!' => ''
				]
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => __( 'Shape', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'neuron-builder' ),
					'square' => __( 'Square', 'neuron-builder' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
					'graphic_element' => 'icon',
					'selected_icon[value]!' => ''
				],
			]
		);

		$this->add_control(
			'title_front',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is the heading', 'neuron-builder' ),
				'placeholder' => __( 'Enter your heading', 'neuron-builder' ),
				'separator' => 'before',
				'label_block' => true
			]
		);

		$this->add_control(
			'description_front',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit dolor sit amet ipsum.', 'neuron-builder', 'neuron-builder' ),
				'placeholder' => __( 'Enter your description', 'neuron-builder' ),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'front_background_tab',
			[
				'label' => __( 'Background', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_background',
				'label' => __( 'Background', 'neuron-builder' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__overlay',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Front Section

		$this->start_controls_section(
			'back_section', [
				'label' => __( 'Back', 'neuron-builder' )
			]
		);

		$this->start_controls_tabs( 'back_tabs' );

		$this->start_controls_tab(
			'back_content_tab',
			[
				'label' => __( 'Content', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'title_back',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is the heading', 'neuron-builder' ),
				'placeholder' => __( 'Enter your heading', 'neuron-builder' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'description_back',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit dolor sit amet ipsum.', 'neuron-builder', 'neuron-builder' ),
				'placeholder' => __( 'Enter your description', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'neuron-builder' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://neuronthemes.com', 'neuron-builder' ),
				'show_external' => true,
				'condition' => [
					'button_text!' => ''
				]
			]
		);

		$this->add_control(
			'link_click',
			[
				'label' => __( 'Apply Link On', 'neuron-builder ' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'box' => __( 'Whole Box', 'neuron-builder ' ),
					'button' => __( 'Button Only', 'neuron-builder ' ),
				],
				'default' => 'button',
				'condition' => [
					'link[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'back_background_tab',
			[
				'label' => __( 'Background', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_background',
				'label' => __( 'Background', 'neuron-builder' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__overlay',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Back Section

		$this->start_controls_section(
			'settings_section', [
				'label' => __( 'Settings', 'neuron-builder' )
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'flip_effect',
			[
				'label' => __( 'Flip Effect', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
					'flip' => 'Flip',
					'slide' => 'Slide',
					'push' => 'Push',
					'zoom-in' => 'Zoom In',
					'zoom-out' => 'Zoom Out',
					'fade' => 'Fade',
				],
				'prefix_class' => 'm-neuron-flip-box--effect-',
			]
		);

		$this->add_control(
			'flip_direction',
			[
				'label' => __( 'Flip Direction', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'up',
				'options' => [
					'left' => __( 'Left', 'neuron-builder' ),
					'right' => __( 'Right', 'neuron-builder' ),
					'up' => __( 'Up', 'neuron-builder' ),
					'down' => __( 'Down', 'neuron-builder' ),
				],
				'condition' => [
					'flip_effect!' => [
						'fade',
						'zoom-in',
						'zoom-out',
					],
				],
				'prefix_class' => 'm-neuron-flip-box--direction-',
			]
		);

		$this->add_control(
			'flip_3d',
			[
				'label' => __( '3D Depth', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'neuron-builder' ),
				'label_off' => __( 'Off', 'neuron-builder' ),
				'return_value' => 'm-neuron-flip-box--3d',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'flip_effect' => 'flip',
				],
			]
		);

		$this->end_controls_section(); // End Settings Section

		$this->start_controls_section(
			'front_style_section', [
				'label' => __( 'Front', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'front_alignment',
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
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_vertical_position',
			[
				'label' => __( 'Vertical Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'neuron-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'neuron-builder' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'neuron-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'toggle' => true,
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__overlay' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'front_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--front',
			]
		);

		$this->add_control(
			'image_heading',
			[
				'label' => __( 'Image', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'graphic_element' => 'image'
				]
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'image'
				]
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => __( 'Size', 'neuron-builder' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'condition' => [
					'graphic_element' => 'image'
				]
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__image' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'graphic_element' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => __( 'Primary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'graphic_element' => 'icon'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-stacked .a-neuron-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-stacked .a-neuron-icon svg' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-framed .a-neuron-icon, {{WRAPPER}} .m-neuron-flip-box__icon--view-default .a-neuron-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-framed .a-neuron-icon svg, {{WRAPPER}} .m-neuron-flip-box__icon--view-default .a-neuron-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => __( 'Secondary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-framed .a-neuron-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-framed .a-neuron-icon svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-stacked .a-neuron-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-flip-box__icon--view-stacked .a-neuron-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon .a-neuron-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon'
				]
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon .a-neuron-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => __( 'Rotate', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .m-neuron-flip-box__icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'graphic_element' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .a-neuron-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'front_title_heading',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_title_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_title_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__title',
			]
		);

		$this->add_control(
			'front_description_heading',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_description_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--front .m-neuron-flip-box__description',
			]
		);

		$this->end_controls_section(); // End Front Style Section

		$this->start_controls_section(
			'back_style_section', [
				'label' => __( 'Back', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'back_alignment',
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
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_vertical_position',
			[
				'label' => __( 'Vertical Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'neuron-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'neuron-builder' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'neuron-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'toggle' => true,
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__overlay' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--back',
			]
		);


		$this->add_control(
			'back_title_heading',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_title_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_title_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__title',
			]
		);

		$this->add_control(
			'back_description_heading',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_description_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__item--back .m-neuron-flip-box__description',
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'neuron-builder' ),
					'sm' => __( 'Small', 'neuron-builder' ),
					'md' => __( 'Medium', 'neuron-builder' ),
					'lg' => __( 'Large', 'neuron-builder' ),
					'xl' => __( 'Extra Large', 'neuron-builder' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-flip-box__button',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text_hover_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_hover_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-flip-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Back Style Section
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-regular',
			];
		}
		return [];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$wrapper_tag = 'div';
		$button_tag = 'a';
		$migration_allowed = Icons_Manager::is_migration_allowed();
		$this->add_render_attribute( 'button', 'class', [
			'm-neuron-flip-box__button',
			'a-neuron-button',
			'h-neuron-size--' . $settings['button_size'],
		] );

		$this->add_render_attribute( 'wrapper', 'class', 'm-neuron-flip-box__item m-neuron-flip-box__item--back' );

		// Button
		if ( ! empty( $settings['link']['url'] ) ) {
			$link_element = 'button';

			if ( $settings['link_click'] == 'box' ) {
				$wrapper_tag = 'a';
				$button_tag = 'button';
				$link_element = 'wrapper';
			}

			$this->add_render_attribute( $link_element, 'href', $settings['link']['url'] );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( $link_element, 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( $link_element, 'rel', 'nofollow' );
			}
		}

		// Icon
		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( $settings['graphic_element'] == 'icon' ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'm-neuron-flip-box__icon' );
			$this->add_render_attribute( 'icon-wrapper', 'class', 'm-neuron-flip-box__icon--view-' . $settings['icon_view'] );

			if ( $settings['icon_view'] != 'default' ) {
				$this->add_render_attribute( 'icon-wrapper', 'class', 'm-neuron-flip-box__icon--shape-' . $settings['icon_shape'] );
			}

			if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) {
				$settings['icon'] = 'fa fa-star';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}

		$has_icon = ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon'] );
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && $migration_allowed;
		?>
		<div class="m-neuron-flip-box">
			<div class="m-neuron-flip-box__item m-neuron-flip-box__item--front">
				<div class="m-neuron-flip-box__overlay">
					<div class="m-neuron-flip-box__inner">
						<?php if ( $settings['graphic_element'] == 'image' ) : ?>
							<div class="m-neuron-flip-box__image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
							</div>
						<?php elseif ( $settings['graphic_element'] == 'icon' ) : ?>
							<div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
								<div class="a-neuron-icon">
									<?php if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $settings['selected_icon'] );
									else : ?>
										<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['title_front'] ) ) : ?>
							<h3 class="m-neuron-flip-box__title"><?php echo esc_attr( $settings['title_front'] ) ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['description_front'] ) ) : ?>
							<div class="m-neuron-flip-box__description"><?php echo esc_attr( $settings['description_front'] ) ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div class="m-neuron-flip-box__overlay">
					<div class="m-neuron-flip-box__inner">
						<?php if ( ! empty( $settings['title_back'] ) ) : ?>
							<h3 class="m-neuron-flip-box__title"><?php echo esc_attr( $settings['title_back'] ) ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['description_back'] ) ) : ?>
							<div class="m-neuron-flip-box__description"><?php echo esc_attr( $settings['description_back'] ) ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['button_text'] ) ) : ?>
							<<?php echo $button_tag; ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
								<?php echo $settings['button_text']; ?>
							</<?php echo $button_tag; ?>>
						<?php endif; ?>
					</div>
				</div>
			</<?php echo $wrapper_tag; ?>>
		</div>
		<?php
    }

    protected function content_template() {
		?>
		<# 
		var btnClasses = 'm-neuron-flip-box__button a-neuron-button h-neuron-size--' + settings.button_size;

		<!-- Image -->
		if ( settings.graphic_element === 'image' && settings.image.url !== '' ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var imageURL = elementor.imagesManager.getImageUrl( image );
		}

		<!-- Button -->
		var wrapperTag = 'div',
		buttonTag = 'a';

		if ( settings.link_click === 'box' ) {
			wrapperTag = 'a';
			buttonTag = 'button';
		}

		<!-- Icon -->
		if ( 'icon' === settings.graphic_element ) {
			var iconWrapperClasses = 'm-neuron-flip-box__icon';
			iconWrapperClasses += ' m-neuron-flip-box__icon--view-' + settings.icon_view;
			if ( 'default' !== settings.icon_view ) {
				iconWrapperClasses += ' m-neuron-flip-box__icon--shape-' + settings.icon_shape;
			}
		}
		var hasIcon = settings.icon || settings.selected_icon,
			iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
		#>
		<div class="m-neuron-flip-box">
			<div class="m-neuron-flip-box__item m-neuron-flip-box__item--front">
				<div class="m-neuron-flip-box__overlay">
					<div class="m-neuron-flip-box__inner">
						<# if ( settings.graphic_element === 'image' && settings.image.url !== '' ) { #>
							<div class="m-neuron-flip-box__image">
								<img src="{{imageURL}}">
							</div>
						<# } else if ( settings.graphic_element == 'icon' ) { #>
							<div class="{{ iconWrapperClasses }}">
								<div class="a-neuron-icon">
									<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
										{{{ iconHTML.value }}}
									<# } else { #>
										<i class="{{ settings.icon }}" aria-hidden="true"></i>
									<# } #>
								</div>
							</div>
						<# } #>

						<# if ( settings.title_front ) { #>
							<h3 class="m-neuron-flip-box__title">{{{ settings.title_front }}}</h3>
						<# } #>

						<# if ( settings.description_front ) { #>
							<div class="m-neuron-flip-box__description">{{{ settings.description_front }}}</div>
						<# } #>
					</div>
				</div>
			</div>
			<{{ wrapperTag }} class="m-neuron-flip-box__item m-neuron-flip-box__item--back">
				<div class="m-neuron-flip-box__overlay">
					<div class="m-neuron-flip-box__inner">
						<# if ( settings.title_back ) { #>
							<h3 class="m-neuron-flip-box__title">{{{ settings.title_back }}}</h3>
						<# } #>

						<# if ( settings.description_back ) { #>
							<div class="m-neuron-flip-box__description">{{{ settings.description_back }}}</div>
						<# } #>

						<# if ( settings.button_text ) { #>
							<{{ buttonTag }} href="#" class="{{ btnClasses }}">{{{ settings.button_text }}}</{{ buttonTag }}>
						<# } #>
					</div>
				</div>
			</{{ wrapperTag }}>
		</div>
		<?php
	}
}
