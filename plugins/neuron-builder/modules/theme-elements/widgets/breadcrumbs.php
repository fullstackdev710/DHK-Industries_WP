<?php
/**
 * Breadcrumbs
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Breadcrumbs extends Base_Widget {

	public function get_name() {
		return 'neuron-breadcrumbs';
	}

	public function get_title() {
		return __( 'Breadcrumbs', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-chevron-double-right neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'breadcrumbs_section',
			[
				'label' => __( 'Breadcrumbs', 'neuron-builder' )
			]
        );
        
        $this->add_control(
			'separator',
			[
				'label' => __( 'Separator', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
			]
        );

        $this->add_control(
			'prefix_breadcrumb',
			[
				'label' => __( 'Prefix Breadcrumb', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
			]
        );
        
        $this->add_control(
			'show_home',
			[
				'label' => __( 'Show Homepage Link', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $this->add_control(
			'home_label',
			[
				'label' => __( 'Homepage Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
                'default' => __( 'Home', 'neuron-builder' ),
                'condition' => [
                    'show_home' => 'yes'
                ]
			]
        );

        $this->add_control(
			'home_url',
			[
				'label' => __( 'Homepage URL', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
                'default' => home_url( '/' ),
                'label_block' => true,
                'dynamic' => [
					'active' => true,
				],
                'condition' => [
                    'show_home' => 'yes'
                ]
			]
        );

        $this->add_control(
			'archive_format',
			[
				'label' => __( 'Archive Format', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Archives for %s', 'neuron-builder' ),
                'label_block' => true,
			]
        );

        $this->add_control(
			'search_results_format',
			[
				'label' => __( 'Search Results Format', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Results for %s', 'neuron-builder' ),
                'label_block' => true,
			]
        );

        $this->add_control(
			'404_format',
			[
				'label' => __( '404 Page', 'neuron-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Error 404: Page not found', 'neuron-builder' ),
                'label_block' => true,
			]
		);
		
		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'p' => 'p',
					'div' => 'div',
					'nav' => 'nav',
					'span' => 'span',
				],
				'default' => 'div',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Breadcrumbs', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-breadcrumbs' => 'justify-content: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Separator Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-breadcrumbs__separator' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-breadcrumbs span:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_breadcrumbs_style' );

		$this->start_controls_tab(
			'tab_color_normal',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_color_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
	}

	protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = [
          'archive_format' => $settings['archive_format'],
          'search_results_format' => $settings['search_results_format'],
          '404_format' => $settings['404_format'],
          'prefix_breadcrumb' => $settings['prefix_breadcrumb'],
		];
		
		if ( $settings['show_home'] == 'yes' ) {
			$args['home_label'] = $settings['home_label'];
			$args['home_url'] = $settings['home_url'];
		}

       	if ( Utils::get_breadcrumbs( $args ) ) {
			?>

			<<?php echo esc_attr( $settings['html_tag'] ) ?> class="m-neuron-breadcrumbs" aria-label="<?php echo esc_html__( 'You are here:', 'neuron-builder' ) ?>">
				<?php
					if ( ! empty( $args['prefix_breadcrumb'] ) ) {
						echo '<span class="m-neuron-breadcrumbs__item--prefix">' .  $args['prefix_breadcrumb'] . '</span>';
					}

					ob_start();

					Icons_Manager::render_icon( $this->get_settings( 'separator' ) );

					$separator_icon = ob_get_contents();

					ob_end_clean();

					$separator = $settings['separator'] ? '<span class="m-neuron-breadcrumbs__separator"> ' . $separator_icon . '</span> ' : '';

					echo implode(' '. $separator . '', Utils::get_breadcrumbs( $args ) );
				?>
			</<?php echo esc_attr( $settings['html_tag'] ) ?>>

		   <?php
	   }
	}

	protected function content_template() {}
}
