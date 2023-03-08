<?php
/**
 * Post Navigation
 * 
 * Display a custom navigation
 * in post or page.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Navigation extends Base_Widget {

	public function get_name() {
		return 'neuron-post-navigation';
	}

	public function get_title() {
		return __('Post Navigation', 'neuron-builder');
	}

	public function get_icon() {
		return 'eicon-post-navigation neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
			];
		}
		return [];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'post_navigation_section',
			[
				'label' => __('Post Navigation', 'neuron-builder')
			]
		);

		$this->add_control(
			'label',
			[
				'label' => __('Label', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'previous_label',
			[
				'label' => __('Previous Label', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Previous', 'neuron-builder'),
				'condition' => [
					'label' => 'yes',
					'hide_previous!' => 'yes' 
				]
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __('Next Label', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Next', 'neuron-builder'),
				'condition' => [
					'label' => 'yes',
					'hide_next!' => 'yes',
				]
			]
		);

		$this->add_control(
			'arrows_icon',
			[
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
				]
			]
		);

		$this->add_control(
			'post_title',
			[
				'label' => __('Post Title', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'same_term',
			[
				'label' => __('Same Term', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => __( 'Taxonomy', 'neuron-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_taxonomies(),
				'default' => 'category',
				'condition' => [
					'same_term' => 'yes'
				]
			]
		);

		$this->add_control(
			'thumbnail_hover',
			[
				'label' => __( 'Thumbnail on Hover', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'neuron-builder'),
				'label_off' => __('Hide', 'neuron-builder'),
				'default' => 'no',
				'return_value' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hide_previous',
			[
				'label' => __('Hide Previous', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'hide_next',
			[
				'label' => __('Hide Next', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Label
		 */
		$this->start_controls_section(
			'post_navigation_label_section',
			[
				'label' => __('Label', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('post_navigation_label_tabs');

		$this->start_controls_tab(
			'post_navigation_label_normal',
			[
				'label' => __('Normal', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_label_normal_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__label' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_navigation_label_normal_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .o-post-navigation__label'
			]
        );
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_navigation_label_hover',
			[
				'label' => __('Hover', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_label_hover_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation a:hover .o-post-navigation__label' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_navigation_label_hover_typography',
				'selector' => '{{WRAPPER}} .o-post-navigation a:hover .o-post-navigation__label'
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Title
		 */
		$this->start_controls_section(
			'post_navigation_title_section',
			[
				'label' => __('Title', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('post_navigation_title_tabs');

		$this->start_controls_tab(
			'post_navigation_title_normal',
			[
				'label' => __('Normal', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_title_normal_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__title' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_navigation_title_normal_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .o-post-navigation__title'
			]
        );
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_navigation_title_hover',
			[
				'label' => __('Hover', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_title_hover_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation a:hover .o-post-navigation__title' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_navigation_title_hover_typography',
				'selector' => '{{WRAPPER}} .o-post-navigation a:hover .o-post-navigation__title'
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Arrow
		 */
		$this->start_controls_section(
			'post_navigation_arrow_section',
			[
				'label' => __('Arrow', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('post_navigation_arrow_tabs');

		$this->start_controls_tab(
			'post_navigation_arrow_normal',
			[
				'label' => __('Normal', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_arrow_normal_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__icon i' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_navigation_arrow_hover',
			[
				'label' => __('Hover', 'neuron-builder'),
			]
		);

		$this->add_control(
			'post_navigation_arrow_hover_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation a:hover .o-post-navigation__icon i' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __('Size', 'neuron-builder'),
				'type' =>Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1
					]
				],
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .o-post-navigation__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'arrow_gap',
			[
				'label' => __('Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__icon.left .o-post-navigation__label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .o-post-navigation__icon.right .o-post-navigation__label' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Position
		 */
		$this->start_controls_section(
			'position_section_style',
			[
				'label' => __('Position', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Align', 'neuron-builder' ),
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
					'justify' => [
						'title' => __( 'Stretch', 'neuron-builder' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'o-post-navigation__align-',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__link:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation' => 'padding: {{SIZE}}{{UNIT}} 0;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Thumbnail
		 */
		$this->start_controls_section(
			'thumbnail_section_style',
			[
				'label' => __( 'Thumbnail', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'thumbnail_hover' => 'yes',
				]
			]
		);

		$this->add_control(
			'thumbnail_position',
			[
				'label' => __( 'Position', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top-left' => __( 'Top Left', 'neuron-builder' ),
					'top-center' => __( 'Top Center', 'neuron-builder' ),
					'top-right' => __( 'Top Right', 'neuron-builder' ),
					'center-left' => __( 'Center Left', 'neuron-builder' ),
					'center' => __( 'Center', 'neuron-builder' ),
					'center-right' => __( 'Center Left', 'neuron-builder' ),
					'bottom-left' => __( 'Bottom Left', 'neuron-builder' ),
					'bottom-center' => __( 'Bottom Center', 'neuron-builder' ),
					'bottom-right' => __( 'Bottom Right', 'neuron-builder' ),
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__hover-image' => 'background-position: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'thumbnail_attachment',
			[
				'label' => __( 'Attachment', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'scroll' => __( 'Scroll', 'neuron-builder' ),
					'fixed' => __( 'Fixed', 'neuron-builder' ),
				],
				'default' => 'scroll',
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__hover-image' => 'background-attachment: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'thumbnail_repeat',
			[
				'label' => __( 'Repeat', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'no-repeat' => __( 'No Repeat', 'neuron-builder' ),
					'repeat' => __( 'Repeat', 'neuron-builder' ),
					'repeat-x' => __( 'Repeat X', 'neuron-builder' ),
					'repeat-y' => __( 'Repeat Y', 'neuron-builder' ),
				],
				'default' => 'no-repeat',
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__hover-image' => 'background-repeat: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'thumbnail_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'auto' => __( 'Auto', 'neuron-builder' ),
					'cover' => __( 'Cover', 'neuron-builder' ),
					'contain' => __( 'Contain', 'neuron-builder' ),
				],
				'default' => 'cover',
				'selectors' => [
					'{{WRAPPER}} .o-post-navigation__hover-image' => 'background-size: {{VALUE}}'
				]
			]
		);

		$this->end_controls_section();
	}

	public function print_hover_thumbnail( $dir = 'prev' ) {
		if ( $this->get_settings( 'thumbnail_hover' ) != 'yes' ) {
			return;
		}

		if ( $this->get_settings( 'same_term' ) == 'yes' ) {
			$post = $dir == 'prev' ? get_previous_post( true, [], $this->get_settings( 'taxonomy' ) ) : get_next_post( true, [], $this->get_settings( 'taxonomy' ) );
		} else {
			$post = $dir == 'prev' ? get_previous_post() : get_next_post();
		}

		$thumbnail_url = $post ? get_the_post_thumbnail_url( $post->ID, 'full' ) : '';

		if ( ! empty ( $thumbnail_url ) )  {
			return 'data-img="'. $thumbnail_url .'"';
		}
	}

	public function get_taxonomies() {
        $taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
    }

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'post-navigation', 'class', [
			'o-post-navigation',
		] );
		
		/**
		 * Previous Label
		 */
		ob_start();
		Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] );
		$icon = ob_get_clean();

		$previous_label = sprintf(
			'<div class="o-post-navigation__icon left">
			%s 
			%s
			</div>
			%s',
			$settings['arrows_icon'] ? $icon : '',
			$settings['label'] == 'yes' ? '<span class="o-post-navigation__label">'. $settings['previous_label'] .'</span>' : '',
			$settings['post_title'] == 'yes' ? '<span class="o-post-navigation__title">%title</span>' : ''
		);

		/**
		 * Next Label
		 */
		$next_label = sprintf(
			'<div class="o-post-navigation__icon right">
			%s %s
			</div>
			%s',
			$settings['label'] == 'yes' ? '<span class="o-post-navigation__label">'. $settings['next_label'] .'</span>' : '',
			$settings['arrows_icon'] ? $icon : '',
			$settings['post_title'] == 'yes' ? '<span class="o-post-navigation__title">%title</span>' : ''
		);

		// Label
		if ( $settings['label'] != 'yes' ) {
			$this->add_render_attribute( 'post-navigation', 'class', 'o-post-navigation--no-label' );
		}

		// Same Term

		if ( $settings['same_term'] == 'yes' ) {
			$prev = get_previous_post_link( '%link', $previous_label, true, '', $settings['taxonomy'] );
			$next = get_next_post_link( '%link', $next_label, true, '', $settings['taxonomy'] ); 
		} else {
			$prev = get_previous_post_link( '%link', $previous_label );
			$next = get_next_post_link( '%link', $next_label ); 
		}

		$prev = $settings['hide_previous'] == 'yes' ? '' : $prev;
		$next = $settings['hide_next'] == 'yes' ? '' : $next;

		if ( $prev || $next ) { 
	?>
		<div <?php echo $this->get_render_attribute_string( 'post-navigation' ); ?>>

			<?php if ( $settings['thumbnail_hover'] == 'yes' ) : ?>
				<div class="o-post-navigation__hover-image"></div>
			<?php endif; ?>

			<?php if ( $settings['hide_previous'] != 'yes' ) : ?>
				<div class="o-post-navigation__link o-post-navigation__link--prev" <?php echo $this->print_hover_thumbnail( 'prev' ) ?>>
					<?php echo $prev ?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['hide_next'] != 'yes' ) : ?>
				<div class="o-post-navigation__link o-post-navigation__link--next" <?php echo $this->print_hover_thumbnail( 'next' ) ?>>
					<?php echo $next; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php
		}
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
}
