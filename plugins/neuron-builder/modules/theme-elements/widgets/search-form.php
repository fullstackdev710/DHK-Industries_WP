<?php
/**
 * Search Form
 * 
 * Display a custom search.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Search_Form extends Base_Widget {

	public function get_name() {
		return 'neuron-search-form';
	}

	public function get_title() {
		return __( 'Search Form', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-site-search neuron-badge';
    }
    
    public function get_categories() {
		return [ 'neuron-elements-site' ];
	}

	public function get_keywords() {
		return [ 'search', 'form' ];
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [ 'elementor-icons-fa-solid' ];
		}
		return [];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'search_form_functionality',
			[
				'label' => __('Functionality', 'neuron-builder')
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Search...', 'neuron-builder' ),
			]
		);

		

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none'  => __( 'None', 'neuron-builder' ),
					'icon'  => __( 'Icon', 'neuron-builder' ),
					'text' => __( 'Text', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-search-form--button-type-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Search', 'neuron-builder' ),
				'condition' => [
					'button_type' => 'text',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'n-icon-search',
					'library' => 'neuron-icons',
				],
				'condition' => [
					'button_type' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Extra Options
		 * 
		 * Change the parameters of query
		 * e.g. &post_type=product
		 */
		if ( class_exists( 'WooCommerce' ) ) {

			$this->start_controls_section(
				'search_form_advanced',
				[
					'label' => __('Advanced', 'neuron-builder')
				]
			);


			$this->add_control(
				'query_arguments',
				[
					'label' => __( 'Query Arguments', 'neuron-builder' ),
					'type' => Controls_Manager::TEXTAREA,
					'placeholder' => __( 'key|value', 'neuron-builder' ),
					'description' => sprintf( __( 'Set custom parameters for the search form. Each parameter in a separate line. Separate parameter key from the value using %s character.', 'neuron-builder' ), '<code>|</code>' ),
				]
			);

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'input_style_section',
			[
				'label' => __('Input', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-search-form__input',
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__input' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__input' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .m-neuron-search-form__container input',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => __( 'Focus', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'input_text_color_focus',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form--focus .m-neuron-search-form__input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_focus',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form--focus .m-neuron-search-form__container' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form--focus .m-neuron-search-form__container' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow_focus',
				'selector' => '{{WRAPPER}} .m-neuron-search-form--focus .m-neuron-search-form__container',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'border',
				'selector' => '{{WRAPPER}} .m-neuron-search-form__input',
				'separator' => 'before',
				'exclude' => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} input[type="search"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Input Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form input[type="search"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-search-form__submit',
				'condition' => [
					'button_type' => 'text',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .m-neuron-search-form__submit',
				'exclude' => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
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
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-search-form__submit' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'button_type' => 'icon',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	private function get_black_list_attributes() {
		static $black_list = null;

		if ( null === $black_list ) {
			$black_list = [ 's' ];

			$black_list = apply_filters( 'neuron/search_form/attributes/black_list', $black_list );
		}

		return $black_list;
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
			'input', [
				'placeholder' => $settings['placeholder'],
				'class' => 'm-neuron-search-form__input',
				'type' => 'search',
				'name' => 's',
				'title' => __( 'Search', 'neuron-builder' ),
				'value' => get_search_query(),
			]
		);

		// Set the selected icon.
		$migration_allowed = Icons_Manager::is_migration_allowed();
		$has_icon = ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon'] );
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && $migration_allowed;

		if ( 'icon' == $settings['button_type'] ) {
			if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) {
				$settings['icon'] = 'n-icon n-icon-search';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}

		?>
		<form class="m-neuron-search-form" role="search" action="<?php echo home_url( '/' ); ?>" method="get">
			<?php 
				if ( class_exists( 'WooCommerce' ) && $settings['query_arguments'] ) { 

					$attributes = explode( "\n", $settings['query_arguments'] );

					$black_list = $this->get_black_list_attributes();

					foreach ( $attributes as $attribute ) {
						if ( ! empty( $attribute ) ) {
							$attr = explode( '|', $attribute, 2 );
							if ( ! isset( $attr[1] ) ) {
								$attr[1] = '';
							}

							if ( ! in_array( strtolower( $attr[0] ), $black_list ) ) {
								?>
								<input name="<?php echo trim( $attr[0] ) ?>" value="<?php echo trim( $attr[1] ) ?>" type="hidden" />
								<?php
							}
						}
					}
				}  
			?>
			

			<?php do_action( 'neuron/search_form/before_open_form', $this ); ?> 

			<div class="m-neuron-search-form__container">

				<?php do_action( 'neuron/search_form/before_input', $this ); ?> 

				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>

				<?php do_action( 'neuron/search_form/after_input', $this ); ?>

				<?php if ( $settings['button_type'] != 'none' ) : ?>
					<button class="m-neuron-search-form__submit" type="submit" title="<?php esc_attr_e( 'Search', 'neuron-builder' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'neuron-builder' ); ?>">
						<?php if ( 'icon' === $settings['button_type'] ) : ?>
							<?php if ( $is_new || $migrated ) :
								Icons_Manager::render_icon( $settings['selected_icon'] );
							else : ?>
								<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
							<?php endif; ?>
							<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'neuron-builder' ); ?></span>
						<?php elseif ( 'text' === $settings['button_type'] && ! empty( $settings['button_text'] ) ) : ?>
							<?php echo $settings['button_text']; ?>
						<?php endif; ?>
					</button>
				<?php endif; ?>
			</div>

			<?php do_action( 'neuron/search_form/before_close_form', $this ); ?> 
		</form>
		<?php
	}

	protected function content_template() {
		?>
		<#
			var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

			
		#>
		<form class="m-neuron-search-form" role="search">
			
			<div class="m-neuron-search-form__container">
				
				<input type="search"
					   name="s"
					   title="<?php esc_attr_e( 'Search', 'neuron-builder' ); ?>"
					   class="m-neuron-search-form__input"
					   placeholder="{{ settings.placeholder }}">

				<# if ( 'none' !== settings.button_type ) { #>
					<button class="m-neuron-search-form__submit" type="submit">
						<# if ( 'icon' === settings.button_type ) { #>
							<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
							<# } else { #>
								<i class="{{ settings.icon || 'n-icon n-icon-search' }}" aria-hidden="true"></i>
							<# } #>
							<span class="elementor-screen-only"><?php esc_html_e( 'Submit', 'neuron-builder' ); ?></span>
						<# } else if ( 'text' === settings.button_type && settings.button_text ) { #>
							{{{ settings.button_text }}}
						<# } #>
					</button>
				<# } #>
			</div>
		</form>
		<?php
	}
}
