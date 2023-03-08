<?php
/**
 * Author Box
 * 
 * Display author information
 * on your single pages or posts.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Author_Box extends Base_Widget {

	public function get_name() {
		return 'neuron-author-box';
	}

	public function get_title() {
		return __( 'Author Box', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-person neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-single', 'neuron-elements-archive' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'author_box_section',
			[
				'label' => __('Author Box', 'neuron-builder')
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __('Source', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current-author' => __('Current Author', 'neuron-builder'),
					'custom' => __('Custom', 'neuron-builder'),
				],
				'default' => 'current-author',
			]
		);

		$this->add_control(
			'avatar',
			[
				'label' => __('Avatar', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __('Off', 'neuron-builder'),
				'label_on' => __('On', 'neuron-builder'),
				'condition' => [
					'source' => 'current-author'
				],
				'separator' => 'before',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'custom_author_avatar',
			[
				'label' => __('Avatar', 'neuron-builder'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src()
				],
				'condition' => [
					'source' => 'custom'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'custom_author_name',
			[
				'label'   => __('Name', 'neuron-builder'),
                'type' => Controls_Manager::TEXT,
				'default' => __('John Doe', 'neuron-builder'),
				'condition' => [
					'source' => 'custom'
				]
			]
        );

		$this->add_control(
			'html_tag',
			[
				'label' => __('HTML Tag', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __('H1', 'neuron-builder'),
					'h2' => __('H2', 'neuron-builder'),
					'h3' => __('H3', 'neuron-builder'),
					'h4' => __('H4', 'neuron-builder'),
					'h5' => __('H5', 'neuron-builder'),
					'h6' => __('H6', 'neuron-builder'),
					'div' => __('div', 'neuron-builder'),
					'span' => __('span', 'neuron-builder')
				],
				'default' => 'h4'
			]
		);

		$this->add_control(
			'biography',
			[
				'label' => __('Biography', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __('Off', 'neuron-builder'),
				'label_on' => __('On', 'neuron-builder'),
				'default' => 'yes',
				'condition' => [
					'source' => 'current-author'
				]
			]
		);

		$this->add_control(
			'custom_author_biography',
			[
				'label' => __('Biography', 'neuron-builder'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('Click onto element to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor.', 'neuron-builder'),
				'condition' => [
					'source' => 'custom'
				]
			]
		);

		$this->add_control(
			'archive_button',
			[
				'label' => __('Archive Button', 'neuron-builder'),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __('Off', 'neuron-builder'),
				'label_on' => __('On', 'neuron-builder'),
				'default' => 'yes',
				'condition' => [
					'source' => 'current-author'
				]
			]
		);

		$this->add_control(
			'custom_archive_button',
			[
				'label' => __('Archive Button', 'neuron-builder'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://neuronthemes.com', 'neuron-builder'),
				'condition' => [
					'source' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'archive_text',
			[
				'label' => __('Archive Text', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'default' => __('All Posts', 'neuron-builder'),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'archive_button',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'source',
							'operator' => '==',
							'value' => 'custom'
						], 
					],
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __('Image', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'image_vertical_alignment',
			[
				'label' => __('Vertical Alignment', 'neuron-builder'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __('Top', 'neuron-builder'),
						'icon' => 'eicon-v-align-top'
					],
					'center' => [
						'title' => __('Middle', 'neuron-builder'),
						'icon' => 'eicon-v-align-middle'
					],
					'end' => [
						'title' => __('Bottom', 'neuron-builder'),
						'icon' => 'eicon-v-align-bottom'
					]
				],
				'label_block' => false
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => __('Size', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'range' => [
					'px' => [
						'max' => 300
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => __('Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'range' => [
					'px' => [
						'max' => 300
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__avatar' => 'margin-right: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __('Border', 'neuron-builder'),
				'selector' => '{{WRAPPER}} .m-author-box__avatar img',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'range' => [
					'px' => [
						'max' => 300
					]
				],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__avatar img' => 'border-radius: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => __('Box Shadow', 'neuron-builder'),
				'selector' => '{{WRAPPER}} .m-author-box__avatar img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_style_section',
			[
				'label' => __('Text', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'name_style_heading',
			[
				'label' => __('Name', 'neuron-builder'),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__content__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-author-box__content__title',
			]
		);

		$this->add_responsive_control(
			'name_gap',
			[
				'label' => __('Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__content__title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				]
			]
		);

		$this->add_control(
			'biography_style_heading',
			[
				'label' => __('Biography', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'biography_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__content__description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'biography_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-author-box__content__description',
			]
		);

		$this->add_responsive_control(
			'biography_gap',
			[
				'label' => __('Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-author-box__content__description' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style_section',
			[
				'label' => __('Button', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('button_style_tabs');

		$this->start_controls_tab(
			'button_style_normal_tab',
			[
				'label' => __('Normal', 'neuron-builder'),
			]
		);

		$this->add_control(
			'button_normal_text_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} a.a-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_normal_bg_color',
			[
				'label' => __('Background Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.a-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_menu_hover_tab',
			[
				'label' => __('Hover', 'neuron-builder'),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __('Text Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} a.a-button:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __('Background Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.a-button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} a.a-button'
				
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => __('Border', 'neuron-builder'),
				'selector' => '{{WRAPPER}} a.a-button',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} a.a-button' => 'border-radius: {{SIZE}}{{UNIT}}'
				]
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __('Padding', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'rem', 'em'],
				'selectors' => [
					'{{WRAPPER}} a.a-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		global $post;

		$author = [
			'avatar' => get_avatar(get_the_author_meta('ID')),
			'name' => get_the_author_meta('display_name', $post->post_author),
			'description' => get_the_author_meta('description', $post->post_author),
			'url' => get_author_posts_url($post->post_author)
		];

		if ($settings['source'] == 'custom') {
			$settings['avatar'] = !empty($settings['custom_author_avatar']['url']) ? 'yes' : 'no';
			$settings['biography'] = $settings['custom_author_biography'] ? 'yes' : 'no';
			$settings['archive_button'] = !empty($settings['custom_archive_button']['url']) ? 'yes' : 'no';

			$author = [
				'avatar' => !empty($settings['custom_author_avatar']['url']) ? '<img src="'. $settings['custom_author_avatar']['url'] .'" alt="'. $settings['custom_author_name'] .'">' : '',
				'name' => $settings['custom_author_name'],
				'description' => $settings['custom_author_biography'],
				'url' => !empty($settings['custom_archive_button']['url']) ? $settings['custom_archive_button']['url'] : ''
			];
		}
		?>
		<div class="m-author-box d-flex">
			<?php if ($settings['avatar'] == 'yes') : ?>
				<div class="m-author-box__avatar">
					<?php echo $author['avatar'] ?>
				</div>
			<?php endif; ?>
			<div class="m-author-box__content">
				<<?php echo $settings['html_tag'] ?> class="m-author-box__content__title"><a href="<?php echo esc_attr($author['url']) ?>"><?php echo esc_attr($author['name']) ?></a></<?php echo $settings['html_tag'] ?>>
				<?php if ($settings['biography'] == 'yes') : ?>
					<p class="m-author-box__content__description">
						<?php echo wp_kses_post($author['description']) ?>
					</p>
				<?php endif; ?>
				<?php if ($settings['archive_button'] == 'yes') : ?>
					<a class="a-button a-button--small a-button--dark-color d-inline-block" href="<?php echo esc_attr($author['url']) ?>"><?php echo esc_attr($settings['archive_text']) ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php
	}

	protected function content_template() {}
}
