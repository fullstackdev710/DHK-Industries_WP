<?php
/**
 * Post Content
 *
 * Prints the content.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;
use Neuron\Modules\ThemeBuilder\Module as ThemeBuilder;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Content extends Base_Widget {

	public function get_name() {
		return 'neuron-post-content';
	}

	public function get_title() {
		return __( 'Post Content', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-post-content neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single' ];
	}

	public function get_keywords() {
		return [ 'content', 'post' ];
	}

	public function show_in_panel() {
		return false;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
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
					'justify' => [
						'title' => __( 'Justified', 'neuron-builder' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render_post_content( $with_wrapper = false ) {
		static $did_posts = [];
		
		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}

		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;

		$editor = Plugin::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( Plugin::elementor()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::elementor()->preview->builder_wrapper( '' ); // XSS ok
		} else {
			$document = ThemeBuilder::instance()->get_document( $post->ID );

			if ( $document ) {
				$preview_type = $document->get_settings( 'preview_type' );
				$preview_id = $document->get_settings( 'preview_id' );

				if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
					$post = get_post( $preview_id );

					if ( ! $post ) {
						return;
					}
				}
			}

			$editor->set_edit_mode( false );

			$content = Plugin::elementor()->frontend->get_builder_content( $post->ID, true );

			if ( empty( $content ) ) {
				Plugin::elementor()->frontend->remove_content_filter();

				setup_postdata( $post );

				echo apply_filters( 'the_content', get_the_content() );

				wp_link_pages( [
					'before' => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . __( 'Pages:', 'neuron-builder' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'neuron-builder' ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				Plugin::elementor()->frontend->add_content_filter();

				return;
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		} // End if().

		// Restore edit mode state
		Plugin::elementor()->editor->set_edit_mode( $is_edit_mode );

		if ( $with_wrapper ) {
			echo '<div class="m-neuron-post__content">' . balanceTags( $content, true ) . '</div>';  // XSS ok.
		} else {
			echo $content; // XSS ok.
		}
	}

	protected function render() {
		$this->render_post_content();
	}

	public function render_plain_content() {}
}
