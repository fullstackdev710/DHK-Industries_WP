<?php
/**
 * Price Table
 * 
 * Create well styled price tables
 * to attract customers to buy
 * your products or services.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\PriceTable\Widgets;

use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Price_Table extends Base_Widget {

	public function get_name() {
		return 'neuron-price-table';
	}

	public function get_title() {
		return __( 'Price Table', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-price-table neuron-badge';
    }
    
    public function get_keywords() {
		return [ 'price table', 'pricing table', 'pricing', 'table', 'product' ];
	}

	protected function register_controls() {
        // Header Section
		$this->start_controls_section(
			'header_section', [
				'label' => __( 'Header', 'neuron-builder' )
			]
        );

        $this->add_control(
			'title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Enter the title', 'neuron-builder' ),
			]
        );
        
        $this->add_control(
			'description',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Enter the description', 'neuron-builder' ),
			]
        );
        
        $this->end_controls_section(); // End Header Section

        // Pricing Section
		$this->start_controls_section(
			'pricing_section', [
				'label' => __( 'Pricing', 'neuron-builder' )
			]
        );

        $this->add_control(
			'currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'neuron-builder' ),
					'dollar' => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'neuron-builder' ),
					'euro' => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'neuron-builder' ),
					'baht' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'neuron-builder' ),
					'franc' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'neuron-builder' ),
					'guilder' => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'neuron-builder' ),
					'krona' => 'kr ' . _x( 'Krona', 'Currency Symbol', 'neuron-builder' ),
					'lira' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'neuron-builder' ),
					'peseta' => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'neuron-builder' ),
					'peso' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'neuron-builder' ),
					'pound' => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'neuron-builder' ),
					'real' => 'R$ ' . _x( 'Real', 'Currency Symbol', 'neuron-builder' ),
					'ruble' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'neuron-builder' ),
					'rupee' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'neuron-builder' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'neuron-builder' ),
					'shekel' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'neuron-builder' ),
					'yen' => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'neuron-builder' ),
					'won' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'custom_symbol',
			[
				'label' => __( 'Custom Symbol', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label' => __( 'Price', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '29.99',
			]
		);

		$this->add_control(
			'currency_format',
			[
				'label' => __( 'Currency Format', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '1,234.56 (Default)',
					',' => '1.234,56',
				],
			]
		);

		$this->add_control(
			'sale',
			[
				'label' => __( 'Sale', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'neuron-builder' ),
				'label_off' => __( 'Off', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'original_price',
			[
				'label' => __( 'Original Price', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '49',
				'condition' => [
					'sale' => 'yes'
				]
			]
		);

		$this->add_control(
			'period',
			[
				'label' => __( 'Period', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Monthly', 'neuron-builder' ),
			]
		);
        
		$this->end_controls_section(); // End Pricing Section

		// Features Section
		$this->start_controls_section(
			'features_section', [
				'label' => __( 'Features', 'neuron-builder' )
			]
		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Item Heading', 'neuron-builder' )
			]
		);

		$default_icon = [
			'value' => 'far fa-check-circle',
			'library' => 'fa-regular',
		];

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => $default_icon,
			]
		);

		$repeater->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'list_items',
			[
				'label' => __( 'List Items', 'neuron-builder' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => false,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => __( 'Item Heading 1#', 'neuron-builder' ),
						'icon' => $default_icon,
					],
					[
						'text' => __( 'Item Heading 2#', 'neuron-builder' ),
						'icon' => $default_icon,
					],
					[
						'text' => __( 'Item Heading 3#', 'neuron-builder' ),
						'icon' => $default_icon,
					]
				],
				'title_field' => '{{{ text }}}',
			]
		);
		
		$this->end_controls_section(); // End Features Section

		// Footer Section
		$this->start_controls_section(
			'footer_section', [
				'label' => __( 'Footer', 'neuron-builder' )
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Sign Up', 'neuron-builder' ),
			]
		);
		
		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::URL,
				'show_external' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#'
				]
			]
		);

		$this->add_control(
			'additional_info',
			[
				'label' => __( 'Additional Info', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'A sample text here', 'neuron-builder' ),
			]
		);

		$this->end_controls_section(); // End Footer Section

		// Ribbon Section
		$this->start_controls_section(
			'ribbon_section', [
				'label' => __( 'Ribbon', 'neuron-builder' )
			]
		);

		$this->add_control(
			'ribbon',
			[
				'label' => __( 'Visibility', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Popular', 'neuron-builder' ),
			]
		);
		
		$this->add_control(
			'ribbon_position',
			[
				'label' => __('Position', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'right',
				'prefix_class' => 'm-neuron-price-table--ribbon-',
				'toggle' => true
			]
		);

		$this->end_controls_section(); // End Footer Section

		// Header Style Section
		$this->start_controls_section(
			'header_style_section', [
				'label' => __( 'Header', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__header' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__heading' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .m-neuron-price-table__heading',
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__description' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .m-neuron-price-table__description',
			]
		);

		$this->end_controls_section(); // End Header Style Section

		// Pricing Style Section
		$this->start_controls_section(
			'pricing_style_section', [
				'label' => __( 'Pricing', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'pricing_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__price' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'pricing_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pricing_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__price' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pricing_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-price-table__price',
			]
		);

		// Currency Symbol
		$this->add_control(
			'currency_symbol_heading',
			[
				'label' => __( 'Currency Symbol', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => ''
				]
			]
		);

		$this->add_control(
			'currency_symbol_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__currency' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'currency_symbol!' => ''
				]
			]
		);

		$this->add_control(
			'currency_symbol_position',
			[
				'label' => __('Position', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'left',
				'toggle' => true,
				'condition' => [
					'currency_symbol!' => ''
				]
			]
		);

		$this->add_control(
			'currency_symbol_v_position',
			[
				'label' => __('Vertical Position', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __('Top', 'neuron-builder'),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __('Middle', 'neuron-builder'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'neuron-builder'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__currency' => 'align-self: {{VALUE}}'
				],
				'condition' => [
					'currency_symbol!' => ''
				]
			] 
		); // Currency End

		// Fractional Part
		$this->add_control(
			'fractional_part_heading',
			[
				'label' => __( 'Fractional Part', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_format!' => ','
				]
			]
		);

		$this->add_control(
			'fractional_part_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__decimal' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'currency_format!' => ','
				]
			]
		);

		$this->add_control(
			'fractional_part_v_position',
			[
				'label' => __('Vertical Position', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __('Top', 'neuron-builder'),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __('Middle', 'neuron-builder'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'neuron-builder'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__decimal' => 'align-self: {{VALUE}}'
				],
				'condition' => [
					'currency_format!' => ','
				]
			]
		); // Fractional Part End

		// Original Price
		$this->add_control(
			'original_price_heading',
			[
				'label' => __( 'Original Price', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sale' => 'yes'
				]
			]
		);

		$this->add_control(
			'original_price_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__original-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'original_price_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-table__original-price',
				'condition' => [
					'sale' => 'yes'
				]
			]
		);

		$this->add_control(
			'original_price_v_position',
			[
				'label' => __('Vertical Position', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __('Top', 'neuron-builder'),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __('Middle', 'neuron-builder'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'neuron-builder'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__original-price' => 'align-self: {{VALUE}}'
				],
				'condition' => [
					'sale' => 'yes'
				]
			]
		); // Original Price End

		// Period
		$this->add_control(
			'period_heading',
			[
				'label' => __( 'Period Part', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => ''
				]
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-table__period',
				'condition' => [
					'period!' => ''
				]
			]
		);

		$this->add_control(
			'period_position',
			[
				'label' => __( 'Currency Format', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'below' => __( 'Below', 'neuron-builder' ),
					'beside' => __( 'Beside', 'neuron-builder' ),
				],
				'default' => 'below',
				'selectors_dictionary' => [
					'below' => '100%',
					'beside' => 'auto'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__period' => 'width: {{VALUE}}'
				],
				'condition' => [
					'period!' => ''
				]
			]
		); // Period End

		$this->end_controls_section(); // End Pricing Style Section

		// Features Style Section
		$this->start_controls_section(
			'features_style_section', [
				'label' => __( 'Features', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'features_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'features_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-table__feature-list li',
			]
		);

		$this->add_control(
			'features_alignment', 
			[
            'label' => __('Alignment', 'neuron-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'neuron-builder'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'neuron-builder'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'neuron-builder'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-neuron-price-table__feature-list' => 'text-align: {{VALUE}}',
            ],
		]);

		$this->add_responsive_control(
			'features_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-inner' => 'margin-left: calc((100% - {{SIZE}}{{UNIT}})/2); margin-right: calc((100% - {{SIZE}}{{UNIT}})/2)',
				],
			]
		);

		// Divider
		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'neuron-builder' ),
					'double' => __( 'Double', 'neuron-builder' ),
					'dotted' => __( 'Dotted', 'neuron-builder' ),
					'dashed' => __( 'Dashed', 'neuron-builder' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list li:not(:first-of-type):before' => 'border-top-style: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list li:not(:first-of-type):before' => 'border-top-color: {{VALUE}}',
				],
				'default' => '#ddd',
				'condition' => [
					'divider' => 'yes'
				]
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list li:not(:first-of-type):before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
					'size' => 2
				],
				'condition' => [
					'divider' => 'yes'
				]
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => '%',
				'default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list li:not(:first-of-type):before' => 'margin-left: calc((100% - {{SIZE}}{{UNIT}})/2); margin-right: calc((100% - {{SIZE}}{{UNIT}})/2)',
				],
				'condition' => [
					'divider' => 'yes'
				]
			]
		);

		$this->add_control(
			'divider_gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__feature-list li:not(:first-of-type):before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'divider' => 'yes'
				]
			]
		);

		$this->end_controls_section(); // End Features Style Section

		// Footer Section
		$this->start_controls_section(
			'footer_style_section', [
				'label' => __( 'Footer', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'footer_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'footer_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button
		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
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
				'selector' => '{{WRAPPER}} .m-neuron-price-table__button',
			]
		);

		$this->start_controls_tabs(
			'button_tabs'
		);

		$this->start_controls_tab(
			'button_normal_tab',
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
					'{{WRAPPER}} .m-neuron-price-table__button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text_hover_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_hover_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Hover Animation', 'neuron-builder' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'button_text!' => '',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'footer_border_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-price-table__button',
				'condition' => [
					'button_text!' => '',
				]
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				]
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				]
			]
		);

		// Additional Info
		$this->add_control(
			'additional_info_heading',
			[
				'label' => __( 'Additional Info', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__additional-info' => 'color: {{VALUE}}',
				],
				'condition' => [
					'additional_info!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_info_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-table__additional-info',
				'condition' => [
					'additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_margin',
			[
				'label' => __( 'Margin', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__additional-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
					'unit' => 'px'
				],
				'condition' => [
					'additional_info!' => '',
				],
			]
		);

		$this->end_controls_section(); // End Footer Style Section

		// Ribbon Section
		$this->start_controls_section(
			'ribbon_section_style', [
				'label' => __( 'Ribbon', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__ribbon-inner' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'ribbon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_distance',
			[
				'label' => __( 'Distance', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)',
				],
				'condition' => [
					'ribbon!' => ''
				],
			]
		);

		$this->add_control(
			'ribbon_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-table__ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-table__ribbon-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-price-table__ribbon-inner',
			]
		);
		
		$this->end_controls_section(); // End Ribbon Style Section
	}
	
	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'franc' => '&#8355;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'yen' => '&#165;',
			'won' => '&#8361;',
			'guilder' => '&fnof;',
			'peso' => '&#8369;',
			'peseta' => '&#8359',
			'lira' => '&#8356;',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real' => 'R$',
			'krona' => 'kr',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	public function print_price( $price ) {
		$currency_format = $this->get_settings('currency_format');
		$output = '';

		if ( $currency_format != ',' ) {
			$price = explode('.', $price);
			$output .= '<span class="m-neuron-price-table__integer">'. $price[0] .'</span>';
			if ( is_array( $price ) && count( $price ) > 1 ) {
				$output .= '<div class="m-neuron-price-table__decimal"><span class="m-neuron-price-table__fractional">'. $price[1] .'</span></div>';
			}
		} else {
			$output = '<span class="m-neuron-price-table__integer">'. $price .'</span>';
		}

		return $output;
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-regular',
			];
		}
		return [];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Button Attributes
		$this->add_render_attribute( 'button_text', 'class', [
			'm-neuron-price-table__button',
		] );

		// Button Hover Animation
		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		?>
			<div class="m-neuron-price-table">
				<?php if ( $settings['title'] || $settings['description'] ) : ?>
					<div class="m-neuron-price-table__header">
						<?php if ( $settings['title'] ) : ?>
							<h2 class="m-neuron-price-table__heading"><?php echo esc_attr( $settings['title'] ) ?></h2>
						<?php endif; ?>
						<?php if ( $settings['description'] ) : ?>
							<span class="m-neuron-price-table__description"><?php echo esc_attr( $settings['description'] ) ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="m-neuron-price-table__price">
					<?php if ( $settings['sale'] == 'yes' && $settings['original_price'] ) : ?>
						<div class="m-neuron-price-table__original-price"><?php echo esc_attr( $this->get_currency_symbol($settings['currency_symbol']) ) ?><?php echo esc_attr( $settings['original_price'] ) ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['currency_symbol'] ) && $settings['currency_symbol_position'] == 'left' ) : ?>
						<span class="m-neuron-price-table__currency m-neuron-price-table__currency--before"><?php echo esc_attr( $this->get_currency_symbol($settings['currency_symbol']) ) ?></span>
					<?php endif; ?>			
					
					<?php if ( $settings['price'] ) : ?>
						<?php echo $this->print_price( $settings['price'] )  ?>
					<?php endif; ?>	

					<?php if ( ! empty( $settings['currency_symbol'] ) && $settings['currency_symbol_position'] == 'right' ) : ?>
						<span class="m-neuron-price-table__currency m-neuron-price-table__currency--after"><?php echo esc_attr( $this->get_currency_symbol($settings['currency_symbol']) ) ?></span>
					<?php endif; ?>	

					<?php if ( $settings['period'] ) : ?>
						<span class="m-neuron-price-table__period"><?php echo esc_attr( $settings['period'] ) ?></span>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $settings['list_items'] ) ) : ?>
					<ul class="m-neuron-price-table__feature-list">
						<?php foreach ( $settings['list_items'] as $list_item ) : ?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $list_item['_id'] ) ?>">
								<div class="m-neuron-price-table__feature-inner">
									<?php Icons_Manager::render_icon( $list_item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
									<span><?php echo esc_attr($list_item['text']) ?></span>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $settings['button_text'] || $settings['additional_info'] ) : ?>
					<div class="m-neuron-price-table__footer">
						<?php if ( $settings['button_text'] ) : ?>
							<?php 
								echo sprintf(
									'<a %s target="%s" rel="%s" href="%s">%s</a>',
									$this->get_render_attribute_string( 'button_text' ),
									$settings['button_link']['is_external'] == 'on' ? '_BLANK' : '_SELF',
									$settings['button_link']['nofollow'] == 'on' ? 'nofollow' : '',
									! empty( $settings['button_link']['url'] ) ? esc_url( $settings['button_link']['url'] )  : 'javascript:void(0)',
									esc_attr( $settings['button_text'] )
								);
							?>
						<?php endif; ?>
						
						<?php if ( $settings['additional_info'] ) : ?>
							<div class="m-neuron-price-table__additional-info"><?php echo wp_kses_post( $settings['additional_info'] ) ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $settings['ribbon'] == 'yes' ) : ?>
				<div class="m-neuron-price-table__ribbon">
					<div class="m-neuron-price-table__ribbon-inner"><?php echo esc_attr( $settings['ribbon_title'] ) ?></div>
				</div>
			<?php endif; ?>
		<?php
	}

	protected function content_template() {
		?>
		<#
			var symbols = {
				dollar: '&#36;',
				euro: '&#128;',
				franc: '&#8355;',
				pound: '&#163;',
				ruble: '&#8381;',
				shekel: '&#8362;',
				baht: '&#3647;',
				yen: '&#165;',
				won: '&#8361;',
				guilder: '&fnof;',
				peso: '&#8369;',
				peseta: '&#8359;',
				lira: '&#8356;',
				rupee: '&#8360;',
				indian_rupee: '&#8377;',
				real: 'R$',
				krona: 'kr'
			};

			var symbol = '',
				iconsHTML = {};

			if ( settings.currency_symbol ) {
				if ( 'custom' !== settings.currency_symbol ) {
					symbol = symbols[ settings.currency_symbol ] || '';
				} else {
					symbol = settings.custom_symbol;
				}
			}

			var buttonClasses = 'm-neuron-price-table__button';

			if ( settings.button_hover_animation ) {
				buttonClasses += ' elementor-animation-' + settings.button_hover_animation;
			}

		view.addRenderAttribute( 'title', 'class', 'm-neuron-price-table__heading' );
		view.addRenderAttribute( 'description', 'class', 'm-neuron-price-table__description' );
		view.addRenderAttribute( 'period', 'class', 'm-neuron-price-table__period' );
		view.addRenderAttribute( 'additional_info', 'class', 'm-neuron-price-table__additional-info' );
		view.addRenderAttribute( 'button_text', 'class', buttonClasses  );
		view.addRenderAttribute( 'ribbon_title', 'class', 'm-neuron-price-table__ribbon-inner' );

		view.addInlineEditingAttributes( 'title', 'none' );
		view.addInlineEditingAttributes( 'description', 'none' );
		view.addInlineEditingAttributes( 'period', 'none' );
		view.addInlineEditingAttributes( 'additional_info' );
		view.addInlineEditingAttributes( 'button_text' );
		view.addInlineEditingAttributes( 'ribbon_title' );

		var currencyFormat = settings.currency_format || '.',
			price = settings.price.split( currencyFormat ),
			intpart = price[0],
			fraction = price[1],

			periodElement = '<span ' + view.getRenderAttributeString( "period" ) + '>' + settings.period + '</span>';

		#>
		<div class="m-neuron-price-table">
			<# if ( settings.title || settings.description ) { #>
				<div class="m-neuron-price-table__header">
					<# if ( settings.title ) { #>
						<h2 {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</h2>
					<# } #>
					<# if ( settings.description ) { #>
						<span {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</span>
					<# } #>
				</div>
			<# } #>

			<div class="m-neuron-price-table__price">
				<# if ( settings.sale && settings.original_price ) { #>
					<div class="m-neuron-price-table__original-price">{{{ symbol + settings.original_price }}}</div>
				<# } #>

				<# if ( ! _.isEmpty( symbol ) && ( 'left' == settings.currency_symbol_position || _.isEmpty( settings.currency_symbol_position ) ) ) { #>
					<span class="m-neuron-price-table__currency">{{{ symbol }}}</span>
				<# } #>
				<# if ( intpart ) { #>
					<span class="m-neuron-price-table__integer">{{{ intpart }}}</span>
				<# } #>
				<div class="m-neuron-price-table__decimal">
					<# if ( fraction ) { #>
						<span class="m-neuron-price-table__fractional-part">{{{ fraction }}}</span>
					<# } #>
				</div>

				<# if ( ! _.isEmpty( symbol ) && 'right' == settings.currency_symbol_position ) { #>
				<span class="m-neuron-price-table__currency elementor-currency--after">{{{ symbol }}}</span>
				<# } #>

				<# if ( settings.period ) { #>
					{{{ periodElement }}}
				<# } #>
			</div>

			<# if ( settings.list_items ) { #>
				<ul class="m-neuron-price-table__feature-list">
				<# _.each( settings.list_items, function( item, index ) {

					var featureKey = view.getRepeaterSettingKey( 'text', 'list_items', index ),
						migrated = elementor.helpers.isIconMigrated( item, 'icon' );

					view.addInlineEditingAttributes( featureKey ); #>

						<li class="elementor-repeater-item-{{ item._id }}">
							<div class="m-neuron-price-table__feature-inner">
								<# if ( item.icon ) { #>
									<i class="{{ item.icon.value }}" aria-hidden="true"></i>
								<# } #>
								<# if ( ! _.isEmpty( item.text.trim() ) ) { #>
									<span {{{ view.getRenderAttributeString( featureKey ) }}}>{{{ item.text }}}</span>
								<# } else { #>
									&nbsp;
								<# } #>
							</div>
						</li>
				<# } ); #>
				</ul>
			<# } #>

			<# if ( settings.button_text || settings.additional_info ) { #>
				<div class="m-neuron-price-table__footer">
					<# if ( settings.button_text ) { #>
						<a href="#" {{{ view.getRenderAttributeString( 'button_text' ) }}}>{{{ settings.button_text }}}</a>
					<# } #>
					<# if ( settings.additional_info ) { #>
						<p {{{ view.getRenderAttributeString( 'additional_info' ) }}}>{{{ settings.additional_info }}}</p>
					<# } #>
				</div>
			<# } #>
		</div>

		<# if ( 'yes' === settings.ribbon && settings.ribbon_title ) {
			var ribbonClasses = 'm-neuron-price-table__ribbon';
			if ( settings.ribbon_position ) {
				ribbonClasses += ' elementor-ribbon-' + settings.ribbon_position;
			} #>
			<div class="{{ ribbonClasses }}">
				<div {{{ view.getRenderAttributeString( 'ribbon_title' ) }}}>{{{ settings.ribbon_title }}}</div>
			</div>
		<# } #>
		<?php
	}
}
