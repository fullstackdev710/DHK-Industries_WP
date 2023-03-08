<?php
/**
 * Post Info
 * 
 * Display post meta information
 * on your single post or page.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Info extends Base_Widget {

	public function get_name() {
		return 'neuron-post-info';
	}

	public function get_title() {
		return __( 'Post Info', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-post-info neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
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

	protected function register_controls() {

		$this->start_controls_section(
			'post_info_section',
			[
				'label' => __('Post Info', 'neuron-builder')
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __('Layout', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => [
					'traditional' => [
						'title' => __('Default', 'neuron-builder'),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __('Inline', 'neuron-builder'),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => __('Type', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'author' => __('Author', 'neuron-builder'),
					'date' => __('Date', 'neuron-builder'),
					'time' => __('Time', 'neuron-builder'),
					'comments' => __('Comments', 'neuron-builder'),
					'terms' => __('Terms', 'neuron-builder'),
					'reading' => __('Reading Duration', 'neuron-builder'),
				],
			]
		);

		$repeater->add_control(
			'date_format',
			[
				'label' => __('Date Format', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => _x('March 6, 2018 (F j, Y)', 'Date Format', 'neuron-builder'),
					'1' => '2018-03-06 (Y-m-d)',
					'2' => '03/06/2018 (m/d/Y)',
					'3' => '06/03/2018 (d/m/Y)',
					'custom' => __('Custom', 'neuron-builder'),
				],
				'condition' => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'custom_date_format',
			[
				'label' => __('Custom Date Format', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'label_block' => false,
				'condition' => [
					'type' => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__('Use the letters: %s', 'neuron-builder'),
					'l D d j S F m M n Y y'
				),
			]
		);

		$repeater->add_control(
			'time_format',
			[
				'label' => __('Time Format', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => '3:31 pm (g:i a)',
					'1' => '3:31 PM (g:i A)',
					'2' => '15:31 (H:i)',
					'custom' => __('Custom', 'neuron-builder'),
				],
				'condition' => [
					'type' => 'time',
				],
			]
		);
		$repeater->add_control(
			'custom_time_format',
			[
				'label' => __('Custom Time Format', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => 'g:i a',
				'placeholder' => 'g:i a',
				'label_block' => false,
				'condition' => [
					'type' => 'time',
					'time_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed time letters (see: http://php.net/manual/en/function.time.php). */
					__('Use the letters: %s', 'neuron-builder'),
					'g G H i a A'
				),
			]
		);

		$repeater->add_control(
			'taxonomy',
			[
				'label' => __('Taxonomy', 'neuron-builder'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'type' => 'terms',
				],
			]
		);

		$repeater->add_control(
			'text_prefix',
			[
				'label' => __('Before', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$repeater->add_control(
			'terms_separator',
			[
				'label' => __('Separator', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => ',',
				'label_block' => false,
				'condition' => [
					'type' => 'terms',
				],
			]
		);

		$repeater->add_control(
			'text_postfix',
			[
				'label' => __('After', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => __( 'Minutes', 'neuron-builder' ),
				'condition' => [
					'type' => 'reading'
				]
			]
		);

		$repeater->add_control(
			'show_avatar',
			[
				'label' => __('Avatar', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type' => 'author',
				],
			]
		);

		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label' => __('Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .m-neuron-icon-list-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'comments_custom_strings',
			[
				'label' => __('Custom Format', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'default' => false,
				'condition' => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_no_comments',
			[
				'label' => __('No Comments', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __('No Comments', 'neuron-builder'),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_one_comment',
			[
				'label' => __('One Comment', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __('One Comment', 'neuron-builder'),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_comments',
			[
				'label' => __('Comments', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __('%s Comments', 'neuron-builder'),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'custom_text',
			[
				'label' => __('Custom', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __('Link', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'type!' => 'time',
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => __('Custom URL', 'neuron-builder'),
				'type' => Controls_Manager::URL,
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => __('Icon', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __('None', 'neuron-builder'),
					'default' => __('Default', 'neuron-builder'),
					'custom' => __('Custom', 'neuron-builder'),
				],
				'default' => 'default',
				'condition' => [
					'show_avatar!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => __( 'Choose Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition' => [
					'show_icon' => 'custom',
					'show_avatar!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type' => 'author',
						'selected_icon' => [
							'value' => 'n-icon-man',
							'library' => 'neuron-icons',
						],
					],
					[
						'type' => 'date',
						'selected_icon' => [
							'value' => 'n-icon-calendar',
							'library' => 'neuron-icons',
						],
					],
					[
						'type' => 'time',
						'selected_icon' => [
							'value' => 'n-icon-clock',
							'library' => 'neuron-icons',
						],
					],
					[
						'type' => 'comments',
						'selected_icon' => [
							'value' => 'n-icon-comment',
							'library' => 'neuron-icons',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => __('List', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __('Space Between', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-items:not(.m-neuron-inline-items) .m-neuron-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .m-neuron-icon-list-items:not(.m-neuron-inline-items) .m-neuron-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .m-neuron-icon-list-items.m-neuron-inline-items .m-neuron-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .m-neuron-icon-list-items.m-neuron-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .m-neuron-icon-list-items.m-neuron-inline-items .m-neuron-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __('Alignment', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __('Start', 'neuron-builder'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'neuron-builder'),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => __('End', 'neuron-builder'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'start' => 'flex-start',
					'end' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post-info' => 'justify-content: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __('Divider', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __('Off', 'neuron-builder'),
				'label_on' => __('On', 'neuron-builder'),
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-item:not(:last-child):after' => 'content: ""',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __('Style', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __('Solid', 'neuron-builder'),
					'double' => __('Double', 'neuron-builder'),
					'dotted' => __('Dotted', 'neuron-builder'),
					'dashed' => __('Dashed', 'neuron-builder'),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-items:not(.m-neuron-inline-items) .m-neuron-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-icon-list-items.m-neuron-inline-items .m-neuron-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __('Weight', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-items:not(.m-neuron-inline-items) .m-neuron-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .m-neuron-inline-items .m-neuron-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __('Width', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __('Height', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __('Icon', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __('Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 20,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __('Text', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_indent',
			[
				'label' => __('Indent', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-text' => 'padding-left: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-icon-list-text, {{WRAPPER}} .m-neuron-icon-list-text a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-icon-list-item, {{WRAPPER}} .m-neuron-icon-list-item a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_terms_style',
			[
				'label' => __('Terms', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'terms_indent',
			[
				'label' => __('Indent', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post-info__terms-list a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_control(
			'terms_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-post-info__terms-list a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'terms_border',
				'selector' => '{{WRAPPER}} .m-neuron-post-info__terms-list a, {{WRAPPER}} .m-neuron-post-info__terms-list span',
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies([
			'show_in_nav_menus' => true,
		], 'objects');

		$options = [
			'' => __('Choose', 'neuron-builder'),
		];

		foreach ($taxonomies as $taxonomy) {
			$options[$taxonomy->name] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_meta_data($repeater_item) {
		$item_data = [];

		switch ($repeater_item['type']) {
			case 'author':
				$item_data['text'] = get_the_author_meta('display_name');
				$item_data['icon'] = 'n-icon-man';
				$item_data['selected_icon'] = [
					'value' => 'n-icon-man',
					'library' => 'neuron-icons',
				]; 
				$item_data['itemprop'] = 'author';

				if ('yes' === $repeater_item['link']) {
					$item_data['url'] = [
						'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
					];
				}

				if ('yes' === $repeater_item['show_avatar']) {
					$item_data['image'] = get_avatar_url(get_the_author_meta('ID'), 96);
				}

				break;

			case 'date':
				$custom_date_format = empty($repeater_item['custom_date_format']) ? 'F j, Y' : $repeater_item['custom_date_format'];

				$format_options = [
					'default' => 'F j, Y',
					'0' => 'F j, Y',
					'1' => 'Y-m-d',
					'2' => 'm/d/Y',
					'3' => 'd/m/Y',
					'custom' => $custom_date_format,
				];

				$item_data['text'] = get_the_time( $format_options[ $repeater_item['date_format'] ] );
				$item_data['icon'] = 'n-icon-calendar';
				$item_data['selected_icon'] = [
					'value' => 'n-icon-calendar',
					'library' => 'n-icon-calendar',
				]; 
				$item_data['itemprop'] = 'datePublished';

				if ('yes' === $repeater_item['link']) {
					$item_data['url'] = [
						'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
					];
				}
				break;

			case 'time':
				$custom_time_format = empty($repeater_item['custom_time_format']) ? 'g:i a' : $repeater_item['custom_time_format'];

				$format_options = [
					'default' => 'g:i a',
					'0' => 'g:i a',
					'1' => 'g:i A',
					'2' => 'H:i',
					'custom' => $custom_time_format,
				];
				$item_data['text'] = get_the_time( $format_options[ $repeater_item['time_format'] ] );
				$item_data['icon'] = 'n-icon-clock';
				$item_data['selected_icon'] = [
					'value' => 'n-icon-clock',
					'library' => 'neuron-icons',
				]; 
				break;

			case 'comments':
				if (comments_open()) {
					$default_strings = [
						'string_no_comments' => __('No Comments', 'neuron-builder'),
						'string_one_comment' => __('One Comment', 'neuron-builder'),
						'string_comments' => __('%s Comments', 'neuron-builder'),
					];

					if ('yes' === $repeater_item['comments_custom_strings']) {
						if (!empty($repeater_item['string_no_comments'])) {
							$default_strings['string_no_comments'] = $repeater_item['string_no_comments'];
						}

						if (!empty($repeater_item['string_one_comment'])) {
							$default_strings['string_one_comment'] = $repeater_item['string_one_comment'];
						}

						if (!empty($repeater_item['string_comments'])) {
							$default_strings['string_comments'] = $repeater_item['string_comments'];
						}
					}

					$num_comments = (int) get_comments_number(); // get_comments_number returns only a numeric value

					if (0 === $num_comments) {
						$item_data['text'] = $default_strings['string_no_comments'];
					} else {
						$item_data['text'] = sprintf( _n($default_strings['string_one_comment'], $default_strings['string_comments'], $num_comments, 'neuron-builder'), $num_comments);
					}

					if ( 'yes' === $repeater_item['link'] ) {
						$item_data['url'] = [
							'url' => get_comments_link(),
						];
					}
					$item_data['icon'] = 'n-icon-comment';
					$item_data['selected_icon'] = [
						'value' => 'n-icon-comment',
						'library' => 'neuron-icons',
					]; 
					$item_data['itemprop'] = 'commentCount';
				}
				break;

			case 'terms':
				$item_data['icon'] = 'n-icon-tag';
				$item_data['selected_icon'] = [
					'value' => 'n-icon-tag',
					'library' => 'neuron-icons',
				]; 
				$item_data['itemprop'] = 'about';

				$item_data['terms_separator'] = $repeater_item['terms_separator'];

				$taxonomy = $repeater_item['taxonomy'];
				$terms = wp_get_post_terms(get_the_ID(), $taxonomy);
				foreach ($terms as $term) {
					$item_data['terms_list'][$term->term_id]['text'] = $term->name;
					if ('yes' === $repeater_item['link']) {
						$item_data['terms_list'][$term->term_id]['url'] = get_term_link($term);
					}
				}
				break;
				

			case 'reading':
				$item_data['text'] = Utils::get_reading_time();

				$item_data['icon'] = 'n-icon-clock'; 
				$item_data['selected_icon'] = [
					'value' => 'n-icon-clock',
					'library' => 'neuron-icons',
				]; 

			break;
		}

		$item_data['type'] = $repeater_item['type'];

		if (!empty($repeater_item['text_prefix'])) {
			$item_data['text_prefix'] = esc_html($repeater_item['text_prefix']);
		}

		if (!empty($repeater_item['text_postfix'])) {
			$item_data['text_postfix'] = esc_html($repeater_item['text_postfix']);
		}

		return $item_data;
	}

	protected function render_item($repeater_item) {
		$item_data = $this->get_meta_data($repeater_item);
		$repeater_index = $repeater_item['_id'];

		if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) {
			return;
		}

		$has_link = false;
		$link_key = 'link_' . $repeater_index;
		$item_key = 'item_' . $repeater_index;

		$this->add_render_attribute($item_key, 'class',
			[
				'm-neuron-icon-list-item',
				'm-neuron-repeater-item-' . $repeater_item['_id'],
			]
		);

		$active_settings = $this->get_active_settings();

		if ('inline' === $active_settings['view']) {
			$this->add_render_attribute($item_key, 'class', 'm-neuron-inline-item');
		}

		if (!empty($item_data['url'])) {
			$has_link = true;
			$this->add_render_attribute($link_key, 'href', $item_data['url']);
		}
		if (!empty($item_data['itemprop'])) {
			$this->add_render_attribute($item_key, 'itemprop', $item_data['itemprop']);
		}

		?>
		<li <?php echo $this->get_render_attribute_string($item_key); ?>>
			<?php if ($has_link) : ?>
			<a <?php echo $this->get_render_attribute_string($link_key); ?>>
				<?php endif; ?>
				<?php $this->render_item_icon_or_image($item_data, $repeater_item, $repeater_index); ?>
				<?php $this->render_item_text($item_data, $repeater_index); ?>
				<?php if ($has_link) : ?>
			</a>
		<?php endif; ?>
		</li>
		<?php
	}

	protected function render_item_icon_or_image($item_data, $repeater_item, $repeater_index) {
		// Set icon according to user settings.
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! $migration_allowed ) {
			if ( 'custom' === $repeater_item['show_icon'] && ! empty( $repeater_item['icon'] ) ) {
				$item_data['icon'] = $repeater_item['icon'];
			} elseif ( 'none' === $repeater_item['show_icon'] ) {
				$item_data['icon'] = '';
			}
		} else {
			if ( 'custom' === $repeater_item['show_icon'] && ! empty( $repeater_item['selected_icon'] ) ) {
				$item_data['selected_icon'] = $repeater_item['selected_icon'];
			} elseif ( 'none' === $repeater_item['show_icon'] ) {
				$item_data['selected_icon'] = [];
			}
		}

		if ( empty( $item_data['icon'] ) && empty( $item_data['selected_icon'] ) && empty( $item_data['image'] ) ) {
			return;
		}

		$migrated = isset( $repeater_item['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $repeater_item['icon'] ) && $migration_allowed;
		$show_icon = 'none' !== $repeater_item['show_icon'];

		if ( ! empty( $item_data['image'] ) || $show_icon ) {
			?>
			<span class="m-neuron-icon-list-icon">
			<?php
			if ( ! empty( $item_data['image'] ) ) :
				$image_data = 'image_' . $repeater_index;
				$this->add_render_attribute( $image_data, 'src', $item_data['image'] );
				$this->add_render_attribute( $image_data, 'alt', $item_data['text'] );
				?>
					<img class="elementor-avatar" <?php echo $this->get_render_attribute_string( $image_data ); ?>>
				<?php elseif ( $show_icon ) : ?>
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $item_data['selected_icon'], [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i class="<?php echo esc_attr( $item_data['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>
				<?php endif; ?>
			</span>
			<?php
		}
	}

	protected function render_item_text($item_data, $repeater_index) {
		$repeater_setting_key = $this->get_repeater_setting_key('text', 'icon_list', $repeater_index);

		$this->add_render_attribute($repeater_setting_key, 'class', [ 'm-neuron-icon-list-text', 'm-neuron-post-info__item', 'm-neuron-post-info__item--type-' . $item_data['type']]);

		if ( ! empty( $item['terms_list'] ) ) {
			$this->add_render_attribute( $repeater_setting_key, 'class', 'm-neuron-terms-list' );
		}

		?>
		<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
			<?php if ( ! empty( $item_data['text_prefix'] ) ) : ?>
				<span class="m-neuron-post-info__item-prefix"><?php echo esc_html($item_data['text_prefix']); ?></span>
			<?php endif; ?>
			<?php
			if ( ! empty( $item_data['terms_list'] ) ) :
				$terms_list = [];
				$item_class = 'm-neuron-post-info__terms-list-item';
				?>
				<span class="m-neuron-post-info__terms-list">
				<?php
				foreach ($item_data['terms_list'] as $term) {
					
					if ( ! is_wp_error( $term ) ) {
						
						if ( ! empty( $term['url'] ) ) {
							$terms_list[] = '<a href="' . esc_attr( $term['url'])  . '" class="' . $item_class . '">' . esc_html($term['text']) . '</a>';
						} else {
							$terms_list[] = '<span class="' . $item_class . '">' . esc_html($term['text']) . '</span>';
						
						}
					}
					
				}

				echo implode($item_data['terms_separator'] . ' ', $terms_list);
				?>
				</span>
			<?php else : ?>
				<?php
				echo wp_kses( $item_data['text'], [
					'a' => [
						'href' => [],
						'title' => [],
						'rel' => [],
					],
				] );
				?>
			<?php endif; ?>
			<?php if ( ! empty( $item_data['text_postfix'] ) ) : ?>
				<span class="m-neuron-post-info__item-postfix"><?php echo esc_html($item_data['text_postfix']); ?></span>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		ob_start();

		if ( ! empty( $settings['icon_list'] ) ) {
			foreach ( $settings['icon_list'] as $repeater_item ) {
				$this->render_item( $repeater_item );
			}
		}

		$items_html = ob_get_clean();

		if ( empty( $items_html ) ) {
			return;
		}

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute('icon_list', 'class', 'm-neuron-inline-items');
		}

		$this->add_render_attribute('icon_list', 'class', [ 'm-neuron-icon-list-items', 'm-neuron-post-info' ]);
		?>
		<ul <?php echo $this->get_render_attribute_string('icon_list'); ?>>
			<?php echo $items_html; ?>
		</ul>
		<?php
	}
	
	protected function content_template() {}
}
