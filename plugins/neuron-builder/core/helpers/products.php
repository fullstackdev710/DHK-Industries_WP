<?php
/**
 * Products Class
 * 
 * Extends the class Posts
 * 
 * @since 1.0.0
 */

namespace Neuron\Core\Helpers;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Core\Helpers\Posts as Posts;
use Neuron\Core\Helpers\Portfolio as Portfolio;
use Neuron\Core\Utils;

class Products extends Posts {

    public function __construct( $thisElement ) {
        // Content
        $this->register_section( $thisElement, 'layout' );
        $this->register_section( $thisElement, 'query' );
        $this->register_section( $thisElement, 'query_metro', [ 'layout' => 'metro' ] );
        $this->register_section( $thisElement, 'pagination', ['carousel!' => 'yes'] );
        $this->register_section( $thisElement, 'filters' );

        // Style
        $this->register_section( $thisElement, 'layout_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'box_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'image_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'pagination_style', ['pagination!' => 'none', 'carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'navigation_style', ['carousel' => 'yes', 'navigation!' => 'none'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'filters_style', ['carousel!' => 'yes', 'filters' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'sale_flash_style', ['carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'add_to_cart_style', ['add_to_cart' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'quick_view_style', ['quick_view' => 'yes'], 'TAB_STYLE' );
        
        if ( defined( 'YITH_WCWL' ) ) {
            $this->register_section( $thisElement, 'wishlist_style', [ 'wishlist' => 'yes' ], 'TAB_STYLE' );
        }
    }

    public function get_woo_source() {
        $fields = [
            'latest-products' => __( 'Latest Products', 'neuron-builder' ),
            'sale' => __( 'Sale', 'neuron-builder' ),
            'featured' => __( 'Featured', 'neuron-builder' ),
            'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
            'current_query' => __( 'Current Query', 'neuron-builder' ),
        ];

        return $fields;
    }

    public function layout_controls() {
        $fields = parent::layout_controls();

        $remove = [
            'skin',
            'excerpt',
            'excerpt_length',
            'image_position',
            'image_width',
            'show_read_more',
            'bottom_divider_title',
            'meta_data',
            'separator_between',
            'bottom_divider_meta_tag',
            'badge',
            'badge_taxonomy',
            'avatar',
        ];

        foreach ( $remove as $key ) {
            unset($fields[$key]);
        }

        $fields['rating'] = [
            'label' => __( 'Rating', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'yes'
        ];

        if ( defined( 'YITH_WCWL' ) ) {
            $fields['wishlist'] = [
                'label' => __( 'Wishlist', 'neuron-builder' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'neuron-builder' ),
                'label_off' => __( 'Hide', 'neuron-builder' ),
                'return_value' => 'yes',
                'default' => 'no',
            ];
        }

        $fields['price'] = [
            'label' => __( 'Price', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'separator' => 'before'
        ];

        $fields['price_switch'] = [
            'label' => __( 'Price Switch', 'neuron-builder' ),
            'description' => __( 'Show Add to Cart in hover.', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'price' => 'yes'
            ]
        ];

        // Add to cart Enable
        $fields['add_to_cart'] = [
            'label' => __( 'Add to Cart', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'separator' => 'before'
        ];

        $fields['add_to_cart_text'] = [
            'label' => __( 'Text', 'neuron-builder' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Add to Cart', 'neuron-builder' ),
            'return_value' => 'yes',
            'condition' => [
                'add_to_cart' => 'yes'
            ]
        ];

        $fields['add_to_cart_selected_icon'] = [
            'label' => __( 'Add To Cart Icon', 'neuron-builder' ),
            'fa4compatibility' => 'add_to_cart_icon',
            'type' => Controls_Manager::ICONS,
            'default' => [],
            'condition' => [
                'add_to_cart' => 'yes'
            ]
        ];

        $fields['add_to_cart_icon_position'] = [
            'label' => __('Icon Position', 'neuron-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'before' => __('Before', 'neuron-builder'),
                'after' => __('After', 'neuron-builder'),
            ],
            'default' => 'after',
            'condition' => [
                'add_to_cart' => 'yes',
                'add_to_cart_selected_icon[value]!' => '',
            ],
            'selectors_dictionary' => [
                'before' => 'row-reverse',
                'after' => 'row',
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__add-to-cart a' => 'flex-direction: {{VALUE}}'
            ]
        ];

        $fields['add_to_cart_icon_spacing_before'] = [
            'label' => __('Icon Spacing', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'add_to_cart' => 'yes',
                'add_to_cart_selected_icon[value]!' => '',
                'add_to_cart_icon_position' => 'before'
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
                '{{WRAPPER}} .m-neuron-product__add-to-cart a span' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
        ];

         $fields['add_to_cart_icon_spacing_after'] = [
            'label' => __('Icon Spacing', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'add_to_cart' => 'yes',
                'add_to_cart_selected_icon[value]!' => '',
                'add_to_cart_icon_position' => 'after'
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
                '{{WRAPPER}} .m-neuron-product__add-to-cart a span' => 'margin-right: {{SIZE}}{{UNIT}}',
            ],
        ];

        // Quick View Icon
        $fields['quick_view'] = [
            'label' => __( 'Quick View', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'no',
            'separator' => 'before',
            'frontend_available' => true
        ];

        $fields['quick_view_selected_icon'] = [
            'label' => __( 'Quick View Icon', 'neuron-builder' ),
            'fa4compatibility' => 'quick_view_icon',
            'type' => Controls_Manager::ICONS,
            'default' => [],
            'condition' => [
                'quick_view' => 'yes'
            ]
        ];

        // Conditions
        $fields['layout']['condition'] = [
            'carousel!' => 'yes'
        ];

        $fields['image_ratio']['condition'] = [
            'layout!' => ['masonry', 'metro'],
        ];

        $fields['image_size']['condition'] = '';

        return $fields;
    }

    public function query_controls() {
        $fields = [];

        $fields['source'] = [
            'label' => __( 'Source', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_woo_source(),
            'default' => 'latest-products'
        ];

        $fields['source_tabs'] = [
            'custom_control' => 'start_controls_tabs',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        // Include
        $fields['include_tab'] = [
            'label' => __( 'Include', 'neuron-builder' ),
            'custom_control' => 'start_controls_tab',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['include_by'] = [
            'label' => __( 'Include By', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => [
                'term' => __( 'Term', 'neuron-builder' ),
                'author' => __( 'Author', 'neuron-builder' ),
            ],
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['include_term'] = [
            'label' => __( 'Term', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_terms( 'product' ),
            'label_block' => true,
            'default' => [],
            'select2options' => [
                'placeholder' => __( 'All', 'neuron-builder' ),
            ],
            'condition' => [
                'include_by' => 'term'
            ],
        ];

        $fields['include_author'] = [
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
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Exclude
        $fields['exclude_tab'] = [
            'label' => __( 'Exclude', 'neuron-builder' ),
            'custom_control' => 'start_controls_tab',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['exclude_by'] = [
            'label' => __( 'Exclude By', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => [
                'current-post' => __( 'Current Post', 'neuron-builder' ),
                'manual-selection' => __( 'Manual Selection', 'neuron-builder' ),
                'term' => __( 'Term', 'neuron-builder' ),
                'author' => __( 'Author', 'neuron-builder' ),
            ],
        ];

        $fields['exclude_manual'] = [
            'label' => __( 'Manual Selection', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['product'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'exclude_by' => 'manual-selection'
            ],
        ];

        $fields['exclude_term'] = [
            'label' => __( 'Term', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_terms( 'product' ),
            'label_block' => true,
            'default' => [],
            'select2options' => [
                'placeholder' => __( 'All', 'neuron-builder' ),
            ],
            'condition' => [
                'exclude_by' => 'term'
            ],
        ];

        $fields['exclude_author'] = [
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
        ];

        $fields['query_offset'] = [
            'label' => __( 'Offset (Skip any Post)', 'neuron-builder' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default' => '',
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        $fields['search_select'] = [
            'label' => __( 'Search & Select', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['product'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'source' => 'manual-selection',
            ],
        ];

        $fields['hr_date'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        $fields['date_order'] = [
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
        ];

        $fields['date_before'] = [
            'label' => __( 'Before', 'neuron-builder' ),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'date_order' => 'custom',
            ]
        ];

        $fields['date_after'] = [
            'label' => __( 'After', 'neuron-builder' ),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'date_order' => 'custom',
            ]
        ];

        $fields['orderby'] = [
            'label' => __( 'Order By', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'date' => __( 'Date', 'neuron-builder' ),
                'title' => __( 'Title', 'neuron-builder' ),
                'menu-order' => __( 'Menu Order', 'neuron-builder' ),
                'rand' => __( 'Random', 'neuron-builder' ),
            ],
            'default' => 'date',
        ];

        $fields['order'] = [
            'label' => __( 'Order', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => __( 'Ascending', 'neuron-builder' ),
                'desc' => __( 'Descending', 'neuron-builder' ),
            ],
            'default' => 'desc',
        ];

        return $fields;
    }

    public function query_metro_controls() {

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

        $fields['neuron_metro_reset'] = [
            'label' => __( 'Reset Metro', 'neuron-builder' ),
            'type' => Controls_Manager::BUTTON,
            'button_type' => 'success neuron-reset-metro',
            'text' => __( 'Reset', 'neuron-builder' ),
            'event' => 'neuron:editor:metro:reset',
            'separator' => 'after'
        ];

        $fields['neuron_metro'] = [
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
        ];

        return $fields;
    }

    public function pagination_controls() {
        $fields = [];

        $fields['allow_order'] = [
            'label' => __( 'Allow Order', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'neuron-builder' ),
            'label_off' => __( 'No', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'no'
        ];

        $fields['results_count'] = [
            'label' => __( 'Results Count', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'no',
            'separator' => 'after'
        ];

        $fields = array_merge( $fields, parent::pagination_controls() );

        return $fields;
    }

    public function filters_controls() {
        $fields = Posts::filters_controls();

        $fields['filters_tax']['options'] = [
            'product_cat' => __( 'Product Category', 'neuron-builder' ),
            'product_tag' => __( 'Product Tags', 'neuron-builder' ),
        ];

        $fields['filters_tax']['default'] = 'product_cat';

        return $fields;
    }

    public function layout_style_controls() {
        $fields = Posts::layout_style_controls();

        return $fields;
    }

    public function box_style_controls() {
        $fields = Posts::box_style_controls();

        return $fields;
    }

    public function image_style_controls() {
        $fields = Posts::image_style_controls();

        // Conditions
        $fields['image_spacing_classic']['condition'] = '';

        $fields['image_hover_secondary']['condition'] = '';

        return $fields;
    }
    
    public function content_style_controls() {
        $fields = [];

        // Title
        $fields['content_title_heading'] = [
            'label' => __('Title', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['content_title_color'] = [
            'label' => __( 'Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__title a' => 'color: {{VALUE}}'
            ],
        ];

        $fields['content_title_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'content_title_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
            'selector' => '{{WRAPPER}} .m-neuron-product__title',
        ];

        $fields['content_title_spacing'] = [
            'label' => __( 'Spacing', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Price
        $fields['content_price_heading'] = [
            'label' => __('Price', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['content_price_color'] = [
            'label' => __( 'Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__price' => 'color: {{VALUE}}'
            ],
        ];

        $fields['content_price_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'content_price_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
            'selector' => '{{WRAPPER}} .m-neuron-product__price',
        ];

        $fields['content_price_alignment'] = [
            'label' => __( 'Alignment', 'neuron-builder' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'neuron-builder'),
                    'icon' => 'eicon-h-align-left',
                ],
                'right' => [
                    'title' => __('Right', 'neuron-builder'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'prefix_class' => 'm-neuron-product__price--alignment-'
        ];

        $fields['content_price_spacing'] = [
            'label' => __( 'Spacing', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}}*[class^="m-neuron-product__price--alignment-"] .m-neuron-product__price' => 'margin: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Star Rating
        $fields['star_rating_heading'] = [
            'label' => __('Star Rating', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_color'] = [
            'label' => __( 'Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .woocommerce .star-rating::before' => 'color: {{VALUE}}',
                '{{WRAPPER}} .woocommerce .star-rating' => 'color: {{VALUE}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_size'] = [
            'label' => __( 'Size', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .woocommerce .star-rating' => 'font-size: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_spacing'] = [
            'label' => __( 'Spacing', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__rating .woocommerce-product-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['content_border'] = [
            'label' => __( 'Border', 'neuron-builder' ),
            'name' => 'content_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-product__content',
            'default' => 'none',
            'separator' => 'before'
        ];

        $fields['content_border_radius'] = [
            'label' => __( 'Border Radius', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['content_products_padding'] = [
            'label' => __( 'Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        return $fields;
    }

    public function pagination_style_controls() {
        $fields = Posts::pagination_style_controls();

        return $fields;
    }
    
    public function navigation_style_controls() {
        $fields = Posts::navigation_style_controls();

        return $fields;
    }
    
    public function sale_flash_style_controls() {
        $fields = Portfolio::sale_flash_style_controls();

        return $fields;
    }
    
    public function add_to_cart_style_controls() {
        $fields = [];

        $fields['add_to_cart_text_color'] = [
			'label' => __( 'Text Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .m-neuron-product__add-to-cart a' => 'color: {{VALUE}};',
			],
        ];

        $fields['add_to_cart_loading_color'] = [
			'label' => __( 'Loading Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .m-neuron-product__add-to-cart .loading:before' => 'border-color: {{VALUE}}; border-bottom-color: transparent;',
			],
        ];
        
        $fields['add_to_cart_bg_color'] = [
			'label' => __( 'Background Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#000',
			'selectors' => [
				'{{WRAPPER}} .m-neuron-product__add-to-cart' => 'background-color: {{VALUE}};',
			],
        ];
        
        $fields['add_to_cart_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'add_to_cart_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-product__add-to-cart a',
        ];

        $fields['add_to_cart_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_border'] = [
            'label' => __( 'Border', 'neuron-builder' ),
            'name' => 'add_to_cart_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-product__add-to-cart',
            'default' => 'none',
        ];

        $fields['add_to_cart_border_radius'] = [
            'label' => __( 'Border Radius', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_shadow'] = [
            'label' => __( 'Box Shadow', 'neuron-builder' ),
            'name' => 'add_to_cart_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-product__add-to-cart'
        ];

        $fields['add_to_cart_second_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_padding'] = [
            'label' => __( 'Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'left' => 6,
                'right' => 6,
                'top' => 6,
                'bottom' => 6,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__add-to-cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_third_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_position'] = [
            'label' => __( 'Position', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'inside' => __( 'Inside', 'neuron-builder' ),
                'outside' => __( 'Outside', 'neuron-builder' ),
            ],
            'prefix_class' => 'm-neuron-product__add-to-cart--position-',
            'render_type' => 'template',
            'default' => 'inside',
        ];

        $fields['add_to_cart_h_position'] = [
            'label' => __('Horizontal Position', 'neuron-builder'),
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
                'justified' => [
                    'title' => __('Justified', 'neuron-builder'),
                    'icon' => 'eicon-h-align-stretch',
                ],
            ],
            'default' => 'justified',
            'prefix_class' => 'm-neuron-product__add-to-cart--h-'
        ];

        $fields['add_to_cart_v_position'] = [
            'label' => __('Vertical Position', 'neuron-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                 'top' => [
                    'title' => __('Top', 'neuron-builder'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Center', 'neuron-builder'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => __('Bottom', 'neuron-builder'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'prefix_class' => 'm-neuron-product__add-to-cart--v-'
        ];

        return $fields;
    }

    public function quick_view_style_controls() {
        $fields = [];

        $fields['quick_view_icon_heading'] = [
            'label' => __( 'Icon', 'neuron-builder' ),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['quick_view_icon_size'] = [
            'label' => __('Icon Size', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__quick-view a' => 'font-size: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['quick_view_icon_color'] = [
            'label' => __( 'Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__quick-view i' => 'color: {{VALUE}}',
                '{{WRAPPER}} .m-neuron-product__quick-view svg path' => 'stroke: {{VALUE}}',
                '{{WRAPPER}} .m-neuron-product__quick-view svg ellipse' => 'stroke: {{VALUE}}'
            ],
        ];

        $fields['quick_view_icon_bg_color'] = [
            'label' => __( 'Background Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__quick-view a' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['quick_view_icon_border_radius'] = [
            'label' => __('Border Radius', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%' ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__quick-view a' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['quick_view_icon_position_x'] = [
            'label' => __('Position X', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'rem', '%' ],
            'default' => [
                'unit' => '%'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__overlay .m-neuron-product__quick-view' => 'right: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['quick_view_icon_position_y'] = [
            'label' => __('Position Y', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'rem', '%' ],
            'default' => [
                'unit' => '%'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__overlay .m-neuron-product__quick-view' => 'top: {{SIZE}}{{UNIT}}',
            ],
        ];

        return $fields;
    }

    public function wishlist_style_controls() {
        $fields = [];

        $fields['wishlist_icon_size'] = [
            'label' => __('Icon Size', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__wishlist .yith-wcwl-add-to-wishlist a:after' => 'font-size: {{SIZE}}{{UNIT}}; width: calc(2em + 2px); height: calc(2em + 2px);',
            ],
        ];

        $fields['wishlist_icon_color'] = [
            'label' => __( 'Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__wishlist .yith-wcwl-add-to-wishlist a:after' => 'color: {{VALUE}}',
            ],
        ];

        $fields['wishlist_icon_bg_color'] = [
            'label' => __( 'Background Color', 'neuron-builder' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__wishlist .yith-wcwl-add-to-wishlist a:after' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['wishlist_icon_border_radius'] = [
            'label' => __('Border Radius', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%' ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__wishlist .yith-wcwl-add-to-wishlist a:after' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['wishlist_icon_position_x'] = [
            'label' => __('Position X', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'rem', '%' ],
            'default' => [
                'unit' => '%'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__overlay .m-neuron-product__wishlist' => 'right: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['wishlist_icon_position_y'] = [
            'label' => __('Position Y', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'rem', '%' ],
            'default' => [
                'unit' => '%'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-product__overlay .m-neuron-product__wishlist' => 'top: {{SIZE}}{{UNIT}}',
            ],
        ];

        return $fields;
    }
}
