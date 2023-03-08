<?php
/**
 * Interactive Posts
 * 
 * Display the posts when mouseover
 * the title of any kind of post.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\InteractivePosts\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Interactive_Posts extends Base_Widget {

	protected $query_args;

	protected $terms = [];


	public function get_name() {
		return 'neuron-interactive-posts';
	}

	public function get_title() {
		return __( 'Interactive Posts', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-form-vertical neuron-badge';
	}

	public function get_keywords() {
		return [ 'interactive', 'post', 'posts', 'interactive link' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function get_taxonomies() {
        $taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
    }

	public function get_source() {

        $output = Utils::get_public_post_types();

        $output = array_merge( $output, [
            'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
            'related' => __( 'Related', 'neuron-builder' ),
			'current_query' => __( 'Current Query', 'neuron-builder' ),
        ]);

        return $output;
    }

	protected function register_controls() {

        $this->start_controls_section(
			'layout_section',
			[
				'label' => __( 'Layout', 'neuron-builder' )
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => [
					'horizontal'  => __( 'Horizontal', 'neuron-builder' ),
					'vertical' => __( 'Vertical', 'neuron-builder' ),
					'grid' => __( 'Grid', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-interactive-posts--',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 12,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'options' => [
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
				],
				'condition' => [
					'layout' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__links' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'
				]
			]
		);

		$this->add_control(
			'meta_data', 
			[
				'label' => __( 'Meta Data', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => [
					'author' => __( 'Author', 'neuron-builder' ),
					'date' => __( 'Date', 'neuron-builder' ),
					'time' => __( 'Time', 'neuron-builder' ),
					'comments' => __( 'Comments', 'neuron-builder' ),
					'terms' => __( 'Terms', 'neuron-builder' ),
				],
				'default' => [ 'date' ],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_between', 
			[
				'label' => __( 'Separator Between', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '/',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					'meta_data!' => []
				]
			]
		);

		$this->add_control(
			'meta_data_taxonomy', 
			[
				'label' => __('Taxonomy', 'neuron-builder'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'meta_data' => 'terms',
				],
			]
		);

		$this->add_responsive_control(
			'meta_align',
			[
				'label' => __( 'Meta Align', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => __( 'Top', 'neuron-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'neuron-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'm-neuron-interactive-posts--meta-align%s__',
				'condition' => [
					'meta_data!' => []
				]
			]
		);

		$this->add_control(
			'image_coverage', 
			[
				'label' => __( 'Image Coverage', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'element' => __( 'Element', 'neuron-builder' ),
					'full-screen' => __( 'Full Screen', 'neuron-builder' ),
					'text' => __( 'Text', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-interactive-posts--image-cover__',
				'default' => 'element',
				'separator' => 'before',
				'frontend_available' => true,
				'render_type' => 'template'
			]
		);

		$this->end_controls_section();

		// Query
		$this->start_controls_section(
			'query_section',
			[
				'label' => __( 'Query', 'neuron-builder' )
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_source(),
				'default' => 'post'
			]
		);

		$this->start_controls_tabs(
			'source_tabs',
			[
				'condition' => [
					'source!' => [ 'manual-selection', 'current_query' ]
				]
			]
		);

        // Include
		$this->start_controls_tab(
			'include_tab',
			[
				'label' => __( 'Include', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'include_by',
			[
				'label' => __( 'Include By', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'default' => [],
				'options' => [
					'term' => __( 'Term', 'neuron-builder' ),
					'author' => __( 'Author', 'neuron-builder' ),
				],
			]
		);

        // Post Types
        $post_types = Utils::get_public_post_types();

        if ( $post_types ) {
            foreach ( $post_types as $id => $value ) {

				$this->add_control(
					'include_term_' . $id,
					[
						'label' => __( 'Term', 'neuron-builder' ),
						'type' => Controls_Manager::SELECT2,
						'multiple' => true,
						'options' => Utils::get_the_terms( $id ),
						'label_block' => true,
						'default' => [],
						'select2options' => [
							'placeholder' => __( 'All', 'neuron-builder' ),
						],
						'condition' => [
							'include_by' => 'term',
							'source' => $id
						],
					]
				);
            }
        }

		$this->add_control(
			'include_term_related',
			[
				'label' => __( 'Term', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_supported_taxonomies(),
				'label_block' => true,
				'default' => [],
				'condition' => [
					'include_by' => 'term',
					'source' => 'related'
				],
			]
		);

		$this->add_control(
			'include_author',
			[
				'label' => __( 'Author', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_authors(),
				'label_block' => true,
				'default' => [''],
				'select2options' => [
					'placeholder' => __( 'All', 'neuron-builder' ),
				],
				'condition' => [
					'include_by' => 'author'
				],
			]
		);

		$this->end_controls_tab();

		// Exclude
		$this->start_controls_tab(
			'exclude_tab',
			[
				'label' => __( 'Exclude', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'exclude_by',
			[
				'label' => __( 'Exclude By', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'default' => [],
				'options' => [
					'current-post' => __( 'Current Post', 'neuron-builder' ),
					'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
					'term' => __( 'Term', 'neuron-builder' ),
					'author' => __( 'Author', 'neuron-builder' ),
				],
			]
		);

		$this->add_control(
			'exclude_manual',
			[
				'label' => __( 'Manual Selection', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_the_posts( ['all'] ),
				'label_block' => true,
				'default' => [],
				'condition' => [
					'exclude_by' => 'manual-selection'
				],
			]
		);

        if ( $post_types ) {
            foreach ( $post_types as $id => $value ) {

				$this->add_control(
					'exclude_term_' . $id,
					[
						'label' => __( 'Term', 'neuron-builder' ),
						'type' => Controls_Manager::SELECT2,
						'multiple' => true,
						'options' => Utils::get_the_terms( $id ),
						'label_block' => true,
						'default' => [],
						'select2options' => [
							'placeholder' => __( 'All', 'neuron-builder' ),
						],
						'condition' => [
							'exclude_by' => 'term',
							'source' => $id
						],
					]
				);
            }
		}
		
		$this->add_control(
			'exclude_author',
			[
				'label' => __( 'Author', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_authors(),
				'label_block' => true,
				'default' => [''],
				'select2options' => [
					'placeholder' => __( 'All', 'neuron-builder' ),
				],
				'condition' => [
					'exclude_by' => 'author'
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => __( 'Offset (Skip any Post)', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => '',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'description' => __( 'Displayed if no relevant results are found. Manual selection display order is random.', 'neuron-builder' ),
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
					'recent-posts' => __( 'Recent Posts', 'neuron-builder' ),
				],
				'condition' => [
					'source' => 'related'
				],
				'default' => 'none',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'search_select',
			[
				'label' => __( 'Search & Select', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_the_posts( ['all'] ),
				'label_block' => true,
				'default' => [],
				'condition' => [
					'source' => 'manual-selection',
				],
			]
		);

		$this->add_control(
			'search_select_fallback',
			[
				'label' => __( 'Search & Select', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utils::get_the_posts( ['all'] ),
				'label_block' => true,
				'default' => [],
				'condition' => [
					'source' => 'related',
					'fallback' => 'manual-selection',
				],
			]
		);

		$this->add_control(
			'hr_date',
			[
			    'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'source!' => [ 'manual-selection', 'current_query' ]
				]
			]
		);

		$this->add_control(
			'date_order',
			[
			    'label' => __( 'Date', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'all' => __( 'All', 'neuron-builder' ),
					'past-day' => __( 'Past Day', 'neuron-builder' ),
					'past-week' => __( 'Past Week', 'neuron-builder' ),
					'past-month' => __( 'Past Month', 'neuron-builder' ),
					'past-year' => __( 'Past Year', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
				'default' => 'all',
				'condition' => [
					'source!' => [ 'manual-selection', 'current_query' ]
				]
			]
		);

		$this->add_control(
			'date_before',
			[
			   	'label' => __( 'Before', 'neuron-builder' ),
				'type' => Controls_Manager::DATE_TIME,
				'label_block' => false,
				'condition' => [
					'date_order' => 'custom',
					'source!' => 'manual-selection',
				]
			]
		);

		$this->add_control(
			'date_after',
			[
			   	'label' => __( 'After', 'neuron-builder' ),
				'type' => Controls_Manager::DATE_TIME,
				'label_block' => false,
				'condition' => [
					'date_order' => 'custom',
					'source!' => 'manual-selection',
				]
			]
		);

		$this->add_control(
			'orderby',
			[
			   	'label' => __( 'Order By', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'post_date' => __( 'Date', 'neuron-builder' ),
					'post_title' => __( 'Title', 'neuron-builder' ),
					'menu_order' => __( 'Menu Order', 'neuron-builder' ),
					'rand' => __( 'Random', 'neuron-builder' ),
				],
				'default' => 'post_date',
				'condition' => [
					'source!' => [ 'manual-selection', 'current_query' ]
				]
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
				'condition' => [
					'source!' => [ 'manual-selection', 'current_query' ]
				]
			]
		);

		$this->add_control(
			'ignore_sticky',
			[
			   	'label' => __( 'Ignore Sticky Posts', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'source' => 'post'
				]
			]
		);

		$this->end_controls_section();
		
		// Pagination		
		$this->start_controls_section(
			'pagination_section',
			[
				'label' => __( 'Pagination', 'neuron-builder' )
			]
		);

		$this->add_control(
			'pagination',
			[
			   	'label' => __( 'Pagination', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'neuron-builder' ),
					'numbers' => __( 'Numbers', 'neuron-builder' ),
					'show-more' => __( 'Show More', 'neuron-builder' ),
					'previous-next' => __( 'Previous/Next', 'neuron-builder' ),
					'numbers-previous-next' => __( 'Numbers + Previous/Next', 'neuron-builder' ),
				],
				'frontend_available' => true,
				'default' => 'none'
			]
		);

		$this->add_control(
			'page_limit',
			[
			   	'label' => __( 'Page Limit', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 40,
				'step' => 1,
				'default' => 5,
				'condition' => [
					'pagination' => ['numbers', 'numbers-previous-next']
				],
			]
		);

		$this->add_control(
			'show_more_text',
			[
			   	'label' => __( 'Show More Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Show More', 'neuron-builder' ),
				'condition' => [
					'pagination' => 'show-more'
				]
			]
		);

		$this->add_control(
			'show_more_loading_text',
			[
			    'label' => __( 'Loading Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Loading...', 'neuron-builder' ),
				'condition' => [
					'pagination' => 'show-more'
				]
			]
		);

		$this->add_control(
			'shorten',
			[
			   	'label' => __( 'Shorten', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'neuron-builder' ),
				'label_off' => __( 'No', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'pagination' => ['numbers', 'numbers-previous-next']
				]
			]
		);

		$this->add_control(
			'previous_label',
			[
			   	'label' => __( 'Previous Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'neuron-builder' ),
				'condition' => [
					'pagination' => ['previous-next', 'numbers-previous-next']
				]
			]
		);

		$this->add_control(
			'next_label',
			[
			   	'label' => __( 'Next Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'neuron-builder' ),
				'condition' => [
					'pagination' => ['previous-next', 'numbers-previous-next']
				],
			]
		);

		$this->add_control(
			'label_icon',
			[
			   	'label' => __( 'Label Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [],
				'condition' => [
					'pagination' => [ 'previous-next', 'numbers-previous-next' ]
				],
			]
		);

		$this->add_responsive_control(
			'label_icon_spacing',
			[
				'label' => __('Icon Spacing', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'condition' => [
					'pagination' => [ 'previous-next', 'numbers-previous-next' ],
					'label_icon[value]!' => '',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .prev .m-neuron-pagination--icon' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-pagination .next .m-neuron-pagination--icon' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'label_icon_size',
			[
				'label' => __('Icon Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'custom_control' => 'add_responsive_control',
				'condition' => [
					'pagination' => ['previous-next', 'numbers-previous-next'],
					'label_icon[value]!' => '',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .m-neuron-pagination--icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'pagination_alignment',
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
					'pagination!' => 'none'
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
			   	'label' => __('Spacing', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'condition' => [
					'pagination!' => 'none'
				],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_style_section',
			[
				'label' => __( 'Layout', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'h_position',
			[
				'label' => __( 'Horizontal Position', 'neuron-builder' ),
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
				'prefix_class' => 'm-neuron-interactive-posts__h-align-',
				'condition' => [
					'layout!' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}.m-neuron-interactive-posts--vertical .m-neuron-interactive-posts__item:not(:last-of-type)' => 'padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.m-neuron-interactive-posts--horizontal .m-neuron-interactive-posts__item:not(:last-of-type)' => 'padding-right: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'layout!' => 'grid'
				]
			]
		);

		$this->add_responsive_control(
			'columns_gap',
			[
				'label' => __( 'Columns Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}}.m-neuron-interactive-posts--grid .m-neuron-interactive-posts__links' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'grid'
				]
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Row Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}}.m-neuron-interactive-posts--grid .m-neuron-interactive-posts__links' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'grid'
				]
			]
		);

		$this->add_responsive_control( 
			'content_padding', [ 
				'label' => __( 'Content Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'vw' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__links' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			] 
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.m-neuron-interactive-posts--grid) .m-neuron-interactive-posts__links' => 'min-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.m-neuron-interactive-posts--grid .m-neuron-interactive-posts__title' => 'min-height: {{SIZE}}{{UNIT}}; width: 100%; display: flex; align-items: center; justify-content: center;', // Grid Issue of link
				],
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

		$this->add_control(
			'first_active',
			[
				'label' => __( 'First Active', 'neuron-builder' ),
				'description' => __( 'Set first item active on load.' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'image_coverage!' => 'text'
				]
			]
		);

		$this->add_control(
			'mouse_out',
			[
				'label' => __( 'Mouse Out', 'neuron-builder' ),
				'description' => __( 'Hides the image when mouse is out of element.' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'image_coverage!' => 'text'
				]
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __( 'Image', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
			   	'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw', 'rem' ],
				'default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__images' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_max_width',
			[
			   	'label' => __( 'Max Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__images' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
			   	'label' => __( 'Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'default' => [
					'unit' => 'vh'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__images' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_h_position',
			[
				'label' => __( 'Horizontal Position', 'neuron-builder' ),
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
				'prefix_class' => 'm-neuron-interactive-posts__image-h-align--',
				'condition' => [
					'image_coverage!' => 'text'
				]
			]
		);

		$this->add_control(
			'image_v_position',
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
				'prefix_class' => 'm-neuron-interactive-posts__image-v-align--',
				'condition' => [
					'image_coverage!' => 'text'
				]
			]
		);

		$this->add_control(
			'image_bg_size',
			[
				'label' => __( 'Image Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover'  => __( 'Cover', 'neuron-builder' ),
					'contain' => __( 'Contain', 'neuron-builder' ),
					'auto' => __( 'Auto', 'neuron-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__image' => 'background-size: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control( 
			'image_animation', [ 
				'label' => __( 'Image Animation', 'neuron-builder' ),
				'type' => Controls_Manager::ANIMATION,
				'custom_control' => 'add_responsive_control',
				'frontend_available' => true,
				'separator' => 'before'
			] 
		);

		$this->add_responsive_control( 
			'image_animation_duration', [ 
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
					'image_animation!' => [
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

		$this->end_controls_section();

		$this->start_controls_section(
			'box_style_section',
			[
				'label' => __( 'Box', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => 'grid'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'exclude' => [ 'color' ],
				'selector' => '{{WRAPPER}} .m-neuron-interactive-posts__item',
			]
        );

		$this->add_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'hr_box',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'box_tabs' );

		$this->start_controls_tab('box_normal_tab', [
			'label' => __( 'Normal', 'neuron-builder' ),
		]);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-interactive-posts__item'
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__item' => 'border-color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();


		$this->start_controls_tab('box_hover_tab', [
			'label' => __( 'Hover', 'neuron-builder' ),
		]);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .m-neuron-interactive-posts__item:hover'
			]
		);

		$this->add_control(
			'box_background_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__item:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__item:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style_section',
			[
				'label' => __( 'Content', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_title_heading',
			[
				'label' => __('Title', 'neuron-builder'),
            	'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__title' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-interactive-posts__title',
			]
		);

		// Meta
		$this->add_control(
			'meta_title_heading',
			[
				'label' => __('Meta', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-interactive-posts__meta-data, {{WRAPPER}} .m-neuron-interactive-posts__meta-data a' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_meta_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-interactive-posts__meta-data',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'pagination_style_section',
			[
				'label' => __( 'Pagination', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination!' => 'none', 
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-pagination .page-numbers, {{WRAPPER}} .m-neuron-pagination',
            	'separator' => 'after'
			]
		);

		$this->add_control(
			'pagination_pointer',
			[
				'label' => __( 'Pointer', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'neuron-builder' ),
					'underline' => __( 'Underline', 'neuron-builder' ),
					'strikethrough' => __( 'Strikethrough', 'neuron-builder' ),
					'diagonal' => __( 'Diagonal', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-pagination--',
				'default' => '',
				'condition' => [
					'pagination!' => 'show-more'
				] 
			]
		);

		$this->start_controls_tabs( 'pagination_color_tabs' );

		$this->start_controls_tab( 
			'pagination_normal_tab', [
				'label' => __( 'Normal', 'neuron-builder' ),
			] 
		);

		$this->add_control(
			'pagination_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(.page-label)' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_label_color',
			[
				'label' => __( 'Label Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => 'show-more'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'pagination_hover_tab', [
				'label' => __( 'Hover', 'neuron-builder' ),
			] 
		);

		$this->add_control(
			'pagination_text_color_hover',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:hover' => 'color: {{VALUE}}'
				],
			]
		);

        $this->add_control(
			'pagination_background_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(.page-label):hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_label_color_hover',
			[
				'label' => __( 'Label Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-label:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => 'show-more'
				]
			]
		);

		$this->add_control(
			'pagination_pointer_color_hover',
			[
				'label' => __( 'Pointer Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(.page-label):hover:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => 'show-more',
					'pagination_pointer!' => ''
				]
			]
		);

		$this->add_control(
			'pagination_hover_animation',
			[
				'label' => __( 'Hover Animation', 'neuron-builder' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'pagination' => 'show-more'
				]
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab( 
			'pagination_active_tab', [
				'label' => __( 'Active', 'neuron-builder' ),
			] 
		);

		$this->add_control(
			'pagination_text_color_active',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .current' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_background_color_active',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
            	'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .current:not(.page-label)' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'pagination!' => 'show-more'
				]
			]
		);

		$this->add_control(
			'pagination_pointer_color_active',
			[
				'label' => __( 'Pointer Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .current:not(.page-label):after' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'pagination!' => 'show-more',
					'pagination_pointer!' => ''
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label' => __( 'Border', 'neuron-builder' ),
				'name' => 'pagination_border',
				'selector' => '{{WRAPPER}} .m-neuron-pagination button',
				'separator' => 'before',
				'condition' => [
					'pagination' => 'show-more'
				]
			]
		);

		$this->add_control(
			'pagination_top_hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'pagination' => 'show-more',
				]
			]
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'size_units' => ['px', '%'],
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(.page-label)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-pagination button',
				'condition' => [
					'pagination' => 'show-more',
				]
			]
		);

		$this->add_control(
			'pagination_bottom_hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'pagination' => 'show-more',
				]
			]
		);

		$this->add_responsive_control(
			'pagination_space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .m-neuron-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
				],
				'condition' => [
					'pagination!' => 'show-more'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'size_units' => ['px', 'em', '%'],
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Paged
	 * 
	 * @since 1.0.0
	 */
	protected function get_paged() {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		return $paged;
	}

	/**
	 * Include By Term
	 * 
	 * Include post that contains
	 * the following term.
	 * 
	 * @since 1.0.0
	 */
	protected function include_by_term() {
		$include = $this->get_settings( 'include_by' );
		$source = $this->get_settings( 'source' );
		$include_term = $this->get_settings( 'include_term_' . $source );

		if ( $source == 'related' || empty ( $include ) || ! in_array( 'term', $include ) || empty( $include_term  ) ) {
			return;
		}

		$this->query_args['tax_query']['relation'] = 'OR';

		foreach ( $include_term as $term ) {
			$termSingle = strstr( $term, '==', false );
			$termType = strstr( $term, '==', true );
			$termObj = str_replace( '==', '', $termSingle );

			$this->query_args['tax_query'][] = [
				'taxonomy' => $termType,
				'terms' => $termObj,
				'field' => 'slug'
			];
		}
	}

	/**
	 * Exclude By Term
	 * 
	 * Include post that contains
	 * the following term.
	 * 
	 * @since 1.0.0
	 */
	protected function exclude_by_term() {
		$exclude = $this->get_settings( 'exclude_by' );
		$source = $this->get_settings( 'source' );
		$exclude_term = $this->get_settings( 'exclude_term_' . $source );

		if ( empty ( $exclude ) || ! in_array( 'term', $exclude ) || empty( $exclude_term  ) ) {
			return;
		}

		foreach ( $exclude_term as $term ) {
			$termSingle = strstr( $term, '==', false );
			$termType = strstr( $term, '==', true );
			$termObj = str_replace( '==', '', $termSingle );

			$this->query_args['tax_query'][] = [
				'taxonomy' => $termType,
				'terms' => $termObj,
				'field' => 'slug',
				'operator' => 'NOT IN'
			];
		}

		// Handle Relation
		if ( ! empty ( $this->get_settings( 'include_by' ) ) || in_array( 'term', $this->get_settings( 'include_by' ) ) ) {
			$this->query_args['tax_query']['relation'] = 'AND';
		}
	}

	/**
	 * Include Manually
	 * 
	 * Include manually any kind
	 * of page or post to the query.
	 * 
	 * @since 1.0.0
	 */
	protected function include_manually() {
		if ( $this->get_settings( 'source' ) != 'manual-selection' || empty ( $this->get_settings('search_select') ) ) {
			return;
		}

		$post_type = [];

		foreach ( $this->get_settings('search_select') as $term ) {
			$termSingle = strstr( $term, '-', false );
			$termType = strstr( $term, '-', true );
			$termObj = str_replace( '-', '', $termSingle );

			$this->query_args['post__in'][] = $termObj;

			if ( ! in_array( $termType, $post_type ) ) {
				$post_type[] = $termType;
			}
		}

		if ( ! empty ( $post_type ) ) {
			$this->query_args['post_type'] = $post_type;
		}
	}

	/**
	 * Exclude Manually
	 * 
	 * Exclude manually any kind
	 * of page or post to the query.
	 * 
	 * @since 1.0.0
	 */
	protected function exclude_manually() {
		$exclude = $this->get_settings( 'exclude_by' );
		$source = $this->get_settings( 'source' );
		$exclude_term = $this->get_settings( 'exclude_term_' . $source );

		if ( ( empty ( $exclude ) || ! in_array( 'term', $exclude ) || empty( $exclude_term  ) ) && empty( $this->get_settings( 'exclude_manual' ) ) ) {
			return;
		}

		$post_type = [];

		foreach ( $this->get_settings( 'exclude_manual' ) as $term ) {
			$termSingle = strstr( $term, '-', false );
			$termType = strstr( $term, '-', true );
			$termObj = str_replace( '-', '', $termSingle );

			$this->query_args['post__not_in'][] = $termObj;
		}
	}

	/**
	 * Query By Author
	 * 
	 * Include or Exclude posts
	 * by a certain author.
	 * 
	 * @since 1.0.0
	 */
	protected function query_by_author() {
		// Include
		if ( ! empty ( $this->get_settings( 'include_by' ) ) && in_array( 'author', $this->get_settings( 'include_by' ) ) && ! empty( $this->get_settings( 'include_author' ) ) ) {
			$this->query_args['author'] = implode(', ', $this->get_settings( 'include_author' ) );
		}

		// Exclude
		if ( ! empty ( $this->get_settings( 'exclude_by' ) ) &&  in_array( 'author', $this->get_settings( 'exclude_by' ) ) && ! empty( $this->get_settings( 'exclude_author' ) )  ) {
			$this->query_args['author__not_in'] = implode(', ', $this->get_settings( 'exclude_author' ) );
		}
	}

	/**
	 * Exclude Curent Post
	 * 
	 * @since 1.0.0
	 */
	protected function query_exclude_current_post() {
		if ( ! empty ( $this->get_settings( 'exclude_by' ) ) && ( in_array( 'current-post', $this->get_settings( 'exclude_by' ) ) ) ) {
			if ( ! empty ( $this->query_args['post__not_in'] ) ) {
				array_push( $this->query_args['post__not_in'], get_the_ID() );
			} else {
				$this->query_args['post__not_in'] = [get_the_ID()];
			}
		}
	}

	/**
	 * Query By Date
	 * 
	 * Include posts that have
	 * a certain time creation.
	 * 
	 * @since 1.0.0
	 */
	function query_by_date() {
		if ( $this->get_settings( 'source' ) == 'manual-selection' || $this->get_settings( 'date_order' ) == 'all' ) {
			return;
		}

		switch ( $this->get_settings( 'date_order' ) ) {
			case 'past-day':
				$this->query_args['date_query'] = ['after' => '-1 day'];
				break;
			case 'past-week':
				$this->query_args['date_query'] = ['after' => '-1 week'];
				break;
			case 'past-month':
				$this->query_args['date_query'] = ['after' => '-1 month'];
				break;
			case 'past-quarter':
				$this->query_args['date_query'] = ['after' => '-3 month'];
				break;
			case 'past-year':
				$this->query_args['date_query'] = ['after' => '-1 year'];
				break;
		}

		if ( $this->get_settings( 'date_order' ) == 'custom' ) {
			$this->query_args['date_query']['inclusive'] = 1;
			
			if ( $this->get_settings( 'date_before' ) ) {
				$this->query_args['date_query']['before'] = $this->get_settings( 'date_before' );
			}

			if ( $this->get_settings( 'date_after' ) ) {
				$this->query_args['date_query']['after'] = $this->get_settings( 'date_after' );
			}
		}
	}

	/**
	 * Related Query
	 * 
	 * Shows related posts due
	 * certain terms or authors.
	 * 
	 * @since 1.0.0
	 */
	protected function include_related() {
		$include = $this->get_settings( 'include_by' );
		$source = $this->get_settings( 'source' );
		$include_term = $this->get_settings( 'include_term_related' );

		if ( $source != 'related' || empty ( $include ) || ! in_array( 'term', $include ) || empty( $include_term  ) ) {
			return;
		}

		$this->query_args['post_type'] =  get_post_type();
		$this->query_args['post__not_in'] = [get_the_ID()];

		$relatedCat = [];

		foreach ( $include_term as $term ) {
			if ( ! empty ( get_the_terms( get_the_ID(), $term  ) ) ) {
				foreach( get_the_terms( get_the_ID(), $term  ) as $category ) {
					$relatedCat[] = $category->term_id;
				}
			}

			$this->query_args['tax_query'][] = [
				'taxonomy' => $term,
				'terms' => $relatedCat,
				'field' => 'term_id'
			];
		}

		$this->query_args['post_type'] = get_post_type();
	}

	/**
	 * Related Fallback
	 * 
	 * Show different posts
	 * or recent posts if the
	 * related query fails to
	 * find posts.
	 * 
	 * @since 1.0.0
	 */
	public function include_related_fallback( $query ) {
		if ( $this->get_settings( 'source' ) != 'related' || $this->get_settings( 'fallback' ) == 'none' ) {
			return $query;
		}

		// Fallback
		$args = $this->query_args;

		// Manual Selection
		if ( $this->get_settings( 'fallback' ) == 'manual-selection' ) {
			$post_type = [];

			foreach ( $this->get_settings( 'search_select_fallback' ) as $term ) {
				$termSingle = strstr( $term, '-', false );
				$termType = strstr( $term, '-', true );
				$termObj = str_replace( '-', '', $termSingle );

				$args['post__in'][] = $termObj;

				if ( ! empty( $args['tax_query'] ) ) {
					unset( $args['tax_query'] );
				}

				if ( ! in_array( $termType, $post_type ) ) {
					$post_type[] = $termType;
				}
			}

			if ( ! empty ( $post_type ) ) {
				$args['post_type'] = $post_type;
			}
		} else {
			if ( ! empty( $args['tax_query'] ) ) {
				unset( $args['tax_query'] );
			}

			$args['post_type'] = ['post', 'portfolio', 'page', 'product'];
		}

		$query = new \WP_Query( $args );

		return $query;
	}

	/**
	 * Query Posts
	 * 
	 * Different properties to the wp_query
	 * to extend the query with includes
	 * and excludes.
	 * 
	 * @since 1.0.0
	 */
	protected function query_posts() {
		$this->query_by_author();
		$this->include_by_term();
		$this->include_manually();
		$this->include_related();

		$this->exclude_by_term();
		$this->exclude_manually();
		$this->query_exclude_current_post();

		$this->query_by_date();
	}

	/**
	 * Show More Pagination
	 * 
	 * Includes and Excludes
	 * posts to display them
	 * via the ajax pagination.
	 * 
	 * @since 1.0.0
	 */
	protected function show_more_pagination() {
		if ( $this->get_settings( 'pagination' ) != 'show-more' ) {
			return;
		}

		$filter = isset( $_GET['filter'] ) ? $_GET['filter'] : '';
		$taxonomy = isset( $_GET['termType'] ) ? $_GET['termType'] : '';
		$exclude = isset( $_GET['exclude'] ) ? $_GET['exclude'] : '';

		if ( $filter ) {
			$this->query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy ? $taxonomy : 'category',
					'field' => 'slug',
					'terms' => $filter
				)
			);
		}

		if ( $exclude ) {
			$this->query_args['post__not_in'] = $exclude;
		}
	}

	/**
	 * Query
	 * 
	 * Loads the query with
	 * different sources and
	 * works with include &
	 * exclude option on it.
	 * 
	 * @since 1.0.0
	 */
	protected function get_query() {
		$this->query_args = [
			'post_type' => $this->get_settings('source'),
			'posts_per_page' => $this->get_settings('posts_per_page') ? $this->get_settings('posts_per_page') : 12,
			'paged' => $this->get_paged(),
			'orderby' => $this->get_settings( 'orderby' ),
			'order' => $this->get_settings( 'order' ),
			'ignore_sticky_posts' => $this->get_settings( 'ignore_sticky' ) == 'yes' ? 1 : '',
			'offset' => $this->get_settings( 'query_offset' ),
		];

		if ( $this->get_settings('source') == 'current_query' ) {
			$this->query_args = $GLOBALS['wp_query']->query_vars;
		}

		$this->query_posts();

		$this->show_more_pagination();

		$query = new \WP_Query( $this->query_args );

		$query = $this->include_related_fallback( $query );

		return $query;
	}

	/**
	 * Get Current Page
	 * 
	 * @since 1.0.0
	 */
	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Get WP Link Page
	 * 
	 * @since 1.0.0
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		global $wp_rewrite;
		$post = get_post();
		$query_args = [];
		$url = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id'] = wp_unslash( $_GET['preview_id'] );
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = [];

		$paged = $this->get_current_page();

		$link_template = '<a class="page-numbers %s page-label" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s page-label">%s</span>';

		ob_start();

		Icons_Manager::render_icon( $this->get_settings( 'label_icon' ) );

		$label_icon = '<span class="m-neuron-pagination--icon">' . ob_get_contents() . '</span>';

		ob_end_clean();

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $label_icon . $this->get_settings( 'previous_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $label_icon . $this->get_settings( 'previous_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'next_label' ) . $label_icon );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'next_label' ) . $label_icon );
		}

		return $return;
	}

	/**
	 * Normal Pagination
	 * 
	 * Includes the numbers pagination
	 * with or without arrows.
	 * 
	 * @since 1.0.0
	 */
	public function normal_pagination( $query = '' ) {
		$settings = $this->get_settings();

		if ( 'none' === $settings['pagination'] ) {
			return;
		}

		global $paged;

		$page_limit = $query->max_num_pages;
		
		if ( '' !== $settings['page_limit'] ) {
			$page_limit = min( $settings['page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$total_pages = $query->max_num_pages;

		$has_numbers = in_array( $settings['pagination'], [ 'numbers', 'numbers-previous-next' ] );
		$has_prev_next = in_array( $settings['pagination'], [ 'previous-next', 'numbers-previous-next' ] );

		$links = [];

		if ( $has_numbers ) {
			$paginate_args = [
				'type' => 'array',
				'current' => $this->get_current_page(),
				'total' => $page_limit,
				'prev_next' => false,
				'show_all' => 'yes' !== $settings['shorten']
			];

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					if ( ! is_multisite() ) {
						$paginate_args['base'] = trailingslashit( get_permalink() ) . '%_%';
						$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
					}
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		echo '<div class="m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'">'. implode( PHP_EOL, $links ) .'</div>';
	}

	/**
	 * Ajax Pagination
	 * 
	 * Load more Posts via
	 * a button which request
	 * for ajax call and returns
	 * on the DOM.
	 * 
	 * @since 1.0.0
	 */
	protected function ajax_pagination() {
		if ( $this->get_settings( 'show_more_text' ) ) {
			$show_more = $this->get_settings( 'show_more_text' );
		} else {
			$show_more = __( 'Show More', 'neuron-builder' );
		}

		$button_class = ['page-numbers', 'a-button'];
		if ( ! empty( $this->get_settings( 'pagination_hover_animation' ) ) ) {
			$button_class[] = 'elementor-animation-' . $this->get_settings('pagination_hover_animation');
		}

		$exclude = '';

		if ( isset( $this->query_args['post__not_in'] ) && ! empty( $this->query_args['post__not_in'] ) ) {
			$exclude = $this->query_args['post__not_in'];

			if ( ! empty ( $exclude ) ) {
				$exclude = implode( ', ', $exclude );
			}
		}

		$loading = $this->get_settings( 'show_more_loading_text' ) ? $this->get_settings( 'show_more_loading_text' ) : $show_more;

		echo '<div class="m-neuron-pagination m-neuron-pagination--interactive" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'"><button id="load-more-posts" class="'. implode(' ', $button_class) .'" data-text="'. $show_more .'" data-exclude="'. $exclude . '" data-loading="'. $loading .'">'. $show_more .'</button></div>';
	}

	/**
	 * Pagination
	 * 
	 * Displays a different pagination
	 * type which can make user able
	 * to navigate through pages or load
	 * more posts via show more button.
	 * 
	 * @since 1.0.0
	 */
	protected function get_pagination( $query ) {
		if ( $this->get_settings( 'pagination' ) == 'none' ) {
			return;
		}

		if ( $this->get_settings( 'pagination' ) == 'show-more' && $query->max_num_pages > $this->get_paged() ) {
			$html = $this->ajax_pagination();
		} else {
			$html = $this->normal_pagination( $query );
		}

		return '<div class="m-neuron-pagination" aria-label="'. esc_html__( 'Pagination', 'neuron-builder' ) .'">' . $html . '</div>';
	}

	/**
	 * Filters in Manual Selection
	 * 
	 * @since 1.0.0
	 */
	protected function filters_manual_selections( $filters_tax ) {
		if ( $this->get_settings( 'source' ) != 'manual-selection' ) {
			return $filters_tax;
		}

		switch( get_post_type() ) {
			case 'post':
				$filters_tax = 'category';
				break;
			case 'portfolio':
				$filters_tax = 'portfolio_category';
				break;
			case 'product':
				$filters_tax = 'product_cat';
				break;
		}

		return $filters_tax;
	}

	/**
	 * Post Meta
	 * 
	 * Different meta of the post
	 * will be displayed via this function.
	 * Author / Date / Time / Comments
	 * 
	 * @since 1.0.0
	 */
	protected function get_post_meta_data() {
		if ( empty ( $this->get_settings( 'meta_data' ) ) ) {
			return;
		}

		$meta_data = [];

		// Author
		if ( in_array( 'author', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['author'] = get_the_author();
		}

		// Date
		if ( in_array( 'date', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['date'] = get_the_time( get_option( 'date_format' ) );
		}

		// Time
		if ( in_array( 'time', $this->get_settings( 'meta_data' ) ) ) {
			$meta_data['time'] = get_the_time( get_option( 'time_format' ) );
		}

		// Comments
		if ( in_array( 'comments', $this->get_settings( 'meta_data' ) ) ) {
			$default_strings = [
				'no_comments' => __( 'No Comments', 'neuron-builder' ),
				'one_comment' => __( 'One Comment', 'neuron-builder' ),
				'comments' => __( '%s Comments', 'neuron-builder' ),
			];

			$meta_data['comments'] = get_comments_number_text( $default_strings['no_comments'], $default_strings['one_comment'], $default_strings['comments'] );
		}

		if ( in_array( 'terms', $this->get_settings( 'meta_data' ) ) ) {
			$taxonomy = $this->get_settings( 'meta_data_taxonomy' );

			if ( $taxonomy ) {
				$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

				foreach ( $terms as $term ) {
					$meta_data['terms'] = '<a href="' . esc_attr( get_term_link( $term ) ) . '"">' . esc_html( $term->name ) . '</a>';
				}
			}
		}

		if ( empty ( $meta_data ) ) {
			return;
		}

		return '<span class="m-neuron-interactive-posts__meta-data"> <span>' . implode( '</span> <span> ', $meta_data ) . '</span> </span>';
	}


	private function render_interactive_links() {
		$query = $this->get_query();

		$item_class = 'm-neuron-interactive-posts__item';

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $this->get_settings( 'animation' ) == 'yes' )  {
			$item_class	.= ' h-neuron-animation--wow';
		}

		while ( $query->have_posts() ) { 
			$query->the_post(); 
				
			if ( has_post_thumbnail() ) { ?>
				<article data-id="<?php the_ID() ?>" <?php post_class( $item_class ) ?>>
					<a class="m-neuron-interactive-posts__title" data-text-interactive="<?php echo esc_attr( get_the_title() ) ?>" href="<?php the_permalink() ?>"><?php the_title() ?></a>
					<?php echo $this->get_post_meta_data() ?>
				</article>
			<?php }
		} 
	}

	private function render_interactive_images() {
		$query = $this->get_query();

		while ( $query->have_posts() ) { 
			$query->the_post(); 
			
			if ( has_post_thumbnail() ) { ?>
				<div data-id="<?php the_ID() ?>" class="m-neuron-interactive-posts__image" style="background-image: url(<?php echo get_the_post_thumbnail_url() ?>)"><?php the_post_thumbnail() ?></div>
			<?php } 
		} 
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$query = $this->get_query();

		if ( $query->have_posts() ) {
		?> 
			<div class="m-neuron-interactive-posts" data-interactive-id="<?php echo esc_attr( md5( $this->get_id() ) ) ?>">
				<div class="m-neuron-interactive-posts__links">
					<?php $this->render_interactive_links() ?>
				</div>
				<div class="m-neuron-interactive-posts__images">
					<?php $this->render_interactive_images() ?>
				</div>
			</div>
		<?php
		}

		wp_reset_postdata();

		$this->get_pagination( $query );
	}

	protected function content_template() {}
}
