<?php
/**
 * Testimonial Carousel
 * 
 * Showcase your testimonials
 * in a more appealing way.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\TestimonialCarousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Testimonial_Carousel extends Base_Widget {

	public function get_name() {
		return 'neuron-testimonial-carousel';
	}

	public function get_title() {
		return __( 'Testimonial Carousel', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel neuron-badge';
	}

	public function get_keywords() {
		return [ 'testimonial', 'carousel', 'slides' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'slides_section',
			[
				'label' => __('Slides', 'neuron-builder'),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
            'content',
            [
                'label' => __('Content', 'neuron-builder'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
				'dynamic' => [
					'active' => true
				]
            ]
		);
		
		$repeater->add_control(
            'image',
            [
                'label' => __('Image', 'neuron-builder'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => get_template_directory_uri() . '/assets/images/placeholder.png'
				],
				'dynamic' => [
					'active' => true
				]
            ]
        );
		
		$repeater->add_control(
            'name',
            [
				'label' => __('Name', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => __('John Doe', 'neuron-builder'),
				'dynamic' => [
					'active' => true
				]
            ]
        );
		
		$repeater->add_control(
            'title',
            [
				'label' => __('Title', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Developer', 'neuron-builder'),
				'dynamic' => [
					'active' => true
				]
            ]
        );

		$this->add_control(
            'slides',
            [
                'label' => __('Slides', 'neuron-builder'),
                'description' => __('Add slides to the testimonial carousel.', 'neuron-builder'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
				'default' => [
					[
						'content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'name' => __('John Doe', 'neuron-builder'),
						'title' => __('Developer', 'neuron-builder'),
						'image' => [
							'url' => NEURON_BUILDER_PLACEHOLDER
						],
					],
					[
						'content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'name' => __('Chris Parker', 'neuron-builder'),
						'title' => __('Designer', 'neuron-builder'),
						'image' => [
							'url' => NEURON_BUILDER_PLACEHOLDER
						],
					],
					[
						'content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'name' => __('Peter Jack', 'neuron-builder'),
						'title' => __('CEO', 'neuron-builder'),
						'image' => [
							'url' => NEURON_BUILDER_PLACEHOLDER
						],
					],
				],
            ]
		);

		$this->add_control(
			'layout',
			[
                'label' => __('Layout', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image-inline' => __('Image Inline', 'neuron-builder'),
					'image-stacked' => __('Image Stacked', 'neuron-builder'),
					'image-above' => __('Image Above', 'neuron-builder'),
					'image-left' => __('Image Left', 'neuron-builder'),
					'image-right' => __('Image Right', 'neuron-builder'),
				],
				'default' => 'image-inline',
				'render_type' => 'template',
				'prefix_class' => 'l-neuron-testimonial--'
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'label' => __( 'Slides Per View', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'auto' => __( 'Auto', 'neuron-builder' ),
				],
				'default' => '1',
				'tablet_default' => '1',
            	'mobile_default' => '1',
				'frontend_available' => true
			]
		);

		$this->add_responsive_control(
			'slide_custom_width',
			[
				'label' => __( 'Custom Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'condition' => [
					'slides_per_view' => 'auto',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label' => __( 'Slides To Scroll', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				],
				'default' => '1',
				'frontend_available' => true
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __('Alignment', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __('Left', 'neuron-builder'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'neuron-builder'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'neuron-builder'),
						'icon' => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'l-neuron-testimonial--alignment__'
			]
		);


		$this->add_responsive_control(
			'width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __('Width', 'neuron-builder'),
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1300,
					],
					'%' => [
						'min' => 50,
					],
				],
				'size_units' => ['%', 'px'],
				'default' => [
					'size' => 95,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings_section', [
                'label' => __( 'Slider Settings', 'neuron-builder' )
			]
		);

		$this->add_control('navigation', [
            'label' => __( 'Navigation', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'neuron-builder' ),
                'arrows-dots' => __( 'Arrows & Dots', 'neuron-builder' ),
                'arrows' => __( 'Arrows', 'neuron-builder' ),
                'dots' => __( 'Dots', 'neuron-builder' ),
            ],
			'default' => 'arrows-dots',
			'frontend_available' => true
        ]);
		
		$this->add_control('infinite', [
            'label' => __( 'Infinite Loop', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'return_value' => 'yes',
            'default' => 'yes'
		]);
		
		$this->add_control('pause_on_hover', [
            'label' => __( 'Pause on Hover', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'yes'
		]);
		
		$this->add_control(
			'centered_slides',
			[
				'label' => __( 'Centered Slides', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'no',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);

		$this->add_control(
			'keyboard_navigation',
			[
				'label' => __( 'Keyboard Navigation', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);
		
		$this->add_control('autoplay', [
            'label' => __( 'Autoplay', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'no'
		]);

        $this->add_control('autoplay_speed', [
            'label' => __( 'Auto Play Speed', 'neuron-builder' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5000,
            'step' => 15,
			'default' => 3000,
			'frontend_available' => true,
			'condition' => [
				'autoplay' => 'yes'
			]
		]);
		
		$this->add_control(
			'animation', 
			[
				'label' => __( 'Initial Animation', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => 'yes'
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'neuron_animations', 
			[
				'label' => __( 'Entrance Animation', 'neuron-builder' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'default' => 'h-neuron-animation--slideUp',
			]
		);

		$this->add_responsive_control(
			'animation_delay',
			[
				'label' => __( 'Animation Delay', 'neuron-builder' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'frontend_available' => true,
				'condition' => [
					'neuron_animations!' => ''
				]
			]
		);

		$this->add_responsive_control(
			'animation_delay_reset',
			[
				'label' => __( 'Animation Delay Reset', 'neuron-builder' ) . ' ' . '(ms)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 100,
				'default' => 300,
				'condition' => [
					'neuron_animations!' => '',
					'animation_delay!' => 0,
				],
				'frontend_available' => true,
				'render_type' => 'UI'
			]
		);

		$this->end_popover();

		$this->add_control(
			'transition',
			[
				'label' => __( 'Transition', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'neuron-builder' ),
					'fade' => __( 'Fade', 'neuron-builder' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => __( 'Transition Speed', 'neuron-builder' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'desktop_default' => [
					'size' => 30,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'frontend_available' => true,
            	'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'carousel_overflow',
			[
				'label' => __( 'Overflow', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hidden' => __( 'Hidden', 'neuron-builder' ),
					'visible' => __( 'Visible', 'neuron-builder' ),
					'visible-right' => __( 'Visible Right', 'neuron-builder' ),
					'visible-left' => __( 'Visible Left', 'neuron-builder' ),
				],
				'default' => 'hidden',
				'tablet_default' => 'hidden',
				'mobile_default' => 'hidden',
				'prefix_class' => 'neuron-swiper--overflow-',
			]
		);
		
        $this->end_controls_section(); // Slider Settings Section End

		$this->start_controls_section(
			'slides_section_style',
			[
				'label' => __('Slides', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label' => __('Background Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_type',
			[
                'label' => __('Border Type', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __('None', 'neuron-builder'),
                    'solid' => __('Solid', 'neuron-builder'),
                    'double' => __('Double', 'neuron-builder'),
                    'dotted' => __('Dotted', 'neuron-builder'),
                    'dashed' => __('Dashed', 'neuron-builder'),
                    'groove' => __('Groove', 'neuron-builder')
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'border-style: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'slide_border_size',
			[
				'label' => __('Border Size', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'slide_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label' => __('Border Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'slide_border_type!' => 'none'
				]
			]
		);


		$this->add_responsive_control(
			'slide_border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'slide_border_type!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'slide_padding',
			[
				'label' => __('Padding', 'neuron-builder'),
				'size_units' => ['px', 'rem', '%'],
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __('Content', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __('Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.l-neuron-testimonial--image-inline .m-neuron-testimonial__content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.l-neuron-testimonial--image-stacked .m-neuron-testimonial__content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.l-neuron-testimonial--image-right .m-neuron-testimonial__content' => 'padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.l-neuron-testimonial--image-left .m-neuron-testimonial__content' => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.l-neuron-testimonial--image-above .m-neuron-testimonial__content' => 'margin-top: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-testimonial__text',
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label' => __('Name', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-testimonial__name',
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __('Title', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-testimonial__title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __('Image', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __('Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__image' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_control(
			'image_border_size',
			[
				'label' => __('Border Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__image img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_border_type',
			[
                'label' => __('Border Type', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __('None', 'neuron-builder'),
                    'solid' => __('Solid', 'neuron-builder'),
                    'double' => __('Double', 'neuron-builder'),
                    'dotted' => __('Dotted', 'neuron-builder'),
                    'dashed' => __('Dashed', 'neuron-builder'),
                    'groove' => __('Groove', 'neuron-builder')
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__image img' => 'border-style: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => __('Border Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-testimonial__image img' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->register_navigation_style_controls();

		$this->start_controls_section(
			'section_quote_style',
			[
				'label' => __('Quote', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( 
			'show_quote', 
			[
				'label' => __( 'Show Quote', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
			]
		);
			
		$this->add_control(
			'quote',
			[
				'label' => __( 'Quote', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'quote_icon',
				'default' => [
					'value' => 'fas fa-quote-left',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_quote' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'quote_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __('Size', 'neuron-builder'),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-quote' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_quote' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'quote_padding',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __('Padding', 'neuron-builder'),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-quote' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2 + 10px ); line-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_quote' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .neuron-swiper-quote',
				'condition' => [
					'show_quote' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'quote_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __('Border Radius', 'neuron-builder'),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-quote' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_quote' => 'yes'
				],
			]
		);

		$this->end_controls_section(); 
	}

	protected function register_navigation_style_controls() {

		$this->start_controls_section( 
			'navigation_style_section', [ 
				'label' => __( 'Navigation', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation!' => 'none'
				]
			] 
		);

		$this->add_control(
			'arrows_heading', [
				'label' => __('Arrows', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => ['arrows', 'arrows-dots']
				]
			]	
		);

		$this->add_control(
			'arrows_icon', [
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
				'recommended' => [
					'fa-solid' => [
						'chevron-left',
						'caret-left',
						'arrow-left',
						'angle-left',
						'chevron-circle-left',
						'caret-square-left',
						'arrow-circle-left',
						'angle-double-left',
						'long-arrow-alt-left',
						'arrow-alt-circle-left',
						'hand-point-left',
					]
				],
				'condition' => [
					'navigation' => ['arrows', 'arrows-dots']
				]
			]	
		);

		$this->add_responsive_control(
			'arrows_size', [
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .neuron-icon' => 'font-size: {{SIZE}}{{UNIT}}'
				],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'condition' => [
					'arrows_icon[value]!' => '',
					'navigation' => ['arrows', 'arrows-dots']
				]
			]	
		);

		$this->add_control(
			'arrows_color', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button .neuron-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => ['arrows', 'arrows-dots']
				]
			]	
		);

		$this->add_control(
			'arrows_position', [
				'label' => __( 'Position', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'arrows_icon[value]!' => '',
					'navigation' => ['arrows', 'arrows-dots']
				]
			]	
		);

		$this->start_popover();

		$this->add_control(
			'arrows_left_heading', [
				'label' => __( 'Left Arrow', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->add_responsive_control(
			'neuron_left_arrow_alignment', [
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top-left' => [
						'title' => __( 'Top Left', 'neuron-builder' ),
					],
					'top-center' => [
						'title' => __( 'Top Center', 'neuron-builder' ),
					],
					'top-right' => [
						'title' => __( 'Top Right', 'neuron-builder' ),
					],
					'center-left' => [
						'title' => __( 'Center Left', 'neuron-builder' ),
					],
					'center' => [
						'title' => __( 'Center Center', 'neuron-builder' ),
					],
					'center-right' => [
						'title' => __( 'Center Right', 'neuron-builder' ),
					],
					'bottom-left' => [
						'title' => __( 'Bottom Left', 'neuron-builder' ),
					],
					'bottom-center' => [
						'title' => __( 'Bottom Center', 'neuron-builder' ),
					],
					'bottom-right' => [
						'title' => __( 'Bottom Right', 'neuron-builder' ),
					],
				],
				'selectors_dictionary' => Utils::get_custom_position_selectors(),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--prev' => '{{VALUE}}'
				],
				'default' => 'center-left',
				'toggle' => false,
			]	
		);

		$this->add_responsive_control(
			'left_arrow_x_position', [
				'label' => __( 'X Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--prev' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->add_responsive_control(
			'left_arrow_y_position', [
				'label' => __( 'Y Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--prev' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->add_control(
			'arrows_right_heading_separator', [
				'type' => Controls_Manager::DIVIDER,
			]	
		);

		$this->add_control(
			'arrows_right_heading', [
				'label' => __( 'Right Arrow', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->add_responsive_control(
			'neuron_right_arrow_alignment', [
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top-left' => [
						'title' => __( 'Top Left', 'neuron-builder' ),
					],
					'top-center' => [
						'title' => __( 'Top Center', 'neuron-builder' ),
					],
					'top-right' => [
						'title' => __( 'Top Right', 'neuron-builder' ),
					],
					'center-left' => [
						'title' => __( 'Center Left', 'neuron-builder' ),
					],
					'center' => [
						'title' => __( 'Center Center', 'neuron-builder' ),
					],
					'center-right' => [
						'title' => __( 'Center Right', 'neuron-builder' ),
					],
					'bottom-left' => [
						'title' => __( 'Bottom Left', 'neuron-builder' ),
					],
					'bottom-center' => [
						'title' => __( 'Bottom Center', 'neuron-builder' ),
					],
					'bottom-right' => [
						'title' => __( 'Bottom Right', 'neuron-builder' ),
					],
				],
				'default' => 'center-right',
				'toggle' => false,
				'selectors_dictionary' => Utils::get_custom_position_selectors(),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--next' => '{{VALUE}}'
				],
			]	
		);

		$this->add_responsive_control(
			'right_arrow_x_position', [
				'label' => __( 'X Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--next' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->add_responsive_control(
			'right_arrow_y_position', [
				'label' => __( 'Y Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-button--next' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'arrows_position' => 'yes'
				]
			]	
		);

		$this->end_popover();

		$this->add_control(
			'hr_dots_top', [
				'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'navigation' => 'arrows-dots'
				]
			]	
		);
		
		$this->add_control(
			'dots_heading', [
				'label' => __('Dots', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ]
				]
			]	
		);
		
		$this->add_control(
			'dots_style', [
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'bullets' => __( 'Bullets', 'neuron-builder' ),
					'numbers' => __( 'Numbers', 'neuron-builder' ),
					'fraction' => __( 'Fraction', 'neuron-builder' ),
					'scrollbar' => __( 'Scrollbar', 'neuron-builder' ),
					'progressbar' => __( 'Progress', 'neuron-builder' ),
				],
				'default' => 'bullets',
				'frontend_available' => true,
				'render_type' => 'template',
				'prefix_class' => 'neuron-dots--style__',
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ]
				]
			]	
		);
		
		$this->add_control(
			'dots_bar_position', [
				'label' => __( 'Bar Position', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'neuron-builder' ),
					'bottom' => __( 'Bottom', 'neuron-builder' ),
				],
				'default' => 'bottom',
				'prefix_class' => 'neuron-dots--bar-position__',
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style' => [ 'progressbar', 'scrollbar' ],
				]
			]	
		);

		$this->add_control(
			'dots_fraction_divider', [
				'label' => __( 'Fraction', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '/',
				'frontend_available' => true,
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style' => 'fraction'
				]
			]	
		);

		$this->add_control(
			'dots_orientation', [
				'label' => __( 'Orientation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => __( 'Horizontal', 'neuron-builder' ),
					'vertical' => __( 'Vertical', 'neuron-builder' ),
				],
				'default' => 'horizontal',
				'prefix_class' => 'neuron-dots--orientation__',
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style!' => [ 'scrollbar', 'progressbar' ]
				]
			]	
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'label' => __( 'Typography', 'neuron-builder' ),
				'name' => 'dots_typography',
				'selector' => '{{WRAPPER}} .neuron-swiper-dots',
				'exclude' => [ 'font-size' ],
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style' => [ 'numbers', 'fraction' ]
				]	
			]
		);

		$this->add_responsive_control(
			'dots_size', [
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 40
					]
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--style__scrollbar .swiper-scrollbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--style__progressbar .swiper-pagination' => 'height: {{SIZE}}{{UNIT}}'
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ]
				]
			]	
		);

		$this->add_responsive_control(
			'dots_gap', [
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100
					]
				],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}}.neuron-dots--bar-position__bottom .swiper-scrollbar, {{WRAPPER}}.neuron-dots--bar-position__bottom .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--bar-position__top .swiper-scrollbar, {{WRAPPER}}.neuron-dots--bar-position__top .swiper-pagination' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style' => [ 'scrollbar', 'progressbar' ]
				]
			]	
		);

		$this->add_responsive_control(
			'dots_space_between', [
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}.neuron-dots--orientation__horizontal .neuron-swiper-dots .swiper-pagination-bullet:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--orientation__vertical .neuron-swiper-dots .swiper-pagination-bullet:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				],
				'default' => [
					'size' => 10,
					'unit' => 'px'
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows-dots' ],
					'dots_style!' => [ 'scrollbar', 'progressbar' ]
				]
			]	
		);

		$this->add_control(
			'dots_position', [
				'label' => __( 'Position', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'dots_style!' => [ 'scrollbar', 'progressbar' ],
					'navigation' => [ 'dots', 'arrows-dots' ]
				]
			]	
		);

		$this->start_popover();

		$this->add_responsive_control(
			'neuron_dots_alignment', [
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'custom_control' => 'add_responsive_control',
				'options' => [
					'top-left' => [
						'title' => __( 'Top Left', 'neuron-builder' ),
					],
					'top-center' => [
						'title' => __( 'Top Center', 'neuron-builder' ),
					],
					'top-right' => [
						'title' => __( 'Top Right', 'neuron-builder' ),
					],
					'center-left' => [
						'title' => __( 'Center Left', 'neuron-builder' ),
					],
					'center' => [
						'title' => __( 'Center Center', 'neuron-builder' ),
					],
					'center-right' => [
						'title' => __( 'Center Right', 'neuron-builder' ),
					],
					'bottom-left' => [
						'title' => __( 'Bottom Left', 'neuron-builder' ),
					],
					'bottom-center' => [
						'title' => __( 'Bottom Center', 'neuron-builder' ),
					],
					'bottom-right' => [
						'title' => __( 'Bottom Right', 'neuron-builder' ),
					],
				],
				'default' => 'bottom-center',
				'toggle' => false,
				'selectors_dictionary' => Utils::get_custom_position_selectors(),
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots' => '{{VALUE}}'
				]
			]	
		);

		$this->add_responsive_control(
			'dots_x_position', [
				'label' => __( 'X Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--position__top-right .neuron-swiper-dots' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--position__center-right .neuron-swiper-dots' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.neuron-dots--position__bottom-right .neuron-swiper-dots' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]	
		);

		$this->add_responsive_control(
			'dots_y_position', [
				'label' => __( 'Y Position', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'rem' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 3,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]	
		);

		$this->end_popover();

		$this->start_controls_tabs( 'dots_tabs', [
			'condition' => [
				'navigation' => [ 'dots', 'arrows-dots' ],
			]
		] );

		$this->start_controls_tab( 'dots_tabs_normal', [
			'label' => __( 'Normal', 'neuron-builder' )
		] );

		$this->add_control(
			'dots_color', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 
					'dots_style' => [ 'numbers', 'fraction' ] 
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet' => 'color: {{VALUE}}',
					'{{WRAPPER}} .neuron-swiper-dots' => 'color: {{VALUE}}',
				],	
			]	
		);

		$this->add_control(
			'dots_bg_color', [
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 'dots_style!' => [ 'numbers', 'fraction' ] ],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.neuron-dots--style__scrollbar .swiper-scrollbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.neuron-dots--style__progressbar .swiper-pagination' => 'background-color: {{VALUE}}',
				],
			]	
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label' => __( 'Border', 'neuron-builder' ),
				'name' => 'dots_border',
				'selector' => '{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet',
				'condition' => [
					'dots_style' => [ 'bullets' ]
				]
			]
		);

		$this->add_control(
			'dots_border_radius', [
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20
					]
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'dots_style' => [ 'bullets' ]
				]
			]	
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'dots_tab_hover', [
			'label' => __( 'Hover & Active', 'neuron-builder' )
		] );
        
		$this->add_control(
			'dots_color_hover', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 
					'dots_style' => [ 'numbers', 'fraction' ] 
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet-active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-current' => 'color: {{VALUE}}',
				],
			]	
		);

		$this->add_control(
			'dots_bg_color_hover', [
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 'dots_style!' => [ 'numbers', 'fraction' ] ],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.neuron-dots--style__scrollbar .swiper-scrollbar-drag' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.neuron-dots--style__progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
				],
			]	
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label' => __( 'Border', 'neuron-builder' ),
				'name' => 'dots_border_hover',
				'selector' => '{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet:hover, {{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet-active',
				'condition' => [
					'dots_style' => [ 'bullets' ]
				]
			]
		);

		$this->add_control(
			'dots_border_radius_hover', [
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20
					]
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .neuron-swiper-dots .swiper-pagination-bullet-active' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'dots_style' => [ 'bullets' ]
				]
			]	
		);

		$this->add_control(
			'dots_hover_animation', [
				'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'scale' => __( 'Scale', 'neuron-builder' ),
				],
				'default' => 'scale',
				'prefix_class' => 'neuron-dots--animation__',
				'condition' => [
					'dots_style' => [ 'bullets' ]
				]
			]	
		);

		$this->add_responsive_control(
			'scrollbar_drag_height', [
				'label' => __( 'Drag Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .swiper-scrollbar-drag' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'dots_style' => [ 'scrollbar' ]
				]
			]	
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
			];
		}
		return [];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', [ 'button', 'neuron-slide-button'] );

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );
		$carousel_count = count( $slides );
		$articleClass = 'm-neuron-testimonial';

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$articleClass .= ' h-neuron-animation--wow';
		}
		?>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
				<?php if ( $settings['show_quote'] ) { ?>
					<div class="neuron-swiper-quote"><?php Icons_Manager::render_icon( $settings['quote'], [ 'aria-hidden' => 'true' ] ) ?></div>
				<?php } ?>
				<div class="swiper-wrapper neuron-slides">
					<?php foreach ( $slides as $slide ) : ?>
						<div class="<?php echo esc_attr( $articleClass ) ?> elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ) ?> swiper-slide">
							<?php if ($slide['image']['url'] && $settings['layout'] == 'image-above') : ?>
								<div class="m-neuron-testimonial__image">
									<img src="<?php echo esc_url($slide['image']['url']) ?>" alt="<?php echo esc_attr($slide['name']) ?>">
								</div>
							<?php endif; ?>
							<?php if ($slide['content']) : ?>
								<div class="m-neuron-testimonial__content">
									<?php if ($slide['content']) : ?>
										<div class="m-neuron-testimonial__text"><?php echo wp_kses_post($slide['content']) ?></div>
									<?php endif; ?>
									<?php if ($settings['layout'] == 'image-left' || $settings['layout'] == 'image-right') : ?>
									<div class="m-neuron-testimonial__cite">
										<?php if ($slide['name']) : ?>
											<span class="m-neuron-testimonial__name"><?php echo esc_attr($slide['name']) ?></span>
										<?php endif; ?>
										<?php if ($slide['title']) : ?>
											<span class="m-neuron-testimonial__title"><?php echo esc_attr($slide['title']) ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="m-neuron-testimonial__footer">
								<?php if ($slide['image']['url'] && $settings['layout'] != 'image-above') : ?>
									<div class="m-neuron-testimonial__image">
										<img src="<?php echo esc_url($slide['image']['url']) ?>" alt="<?php echo esc_attr($slide['name']) ?>">
									</div>
								<?php endif; ?>
								<?php if ($settings['layout'] != 'image-left' && $settings['layout'] != 'image-right') : ?>
									<div class="m-neuron-testimonial__cite">
										<?php if ($slide['name']) : ?>
											<span class="m-neuron-testimonial__name"><?php echo esc_attr($slide['name']) ?></span>
										<?php endif; ?>
										<?php if ($slide['title']) : ?>
											<span class="m-neuron-testimonial__title"><?php echo esc_attr($slide['title']) ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( 1 < $carousel_count ) : ?>

				<?php if ( $settings['dots_style'] == 'scrollbar' ) : ?>
					<div class="swiper-scrollbar"></div>
				<?php endif; ?>

				<?php if ( $carousel_dots ) : ?>
					<div class="swiper-pagination neuron-swiper-dots"></div>
				<?php endif; ?>
				
				<?php if ( $carousel_arrows ) : ?>
					<div class="neuron-swiper-button neuron-swiper-button--prev">
						<?php if ( $settings['arrows_icon'] ) : ?>
							<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
						<?php endif; ?>
						<span class="neuron-swiper-button--hidden"><?php _e( 'Previous', 'neuron-builder' ); ?></span>
					</div>
					<div class="neuron-swiper-button neuron-swiper-button--next">
						<?php if ( $settings['arrows_icon'] ) : ?>
							<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
						<?php endif; ?>
						<span class="neuron-swiper-button--hidden"><?php _e( 'Next', 'neuron-builder' ); ?></span>
					</div>
				<?php endif; ?>

			<?php endif; ?>
				
		</div>
		<?php
	}

		protected function content_template() {
		?>
		<#
			var navi             = settings.navigation,
				showDots         = ( 'dots' === navi || 'arrows-dots' === navi ),
				showArrows       = ( 'arrows' === navi || 'arrows-dots' === navi );
		#>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container">

				<# if ( settings.show_quote ) { 
						var quoteHTML = elementor.helpers.renderIcon( view, settings.quote, { 'aria-hidden': true }, 'i' , 'object' );
					#>
						<# if ( quoteHTML ) { #>
							<div class="neuron-swiper-quote">{{{ quoteHTML.value }}}</div>
						<# } #>
				<# } #>

				<div class="swiper-wrapper neuron-slides">
					<# jQuery.each( settings.slides, function( index, slide ) { #>
						<div class="m-neuron-testimonial elementor-repeater-item-{{ slide._id }} swiper-slide">
							<# if ( slide.image.url && settings.layout === 'image-above' ) { #>
								<div class="m-neuron-testimonial__image">
									<img src="{{{ slide.image.url }}}" alt="{{{ slide.name }}}">
								</div>
							<# } #>
							<# if ( slide.content ) { #>
								<div class="m-neuron-testimonial__content">
									<# if ( slide.content ) { #>
										<div class="m-neuron-testimonial__text">{{{ slide.content }}}</div>
									<# } #>
									<# if ( settings.layout == 'image-left' || settings.layout == 'image-right' ) { #>
										<div class="m-neuron-testimonial__cite">
											<# if ( slide.name ) { #>
												<span class="m-neuron-testimonial__name">{{{ slide.name }}}</span>
											<# } #>
											<# if ( slide.title ) { #>
												<span class="m-neuron-testimonial__title">{{{ slide.title }}}</span>
											<# } #>
										</div>
									<# } #>
								</div>
							<# } #>
							<div class="m-neuron-testimonial__footer">
								<# if ( slide.image.url && settings.layout != 'image-above' ) { #>
									<div class="m-neuron-testimonial__image">
										<img src="{{{ slide.image.url }}}" alt="{{{ slide.name }}}">
									</div>
								<# } #>
								<# if ( settings.layout != 'image-left' && settings.layout != 'image-right' ) { #>
									<div class="m-neuron-testimonial__cite">
										<# if ( slide.name ) { #>
											<span class="m-neuron-testimonial__name">{{{ slide.name }}}</span>
										<# } #>
										<# if ( slide.title ) { #>
											<span class="m-neuron-testimonial__title">{{{ slide.title }}}</span>
										<# } #>
									</div>
								<# } #>
							</div>
						</div>
					<# } ); #>
				</div>
			</div>

			<# if ( 1 < settings.slides.length ) { #>
				<# if ( showDots ) { #>
					<div class="swiper-pagination neuron-swiper-dots"></div>
				<# } #>

				<# if ( settings.arrows_style == 'scrollbar' ) { #>
					<div class="swiper-scrollbar"></div>
				<# } #>

				<# if ( showArrows ) { 
					iconHTML = elementor.helpers.renderIcon( view, settings.arrows_icon, { 'aria-hidden': true }, 'i' , 'object' );
				#>
					<div class="neuron-swiper-button neuron-swiper-button--prev">
						<# if ( iconHTML ) { #>
							<div class="neuron-icon">{{{ iconHTML.value }}}</div>
						<# } #>
						<span class="neuron-swiper-button--hidden"><?php _e( 'Previous', 'neuron-builder' ); ?></span>
					</div>
					<div class="neuron-swiper-button neuron-swiper-button--next">
						<# if ( iconHTML ) { #>
							<div class="neuron-icon">{{{ iconHTML.value }}}</div>
						<# } #>
						<span class="neuron-swiper-button--hidden"><?php _e( 'Next', 'neuron-builder' ); ?></span>
					</div>
				<# } #>
			<# } #>
		</div>
		<?php
	}
}
