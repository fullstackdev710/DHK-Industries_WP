<?php
namespace Neuron\Modules\TableOfContents\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Neuron\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Table_Of_Contents extends Base_Widget {

	public function get_name() {
		return 'neuron-table-of-contents';
	}

	public function get_title() {
		return __( 'Table of Contents', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements', 'neuron-elements-single' ];
	}

	public function get_keywords() {
		return [ 'toc' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'table_of_contents',
			[
				'label' => __( 'Table of Contents', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => __( 'Table of Contents', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h4',
			]
		);

		$this->start_controls_tabs( 'include_exclude_tags', [ 'separator' => 'before' ] );

		$this->start_controls_tab( 'include',
			[
				'label' => __( 'Include', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'headings_by_tags',
			[
				'label' => __( 'Anchors By Tags', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'container',
			[
				'label' => __( 'Container', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'This control confines the Table of Contents to heading elements under a specific container', 'neuron-builder' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // include

		$this->start_controls_tab( 'exclude',
			[
				'label' => __( 'Exclude', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'exclude_headings_by_selector',
			[
				'label' => __( 'Anchors By Selector', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selectors, in a comma-separated list', 'neuron-builder' ),
				'default' => [],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // exclude

		$this->end_controls_tabs(); // include_exclude_tags

		$this->add_control(
			'marker_view',
			[
				'label' => __( 'Marker View', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => [
					'numbers' => __( 'Numbers', 'neuron-builder' ),
					'bullets' => __( 'Bullets', 'neuron-builder' ),
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
				'condition' => [
					'marker_view' => 'bullets',
				],
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // table_of_contents

		$this->start_controls_section(
			'additional_options',
			[
				'label' => __( 'Additional Options', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'word_wrap',
			[
				'label' => __( 'Word Wrap', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'ellipsis',
				'prefix_class' => 'm-neuron-toc--content-',
			]
		);

		$this->add_control(
			'minimize_box',
			[
				'label' => __( 'Minimize Box', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'expand_icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$this->add_control(
			'collapse_icon',
			[
				'label' => __( 'Minimize Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'minimized_on',
			[
				'label' => __( 'Minimized On', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => [
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'neuron-builder' ), $breakpoints['md'] ),
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'neuron-builder' ), $breakpoints['lg'] ),
					'none' => __( 'None', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-toc--minimized-on-',
				'condition' => [
					'minimize_box!' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hierarchical_view',
			[
				'label' => __( 'Hierarchical View', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_subitems',
			[
				'label' => __( 'Collapse Subitems', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'The "Collapse" option should only be used if the Table of Contents is made sticky', 'neuron-builder' ),
				'condition' => [
					'hierarchical_view' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // settings

		$this->start_controls_section(
			'box_style',
			[
				'label' => __( 'Box', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => '--box-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-padding: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}}' => '--box-min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->end_controls_section(); // box_style

		$this->start_controls_section(
			'header_style',
			[
				'label' => __( 'Header', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--header-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-toc__header, {{WRAPPER}} .m-neuron-toc__header-title',
			]
		);

		$this->add_control(
			'toggle_button_color',
			[
				'label' => __( 'Icon Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_separator_width',
			[
				'label' => __( 'Separator Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // header_style

		$this->start_controls_section(
			'list_style',
			[
				'label' => __( 'List', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'list_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-toc__list-item',
			]
		);

		$this->add_control(
			'list_indent',
			[
				'label' => __( 'Indent', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-toc__list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'item_text_color_normal',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_normal',
			[
				'label' => __( 'Underline', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // normal

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_hover',
			[
				'label' => __( 'Underline', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // hover

		$this->start_controls_tab( 'active',
			[
				'label' => __( 'Active', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'item_text_color_active',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_active',
			[
				'label' => __( 'Underline', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // active

		$this->end_controls_tabs(); // item_text_style

		$this->add_control(
			'heading_marker',
			[
				'label' => __( 'Marker', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--marker-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'marker_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--marker-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // list_style
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'body', 'class', 'm-neuron-toc__body' );

		if ( $settings['collapse_subitems'] ) {
			$this->add_render_attribute( 'body', 'class', 'm-neuron-toc__list-items--collapsible' );
		}
		?>
		<div class="m-neuron-toc__header">
			<?php echo '<' . $settings['html_tag'] . ' class="m-neuron-toc__header-title">' . $settings['title'] . '</' . $settings['html_tag'] . '>'; ?>
			<?php if ( 'yes' === $settings['minimize_box'] ) : ?>
				<div class="m-neuron-toc__toggle-button m-neuron-toc__toggle-button--expand"><?php Icons_Manager::render_icon( $settings['expand_icon'] ); ?></div>
				<div class="m-neuron-toc__toggle-button m-neuron-toc__toggle-button--collapse"><?php Icons_Manager::render_icon( $settings['collapse_icon'] ); ?></div>
			<?php endif; ?>
		</div>
		<div <?php echo $this->get_render_attribute_string( 'body' ); ?>>
			<div class="m-neuron-toc__spinner-container">
				<i class="m-neuron-toc__spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</div>
		</div>
		<?php
	}
}
