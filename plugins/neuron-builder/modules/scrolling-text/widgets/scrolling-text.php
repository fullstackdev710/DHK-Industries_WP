<?php
/**
 * Page Title
 * 
 * Prints the page title.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ScrollingText\Widgets;

use Elementor\Widget_Heading;
use Elementor\Controls_Manager;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Scrolling_Text extends Widget_Heading {

	public function get_name() {
		return 'neuron-scrolling-text';
	}

	public function get_title() {
		return __( 'Scrolling Text', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-heading neuron-badge';
    }
    
    public function get_categories() {
		return [ 'neuron-elements' ];
	}

	public function get_keywords() {
		return [ 'text', 'scrolling', 'scrolling text', 'moving text' ];
    }

    public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}

    public function register_controls() {
        parent::register_controls();

        $this->start_controls_section(
			'scrolling_text_section', [
				'label' => __( 'Scrolling', 'neuron-builder' )
			]
        );

        $this->add_control( 
			'text_repeat', [ 
				'label' => __( 'Text Repeat', 'neuron-builder' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 30,
                'default' => 3
			] 
        );
        
        $this->add_control( 
			'scrolling_duration', [ 
				'label' => __( 'Scrolling Duration', 'neuron-builder' ) . ' ' . '(s)',
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 30,
                'default' => 5,
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'animation-duration: {{VALUE}}s'
                ]
			] 
        );
        
        $this->add_responsive_control(
			'text_indent',
			[
				'label' => __( 'Text Indent', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw', 'rem' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'padding: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }

    /**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();

        $repeat = $settings['text_repeat'] ? $settings['text_repeat'] : 3;

        ob_start();
        
        parent::render();

        $output = ob_get_clean();
        ?>
            <div class="a-scrolling-text">
                <div class="a-scrolling-text__inner">
                    <?php 
                        for ( $i = 0; $i < $repeat; $i++ ) {
                            echo $output;
                        }
                    ?>
                </div>
            </div>
        <?php
    }
    
    protected function content_template() {}
}
