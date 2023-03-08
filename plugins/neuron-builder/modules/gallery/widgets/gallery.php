<?php
/**
 * Media Gallery
 * 
 * Create custom and professional
 * media galleries same as posts
 * layout and style.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Gallery\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Embed;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Gallery extends Base_Widget {

	private $filters = [];

	public function get_name() {
		return 'neuron-gallery';
	}

	public function get_title() {
		return __( 'Gallery', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-gallery-justified neuron-badge';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'neuron-packery', 'neuron-object-fit'  ];
	}

	public function get_keywords() {
		return [ 'gallery', 'images', 'image', 'slider', 'slide', 'carousel' ];
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

	protected function register_controls() {
		
		$this->register_layout_controls();

		$this->register_filters_controls();

		$this->register_layout_style_controls();

		$this->register_box_style_controls();

		$this->register_image_style_controls();

		$this->register_overlay_style_controls();

		$this->register_content_style_controls();
		
		$this->register_navigation_style_controls();

		$this->register_filters_style_controls();
	}

	protected function register_layout_controls() {
		$this->start_controls_section( 
			'layout_section', [ 
				'label' => __( 'Layout', 'neuron-builder' ) 
			] 
		);

		$this->add_control(
			'gallery_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Type', 'neuron-builder' ),
				'default' => 'single',
				'options' => [
					'single' => __( 'Single', 'neuron-builder' ),
					'multiple' => __( 'Multiple', 'neuron-builder' ),
				],
				'condition' => [
					'carousel!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'gallery',
			[
				'type' => Controls_Manager::GALLERY,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'gallery_type',
							'value' => 'single',
						],
						[
							'terms' => [
								[
									'name' => 'carousel',
									'operator' => '===',
									'value' => 'yes',
								],
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'gallery_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Title', 'neuron-builder' ),
				'default' => __( 'New Gallery', 'neuron-builder' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'multiple_gallery',
			[
				'type' => Controls_Manager::GALLERY,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'galleries',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __( 'Galleries', 'neuron-builder' ),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ gallery_title }}}',
				'render_type' => 'template',
				'default' => [
					[
						'gallery_title' => __( 'New Gallery', 'neuron-builder' ),
					],
				],
				'condition' => [
					'gallery_type' => 'multiple',
					'carousel!' => 'yes'
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'neuron-builder' ),
					'rand' => __( 'Random', 'neuron-builder' ),
				],
			]
		);

		$this->add_control(
			'gallery_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'condition' => [
					'carousel!' => 'yes',
					'layout!' => 'metro' 
				],
				'prefix_class' => 'l-neuron-grid-wrapper%s--columns__',
				'frontend_available' => true,
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
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'condition' => [
					'carousel' => 'yes'
				],
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
					'carousel' => 'yes',
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
				'tablet_default' => '1',
				'mobile_default' => '1',
				'condition' => [
					'carousel' => 'yes'
				],
				'frontend_available' => true
			]
		);

		$this->add_control(
			'carousel',
			[
				'label' => __( 'Carousel', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true
			]
		);

		$this->add_control(
			'carousel_settings',
			[
				'label' => __( 'Carousel Settings', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'Default', 'neuron-builder' ),
				'label_on' => __( 'Custom', 'neuron-builder' ),
				'return_value' => 'yes',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'arrows-dots' => __( 'Arrows & Dots', 'neuron-builder' ),
					'arrows' => __( 'Arrows', 'neuron-builder' ),
					'dots' => __( 'Dots', 'neuron-builder' ),
				],
				'default' => 'arrows-dots',
				'frontend_available' => true,
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);

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

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'no',
				'condition' => [
					'carousel' => 'yes'
				]
			]
		);

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
			'transition_speed',
			[
				'label' => __( 'Transition Speed', 'neuron-builder' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
				'condition' => [
					'carousel' => 'yes'
				]
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
				'condition' => [
					'carousel' => 'yes'
				],
			]
		);

		$this->end_popover( 'carousel_popover_end' );

		$this->add_control(
			'layout',
			[
			    'label' => __( 'Layout', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'grid' => __( 'Grid', 'neuron-builder' ),
					'masonry' => __( 'Masonry', 'neuron-builder' ),
					'metro' => __( 'Metro', 'neuron-builder' ),
				],
				'default' => 'grid',
				'condition' => [
					'columns!' => '1',
					'carousel!' => 'yes',
				],
				'frontend_available' => true,
				'prefix_class' => 'm-neuron-gallery--layout-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'metro_columns_responsive',
			[
			    'label' => __( 'Responsive Columns', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( '1', 'neuron-builder' ),
					'2' => __( '2', 'neuron-builder' ),
					'3' => __( '3', 'neuron-builder' ),
					'4' => __( '4', 'neuron-builder' ),
					'5' => __( '5', 'neuron-builder' ),
					'6' => __( '6', 'neuron-builder' ),
				],
				'default' => '2',
				'selectors_dictionary' => [
					'1' => '100%',
					'2' => '50%',
					'3' => '33.3333%',
					'4' => '25%',
					'5' => '50%',
					'6' => '16.666%',
				],
				'selectors' => [
					'(mobile){{WRAPPER}} .l-neuron-grid--metro .l-neuron-grid__item' => 'max-width: {{VALUE}} !important; flex: 0 0 {{VALUE}}!important'
				],
				'condition' => [
					'layout' => 'metro'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
			    'label' => __( 'Image Size', 'neuron-builder' ),
				'name' => 'thumbnail_image',
				'default' => 'large',
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
			    'label' => __( 'Image Ratio', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.66,
				],
				'condition' => [
					'layout!' => [ 'masonry', 'metro' ],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__thumbnail--link' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->add_responsive_control( 
			'image_width', [ 
				'label' => __( 'Image Width', 'neuron-builder' ),
				'size_units' => [ '%', 'px', 'rem' ],
				'default' => [
					'unit' => '%'
				],
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__thumbnail img' => 'width: {{SIZE}}{{UNIT}}',
				],
			] 
		);

		$this->add_control(
			'lightbox',
			[
				'label' => __( 'Lightbox', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'lightbox_details',
			[
				'label' => __( 'Lightbox Details', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'title' => __( 'Title', 'neuron-builder' ),
					'caption' => __( 'Caption', 'neuron-builder' ),
					'description' => __( 'Description', 'neuron-builder' ),
					'none' => __( 'None', 'neuron-builder' ),
				],
				'default' => 'title',
				'condition' => [
					'lightbox' => 'yes'
				],
			]
		);

		$this->add_control(
			'image_details',
			[
				'label' => __( 'Image Details', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => [
					'title' => __( 'Title', 'neuron-builder' ),
					'caption' => __( 'Caption', 'neuron-builder' ),
					'description' => __( 'Description', 'neuron-builder' ),
				],
				// 'default' => [ 'caption' ],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h4',
				'condition' => [
					'image_details' => 'title',
					'overlay!' => 'image-details'
				]
			]
		);

		$this->add_control(
			'overlay',
			[
				'label' => __( 'Overlay', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'icon' => __( 'Icon', 'neuron-builder' ),
					'image-details' => __( 'Image Details', 'neuron-builder' ),
				],
				'default' => 'none',
				'separator' => 'before',
				'prefix_class' => 'm-neuron-gallery--overlay-',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'overlay_icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa far fa-eye',
					'library' => 'fa-regular',
				],
				'condition' => [
					'overlay' => 'icon'
				]
			]
		);

		$this->add_control(
			'overlay_image_details',
			[
				'label' => __( 'Overlay Image Details', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => [
					'title' => __( 'Title', 'neuron-builder' ),
					'caption' => __( 'Caption', 'neuron-builder' ),
					'description' => __( 'Description', 'neuron-builder' ),
				],
				'default' => [ 'title' ],
				'condition' => [
					'overlay' => 'image-details'
				]
			]
		);

		$this->add_control(
			'html_tag_overlay',
			[
				'label' => __( 'HTML Tag', 'neuron-builder' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'a',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utils::get_hover_animations(),
				'default' => 'translate',
				'condition' => [
					'overlay' => 'image-details',
				],
				'prefix_class' => 'm-neuron-gallery__overlay--hover-animation-',
				'frontend_available' => true
			]
		);

		$this->end_controls_section();
	}
	
	protected function register_filters_controls() {
		$this->start_controls_section( 
			'filters_section', [ 
				'label' => __( 'Filters', 'neuron-builder' ),
				'condition' => [
					'gallery_type' => 'multiple',
					'carousel!' => 'yes'
				]
			] 
		);

		$this->add_control( 
			'filters', [ 
				'label' => __( 'Filters', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'carousel!' => 'yes'
				],
			] 
		);

		$this->add_control( 
			'filters_style', [ 
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'normal' => __( 'Normal', 'neuron-builder' ),
					'dropdown' => __( 'Dropdown', 'neuron-builder' ),
				],
				'default' => 'normal',
				'condition' => [
					'filters' => 'yes',
				]
			] 
		);

		$this->add_responsive_control( 
			'filters_columns', [ 
				'label' => __( 'Columns', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Auto', 'neuron-builder' ),
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters ul' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr);'
				],
				'condition' => [
					'filters' => 'yes',
				]
			] 
		);

		$this->add_control( 
			'filters_prefix', [ 
				'label' => __( 'Prefix', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Show Me', 'neuron-builder' ),
				'condition' => [
					'filters' => 'yes',
					'filters_style' => 'dropdown'
				]
			] 
		);

		$this->add_control( 
			'filters_close_click', [ 
				'label' => __( 'Close on Click', 'neuron-builder' ),
				'description' => __( 'Close dropdown menu on click' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'neuron-builder' ),
				'label_off' => __( 'Off', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'filters' => 'yes',
					'filters_style' => 'dropdown',
				]
			] 
		);

		$this->add_control( 
			'filter_all', [ 
				'label' => __( 'Filter All', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'filters' => 'yes',
				]
			] 
		);

		$this->add_control( 
			'filter_all_string', [ 
				'label' => __( 'String', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'All', 'neuron-builder' ),
				'condition' => [
					'filters' => 'yes',
					'filter_all' => 'yes',
				]
			] 
		);

		$this->end_controls_section();
	}

	protected function register_layout_style_controls() {
		$this->start_controls_section( 
			'layout_style_section', [ 
				'label' => __( 'Layout', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			] 
		);

		$this->add_responsive_control( 
			'columns_gap', [ 
				'label' => __( 'Columns Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'condition' => [
					'carousel!' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .l-neuron-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .l-neuron-grid .l-neuron-grid__item' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2)',
				]
			] 
		);

		$this->add_responsive_control( 
			'space_between', [ 
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
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
				'condition' => [
					'carousel' => 'yes',
				],
			] 
		);

		$this->add_responsive_control( 
			'row_gap', [ 
				'label' => __( 'Row Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'condition' => [
					'carousel!' => 'yes',
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .l-neuron-grid' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .l-neuron-grid__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			] 
		);

		$this->add_control( 
			'animation', [ 
				'label' => __( 'Initial Animation', 'neuron-builder' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => 'yes'
			] 
		);

		$this->start_popover();

        $this->add_responsive_control( 
			'neuron_animations', [ 
				'label' => __( 'Entrance Animation', 'neuron-builder' ),
				'type' => Controls_Manager::ANIMATION,
				'custom_control' => 'add_responsive_control',
				'default' => 'h-neuron-animation--slideUp',
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

		$this->add_control( 
			'alignment', [ 
				'label' => __('Alignment', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
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
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail' => 'text-align: {{VALUE}}' 
				],
			] 
		);

		// @TODO: Fix it or remove it
		$this->add_responsive_control( 
			'spacing_offset', [ 
				'label' => __( 'Offset', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'l-neuron-grid-wrapper%s--offset-',
				'condition' => [
					'carousel!' => 'yes',
					'layout!' => 'grid'
				]
			] 
		);

		$this->end_controls_section();
	}

	protected function register_box_style_controls() {
		$this->start_controls_section( 
			'box_style_section', [ 
				'label' => __( 'Box', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			] 
		);

		$this->add_control( 
			'border_width', [ 
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);

		$this->add_control( 
			'border_radius', [ 
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner' => 'border-radius: {{SIZE}}{{UNIT}}',
				]
			] 
		);

		$this->add_responsive_control( 
			'padding', [ 
				'label' => __( 'Padding', 'neuron-builder' ),
				'size_units' => [ 'px', 'rem', '%' ],
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);


		$this->add_control( 
			'hr_box', [ 
				'type' => Controls_Manager::DIVIDER,
			] 
		);

		$this->start_controls_tabs( 'box_tabs' );

		// Normal
		$this->start_controls_tab( 'box_normal_tab', [ 'label' => __( 'Normal', 'neuron-builder' ) ] );

		$this->add_group_control( 
			Group_Control_Box_Shadow::get_type(), [ 
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-gallery__inner'
			] 
		);

		$this->add_control( 
			'box_background_color', [ 
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner' => 'background-color: {{VALUE}}',
				],
			] 
		);

		$this->add_control( 
			'box_border_color', [ 
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner' => 'border-color: {{VALUE}}',
				],
			] 
		);

		$this->end_controls_tab();


		// Hover
		$this->start_controls_tab( 'box_hover_tab', [
            'label' => __( 'Hover', 'neuron-builder' ),
		] );
		
		$this->add_group_control( 
			Group_Control_Box_Shadow::get_type(), [ 
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .m-neuron-gallery__inner:hover'
			] 
		);

		$this->add_control( 
			'box_background_color_hover', [ 
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner:hover' => 'background-color: {{VALUE}}',
				],
			] 
		);

		$this->add_control( 
			'box_border_color_hover', [ 
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__inner:hover' => 'border-color: {{VALUE}}',
				],
			] 
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_image_style_controls() {

		$this->start_controls_section( 
			'image_style_section', [ 
				'label' => __( 'Image', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			] 
		);

		$this->add_control( 
			'image_border_radius', [ 
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);

		$this->add_responsive_control( 
			'image_spacing_classic', [ 
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__thumbnail--link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			] 
		);

		$this->start_controls_tabs( 'image_tabs' );
		
        // Normal
		$this->start_controls_tab( 'image_tab_normal', [
			'label' => __( 'Normal', 'neuron-builder' )
		] );

		$this->add_group_control( 
			Group_Control_Css_Filter::get_type(), [ 
				'label' => __( 'CSS Filters', 'neuron-builder' ),
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .m-neuron-gallery__thumbnail img',
			] 
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab( 'image_hover_tab', [
			'label' => __( 'Hover', 'neuron-builder' )
		] );

		$this->add_group_control( 
			Group_Control_Css_Filter::get_type(), [ 
				'label' => __( 'CSS Filters', 'neuron-builder' ),
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .m-neuron-gallery__thumbnail:hover img',
			] 
		);

		$this->add_control( 
			'image_animation_hover', [ 
				'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'zoom-in' => __( 'Zoom In', 'neuron-builder' ),
				],
				'default' => 'none',
				'frontend_available' => true
			] 
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_overlay_style_controls() {

		$this->start_controls_section( 
			'overlay_style_section', [ 
				'label' => __( 'Overlay', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'overlay!' => 'none'
				]
			] 
		);

		$this->add_control( 
			'overlay_background', [ 
				'label' => __( 'Background Type', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'color' => [
						'title' => __('Color', 'neuron-builder'),
						'icon' => 'eicon-paint-brush',
					],
					'gradient' => [
						'title' => __('Gradient', 'neuron-builder'),
						'icon' => 'eicon-barcode',
					],
					'none' => [
						'title' => __('None', 'neuron-builder'),
						'icon' => 'eicon-ban',
					],
				],
				'label_block' => false,
				'render_type' => 'ui',
				'prefix_class' => 'm-neuron-gallery__overlay--bg-',
				'condition' => [
					'hover_animation' => ['translate', 'scale']
				]
			] 
		);

		$this->add_control( 
			'overlay_color', [ 
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(51, 51, 51, 0.8)',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'overlay_background' => ['color', 'gradient'],
					'hover_animation' => ['translate', 'scale']
				],
			] 
		);

		$this->add_control( 
			'overlay_color_stop', [ 
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'render_type' => 'ui',
				'condition' => [
					'overlay_background' => ['gradient'],
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_control( 
			'overlay_color_b', [ 
				'label' => __( 'Second Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f2295b',
				'render_type' => 'ui',
				'condition' => [
					'overlay_background' => ['gradient'],
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_control( 
			'overlay_color_b_stop', [ 
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'render_type' => 'ui',
				'condition' => [
					'overlay_background' => ['gradient'],
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_control( 
			'overlay_gradient_type', [ 
				'label' => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'linear' => __( 'Linear', 'neuron-builder' ),
					'radial' => __( 'Radial', 'neuron-builder' ),
				],
				'default' => 'linear',
				'render_type' => 'ui',
				'condition' => [
					'overlay_background' => ['gradient'],
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_control( 
			'overlay_gradient_angle', [ 
				'label' => __( 'Angle', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'default' => [
					'unit' => 'deg',
					'size' => 180,
				],
				'range' => [
					'deg' => [
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{overlay_color.VALUE}} {{overlay_color_stop.SIZE}}{{overlay_color_stop.UNIT}}, {{overlay_color_b.VALUE}} {{overlay_color_b_stop.SIZE}}{{overlay_color_b_stop.UNIT}})',
				],
				'condition' => [
					'overlay_background' => ['gradient'],
					'overlay_gradient_type' => 'linear',
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_control( 
			'overlay_gradient_position', [ 
				'label' => __( 'Position', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'center center' => __( 'Center Center', 'neuron-builder' ),
					'center left' => __( 'Center Left', 'neuron-builder' ),
					'center right' => __( 'Center Right', 'neuron-builder' ),
					'top center' => __( 'Top Center', 'neuron-builder' ),
					'top left' => __( 'Top Left', 'neuron-builder' ),
					'top right' => __( 'Top Right', 'neuron-builder' ),
					'bottom center' => __( 'Bottom Center', 'neuron-builder' ),
					'bottom left' => __( 'Bottom Left', 'neuron-builder' ),
					'bottom right' => __( 'Bottom Right', 'neuron-builder' ),
				],
				'default' => 'center center',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{overlay_color.VALUE}} {{overlay_color_stop.SIZE}}{{overlay_color_stop.UNIT}}, {{overlay_color_b.VALUE}} {{overlay_color_b_stop.SIZE}}{{overlay_color_b_stop.UNIT}})',
				],
				'condition' => [
					'overlay_background' => ['gradient'],
					'overlay_gradient_type' => 'radial',
					'hover_animation' => ['translate', 'scale']
				],
				'of_type' => 'gradient',
			] 
		);

		$this->add_responsive_control( 
			'overlay_spacing', [ 
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'hover_animation' => ['translate', 'scale']
				]
			] 
		);

		$this->add_responsive_control( 
			'overlay_content_padding', [ 
				'label' => __( 'Content Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'hover_animation' => ['translate', 'scale']
				]
			] 
		);

		$this->add_control( 
			'hr_overlay', [ 
				'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'hover_animation' => ['translate', 'scale'],
					'overlay_background!' => 'none'
				]
			] 
		);

		$this->add_control( 
			'overlay_active', [ 
				'label' => __( 'Active', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'active',
				'condition' => [
					'hover_animation' => ['translate', 'scale'],
					'overlay_background!' => 'none'
				],
				'prefix_class' => 'm-neuron-gallery__overlay--'
			] 
		);

		$this->add_control( 
			'overlay_active_reverse', [ 
				'label' => __( 'Active Reverse', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'reverse',
				'condition' => [
					'overlay_active' => 'active',
					'hover_animation' => ['translate', 'scale'],
					'overlay_background!' => 'none'
				],
				'prefix_class' => 'm-neuron-gallery__overlay--'
			] 
		);

		$this->add_control( 
			'overlay_h_position', [ 
				'label' => __('Horizontal', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'neuron-builder'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'neuron-builder'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'neuron-builder'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'separator' => 'before',
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'align-items: {{VALUE}}',
				],
			] 
		);

		$this->add_control( 
			'overlay_v_position', [ 
				'label' => __('Vertical', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Top', 'neuron-builder'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __('Center', 'neuron-builder'),
						'icon' => 'eicon-v-align-middle',
					],
					'right' => [
						'title' => __('Bottom', 'neuron-builder'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay' => 'justify-content: {{VALUE}}',
				],
			] 
		);

		$this->add_control(
			'tooltip_heading', [
				'label' => __('Tooltip', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'hover_animation' => ['tooltip']
				],
			]	
		);

		$this->add_control(
			'tooltip_color', [
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'.m-neuron-gallery--tooltip__detail' => 'color: {{VALUE}}',
				],
				'condition' => [
					'hover_animation' => ['tooltip']
				],
			]
		);
      
		$this->add_control(
			'tooltip_background_color', [
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.m-neuron-gallery--tooltip__detail' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'hover_animation' => ['tooltip']
				],
			]
		);

		$this->add_control(
			'overlay_icon_color', [
				'label' => __( 'Icon Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'overlay' => ['icon']
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'overlay_icon_size', [
				'label' => __( 'Icon Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__overlay i' => 'font-size: {{SIZE}}{{UNIT}}'
				],
				'condition' => [
					'overlay' => ['icon']
				]
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_style_controls() {
		
		$this->start_controls_section( 
			'content_style_section', [ 
				'label' => __( 'Content', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			] 
		);

		$this->add_control(
			'title_heading', [
				'label' => __('Title', 'neuron-builder'),
				'type' => Controls_Manager::HEADING
			]	
		);

		$this->add_control(
			'title_color', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--title' => 'color: {{VALUE}}'
				]
			]	
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'label' => __( 'Typography', 'neuron-builder' ),
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-gallery__image-detail--title',
			]	
		);

		$this->add_responsive_control(
			'title_spacing', [
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'after',
			]	
		);
		
		$this->add_control(
			'caption_heading', [
				'label' => __('Caption', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
			]	
		);

		$this->add_control(
			'caption_color', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--caption' => 'color: {{VALUE}}'
				],
			]	
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'label' => __( 'Typography', 'neuron-builder' ),
				'name' => 'caption_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-gallery__image-detail--caption',
			]	
		);

		$this->add_control(
			'caption_spacing', [
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--caption' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'after',
			]	
		);

		$this->add_control(
			'description_heading', [
				'label' => __('Description', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
			]	
		);
       
		$this->add_control(
			'description_color', [
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--description' => 'color: {{VALUE}}'
				],
			]	
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'label' => __( 'Typography', 'neuron-builder' ),
				'name' => 'description_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-gallery__image-detail--description',
			]	
		);

		$this->add_responsive_control(
			'description_spacing', [
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-detail--description' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				],
			]	
		);

		$this->add_control(
			'content_bg_color', [
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-details' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]	
		);

		$this->add_responsive_control( 
			'content_padding', [ 
				'label' => __( 'Content Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);

		$this->add_responsive_control( 
			'content_margin', [ 
				'label' => __( 'Content Margin', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-gallery__image-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-gallery__image-details',
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
					'carousel' => 'yes', 
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

	protected function register_filters_style_controls() {
		$this->start_controls_section( 
			'filters_style_section', [ 
				'label' => __( 'Filters', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [ 'filters' => 'yes' ]
			] 
		);

		$this->add_responsive_control(
			'filters_space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters ul' => 'grid-row-gap: {{SIZE}}{{UNIT}}; grid-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_dropdown_spacing',
			[
				'label' => __( 'Dropdown Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters--dropdown.active' => 'padding: {{SIZE}}{{UNIT}} 0 !important;',
				],
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_responsive_control(
			'filters_alignment',
			[
				'label' => __('Alignment', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
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
				'condition' => [
					'filters' => 'yes'
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters ul' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'neuron-builder' ),
				'name' => 'filters_typography',
				'selector' => '{{WRAPPER}} .m-neuron-filters',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->start_controls_tabs( 'filters_tabs' );

		$this->start_controls_tab( 'filters_tabs_normal', [ 'label' => __( 'Normal', 'neuron-builder' ) ] );

		$this->add_control(
			'filters_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters__item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_active_color',
			[
				'label' => __( 'Active Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters--dropdown__active span' => 'color: {{VALUE}}',
				],
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'filters_tabs_hover_active', [ 'label' => __( 'Hover & Active', 'neuron-builder' ) ] );

		$this->add_control(
			'filters_color_hover',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters__item:hover, {{WRAPPER}} .m-neuron-filters__item.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_control(
			'filters_dropdown_heading',
			[
				'label' => __( 'Dropdown', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Dropdown Typography', 'neuron-builder' ),
				'name' => 'filters_dropdown_typography',
				'selector' => '{{WRAPPER}} .m-neuron-filters--dropdown',
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Active Typography', 'neuron-builder' ),
				'name' => 'filters_active_typography',
				'selector' => '{{WRAPPER}} .m-neuron-filters--dropdown__active span',
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label' => __( 'Border', 'neuron-builder' ),
				'name' => 'filters_active_border',
				'selector' => '{{WRAPPER}} .m-neuron-filters--dropdown__active span',
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_control(
			'filters_active_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters--dropdown__active span' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->add_responsive_control(
			'filters_active_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-filters--dropdown__active span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'filters_style' => 'dropdown'
				]
			]
		);

		$this->end_controls_section(); 
	}

	protected function get_filters() {
		if  ( ( $this->get_settings('filters') != 'yes' && ! empty ( $this->filters ) ) || $this->get_settings( 'gallery_type' ) == 'single' || $this->get_settings( 'carousel' ) == 'yes' ) {
			return;
		}

		$this->filters = apply_filters( 'neuron/element/gallery/filters', $this->filters, $this->get_settings( 'gallery_type' ) );
		?>
			<div class="m-neuron-filters" aria-label="<?php echo esc_html__( 'Filters', 'neuron-builder' ) ?>">
				<ul <?php echo $this->get_render_attribute_string( 'filters' ) ?>>
					<?php if ( $this->get_settings( 'filter_all' ) == 'yes' ) : ?>
						<li class="m-neuron-filters__item active" data-filter="*"><?php echo $this->get_settings( 'filter_all_string' ) ? $this->get_settings( 'filter_all_string' ) : esc_html__( 'All', 'neuron-builder' ) ?></li>
					<?php endif; ?>

					<?php foreach( $this->filters as $filter ) : ?>
						<?php $filterSlug = preg_replace( '/\s*/', '', $filter ); ?>
						<li class="m-neuron-filters__item" data-filter="<?php echo esc_attr( strtolower( $filterSlug ) ) ?>"><?php echo esc_attr( $filter ) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
	}

	protected function get_lightbox_details( $gallery_attachment ) {
		$lightbox_details = $this->get_settings('lightbox_details');
		$output = [];

		if ( empty ( $lightbox_details ) || $lightbox_details == 'none' ) {
			return;
		}

		$details = [
			'title' => $gallery_attachment->post_title,
			'description' => $gallery_attachment->post_content,
			'caption' => $gallery_attachment->post_excerpt
		];

		if ( $details[$lightbox_details] ) {
			$output[$lightbox_details] = $details[$lightbox_details]; 
		}

		return $output;
	}

	protected function get_image_details( $gallery_attachment ) {
		$image_details = $this->get_settings('image_details');

		if ( empty ( $image_details ) ) {
			return;
		}

		$details = [
			'title' => $gallery_attachment->post_title,
			'description' => $gallery_attachment->post_content,
			'caption' => $gallery_attachment->post_excerpt
		];

		$details = apply_filters( 'neuron/element/gallery/image_details', $details, $gallery_attachment, $image_details );
	?>
			<<?php echo $this->get_settings( 'html_tag' ) ?> class="m-neuron-gallery__image-details">
				<?php 
				foreach ( $image_details as $detail ) : 
					if ( $details[$detail] ) :
				?>
					<span class="m-neuron-gallery__image-detail m-neuron-gallery__image-detail--<?php echo esc_attr( $detail ) ?>"><?php echo $details[$detail] ?></span>
				<?php 
					endif;
				endforeach; 
				?>
			</<?php echo $this->get_settings( 'html_tag' ) ?>>
		<?php
	}

	protected function get_overlay( $gallery_attachment ) {
		$overlay = $this->get_settings( 'overlay' );
		$overlay_image_details = $this->get_settings( 'overlay_image_details' );

		$output = [];

		$details = [
			'title' => $gallery_attachment->post_title,
			'description' => $gallery_attachment->post_content,
			'caption' => $gallery_attachment->post_excerpt
		];

		if ( $overlay == 'icon' ) {
			Icons_Manager::render_icon( $this->get_settings( 'overlay_icon' ), [ 'aria-hidden' => 'true' ] );
			return;
		} else if ( $overlay == 'image-details' && ! empty ( $overlay_image_details ) ) {
			foreach ( $overlay_image_details as $image_detail ) {
				if ( $details[$image_detail] ) {
					$output[] = '<span class="m-neuron-gallery__overlay--detail m-neuron-gallery__image-detail--'. esc_attr( $image_detail ) .'">'. esc_attr( $details[$image_detail] ) .'</span>';
				}
			}
		}

		$output = apply_filters( 'neuron/element/gallery/overlay', $output, $gallery_attachment );

		echo implode( $output, '' );
	}

	/**
	 * Packery
	 * 
	 * Loads Packery in 
	 * Masonry & Metro.
	 * 
	 * @since 1.0.0
	 */
	protected function load_packery() {
		$isMetroMasonry = $this->get_settings( 'layout' ) == 'masonry' || $this->get_settings( 'layout' ) == 'metro';

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() == true && $isMetroMasonry ) {
		?>
		<script>
			var grid = new Packery( '.l-neuron-grid[data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) )  ?>"]', {
				itemSelector: '.l-neuron-grid__item'
			} );

			window.document.onload = function () {
				grid.layout();
			}	
		</script>
		<?php
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Gallery
		$galleries = [];
		$filters = [];

		if ( $settings['gallery_type'] == 'multiple' ) {
			$multiple_gallery = $settings['galleries'];

			if ( ! empty ( $multiple_gallery ) ) {
				foreach ( $multiple_gallery as $index => $gallery ) {

					if ( $gallery['gallery_title'] ) {
						$this->filters[] = $gallery['gallery_title'];
					}

					if ( isset( $gallery['multiple_gallery'] ) && is_array( $gallery['multiple_gallery'] ) ) {
						foreach( $gallery['multiple_gallery'] as $key => $innerGallery ) {
							$title = strtolower( preg_replace( '/\s*/', '', $gallery['gallery_title'] ) );
							$galleries[$key . '=='  . $title] = $innerGallery;
						}
					} else {
						$galleries[] = $gallery['multiple_gallery'];
					}
				}
			}
		} else {
			$galleries = $settings['gallery'];
		}

		// Random
		if ( $settings['orderby'] == 'rand' && ! empty( $galleries ) ) {
			shuffle( $galleries );
		}

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );

		// Grid
		$this->add_render_attribute( 'grid', 'class', [
			$settings['carousel'] == 'yes' ? 'swiper-wrapper neuron-slides' : 'l-neuron-grid',
		] );

		$this->add_render_attribute( 'grid', 'data-masonry-id', [
			esc_attr( md5( $this->get_id() ) ) 
		] );

		// Carousel
		if ( $settings['carousel'] == 'yes' ) {
			$article_class = 'swiper-slide';
		} else {
			$article_class = 'l-neuron-grid__item';
		}

		// Filters
		echo $this->get_filters();

		// Grid Class
		$this->add_render_attribute( 'neuron-swiper', 'class', [
			'neuron-swiper',
		] );

		// Prevent Flickr
		if ( $settings['carousel'] == 'yes' && $settings['animation'] != 'yes' ) {
			$this->add_render_attribute( 'neuron-swiper', 'class', [
				'neuron-swiper--prevent-flickr',
			] );
		}

		if ( ! empty ( $galleries ) ) {
		?>	
		<?php if ( $settings['carousel'] == 'yes' ) : ?>
			<div <?php echo $this->get_render_attribute_string( 'neuron-swiper' ) ?>>
				<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
		<?php endif; // End Carousel ?>

				<div <?php echo $this->get_render_attribute_string( 'grid' ) ?>>
					<?php foreach ( $galleries as $key => $gallery ) { 

						$id = $gallery['id'];
						$gallery_src = wp_get_attachment_image_src( $id, $settings['thumbnail_image_size'] );
						$gallery_attachment = get_post( $id );
						$metro_column = get_post_meta($gallery_attachment->ID, '_metro_column', true);
						$external_url = get_post_meta($gallery_attachment->ID, '_external_url', true);

						if ( ! $gallery_src ) {
							continue;
						}

						$gallery_data = [
							'title' => $gallery_attachment->post_title,
							'caption' => $gallery_attachment->post_excerpt,
							'description' => $gallery_attachment->post_content,
							'alt' => get_post_meta( $gallery_attachment->ID, '_wp_attachment_image_alt', true ),
							'media' => wp_get_attachment_image_src( $id, 'full' )['0'],
							'src' => $gallery_src[0],
							'width' => $gallery_src[1],
							'height' => $gallery_src[2],
						];

						// Before Item
						$article_class = apply_filters( 'neuron/element/gallery/before_item_class', $article_class, $gallery_attachment );

						$this->add_render_attribute( 'gallery_item_url_' . $id,
							[
								'class' => [
									$article_class,
									'm-neuron-gallery__item',
								],
								'data-elementor-open-lightbox' => $settings['lightbox'] == 'yes' ? 'yes' : 'no',
								'data-elementor-lightbox-slideshow' => 'all-'. $this->get_ID(),
								'data-id' => $id
							]
						);

						// External URL & Lightbox Option
						if ( $settings['lightbox'] != 'yes' ) {
							if  ( $external_url ) {
								$this->add_render_attribute( 'gallery_item_url_' . $id, [ 'href' => $external_url ] ); 
							}
						} else {
							if ( $settings['html_tag_overlay'] == 'a' ) {
								$this->add_render_attribute( 'gallery_item_url_' . $id, [ 'href' => $gallery_data['media'] ] );
							}
						}

						// Filters
						if ( $settings['gallery_type'] == 'multiple' ) {
							$filter_class = explode( "==", $key ); 

							$this->add_render_attribute( 'gallery_item_url_' . $id,
								[
									'class' => isset( $filter_class[1] ) ? $filter_class[1] : ''
								]
							);
						}

						// Animations
						if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
							$this->add_render_attribute( 'gallery_item_url_' . $id,
							[
								'class' => 'h-neuron-animation--wow'
							]);
						}

						// Lightbox Details
						if ( $this->get_settings('lightbox_details') != 'none' ) {
							$this->add_render_attribute( 'gallery_item_url_' . $id,
								[
									'data-elementor-lightbox-title' => $this->get_lightbox_details( $gallery_attachment ),
								]
							);
						}

						$this->add_render_attribute( 'gallery_item_image_' . $id,
							[
								'src' => $gallery_data['src'],
							]
						);

						// Alt attribute
						if ( ! empty ( $gallery_data['alt'] ) ) {
							$this->add_render_attribute( 'gallery_item_image_' . $id,
								[
									'alt' => $gallery_data['alt'],
								]
							);	
						}

						// Metro Column
						if ( $settings['layout'] == 'metro' && isset( $metro_column ) ) {
							$this->add_render_attribute( 'gallery_item_url_' . $id,
								[
									'data-metro-column' => $metro_column ? $metro_column : '3'
								]
							);
						}

						// Check for SVG
						if ( ! empty( $gallery_data['src'] ) && strpos( $gallery_data['src'], '.svg' ) ) {
							$this->add_render_attribute( 'gallery_item_url_' . $id,
								[
									'class' => 'm-neuron-gallery__item--svg-img'
								]
							);
						}
						
						// Before Item Start
						do_action( 'neuron/element/gallery/before_item_start', $this, $id, $gallery_attachment );
					?>
						<<?php echo $this->get_settings( 'html_tag_overlay' ) ?> <?php echo $this->get_render_attribute_string( 'gallery_item_url_' . $id ); ?>>

							<div class="m-neuron-gallery__inner">

								<?php do_action( 'neuron/element/gallery/after_inner_start', $this, $id, $gallery_attachment ) ?>

								<div class="m-neuron-gallery__thumbnail--link">
									<div class="m-neuron-gallery__thumbnail">
										<img <?php echo $this->get_render_attribute_string( 'gallery_item_image_' . $id ); ?> />
									</div>
									<?php if ( $settings['overlay'] != 'none' ) : ?>
										<div class="m-neuron-gallery__overlay">
											<?php $this->get_overlay( $gallery_attachment ) ?>
										</div>
									<?php endif; ?>
								</div>
								<?php echo $this->get_image_details( $gallery_attachment ) ?>

								<?php do_action( 'neuron/element/gallery/before_inner_end', $this, $id, $gallery_attachment ) ?>

							</div>

						</<?php echo $this->get_settings( 'html_tag_overlay' ) ?>>

						<?php do_action( 'neuron/element/gallery/after_item_end', $this, $id, $gallery_attachment ) ?>

					<?php } ?>
				</div>

			<?php if ( $settings['carousel'] == 'yes' ) : ?>
				</div>

			<?php if ( $settings['dots_style'] == 'scrollbar' ) : ?>
				<div class="swiper-scrollbar"></div>
			<?php endif; ?>

			<?php if ( $carousel_dots ) : ?>
				<div class="swiper-pagination neuron-swiper-dots" data-swiper-pagination-id="<?php echo md5( $this->get_id() ) ?>"></div>
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
			</div>
		<?php endif; // End Carousel ?>
		<?php
			$this->load_packery();
		}
	}

	protected function content_template() {}
}
