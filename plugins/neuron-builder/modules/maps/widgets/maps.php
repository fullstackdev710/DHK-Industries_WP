<?php
/**
 * Advanced Google Maps
 * 
 * Extending the normal map
 * by making possible to add
 * custom markers and style.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Maps\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Maps extends Base_Widget {

	public function get_name() {
		return 'neuron-maps';
	}

	public function get_title() {
		return __( 'Maps', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-google-maps neuron-badge';
    }
    
    public function get_keywords() {
		return [ 'map', 'google', 'open maps' ];
    }
    
    public function get_script_depends() {
        return [ 'neuron-google-maps' ];
	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'functionality_tab',
			[
				'label' => __('Functionality', 'neuron-builder'),
			]
        );

        $this->add_control(
			'api_key',
			[
                'label' => __( 'API Key', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' )
                ],
				'default' => 'default'
			]
        );
        
        $this->add_control(
			'api_key_custom',
			[
				'label'   => __( 'Custom API Key', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'api_key' => 'custom'
				],
				'frontend_available' => true
			]
        );

        $this->add_control(
			'google_maps_raw',
			[
                'raw' => __( '<small>Do not forget to enter the API Key in the Theme Panel > Integrations > <a href='. esc_url( admin_url( 'admin.php' ) . '?page=settings' ) .'>Google Maps</a></small>', 'neuron-builder' ),
                'type' => Controls_Manager::RAW_HTML,
                'field_type' => 'html',
                'condition' => [
                    'api_key!' => 'custom'
                ]
			]
		);

        $this->add_control(
			'map_latitude',
			[
				'label'   => __('Map Latitude', 'neuron-builder'),
                'type' => Controls_Manager::TEXT,
                'default' => '40.783058',
				'separator' => 'before',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'map_longitude',
			[
				'label'   => __('Map Longitude', 'neuron-builder'),
                'type' => Controls_Manager::TEXT,
				'default' => '-73.971252',
				'frontend_available' => true,
			]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
			'options_tab',
			[
				'label' => __('Options', 'neuron-builder'),
			]
        );

        $this->add_control(
			'zoom',
			[
				'label' => __('Zoom', 'neuron-builder'),
				'type' => Controls_Manager::NUMBER,
				'default' => 13,
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
				'frontend_available' => true,
			]
        );
        
        $this->add_control(
			'style',
			[
                'label' => __('Style', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __('Default', 'neuron-builder'),
					'classic' => __('Classic', 'neuron-builder'),
					'custom' => __('Custom', 'neuron-builder')
				],
                'default' => 'classic',
                'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'custom_style',
			[
				'label'   => '',
                'type' => Controls_Manager::CODE,
                'description' => __('Enter the custom style, get custom style <a href="https://snazzymaps.com/" target="_BLANK">here</a>.', 'neuron-builder'),
                'frontend_available' => true,
                'condition' => [
                    'style' => 'custom'
                ],
			]
        );

        $this->add_responsive_control(
			'height',
			[
				'label' => __('Height', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['vh', 'px', '%'],
				'selectors' => [
					'{{WRAPPER}} .map-holder > .map' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
				'default' => [
					'unit' => 'px',
					'size' => 500
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				]
			]
		);

        $this->add_control(
			'map_controls_heading',
			[
				'label' => __('Map Controls', 'neuron-builder'),
				'type' => Controls_Manager::HEADING
			]
        );

        $this->add_control(
			'scroll_zoom',
			[
				'label' => __('Scroll Zoom', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'type',
			[
				'label' => __('Type', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'zoom_control',
			[
				'label' => __('Zoom', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'fullscreen',
			[
				'label' => __('Fullscreen', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'street_view',
			[
				'label' => __('Street View', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );

        $this->add_control(
			'draggable',
			[
				'label' => __('Draggable', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
        );

        $this->end_controls_section();
        
		$this->start_controls_section(
			'markers_tab',
			[
				'label' => __('Markers', 'neuron-builder'),
			]
		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'map_latitude', 
			[
				'label' => __('Map Latitude', 'neuron-builder'),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'map_longitude', 
			[
				'label' => __('Map Longitude', 'neuron-builder'),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'image', 
			[
				'label' => __('Image', 'neuron-builder'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => [],
				],
			]
		);

		$repeater->add_control(
			'image_width', 
			[
				'label' => __('Width ', 'neuron-builder') . '(px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'step' => 5,
				'condition' => [
					'image[url]!' => ''
				]
			]
		);

		$repeater->add_control(
			'image_height', 
			[
				'label' => __('Height ', 'neuron-builder') . '(px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'step' => 5,
				'condition' => [
					'image[url]!' => ''
				]
			]
		);

		$repeater->add_control(
			'retina', 
			[
				'label' => __('Retina Ready', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$repeater->add_control(
			'title', 
			[
				'label' => __('Title', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'content', 
			[
				'label' => __('Content', 'neuron-builder'),
				'type' => Controls_Manager::TEXTAREA
			]
		);

		$this->add_control(
            'markers',
            [
                'label' => __('Markers', 'neuron-builder'),
                'description' => __('Add markers to the map.', 'neuron-builder'),
                'type' => Controls_Manager::REPEATER,
                'prevent_empty' => false,
                'frontend_available' => true,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'map_latitude' => '40.775541',
                        'map_longitude' => '-73.986267',
                        'title' => __('Marker Title 1#', 'neuron-builder'),
                        'content' => __('Marker Content 1#', 'neuron-builder'),
                        'retina' => 'yes'
                    ],
                    [
                        'map_latitude' => '40.787239',
                        'map_longitude' => '-73.945772',
                        'title' => __('Marker Title 2#', 'neuron-builder'),
                        'content' => __('Marker Content 2#', 'neuron-builder'),
                        'retina' => 'yes'
                    ]
                ]
            ]
		);

		$this->end_controls_section();
    }

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();
	?>
        <div class="map-holder">
            <div id="map-<?php echo esc_attr( $this->get_id() ) ?>" class="map"></div>
        </div>
	<?php
	}

	protected function content_template() {}
}
