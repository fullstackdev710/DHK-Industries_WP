<?php
/**
 * Column Linking
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ColumnLinking;

use Neuron\Base\Module_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'neuron-column-linking';
	}

	public function __construct() {
		parent::__construct();

			add_action( 'elementor/element/column/layout/before_section_end', array( $this, 'widget_extensions' ), 10, 2 );
			add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render_options' ), 10 );
		}
		
		/**
		 * After layout callback
		 *
		 * @param  object $element
		 * @param  array $args
		 * @return void
		 */
		public function widget_extensions( $element, $args ) {
			$element->add_control(
				'neuron_column_link',
				[
					'label'       => __( 'Column Link', 'neuron-builder' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => [
						'active' => false,
					],
					'placeholder' => __( 'https://your-link.com', 'neuron-builder' ),
					'selectors'   => [],
				]
			);
		}


		public function before_render_options( $element ) {
			$settings  = $element->get_settings_for_display();

			if ( isset( $settings['neuron_column_link'], $settings['neuron_column_link']['url'] ) && ! empty( $settings['neuron_column_link']['url'] ) ) {
				$element->add_render_attribute( '_wrapper', 'class', 'a-neuron-clickable-col' );
				$element->add_render_attribute( '_wrapper', 'style', 'cursor: pointer;' );
				$element->add_render_attribute( '_wrapper', 'data-column-clickable', $settings['neuron_column_link']['url'] );
				$element->add_render_attribute( '_wrapper', 'data-column-clickable-blank', $settings['neuron_column_link']['is_external'] ? '_blank' : '_self' );
			}
		}
}
