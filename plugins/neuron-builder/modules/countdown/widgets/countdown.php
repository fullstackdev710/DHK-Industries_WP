<?php
/**
 * Countdown
 * 
 * Animated numbers to  
 * a specified time.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Countdown\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Countdown extends Base_Widget {

	private $_default_countdown_labels;

	public function get_name() {
		return 'neuron-countdown';
	}

	public function get_title() {
		return __( 'Countdown', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-countdown neuron-badge';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'count', 'coming soon' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'countdown_functionality',
			[
				'label' => __('Functionality', 'neuron-builder'),
			]
        );

        $this->add_control(
			'due_date',
			[
				'label' => __('Due Date', 'neuron-builder'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month') + (get_option('gmt_offset') * HOUR_IN_SECONDS)),
				'description' => __('Date set according to your timezone: %s.', 'neuron-builder'),
				// 'frontend_available' => true
			]
        );
        
        $this->add_control(
			'view',
			[
				'label' => __('View', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'block' => __('Block', 'neuron-builder'),
					'inline' => __('Inline', 'neuron-builder'),
				],
				'default' => 'block',
				'prefix_class' => 'neuron-countdown-view--'
			]
        );
        
        $this->add_control(
			'days',
			[
				'label' => __('Days', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'hours',
			[
				'label' => __('Hours', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'minutes',
			[
				'label' => __('Minutes', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'seconds',
			[
				'label' => __('Seconds', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'labels',
			[
				'label' => __('Labels', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'custom_labels',
			[
				'label' => __('Custom Labels', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'neuron-builder'),
				'label_off' => __('No', 'neuron-builder'),
				'return_value' => 'yes',
                'condition' => [
                    'labels' => 'yes'
                ]
			]
        );
        
        $this->add_control(
			'label_days',
			[
				'label'   => __('Days', 'neuron-builder'),
                'type'    => Controls_Manager::TEXT,
				'default' => __('Days', 'neuron-builder'),
				'condition' => [
					'labels' => 'yes',
					'custom_labels' => 'yes',
					'days' => 'yes'
				]
			]
        );
        
        $this->add_control(
			'label_hours',
			[
				'label'   => __('Hours', 'neuron-builder'),
                'type'    => Controls_Manager::TEXT,
				'default' => __('Hours', 'neuron-builder'),
				'condition' => [
					'labels' => 'yes',
					'custom_labels' => 'yes',
					'hours' => 'yes'
				]
			]
        );
        
        $this->add_control(
			'label_minutes',
			[
				'label'   => __('Minutes', 'neuron-builder'),
                'type'    => Controls_Manager::TEXT,
				'default' => __('Minutes', 'neuron-builder'),
				'condition' => [
					'labels' => 'yes',
					'custom_labels' => 'yes',
					'minutes' => 'yes'
				]
			]
        );
        
        $this->add_control(
			'label_seconds',
			[
				'label'   => __('Seconds', 'neuron-builder'),
                'type'    => Controls_Manager::TEXT,
				'default' => __('Seconds', 'neuron-builder'),
				'condition' => [
					'labels' => 'yes',
					'custom_labels' => 'yes',
					'seconds' => 'yes'
				]
			]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
			'countdown_boxes',
			[
				'label' => __('Boxes', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_responsive_control(
			'boxes_container_width',
			[
                'label' => __('Container Width', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
				'selectors' => [
					'{{WRAPPER}} .neuron-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
        );
        
        $this->add_control(
			'boxes_background_color',
			[
				'label' => __('Background Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
                'selectors' => [
                    '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item' => 'background-color: {{VALUE}}',
                ],
			]
        );
        
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'boxes_border',
				'selector' => '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item',
				'separator' => 'before',
			]
        );
        
        $this->add_control(
			'boxes_border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'boxes_space_between',
			[
                'label' => __('Space Between', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
        );

        $this->add_control(
			'boxes_padding',
			[
				'label' => __('Padding', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
			'countdown_content',
			[
				'label' => __('Content', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
			'content_numbers_heading',
			[
				'label' => __('Numbers', 'neuron-builder'),
				'type' => Controls_Manager::HEADING
			]
        );
        
        $this->add_control(
			'numbers_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item .neuron-countdown-numbers' => 'color: {{VALUE}}',
                ],
			]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbers_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item .neuron-countdown-numbers',
			]
        );
        
        $this->add_control(
			'content_labels_heading',
			[
				'label' => __('Labels', 'neuron-builder'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
        );
        
        $this->add_control(
			'labels_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
                'selectors' => [
                    '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item .neuron-countdown-label' => 'color: {{VALUE}}',
                ],
			]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .neuron-countdown-wrapper .neuron-countdown-item .neuron-countdown-label',
			]
		);
        
		$this->end_controls_section();
	}

	private function init_default_countdown_labels() {
		$settings = $this->get_settings_for_display();

		$this->_default_countdown_labels = [
			'label_days' => __( 'Days', 'neuron-builder' ),
			'label_hours' => __( 'Hours', 'neuron-builder' ),
			'label_minutes' => __( 'Minutes', 'neuron-builder' ),
			'label_seconds' => __( 'Seconds', 'neuron-builder' ),
		];
	}

	public function get_default_countdown_labels() {
		if ( ! $this->_default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}

	protected function render() {
        $settings = $this->get_settings_for_display();
		$due_date = $settings['due_date'];

		// Handle timezone ( we need to set GMT time )
		$gmt = get_gmt_from_date( $due_date . ':00' );
		$due_date = strtotime( $gmt );
		
        $countdown_markup = [];
		
        $countdown = [
            'label_days' => [
				'visibility' => $settings['days'],
				'class' => 'neuron-countdown-days'
            ],
            'label_hours' => [
				'visibility' => $settings['hours'],
				'class' => 'neuron-countdown-hours'
            ],
            'label_minutes' => [
				'visibility' => $settings['minutes'],
				'class' => 'neuron-countdown-minutes'
            ],
            'label_seconds' => [
				'visibility' => $settings['seconds'],
				'class' => 'neuron-countdown-seconds'
            ],
		];

		$default_labels = $this->get_default_countdown_labels();

        foreach( $countdown as $key => $value ) {

            if ( $value['visibility'] == 'yes' ) {
				$countdown_markup[] = '<div class="neuron-countdown-item"><span class="' . $value['class'] . ' neuron-countdown-numbers"></span>';
				
				if ( $settings['labels'] == 'yes' ) {
					if ( $settings['custom_labels'] == 'yes' ) {
						$countdown_markup[] = '<span class="neuron-countdown-label">' . $settings[ $key ] .'</span>';
					} else {
						$countdown_markup[] = '<span class="neuron-countdown-label">' . $default_labels[ $key ]  .'</span>';
					}
				}
				
                $countdown_markup[] = '</div>';
			}
		}

		if ( $countdown_markup ) {
		?>
		<div class="neuron-countdown-wrapper" data-date="<?php echo esc_attr( $due_date ) ?>">
			<?php echo implode( ' ', $countdown_markup ) ?>
		</div>
		<?php
		}
	}
}
