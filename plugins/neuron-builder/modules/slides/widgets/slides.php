<?php
/**
 * Slides
 * 
 * Interactive element which works
 * as slider to showcase different 
 * content of your website.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Slides\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Slides extends Base_Widget {
	
	public function get_name() {
		return 'neuron-slides';
	}

	public function get_title() {
		return __( 'Slides', 'neuron-builder');
	}

	public function get_icon() {
		return 'eicon-slides neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements' ];
	}
	
	public function get_keywords() {
		return [ 'slides', 'slider', 'carousel' ];
	}

	protected function register_controls() {
        // Slides
		$this->start_controls_section(
			'slides_content', [
				'label' => __( 'Slides', 'neuron-builder' )
			]
        );

        // Slides Repeater
        $repeater = new Repeater();

        $repeater->start_controls_tabs(
			'slides_tabs'
		);

        // Background Tab
		$repeater->start_controls_tab(
			'slides_background_tab',
			[
				'label' => __( 'Background', 'neuron-builder' )
				
			]
        );
        
        $repeater->add_control(
			'background_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D7DDE8',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--background' => 'background-color: {{VALUE}};',
				],
			]
        );
        
        $repeater->add_control(
			'background_image',
			[
				'label' => __( 'Image', 'neuron-builder' ),
				'type' => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--background' => 'background-image: url({{URL}});',
				],
			]
        );
        
        $repeater->add_control(
			'image_size', [
				'label' => __( 'Size', 'neuron-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => __( 'Cover', 'neuron-builder' ),
                    'contain' => __( 'Contain', 'neuron-builder' ),
                    'auto' => __( 'Auto', 'neuron-builder' ),
                ],
                'default' => 'cover',
                'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--background' => 'background-size: {{VALUE}};',
				],
                'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				]
			]
		);
		
		$repeater->add_control(
			'ken_burns_effect',
			[
				'label' => __( 'Ken Burns Effect', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'zoom_direction', [
				'label' => __( 'Zoom Direction', 'neuron-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'in' => __( 'In', 'neuron-builder' ),
                    'out' => __( 'Out', 'neuron-builder' ),
                ],
				'default' => 'in',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'ken_burns_effect',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				]
			]
		);
		
		$repeater->add_control(
			'background_overlay',
			[
				'label' => __( 'Background Overlay', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'background_overlay_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.7)',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--background-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'normal' => __( 'Normal', 'neuron-builder' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'default' => 'normal',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

        $repeater->end_controls_tab(); // Background Tab Ending

        // Content Tab
        $repeater->start_controls_tab(
			'slides_content_tab',
			[
				'label' => __( 'Content', 'neuron-builder' ),
			]
		);

		$repeater->add_control(
			'heading',
			[
				'label' => __( 'Title & Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Slide Heading', 'neuron-builder' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'neuron-builder' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://neuronthemes.com', 'neuron-builder' ),
			]
		);

		$repeater->add_control(
			'apply_link',
			[
				'label' => __( 'Apply Link', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'whole-slide' => __( 'Whole Slide', 'neuron-builder' ),
					'button-only' => __( 'Button Only', 'neuron-builder' ),
				],
				'default' => 'whole-slide',
				'conditions' => [
					'terms' => [
						[
							'name' => 'link[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

        $repeater->end_controls_tab(); // Content Tab Ending

        // Style Tab
        $repeater->start_controls_tab(
			'slides_style_tab',
			[
				'label' => __( 'Style', 'neuron-builder' ),
			]
		);

		$repeater->add_control(
			'custom_style',
			[
				'label' => __( 'Custom', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Enable custom styling for this slide.', 'neuron-builder' ),
			]
		);

		$repeater->add_control(
			'horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--inner' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'vertical_position',
			[
				'label' => __( 'Vertical Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide--inner' => 'text-align: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .neuron-slide-heading' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}} .neuron-slide-description' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}} .neuron-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}} !important',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

        $repeater->end_controls_tab(); // Style Tab Ending

		$repeater->end_controls_tabs();
		
        $this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'neuron-builder' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'heading' => __( 'Slide Heading 1#', 'neuron-builder' ),
						'description' => ('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor'),
						'background_color' => '#1CD8D2',
					],
					[
						'heading' => __( 'Slide Heading 2#', 'neuron-builder' ),
						'description' => ('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor'),
						'background_color' => '#aab1b7',
					],
					[
						'heading' => __( 'Slide Heading 3#', 'neuron-builder' ),
						'description' => ('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor'),
						'background_color' => '#23a455',
					]
				],
				'title_field' => '{{{ heading }}}',
			]
		);
		
		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
        
        $this->end_controls_section(); // Slides Content End

        // Slider Settings 
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
			'keyboard_navigation',
			[
				'label' => __( 'Keyboard Navigation', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes',
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
				'default' => 200,
				'frontend_available' => true,
				'condition' => [
					'neuron_animations!' => ''
				]
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
		
        $this->end_controls_section(); // Slider Settings Section End

        // Slides Style
		$this->start_controls_section(
			'slides_style', [
                'label' => __( 'Slides', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE
			]
        );

		$this->add_responsive_control(
			'content_width',
			[
				'label' => __( 'Content Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'default' => [
					'unit' => '%',
					'size' => 60
				],
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-content' => 'width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
				]
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors' => [
					'{{WRAPPER}} .swiper-slide--inner' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
			]
		);

		$this->add_control(
			'vertical_position',
			[
				'label' => __( 'Vertical Position', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors' => [
					'{{WRAPPER}} .swiper-slide--inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} .swiper-slide--inner' => 'text-align: {{VALUE}}',
				],
			]
		);
        
		$this->end_controls_section(); // Slides Content End
		
		// Content Style
		$this->start_controls_section(
			'content_style', [
                'label' => __( 'Content', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .neuron-slide-heading',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .neuron-slide-description',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
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
				'selector' => '{{WRAPPER}} .neuron-slide-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .neuron-slide-button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Styling Tabs
		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_style_normal',
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
					'{{WRAPPER}} .neuron-slide-button' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_border_color_normal',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button' => 'border-color: {{VALUE}}',
				],
			]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_background_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_border_color_hover',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .neuron-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->end_controls_section(); // Content Style End

		$this->register_navigation_style_controls();
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
				'elementor-icons-fa-brands',
			];
		}
		return [];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
    
        if ( empty( $settings['slides'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', [ 'button', 'neuron-slide-button' ] );
		$content_item_class = '';

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$this->add_render_attribute( 'button', 'class', [ 'neuron-slide-animation' ] );
			$content_item_class = 'neuron-slide-animation';
		}

		$slides = [];
		$slide_count = 0;

		// Slides
		foreach ( $settings['slides'] as $slide ) {
			$slide_html = $btn_attributes = $slide_attributes = $slide_overlay = '';

			$slide_element = $btn_element = 'div';
			
			// Slide URL
			$slide_url = $slide['link']['url'];
			if ( ! empty( $slide_url ) ) {
				$this->add_render_attribute( 'slide_url' . $slide_count, 'href', $slide_url );

				if ( $slide['link']['is_external'] ) {
					$this->add_render_attribute( 'slide_url' . $slide_count, 'target', '_blank' );
				}

				// Link Apply
				if ( 'button-only' === $slide['apply_link'] ) {
					$btn_element = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_url' . $slide_count );
				} else {
					$slide_element = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_url' . $slide_count );
				}
			}

			// Background Overlay
			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_overlay = '<div class="swiper-slide--background-overlay"></div>';
			}
			// Start Neuron Slide Content
			$slide_html .= '<div class="neuron-slide-content">';

			// Heading 
			$slide_html .= $slide['heading'] ? '<div class="neuron-slide-heading '. $content_item_class .'" >' . $slide['heading'] . '</div>' : '';

			// Description
			$slide_html .= $slide['description'] ? '<div class="neuron-slide-description '. $content_item_class .'" >' . $slide['description'] . '</div>' : '';

			// Button 
			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . ' >' . $slide['button_text'] . '</' . $btn_element . '>';
			}

			// Ken Burns Effect
			$ken_class = '';
			if ( '' != $slide['ken_burns_effect'] ) {
				$ken_class = ' h-kenBurnsNeuron h-kenBurnsNeuron--' . $slide['zoom_direction'];
			}

			$slide_html .= '</div>'; // End Neuron Slide Content

			// Neuron Slide Content Output
			$slide_html = '<div class="swiper-slide--background' . $ken_class . '"></div>' . $slide_overlay  .'
								<' . $slide_element . ' ' . $slide_attributes . ' class="swiper-slide--inner">
								' . $slide_html . '</' . $slide_element . '>';

			$slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . ' swiper-slide">' . $slide_html . '</div>';

			// Increase Slide Count for Index
			$slide_count++;
		}

		// Carousel Class
		$carousel_class = [ 'neuron-slides' ];

		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );
		$carousel_count = count( $settings['slides'] );
		?>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
				<div class="swiper-wrapper neuron-slides">
					<?php echo implode( '', $slides ); ?>
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
				<div class="swiper-wrapper neuron-slides">
					<# jQuery.each( settings.slides, function( index, slide ) { #>
						<div class="elementor-repeater-item-{{ slide._id }} swiper-slide">
							<# 
								var kenClass = '';

								if ( '' != slide.ken_burns_effect ) {
									kenClass = ' h-kenBurnsNeuron h-kenBurnsNeuron--' + slide.zoom_direction;
								}
							#>
							<div class="swiper-slide--background{{ kenClass }}"></div>
							<# if ( 'yes' === slide.background_overlay ) { #>
								<div class="swiper-slide--background-overlay"></div>
							<# } #>
							<div class="swiper-slide--inner">
								<div class="neuron-slide-content">
									<# if ( slide.heading ) { #>
										<div class="neuron-slide-heading neuron-slide-animation">{{{ slide.heading }}}</div>
									<# }
									if ( slide.description ) { #>
										<div class="neuron-slide-description neuron-slide-animation">{{{ slide.description }}}</div>
									<# }
									if ( slide.button_text ) { #>
										<div class="a-neuron-button neuron-slide-button neuron-slide-animation">{{{ slide.button_text }}}</div>
									<# } #>
								</div>
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
