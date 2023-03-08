<?php
/**
 * Archive Posts Class
 * 
 * Extends the class Posts
 */

namespace Neuron\Core\Helpers;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Core\Helpers\Posts as Posts;
use Neuron\Core\Utils;

class Portfolio extends Posts {

    public function __construct( $thisElement ) {
        // Content
        $this->register_section( $thisElement, 'layout' );
        $this->register_section( $thisElement, 'query' );
        $this->register_section( $thisElement, 'query_metro', [ 'layout' => 'metro' ] );
        $this->register_section( $thisElement, 'pagination', ['carousel!' => 'yes'] );
        $this->register_section( $thisElement, 'filters' );
        
        // Style
        $this->register_section( $thisElement, 'layout_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'item_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'item_overlay_style', ['hover_animation!' => ['tooltip', 'fixed']], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'tooltip_style', ['hover_animation' => 'tooltip'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'fixed_style', ['hover_animation' => 'fixed'], 'TAB_STYLE' );

        if ( class_exists ( 'WooCommerce' ) ) {
            $this->register_section( $thisElement, 'sale_flash_style', ['source' => 'product', 'carousel!' => 'yes'], 'TAB_STYLE' );
            $this->register_section( $thisElement, 'add_to_cart_style', ['source' => 'product', 'add_to_cart' => 'yes', 'hover_animation!' => ['tooltip', 'fixed']], 'TAB_STYLE' );
        }
        
        $this->register_section( $thisElement, 'pagination_style', ['pagination!' => 'none', 'carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'navigation_style', ['carousel' => 'yes', 'navigation!' => 'none'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'filters_style', ['carousel!' => 'yes', 'filters' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_order', '', 'TAB_STYLE' );
    }

    public function layout_controls() {
        $fields = parent::layout_controls();

        // Unregister Controls
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
        
        // Register New Controls
        $fields['category'] = [
            'label' => __( 'Category', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'source!' => 'product'
            ]
        ];

        $fields['category_count'] = [
            'label' => __( 'Category Count', 'neuron-builder' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
            'condition' => [
                'category' => 'yes',
                'source!' => 'product'
            ]
        ];

        $fields['category_type'] = [
            'label' => __('Category Type', 'neuron-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_taxonomies(),
            'default' => 'category',
            'condition' => [
                'category' => 'yes',
                'source!' => 'product'
            ]
        ];

        if ( class_exists( 'WooCommerce' ) ) {
            $fields['price'] = [
                'label' => __( 'Price', 'neuron-builder' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'neuron-builder' ),
                'label_off' => __( 'Hide', 'neuron-builder' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'source' => 'product'
                ]
            ];
        }

        if ( class_exists( 'WooCommerce' ) ) {

            $fields['meta_bottom_divider'] = [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [
                    'source' => 'product'
                ]
            ];

            $fields['add_to_cart'] = [
                'label' => __( 'Add to Cart', 'neuron-builder' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'neuron-builder' ),
                'label_off' => __( 'Hide', 'neuron-builder' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'source' => 'product'
                ]
            ];

            $fields['add_to_cart_text'] = [
                'label' => __( 'Text', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Add to Cart', 'neuron-builder' ),
                'return_value' => 'yes',
                'condition' => [
                    'source' => 'product',
                    'add_to_cart' => 'yes'
                ]
            ];

            $fields['add_to_cart_selected_icon'] = [
                'label' => __( 'Icon', 'neuron-builder' ),
                'fa4compatibility' => 'add_to_cart_icon',
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shopping-cart',
                    'library' => 'solid',
                ],
                'condition' => [
                    'add_to_cart' => 'yes',
                    'source' => 'product'
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
                    'source' => 'product',
                    'add_to_cart' => 'yes',
                    'add_to_cart_selected_icon[value]!' => '',
                ],
                'selectors_dictionary' => [
                    'before' => 'row-reverse',
                    'after' => 'row',
                ],
                'selectors' => [
                    '{{WRAPPER}} .m-neuron-portfolio__add-to-cart a' => 'flex-direction: {{VALUE}}'
                ]
            ];

            $fields['add_to_cart_icon_spacing_before'] = [
                'label' => __('Icon Spacing', 'neuron-builder'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'source' => 'product',
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
                    '{{WRAPPER}} .m-neuron-portfolio__add-to-cart a span' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ];

            $fields['add_to_cart_icon_spacing_after'] = [
                'label' => __('Icon Spacing', 'neuron-builder'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'source' => 'product',
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
                    '{{WRAPPER}} .m-neuron-portfolio__add-to-cart a span' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
            ];

        }

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

    public function layout_style_controls() {
        $fields = parent::layout_style_controls();
        
        $newFields = [];

        $fields['alignment_vertical'] = [
            'label' => __('Alignment Vertical', 'neuron-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => __('Top', 'neuron-builder'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Middle', 'neuron-builder'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => __('Bottom', 'neuron-builder'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'selectors_dictionary' => [
                'top' => 'flex-start',
                'bottom' => 'flex-end',
            ],
            'condition' => [
                'hover_animation!' => ['fixed', 'tooltip']
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__overlay' => 'justify-content: {{VALUE}}' 
            ],
        ];

        $fields['alignment'] = [
            'label' => __('Alignment Horizontal', 'neuron-builder'),
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
            'condition' => [
                'hover_animation!' => ['fixed', 'tooltip']
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__overlay' => 'text-align: {{VALUE}}',
            ],
        ];

        foreach ( $fields as $key => $field ) {
            $newFields[$key] = $field;

            if ( $key == 'animation_popover_end' ) {
                $newFields['hover_animation'] = [
                    'label' => __( 'Hover Animation', 'neuron-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => Utils::get_hover_animations(),
                    'default' => 'translate',
                    'prefix_class' => 'm-neuron-portfolio--hover-',
                    'frontend_available' => true
                ];
            }
        }

        $newFields['tooltip_animation'] = [
            'label' => __( 'Tooltip Animation', 'neuron-builder' ),
            'type' => Controls_Manager::ANIMATION,
            'custom_control' => 'add_responsive_control',
            'frontend_available' => true,
            'default' => 'h-neuron-animation--slideUp',
            'condition' => [
                'hover_animation' => [ 'tooltip' ]
            ],
        ];

        return $newFields;
    }

    public static function item_style_controls() {
        $fields = [];

        $fields['item_border_radius'] = [
            'label' => __('Border Radius', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__thumbnail, {{WRAPPER}} .m-neuron-portfolio__overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['item_tabs'] = [
            'custom_control' => 'start_controls_tabs'
        ];

        // Normal
        $fields['item_normal_tab'] = [
            'label' => __( 'Normal', 'neuron-builder' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['item_shadow'] = [
            'label' => __( 'Box Shadow', 'neuron-builder' ),
            'name' => 'item_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__thumbnail--link'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Hover
        $fields['item_hover_tab'] = [
            'label' => __( 'Hover', 'neuron-builder' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['image_animation_hover'] = [
            'label' => __( 'Animation', 'neuron-builder' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __( 'None', 'neuron-builder' ),
                'zoom-in' => __( 'Zoom In', 'neuron-builder' ),
            ],
            'default' => '',
            'frontend_available' => true
        ];

        $fields['item_shadow_hover'] = [
            'label' => __( 'Box Shadow', 'neuron-builder' ),
            'name' => 'item_shadow_hover',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__thumbnail--link:hover'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        return $fields;
    }

    public static function item_overlay_style_controls() {
        $fields = [];

        $fields['item_overlay_active'] = [
            'label' => __( 'Active', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'active',
            'condition' => [
                'hover_animation!' => ['tooltip', 'fixed']
            ],
            'prefix_class' => 'm-neuron-portfolio__overlay--'
        ];

        $fields['item_overlay_active_reverse'] = [
            'label' => __( 'Active Reverse', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'reverse',
            'condition' => [
                'item_overlay_active' => 'active',
                'hover_animation!' => ['tooltip', 'fixed']
            ],
            'prefix_class' => 'm-neuron-portfolio__overlay--'
        ];

        $fields['active_bottom_divider'] = [
            'type' => Controls_Manager::DIVIDER,
            'condition' => [
                'hover_animation!' => ['tooltip', 'fixed']
            ]
        ];

        $fields['item_overlay_background'] = [
			'label' => __( 'Background Type', 'neuron-builder' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'color' => [
                    'title' => __('Classic', 'neuron-builder'),
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
            'condition' => [
                'hover_animation!' => ['tooltip', 'fixed']
            ],
            'prefix_class' => 'm-neuron-portfolio__overlay--'
        ];

		$fields['item_overlay_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__overlay' => 'background-color: {{VALUE}};',
			],
			'condition' => [
                'item_overlay_background' => [ 'color', 'gradient' ],
                'hover_animation!' => ['tooltip', 'fixed']
			],
		];

        $fields['item_overlay_image'] = [
			'label' => __( 'Image', 'neuron-builder' ),
			'type' => Controls_Manager::MEDIA,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__overlay' => 'background-image: url("{{URL}}"); background-size: cover; background-position: center; background-repeat: no-repeat;',
			],
            'media_type' => 'image',
            'dynamic' => [
                'active' => true,
            ],
			'condition' => [
                'item_overlay_background' => [ 'color' ],
                'hover_animation!' => ['tooltip', 'fixed']
			],
		];

		$fields['item_overlay_color_stop'] = [
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
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
		];

		$fields['item_overlay_color_b'] = [
			'label' => __( 'Second Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f2295b',
			'render_type' => 'ui',
			'condition' => [
                'item_overlay_background' => [ 'gradient' ],
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
		];

		$fields['item_overlay_color_b_stop'] = [
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
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
		];

		$fields['item_overlay_gradient_type'] = [
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
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
		];

		$fields['item_overlay_gradient_angle'] = [
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
				'{{WRAPPER}} .m-neuron-portfolio__overlay' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{item_overlay_color.VALUE}} {{item_overlay_color_stop.SIZE}}{{item_overlay_color_stop.UNIT}}, {{item_overlay_color_b.VALUE}} {{item_overlay_color_b_stop.SIZE}}{{item_overlay_color_b_stop.UNIT}})',
			],
			'condition' => [
				'item_overlay_background' => [ 'gradient' ],
                'item_overlay_gradient_type' => 'linear',
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
		];

		$fields['item_overlay_gradient_position'] = [
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
				'{{WRAPPER}} .m-neuron-portfolio__overlay' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{item_overlay_color.VALUE}} {{item_overlay_color_stop.SIZE}}{{item_overlay_color_stop.UNIT}}, {{item_overlay_color_b.VALUE}} {{item_overlay_color_b_stop.SIZE}}{{item_overlay_color_b_stop.UNIT}})',
			],
			'condition' => [
				'item_overlay_background' => [ 'gradient' ],
                'item_overlay_gradient_type' => 'radial',
                'hover_animation!' => ['tooltip', 'fixed']
			],
			'of_type' => 'gradient',
        ];

        $fields['item_overlay_spacing_divider'] = [
            'type' => Controls_Manager::DIVIDER,
        ];
        
        $fields['item_overlay_spacing'] = [
            'label' => __( 'Spacing', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'condition' => [
				'item_overlay_background!' => 'none',
				'hover_animation!' => ['tooltip', 'fixed'],
			],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__overlay' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
            ],
        ];

        $fields['item_overlay_content_padding'] = [
            'label' => __( 'Content Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'condition' => [
                'hover_animation!' => ['tooltip', 'fixed']
            ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['item_overlay_title_divider'] = [
            'type' => Controls_Manager::DIVIDER,
        ];
        
        // Title
        $fields['item_overlay_title_heading'] = [
            'label' => __('Title', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['item_overlay_title_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__title' => 'color: {{VALUE}};',
			],
        ];

        $fields['item_overlay_title_spacing'] = [
            'label' => __('Spacing', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            ],
        ];
        
        $fields['item_overlay_title_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'item_overlay_title_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__title',
        ];

        // Category
        $fields['item_overlay_category_heading'] = [
            'label' => __('Category', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['item_overlay_category_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__category' => 'color: {{VALUE}};',
			]
        ];

         $fields['item_overlay_category_spacing'] = [
            'label' => __('Spacing', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__category' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['item_overlay_category_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'item_overlay_category_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
            ],
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__category',
        ];

        // Price
        if ( class_exists( 'WooCommerce' ) ) {

            $fields['item_overlay_price_heading'] = [
                'label' => __('Price', 'neuron-builder'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'source' => 'product'
                ]
            ];
    
            $fields['item_overlay_price_color'] = [
                'label' => __( 'Color', 'neuron-builder' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .m-neuron-portfolio__price *' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .m-neuron-portfolio__price' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'source' => 'product'
                ]
            ];

            $fields['item_overlay_price_spacing'] = [
                'label' => __('Spacing', 'neuron-builder'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .m-neuron-portfolio__price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'source' => 'product'
                ]
            ];
            
            $fields['item_overlay_price_typography'] = [
                'label' => __( 'Typography', 'neuron-builder' ),
                'name' => 'item_overlay_price_typography',
                'custom_key' => Group_Control_Typography::get_type(),
                'custom_control' => 'add_group_control',
                'selector' => '{{WRAPPER}} .m-neuron-portfolio__price',
                'condition' => [
                    'source' => 'product'
                ]
            ];
        }

        return $fields;
    }

    public static function tooltip_style_controls() {
        $fields = [];

        // Title
        $fields['tooltip_title_heading'] = [
            'label' => __('Title', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['tooltip_title_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__title' => 'color: {{VALUE}} !important;',
			],
        ];

        $fields['tooltip_title_bg_color'] = [
			'label' => __( 'Background Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'#tooltip-caption .m-neuron-portfolio--hover-tooltip__title' => 'background-color: {{VALUE}} !important;',
			],
        ];

        $fields['tooltip_title_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'tooltip_title_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '#tooltip-caption .m-neuron-portfolio--hover-tooltip__title',
        ];

        $fields['tooltip_title_padding'] = [
            'label' => __( 'Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        // Subtitle
        $fields['tooltip_subtitle_heading'] = [
            'label' => __('Subtitle', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['tooltip_subtitle_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'color: {{VALUE}} !important;',
			]
        ];

        $fields['tooltip_subtitle_bg_color'] = [
			'label' => __( 'Background Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
			]
        ];

        $fields['tooltip_subtitle_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'tooltip_subtitle_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle',
        ];

        $fields['tooltip_subtitle_padding'] = [
            'label' => __( 'Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['subtitle_offset_orientation_h'] = [
            'label' => __( 'Horizontal Orientation', 'neuron-builder' ),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'default' => 'end',
            'options' => [
                'start' => [
                    'title' => __( 'Left', 'neuron-builder' ),
                    'icon' => 'eicon-h-align-left',
                ],
                'end' => [
                    'title' => __( 'Right', 'neuron-builder' ),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'classes' => 'elementor-control-start-end',
            'render_type' => 'ui',
        ];

        $fields['subtitle_offset_x'] = [
            'label' => __( 'Offset', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'size' => '0',
                ],
                'custom_control' => 'add_responsive_control',
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors' => [
					'#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'subtitle_offset_orientation_h!' => 'end',
				],
        ];

        $fields['subtitle_offset_x_end'] = [
            'label' => __( 'Offset', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'default' => [
                'size' => '0',
            ],
            'size_units' => [ 'px', '%', 'vw', 'vh' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'right: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'subtitle_offset_orientation_h' => 'end',
            ],
        ];

        $fields['subtitle_offset_orientation_v'] = [
            'label' => __( 'Vertical Orientation', 'neuron-builder' ),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'default' => 'start',
            'options' => [
                'start' => [
                    'title' => __( 'Top', 'neuron-builder' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'end' => [
                    'title' => __( 'Bottom', 'neuron-builder' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'render_type' => 'ui',
        ];

        $fields['subtitle_offset_y'] = [
            'label' => __( 'Offset', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'size_units' => [ 'px', '%', 'vh', 'vw' ],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'size' => '0',
            ],
            'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'top: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'subtitle_offset_orientation_v!' => 'end',
            ],
        ];

        $fields['subtitle_offset_y_end'] = [
            'label' => __( 'Offset', 'neuron-builder' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'size_units' => [ 'px', '%', 'vh', 'vw' ],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'size' => '0',
            ],
            'selectors' => [
                '#tooltip-caption .m-neuron-portfolio--hover-tooltip__subtitle' => 'bottom: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'subtitle_offset_orientation_v' => 'end',
            ],
        ];

        return $fields;
    }

    public static function fixed_style_controls() {
        $fields = [];

        // Title
        $fields['fixed_title_heading'] = [
            'label' => __('Title', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['fixed_title_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'#fixed-caption h4' => 'color: {{VALUE}} !important;',
			],
        ];

        $fields['fixed_title_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'fixed_title_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '#fixed-caption h4',
        ];

        $fields['fixed_title_spacing'] = [
            'label' => __('Spacing', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '#fixed-caption h4' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
            ],
        ];

        // Subtitle
        $fields['fixed_subtitle_heading'] = [
            'label' => __('Subtitle', 'neuron-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['fixed_subtitle_color'] = [
			'label' => __( 'Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'#fixed-caption span' => 'color: {{VALUE}} !important;',
			]
        ];


        $fields['fixed_subtitle_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'fixed_subtitle_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '#fixed-caption span',
        ];

        return $fields;
    }

    public static function sale_flash_style_controls() {

        $fields = [];

        $fields['sale_flash'] = [
            'label' => __( 'Sale Flash', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'neuron-builder' ),
            'label_off' => __( 'Hide', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'no'
        ];

        $fields['sale_flash_text_color'] = [
			'label' => __( 'Text Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'color: {{VALUE}};',
			],
			'condition' => [
                'sale_flash' => 'yes',
			],
        ];
        
        $fields['sale_flash_background_color'] = [
			'label' => __( 'Background Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'background-color: {{VALUE}};',
			],
			'condition' => [
                'sale_flash' => 'yes',
			],
        ];
        
        $fields['sale_flash_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'sale_flash_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__sale-flash',
            'condition' => [
                'sale_flash' => 'yes',
			]
        ];

        $fields['sale_flash_border_radius'] = [
            'label' => __( 'Border Radius', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_width'] = [
            'label' => __('Width', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'width: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_height'] = [
            'label' => __('Height', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'height: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_position'] = [
			'label' => __( 'Position', 'neuron-builder' ),
            'type' => Controls_Manager::CHOOSE,
            'condition' => [
                'sale_flash' => 'yes',
			],
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
            'selectors_dictionary' => [
                'left' => 'left: 0; right: auto;',
                'right' => 'right: 0; left: auto;'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__sale-flash' => '{{VALUE}}',
            ],
			'label_block' => false,
        ];
        
        $fields['sale_flash_distance'] = [
            'label' => __('Distance', 'neuron-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
            ],
            'range' => [
                'px' => [
                    'min' => -20,
                    'max' => 20,
                    'step' => 1,
                ],
                'em' => [
                    'min' => -2,
                    'max' => 2,
                    'step' => 0.1
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__sale-flash' => 'margin: {{SIZE}}{{UNIT}}',
            ],
        ];

        return $fields;
    }

    public static function add_to_cart_style_controls() {

        $fields['add_to_cart_text_color'] = [
			'label' => __( 'Text Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__add-to-cart' => 'color: {{VALUE}};',
			],
        ];
        
        $fields['add_to_cart_bg_color'] = [
			'label' => __( 'Background Color', 'neuron-builder' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-neuron-portfolio__add-to-cart' => 'background-color: {{VALUE}};',
			],
        ];
        
        $fields['add_to_cart_typography'] = [
            'label' => __( 'Typography', 'neuron-builder' ),
            'name' => 'add_to_cart_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__add-to-cart',
        ];

        $fields['add_to_cart_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_border'] = [
            'label' => __( 'Border', 'neuron-builder' ),
            'name' => 'add_to_cart_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__add-to-cart',
            'default' => 'none',
        ];

        $fields['add_to_cart_border_radius'] = [
            'label' => __( 'Border Radius', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_shadow'] = [
            'label' => __( 'Box Shadow', 'neuron-builder' ),
            'name' => 'add_to_cart_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-neuron-portfolio__add-to-cart'
        ];

        $fields['add_to_cart_second_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_padding'] = [
            'label' => __( 'Padding', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__add-to-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_third_divider'] = [
            'type' => Controls_Manager::DIVIDER
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
            'prefix_class' => 'm-neuron-portfolio__add-to-cart--h-'
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
            'prefix_class' => 'm-neuron-portfolio__add-to-cart--v-'
        ];

        $fields['add_to_cart_spacing'] = [
            'label' => __( 'Spacing', 'neuron-builder' ),
            'type' => Controls_Manager::DIMENSIONS,
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .m-neuron-portfolio__add-to-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ];

        return $fields;
    }

        public function content_order_controls() {
        $fields = [];

        $fields['allow_content_order'] = [
            'label' => __( 'Allow Ordering', 'neuron-builder' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'neuron-builder' ),
            'label_off' => __( 'No', 'neuron-builder' ),
            'return_value' => 'yes',
            'default' => 'no',
        ];

        $repeater = new Repeater();

		$repeater->add_control(
			'type', [
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'title' => __( 'Title', 'neuron-builder' ),
                    'category' => __( 'Category', 'neuron-builder' ),
                    'price' => __( 'Price', 'neuron-builder' ),
                    'cart' => __( 'Cart', 'neuron-builder' ),
                ]
			]
        );

        $fields['content_order'] = [
            'type' => Controls_Manager::REPEATER,
            'show_label' => false,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'type' => 'title' ],
                [ 'type' => 'category' ],
            ],
            'condition' => [
                'allow_content_order' => 'yes',
                'source!' => 'product'
            ],
            'title_field' => '<span> {{{ neuron.helpers.marionetteTitle(type) }}} </span>',
            'item_actions' => [
				'add' => false,
				'duplicate' => false,
				'remove' => false,
				'sort' => true,
			],
        ];

        $fields['content_order_woo'] = [
            'type' => Controls_Manager::REPEATER,
            'show_label' => false,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'type' => 'title' ],
                [ 'type' => 'price' ],
                [ 'type' => 'cart' ],
            ],
            'condition' => [
                'allow_content_order' => 'yes',
                'source' => 'product'
            ],
            'title_field' => '<span> {{{ neuron.helpers.marionetteTitle(type) }}} </span>',
            'item_actions' => [
				'add' => false,
				'duplicate' => false,
				'remove' => false,
				'sort' => true,
			],
        ];

        return $fields;
    }
}
