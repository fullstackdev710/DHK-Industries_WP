<?php
namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Categories extends Base_Widget {

	protected $query_args;

	protected $terms = [];

	public function get_name() {
		return 'neuron-woo-categories';
	}

	public function get_title() {
		return __( 'Product Categories', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-categories neuron-badge';
	}

	public function get_keywords() {
		return [ 'shop', 'store', 'categories', 'product', 'woo', 'woocommerce' ];
	}

	public function get_categories() {
		return [
			'neuron-woo-elements',
		];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'neuron-packery', 'neuron-object-fit' ];
	}

	public function get_columns() {
		return  [
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
            '11' => '11',
            '12' => '12',
        ];
	}

	public function get_selectors_dictionary() {
		return  [
            '1' => '8.33',
            '2' => '16.66',
            '3' => '25',
            '4' => '33.33',
            '5' => '41.66',
            '6' => '50',
            '7' => '58.33',
            '8' => '66.67',
            '9' => '75',
            '10' => '83.33',
            '11' => '91.66',
            '12' => '100',
        ];
	}

	public function get_woo_categories() {
		
		$output = [];

		$terms = get_terms([
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		] );

		if ( ! empty ( $terms ) ) {
			foreach ( $terms as $term ) {
				$output[$term->term_id] = $term->name;
			}
		}

		return $output;
	}

	protected function get_carousel_popover() {
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
				'default' => 'yes',
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

		$this->end_popover();
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'neuron-builder' ),
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
				'mobile_default' => '1',
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
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
				],
				'default' => 1,
				'tablet_default' => 1,
				'mobile_default' => 0,
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

		$this->get_carousel_popover();

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
					'columns!' => '0',
					'carousel!' => 'yes',
				],
				'frontend_available' => true,
				'prefix_class' => 'm-neuron-posts--layout-',
				'render_type' => 'template',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'large',
			]
		);

		$this->add_control(
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
					'layout!' => ['masonry', 'metro'],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post .m-neuron-post__thumbnail--link' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
				'default' => 'h3',
				'condition' => [
					'title' => 'yes'
				]
			]
		);

		$this->add_control(
			'count',
			[
				'label' => __( 'Count', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'meta_type',
			[
				'label' => __( 'Meta Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inside' => __( 'Inside', 'neuron-builder' ),
					'outside' => __( 'Outside', 'neuron-builder' ),
				],
				'default' => 'outside',
				'prefix_class' => 'm-neuron-category--',
				'render_type' => 'template'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'show-all' => __( 'Show All', 'neuron-builder' ),
					'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
				],
				'default' => 'show-all',
			]
		);

		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->get_woo_categories(),
				'multiple' => true,
				'condition' => [
					'source' => 'manual-selection'
				]
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' => __( 'Hide Empty', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'name' => __( 'Name', 'neuron-builder' ),
					'slug' => __( 'Slug', 'neuron-builder' ),
					'description' => __( 'Description', 'neuron-builder' ),
					'count' => __( 'Count', 'neuron-builder' ),
				],
				'default' => 'name',
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'asc' => __( 'Ascending', 'neuron-builder' ),
					'desc' => __( 'Descending', 'neuron-builder' ),
				],
				'default' => 'desc',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'query_metro_section',
			[
				'label' => __( 'Metro', 'neuron-builder' ),
				'condition' => [
					'layout' => 'metro',
					'carousel!' => 'yes'
				]
			]
		);

		$columns = [
            '1' => '1' . ' ' . __( 'Column', 'neuron-builder' ),
            '2' => '2' . ' ' . __( 'Column', 'neuron-builder' ),
            '3' => '3' . ' ' . __( 'Column', 'neuron-builder' ),
            '4' => '4' . ' ' . __( 'Column', 'neuron-builder' ),
            '5' => '5' . ' ' . __( 'Column', 'neuron-builder' ),
            '6' => '6' . ' ' . __( 'Column', 'neuron-builder' ),
            '7' => '7' . ' ' . __( 'Column', 'neuron-builder' ),
            '8' => '8' . ' ' . __( 'Column', 'neuron-builder' ),
            '9' => '9' . ' ' . __( 'Column', 'neuron-builder' ),
            '10' => '10' . ' ' . __( 'Column', 'neuron-builder' ),
            '11' => '11' . ' ' . __( 'Column', 'neuron-builder' ),
            '12' => '12' . ' ' . __( 'Column', 'neuron-builder' ),
        ];

        $selectors_dictionary = [
            '1' => '8.33',
            '2' => '16.66',
            '3' => '25',
            '4' => '33.33',
            '5' => '41.66',
            '6' => '50',
            '7' => '58.33',
            '8' => '66.67',
            '9' => '75',
            '10' => '83.33',
            '11' => '91.66',
            '12' => '100',
		];
		
		$repeater = new Repeater();

		$repeater->add_control(
			'post', [
                'type' => Controls_Manager::TEXT,
                'show_label' => false,
                'label_block' => true,
			]
		);

		$repeater->add_control(
			'column', [
                'show_label' => false,
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
                'options' => $columns,
                'default' => '3',
                'selectors_dictionary' => $selectors_dictionary,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:not(.swiper-slide)' => 'flex: 0 0 {{VALUE}}%; max-width: {{VALUE}}%;',
                ],
                'render_type' => 'template',
 			]
        );

		$this->add_control(
			'neuron_metro_reset',
			[
				'label' => __( 'Reset Metro', 'neuron-builder' ),
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'success neuron-reset-metro',
				'text' => __( 'Reset', 'neuron-builder' ),
				'event' => 'neuron:editor:metro:reset',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'neuron_metro',
			[
				'label' => __( 'Metro', 'neuron-builder' ),
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ post }}}',
				'item_actions' => [
					'add' => false,
					'duplicate' => false,
					'remove' => false,
					'sort' => false,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => __( 'Layout', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'columns_gap',
			[
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
				'condition' => [
					'carousel' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Row Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'frontend_available' => true,
				'condition' => [
					'carousel!' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .l-neuron-grid' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .l-neuron-grid__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
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

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'start' => 'flex-start',
					'end' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__text' => 'justify-content: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'offset',
			[
				'label' => __( 'Offset', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'l-neuron-grid-wrapper%s--offset-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Box', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_type' => 'outside'
				]
			]
		);

		$this->add_control(
			'box_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_content_padding',
			[
				'label' => __( 'Content Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'box_tabs' );

		$this->start_controls_tab(
			'normal_box_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-post__inner',
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_box_tab',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-post__inner:hover',
			]
		);

		$this->add_control(
			'hover_box_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_box_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post__inner:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-category__text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__thumbnail--link' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 10,
					'unit' => 'px'
				],
				'condition' => [
					'meta_type' => 'outside'
				]
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'normal_image_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .m-neuron-category__thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_image_tab',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'hover_image_css_filters',
				'selector' => '{{WRAPPER}} .m-neuron-category__thumbnail:hover img',
			]
		);

		$this->add_control(
			'hover_image_animation',
			[
				'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'neuron-builder' ),
					'zoom-in' => __( 'Zoom In', 'neuron-builder' ),
				],
				'default' => '',
				'prefix_class' => 'm-neuron-category--'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_type' => 'outside'
				]
			]
		);

		$this->add_control(
			'heading_content_title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'content_title_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-category__title'
            ]
		);

		$this->add_control(
			'heading_content_count',
			[
				'label' => __( 'Count', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_count_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'content_count_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-category__count'
            ]
		);

		$this->add_control(
			'content_count_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__count' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_overlay',
			[
				'label' => __( 'Item Overlay', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_type' => 'inside'
				]
			]
		);

		$this->add_control( 
			'overlay_active', 
			[
				'label' => __( 'Active', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'm-neuron-portfolio__overlay--'
			]
		);

		$this->add_control(
			'overlay_active_reverse', 
			[
				'label' => __( 'Active Reverse', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'item_overlay_active' => 'active',
				],
				'prefix_class' => 'm-neuron-portfolio__overlay--'
			]
		);

		$this->add_control(
			'overlay_divider', 
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'item_overlay_background', 
			[
				'label' => __( 'Background Type', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'color' => [
						'title' => __( 'Color', 'neuron-builder' ),
						'icon' => 'eicon-paint-brush',
					],
					'gradient' => [
						'title' => __( 'Gradient', 'neuron-builder' ),
						'icon' => 'eicon-barcode',
					],
					'none' => [
						'title' => __( 'None', 'neuron-builder' ),
						'icon' => 'eicon-ban',
					],
				],
				'label_block' => false,
				'render_type' => 'ui',
				'prefix_class' => 'm-neuron-category__overlay--'
			]
		);

		$this->add_control(
			'item_overlay_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__text' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'item_overlay_background' => [ 'color', 'gradient' ],
				],
			]
		);

		$this->add_control(
			'item_overlay_color_stop',
			[
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'render_type' => 'ui',
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_control(
			'item_overlay_color_b',
			[
				'label' => __( 'Second Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f2295b',
				'render_type' => 'ui',
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_control(
			'item_overlay_color_b_stop',
			[
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'render_type' => 'ui',
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_control(
			'item_overlay_gradient_type',
			[
				'label' => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'linear' => __( 'Linear', 'neuron-builder' ),
					'radial' => __( 'Radial', 'neuron-builder' ),
				],
				'default' => 'linear',
				'render_type' => 'ui',
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_control(
			'item_overlay_gradient_angle',
			[
				'label' => __( 'Angle', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
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
					'{{WRAPPER}} .m-neuron-category__text' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{item_overlay_color.VALUE}} {{item_overlay_color_stop.SIZE}}{{item_overlay_color_stop.UNIT}}, {{item_overlay_color_b.VALUE}} {{item_overlay_color_b_stop.SIZE}}{{item_overlay_color_b_stop.UNIT}})',
				],
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
					'item_overlay_gradient_type' => 'linear',
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_control(
			'item_overlay_gradient_position',
			[
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
					'{{WRAPPER}} .m-neuron-category__text' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{item_overlay_color.VALUE}} {{item_overlay_color_stop.SIZE}}{{item_overlay_color_stop.UNIT}}, {{item_overlay_color_b.VALUE}} {{item_overlay_color_b_stop.SIZE}}{{item_overlay_color_b_stop.UNIT}})',
				],
				'condition' => [
					'item_overlay_background' => [ 'gradient' ],
					'item_overlay_gradient_type' => 'radial',
				],
				'of_type' => 'gradient',
			]
		);

		$this->add_responsive_control(
			'item_overlay_spacing',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__text' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom:{{BOTTOM}}{{UNIT}}; left:{{LEFT}}{{UNIT}}; width: auto; height: auto;',
				],
				'condition' => [
					'item_overlay_background!' => 'none' 
				]
			]
		);

		$this->add_responsive_control(
			'item_overlay_content_spacing',
			[
				'label' => __( 'Content Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'item_overlay_background!' => 'none' 
				]
			]
		);

		$this->add_control(
			'overlay_second_divider', 
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'heading_overlay_title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'overlay_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'overlay_title_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-category__title'
            ]
		);

		$this->add_control(
			'heading_overlay_count',
			[
				'label' => __( 'Count', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_count_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
			[
                'name' => 'overlay_count_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-category__count'
            ]
		);

		$this->add_control(
			'overlay_count_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-category__count' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->register_navigation_style_controls();
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

	/**
	 * Query
	 * 
	 * Loads the query of categories.
	 * 
	 * @since 1.0.0
	 */
	protected function get_query() {
		$args = [
			'taxonomy' => 'product_cat',
			'hide_empty' => $this->get_settings( 'hide_empty' ) ? true : false,
			'orderby' => $this->get_settings( 'orderby' ),
			'order' => $this->get_settings( 'order' ),
		];

		if ( $this->get_settings( 'source' ) == 'manual-selection' && ! empty ( $this->get_settings( 'categories' ) ) ) {
			$args['include'] = $this->get_settings( 'categories' ) ? implode( ', ', $this->get_settings( 'categories' ) ) : '';
		}

		$query = get_terms( $args );
		
		$this->query_args = $query;
	}


	/**
	 * Repeater Item
	 * 
	 * Add Repeater Item when metro or
	 * carousel is activated.
	 * 
	 * @since 1.0.0
	 */
	protected function repeater_item( $post_class, $id ) {
		if ( $this->get_settings( 'layout' ) != 'metro' && $this->get_settings( 'carousel' ) != 'yes' ) {
			return;
		}

		if ( $this->get_settings('neuron_metro') ) {
			foreach ( $this->get_settings('neuron_metro') as $metro ) {
				if ( $metro['id'] == $id) {
					return $post_class = ' elementor-repeater-item-' . $metro['_id'];
				}
			}
		}
	}


	/**
	 * Category Thumbnail
	 * 
	 * Displays the image on the top.
	 * 
	 * @since 1.0.0
	 */
	protected function get_category_thumbnail( $term ) {
		$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true ); 

		if ( $this->get_settings( 'thumbnail_size' ) == 'custom' && ( ! empty ( $this->get_settings( 'thumbnail_custom_dimension' )['width'] ) || ! empty ( $this->get_settings( 'thumbnail_custom_dimension' )['height'] ) ) ) {
			$image_size = [
				$this->get_settings( 'thumbnail_custom_dimension' )['width'] ? $this->get_settings( 'thumbnail_custom_dimension' )['width'] : 9999,
				$this->get_settings( 'thumbnail_custom_dimension' )['height'] ? $this->get_settings( 'thumbnail_custom_dimension' )['height'] : 9999,
			];
		} else {
			$image_size = $this->get_settings( 'thumbnail_size' );
		}

		$image = wp_get_attachment_image( $thumbnail_id, $image_size ); 

		if ( $image ) {
			echo '<a class="m-neuron-post__thumbnail--link m-neuron-category__thumbnail--link" href="'. get_term_link( $term->term_id, 'product_cat' ) .'"><div class="m-neuron-post__thumbnail m-neuron-category__thumbnail">'. $image .'</div></a>';
		} else {
			echo '<a class="m-neuron-post__thumbnail--link m-neuron-category__thumbnail--link" href="'. get_term_link( $term->term_id, 'product_cat' ) .'"><div class="m-neuron-post__thumbnail m-neuron-category__thumbnail">' . wc_placeholder_img( $image_size ) . '</div></a>';
		}

	}

	/**
	 * Category Title
	 * 
	 * @since 1.0.0
	 */
	protected function get_category_title( $term ) {
		if ( $this->get_settings( 'title' ) != 'yes' ) {
			return;
		}

		if ( $this->get_settings( 'meta_type' ) == 'inside' ) {
			echo '<' . $this->get_settings( 'html_tag' ) . ' class="m-neuron-category__title">' . $term->name . '</' . $this->get_settings( 'html_tag' ) . '>';
		} else {
			echo '<' . $this->get_settings( 'html_tag' ) . ' class="m-neuron-category__title"><a href="' . esc_url( get_term_link( $term->term_id, 'product_cat' ) )  . '">' . $term->name . '</a></' . $this->get_settings( 'html_tag' ) . '>';
		}
	}

	/**
	 * Category Count
	 * 
	 * @since 1.0.0
	 */
	protected function get_category_count( $term ) {
		if ( $this->get_settings( 'count' ) != 'yes' || ! $term->count ) {
			return;
		}

		echo '<span class="m-neuron-category__count">(' . $term->count . ')</span>';
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
		$isMetroMasonry = ( $this->get_settings( 'layout' ) == 'masonry' || $this->get_settings( 'layout' ) == 'metro' );

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

		$query = $this->get_query();

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );

		if ( $settings['carousel'] == 'yes' ) {
			$gridClass = 'swiper-wrapper neuron-slides';
			$articleClass = 'swiper-slide';
		} else {
			$gridClass = 'l-neuron-grid';
			$articleClass = 'l-neuron-grid__item';
		}

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$articleClass .= ' h-neuron-animation--wow';
		}
		?>
		<?php if ( $settings['carousel'] == 'yes' ) : ?>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
			<?php endif; // End Carousel ?>
				<div class="<?php echo esc_attr( $gridClass ) ?>" data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
					<?php 
					if ( $this->query_args ) {
						foreach ( $this->query_args as $term ) {
							$post_class = '';

							if ( $this->repeater_item( $post_class, $term->term_id ) ) {
								$post_class = $this->repeater_item( $post_class, $term->term_id );
							}
						?>
							<article class="<?php echo esc_attr( $articleClass . ' m-neuron-post m-neuron-category ' . $post_class ) ?>" data-id="<?php echo esc_attr( $term->term_id ) ?>">
								<div class="m-neuron-post__inner m-neuron-category__inner">
									<?php if ( $this->get_settings( 'meta_type' ) == 'inside' ) : ?>
										<a class="m-neuron-category__link" href="<?php echo esc_url( get_term_link( $term->term_id, 'product_cat' ) ) ?>"></a>
									<?php endif; ?>

									<?php $this->get_category_thumbnail( $term ) ?>

									<div class="m-neuron-category__text m-neuron-post__text">
										<?php $this->get_category_title( $term ) ?>
										<?php $this->get_category_count( $term ) ?>
									</div>
								</div>
							</article>
						<?php
						}
					}
					?>
				</div>

			<?php if ( $settings['carousel'] == 'yes' ) : ?>
				</div>

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
			</div>
		<?php endif; // End Carousel ?>
		<?php
		$this->load_packery();
	}
}
