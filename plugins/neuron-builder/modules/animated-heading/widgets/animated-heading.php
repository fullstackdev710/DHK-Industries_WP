<?php
/**
 * Animated Heading
 * 
 * Extra animation like typing
 * and animated to the actual
 * heading with more features.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\AnimatedHeading\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Animated_Heading extends Base_Widget {

	public function get_name() {
		return 'neuron-animated-heading';
	}

	public function get_title() {
		return __( 'Animated Heading', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-animated-headline neuron-badge';
	}
	
	public function get_keywords() {
		return [ 'animated', 'heading', 'animated heading', 'animate heading' ];
	}

    public function get_script_depends() {
        return [ 'neuron-typed' ];
    }

	protected function register_controls() {
        // Heading
		$this->start_controls_section(
			'heading_section', [
				'label' => __( 'Heading', 'neuron-builder' )
			]
        );

        $this->add_control(
			'style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'animated' => __( 'Animated', 'neuron-builder' ),
					'rotating' => __( 'Rotating', 'neuron-builder' ),
					'highlighted' => __( 'Highlighted', 'neuron-builder' ),
				],
				'default' => 'animated',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'highlighted_shape',
			[
				'label'   => __( 'Shape', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'neuron-builder' ),
					'curly' => __( 'Curly', 'neuron-builder' ),
					'underline' => __( 'Underline', 'neuron-builder' ),
					'double' => __( 'Double', 'neuron-builder' ),
					'double-underline' => __( 'Double Underline', 'neuron-builder' ),
					'underline-zigzag' => __( 'Underline Zigzag', 'neuron-builder' ),
					'diagonal' => __( 'Diagonal', 'neuron-builder' ),
					'strikethrough' => __( 'Strikethrough', 'neuron-builder' ),
					'x' => __( 'X', 'neuron-builder' ),
				],
				'default' => 'circle',
                'condition' => [
                    'style' => 'highlighted'
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'rotating_animation',
			[
				'label'   => __( 'Animation', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'typing' => __( 'Typing', 'neuron-builder' ),
					'slide-down' => __( 'Slide Down', 'neuron-builder' ),
				],
				'default' => 'typing',
                'condition' => [
                    'style' => 'rotating'
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'animated_type',
			[
				'label'   => __( 'Type', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'line' => __( 'Line', 'neuron-builder' ),
					'word' => __( 'Word', 'neuron-builder' ),
				],
				'default' => 'line',
                'condition' => [
                    'style' => 'animated'
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'animated_direction',
			[
				'label'   => __( 'Direction', 'neuron-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'column' => __( 'Column', 'neuron-builder' ),
					'row' => __( 'Row', 'neuron-builder' ),
				],
				'default' => 'column',
				'tablet_default' => 'column',
				'phone_default' => 'row',
				'prefix_class' => 'a-animated-heading__direction%s-',
                'condition' => [
                    'style' => 'animated',
                    'animated_type' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'animated_animation', // TODO PREVENT without animation
			[
				'label' => __( 'Entrance Animation', 'neuron-builder' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'default' => 'h-neuron-animation--slideUp',
				'condition' => [
					'style' => 'animated'
				]
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
					'animated_animation!' => [
						'',
						'none',
						'h-neuron-animation--specialOne', 
						'h-neuron-animation--clipFromLeft', 
						'h-neuron-animation--clipFromRight', 
						'h-neuron-animation--clipUp', 
						'h-neuron-animation--clipBottom'
					], 
					'style' => 'animated'
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
				'default' => 200,
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [
					'animated_animation!' => '',
					'style' => 'animated'
				],
			] 
		);
        
        $this->add_control(
            'style_divider', [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
			'before_text',
			[
				'label'   => __( 'Before Text', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'I have created an ', 'neuron-builder' ),
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'label_block' => true,
				'separator' => 'none',
                'condition' => [
                    'style!' => 'animated'
                ]
			]
		);
		
		$this->add_control(
			'highlighted_text',
			[
				'label'   => __( 'Highlighted Text', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXT,
				'default' => "Innovative",
				'label_block' => true,
                'condition' => [
                    'style' => 'highlighted'
				],
				'dynamic' => [
					'active' => true,
				],
				'frontend_available' => true,
			]
		);
        
        $this->add_control(
			'rotating_text',
			[
				'label'   => __( 'Rotating Text', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => "Innovative\nAmazing\nAstonishing",
                'condition' => [
                    'style' => 'rotating'
				],
				'dynamic' => [
					'active' => true,
				],
				'frontend_available' => true
			]
        );
        
        $this->add_control(
			'animated_text',
			[
				'label'   => __( 'Animated', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXTAREA,
                'default' => "A sample example how\nanimated heading works.",
                'condition' => [
                    'style' => 'animated'
				],
				'dynamic' => [
					'active' => true,
				],
				'frontend_available' => true
			]
        );
        
        $this->add_control(
			'after_text',
			[
				'label'   => __( 'After Text', 'neuron-builder' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'website.', 'neuron-builder' ),
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'label_block' => true,
				'separator' => 'none',
                'condition' => [
                    'style!' => 'animated'
                ]
			]
        );

        $this->add_control(
			'link',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::URL,
				'show_external' => true,
				'dynamic' => [
					'active' => true,
				],
                'separator' => 'before'
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
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition' => [
					'animated_direction!' => 'row',
				],
                'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading' => 'text-align: {{VALUE}}',
				],
			]
        );

        $this->add_responsive_control(
			'alignment_d_row',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors_dictionary' => [
					'start' => 'flex-start',
					'end' => 'flex-end'
				],
				'default' => 'left',
				'condition' => [
					'style' => 'animated',
					'animated_type' => 'line',
					'animated_direction' => 'row',
				],
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper' => 'justify-content: {{VALUE}}',
				],
			]
        );
        
        $this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'neuron-builder' ),
					'h2' => __( 'H2', 'neuron-builder' ),
					'h3' => __( 'H3', 'neuron-builder' ),
					'h4' => __( 'H4', 'neuron-builder' ),
					'h5' => __( 'H5', 'neuron-builder' ),
					'h6' => __( 'H6', 'neuron-builder' ),
					'div' => __( 'div', 'neuron-builder' ),
					'span' => __( 'span', 'neuron-builder' ),
					'p' => __( 'p', 'neuron-builder ')
				],
				'default' => 'h2'
			]
		);

        $this->end_controls_section(); // End Heading Section

        // Additional Opitons
		$this->start_controls_section(
			'additional_options_section', [
				'label' => __( 'Additional Options', 'neuron-builder' ),
				'condition' => [
                    'style' => 'rotating'
				],
			]
        );

        $this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'neuron-builder' ),
				'label_off' => __( 'Off', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'cursor_char',
			[
				'label' => __( 'Cursor Character', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
				'default' => '_',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'type_speed',
			[
				'label' => __( 'Type Speed', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 10,
                'default' => 110,
				'frontend_available' => true,
			]
		); 

		$this->add_control(
			'start_delay',
			[
				'label' => __( 'Start Delay', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 10,
				'default' => 300,
				'frontend_available' => true,
			]
        );
        
        $this->add_control(
			'increase_delay',
			[
				'label' => __( 'Increase Delay', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 10,
                'default' => 300,
				'frontend_available' => true,
				'condition' => [
                    'style' => 'rotating'
				],
			]
		);

		$this->add_control(
			'back_delay',
			[
				'label' => __( 'Back Delay', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 10,
				'default' => 510,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'back_speed',
			[
				'label' => __( 'Back Speed', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 10,
				'default' => 60,
				'frontend_available' => true,
			]
		);

        $this->end_controls_section(); // End Additional Options Section

        // Heading Style
		$this->start_controls_section(
			'heading_style_section', [
                'label' => __( 'Heading', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE
			]
        );

        $this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading' => 'color: {{VALUE}}'
				],
			]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .a-animated-heading'
            ]
		);
		
		$this->add_responsive_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'style' => 'animated',
                    'animated_type' => 'line',
                    'animated_direction' => 'row',
				],
				'selectors' => [
					'{{WRAPPER}}.a-animated-heading__direction-row .a-animated-heading__text--dynamic:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
            
        $this->add_control(
            'animated_heading',
            [
                'label' => __( 'Animated', 'neuron-builder' ),
                'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'style!' => 'animated'
                ],
            ]
        );

        $this->add_control(
			'animated_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
                'selectors' => [
                    '{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper' => 'color: {{VALUE}}'
                ],
                'condition' => [
                    'style!' => 'animated'
                ]
			]
        );

        $this->add_control(
			'animated_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper' => 'background-color: {{VALUE}}'
                ],
                'condition' => [
                    'style' => 'rotating'
                ]
			]
		);
		
		$this->add_control(
			'animated_cursor_color',
			[
				'label' => __( 'Cursor Color', 'neuron-builder' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}}'
                ],
                'condition' => [
                    'style' => 'rotating'
                ]
			]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'animated_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
                'selector' => '{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper',
                'condition' => [
                    'style!' => 'animated'
                ]
            ]
        );

		$this->end_controls_section(); // End Heading Style Section
		
		$this->start_controls_section(
			'section_style_marker',
			[
				'label' => __( 'Shape', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper path' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'stroke_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'above_content',
			[
				'label' => __( 'Bring to Front', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper svg' => 'z-index: 2',
					'{{WRAPPER}} .a-animated-heading__text--dynamic' => 'z-index: auto',
				],
			]
		);

		$this->add_control(
			'rounded_edges',
			[
				'label' => __( 'Rounded Edges', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .a-animated-heading__text--dynamic-wrapper path' => 'stroke-linecap: round; stroke-linejoin: round',
				],
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();

		$tag = $settings['html_tag'];

		$this->add_render_attribute( 'headline', 'class', [
			'a-animated-heading',
			'a-animated-heading--' . $settings['style'],
		] );

		$this->add_render_attribute( 'headline', 'data-id', [
			$this->get_id(),
		] );

		if ( $settings['style'] == 'rotating' && $settings['rotating_animation'] ) {
			$this->add_render_attribute( 'headline', 'class', 'a-animated-heading--rotating__' . $settings['rotating_animation'] );
		} 

		if ( $settings['style'] == 'animated' && $settings['animated_type'] ) {
			$this->add_render_attribute( 'headline', 'class', 'a-animated-heading--animated__' . $settings['animated_type'] );
		} 

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			echo '<a ' . $this->get_render_attribute_string( 'url' ) . '>';
		}

		?>
		<<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'headline' ); ?>>

			<?php if ( ! empty( $settings['before_text'] && $settings['style'] != 'animated' ) ) : ?>
				<span class="a-animated-heading__text"><?php echo $settings['before_text']; ?></span>
			<?php endif; ?>

			<span class="a-animated-heading__text--dynamic-wrapper a-animated-heading__text"></span>

			<?php if ( ! empty( $settings['after_text'] && $settings['style'] != 'animated' ) ) : ?>
				<span class="a-animated-heading__text"><?php echo $settings['after_text']; ?></span>
			<?php endif; ?>

		</<?php echo $tag; ?>>
		<?php

		if ( ! empty( $settings['link']['url'] ) ) {
			echo '</a>';
		}
	}

	protected function content_template() {
		?>
		<#
		var headlineClasses = [ 'a-animated-heading a-animated-heading--' + settings.style ],
			html_tag = settings.html_tag;

		if ( 'rotating' === settings.style ) {
			headlineClasses += ' a-animated-heading--rotating__' + settings.rotating_animation;
		}

		if ( 'animated' === settings.style ) {
			headlineClasses += ' a-animated-heading--animated__' + settings.animated_type;
		}

		if ( settings.link.url ) { #>
			<a href="#">
		<# } #>
			<{{{ html_tag }}} class="{{{ headlineClasses }}}">
				<# if ( settings.before_text && settings.style != 'animated' ) { #>
					<span class="a-animated-heading__text">{{{ settings.before_text }}}</span>
				<# } #>

				<span class="a-animated-heading__text--dynamic-wrapper a-animated-heading__text"></span>

				<# if ( settings.after_text && settings.style != 'animated' ) { #>
					<span class="a-animated-heading__text">{{{ settings.after_text }}}</span>
				<# } #>
			</{{{ html_tag }}}>
		<# if ( settings.link.url ) { #>
			</a>
		<# } #>
		<?php
	}
}
