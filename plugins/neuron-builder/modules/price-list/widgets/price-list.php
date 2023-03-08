<?php
/**
 * Price List
 * 
 * Add pricing list as
 * rows for different
 * pricing of your products.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\PriceList\Widgets;

use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Base\Base_Widget;

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

class Price_List extends Base_Widget {

	public function get_name() {
		return 'neuron-price-list';
	}

	public function get_title() {
		return __('Price List', 'neuron-builder');
	}

	public function get_icon() {
		return 'eicon-price-list neuron-badge';
	}

	public function get_keywords() {
		return [ 'price', 'list', 'ecommerce', 'pricing', 'product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'price_list',
			[
				'label' => __('List', 'neuron-builder'),
			]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'price',
            [
                'label' => __('Price', 'neuron-builder'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true
				]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'neuron-builder'),
                'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'dynamic' => [
					'active' => true
				]
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'neuron-builder'),
                'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'dynamic' => [
					'active' => true
				]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'neuron-builder'),
                'type' => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'neuron-builder'),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true
				]
            ]
        );


        $this->add_control(
            'price_lists',
            [
                'label' => __('List Items', 'neuron-builder'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
				'default' => [
					[
                        'price' => '$20',
                        'title' => __('First item on the list', 'neuron-builder'),
						'description' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'link' => ['url' => '#']
					],
					[
                        'price' => '$9',
                        'title' => __('Second item on the list', 'neuron-builder'),
						'description' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'link' => ['url' => '#']
					],
					[
                        'price' => '$22',
                        'title' => __('Third item on the list', 'neuron-builder'),
						'description' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'neuron-builder'),
						'link' => ['url' => '#']
					],
                ],
                'title_field' => '{{{ title }}}'
            ]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'list_style_section',
            [
                'label' => __('List Box', 'neuron-builder'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'title_heading',
			[
				'label' => __('Title', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
			]
        );
        
        $this->add_control(
			'heading_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__header' => 'color: {{VALUE}};',
				],
			]
        );
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __('Typography', 'neuron-builder'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-list__header'
			]
		);
		
		$this->add_control(
			'price_heading',
			[
				'label' => __('Price', 'neuron-builder'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
        );
        
        $this->add_control(
			'price_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__price' => 'color: {{VALUE}};',
				],
			]
        );
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'label' => __('Typography', 'neuron-builder'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .m-neuron-price-list__price'
			]
        );
        
        $this->add_control(
			'description_heading',
			[
				'label' => __('Description', 'neuron-builder'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
        );

        $this->add_control(
			'description_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .m-neuron-price-list__description',
			]
		);

        $this->add_control(
			'separator_heading',
			[
				'label' => __('Separator', 'neuron-builder'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
        );

        $this->add_control(
			'separator_style',
			[
				'label' => __('Style', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __('Solid', 'neuron-builder'),
					'dotted' => __('Dotted', 'neuron-builder'),
					'dashed' => __('Dashed', 'neuron-builder'),
					'double' => __('Double', 'neuron-builder'),
					'none' => __('None', 'neuron-builder')
				],
				'default' => 'dotted',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__separator' => 'border-bottom-style: {{VALUE}}',
				],
			]
        );
        
        $this->add_control(
			'separator_weight',
			[
				'label' => __('Weight', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 10,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
                ],
                'default' => [
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__separator' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
        );
        
        $this->add_control(
			'separator_color',
			[
				'label' => __('Color', 'neuron-builder'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__separator' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'separator_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'separator_spacing',
			[
				'label' => __('Spacing', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__separator' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __('Image', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'thumbnail',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __('Border Radius', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => __('Spacing', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__image' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .m-neuron-price-list__image + .m-neuron-price-list__text' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_style_section',
			[
				'label' => __('Item', 'neuron-builder'),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __('Rows Gap', 'neuron-builder'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label' => __('Vertical Align', 'neuron-builder'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __('Top', 'neuron-builder'),
					'bottom' => __('Bottom', 'neuron-builder'),
					'center' => __('Center', 'neuron-builder'),
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list__item' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'default' => 'top',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-price-list > li',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'neuron-builder'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-price-list > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function render_image($item, $instance) {
		$image_id = $item['image']['id'];
		$image_size = $instance['image_size_size'];
		if ($image_size === 'custom') {
			$image_src = Group_Control_Image_Size::get_attachment_image_src($image_id, 'image_size', $instance);
		} else {
			$image_src = wp_get_attachment_image_src($image_id, $image_size);
			$image_src = $image_src[0];
		}

		return sprintf('<img src="%s" alt="%s" />', $image_src, $item['title']);
	}

	private function render_item_header($item) {
		$url = $item['link']['url'];

		$item_id = $item['_id'];

		if ($url) {
			$unique_link_id = 'item-link-' . $item_id;

			$this->add_render_attribute( $unique_link_id, [
				'href' => $url,
				'class' => 'm-neuron-price-list__item',
			] );

			if ( $item['link']['is_external'] ) {
				$this->add_render_attribute($unique_link_id, 'target', '_blank');
			}

			return '<li><a ' . $this->get_render_attribute_string( $unique_link_id ) . '>';
		} else {
			return '<li class="m-neuron-price-list__item">';
		}
	}

	private function render_item_footer( $item ) {
		if ($item['link']['url']) {
			return '</a></li>';
		} else {
			return '</li>';
		}
    }
    
	protected function render() {
		$settings = $this->get_settings_for_display();
	?>
        <ul class="m-neuron-price-list">
            <?php foreach ($settings['price_lists'] as $item) : ?>
                <?php echo $this->render_item_header( $item ); ?>
					<?php if ( ! empty( $item['image']['url'] ) ) : ?>
						<div class="m-neuron-price-list__image">
							<?php echo $this->render_image( $item, $settings ); ?>
						</div>
					<?php endif; ?>
					<div class="m-neuron-price-list__text">
						<div class="m-neuron-price-list__header">
							<?php if ( ! empty ( $item['title'] ) ) : ?>
								<span class="m-neuron-price-list__title"><?php echo $item['title'] ?></span>
							<?php endif; ?>
							<?php if ( $settings['separator_style'] != 'none' ) : ?>
								<span class="m-neuron-price-list__separator"></span>
							<?php endif; ?>
							<?php if ( ! empty ( $item['price'] ) ) : ?>
								<span class="m-neuron-price-list__price"><?php echo $item['price'] ?></span>
							<?php endif; ?>
						</div>
						<?php if ( ! empty ( $item['description'] ) ) : ?>
							<p class="m-neuron-price-list__description"><?php echo $item['description'] ?></p>
						<?php endif; ?>
					</div>
				<?php echo $this->render_item_footer( $item ); ?>
            <?php endforeach; ?>
        </ul>
	<?php
	}

		protected function content_template() {
		?>
		<ul class="m-neuron-price-list">
			<#
				for ( var i in settings.price_lists ) {
					var item = settings.price_lists[i],
						item_open_wrap = '<li class="m-neuron-price-list__item">',
						item_close_wrap = '</li>';
					if ( item.link.url ) {
						item_open_wrap = '<li><a href="' + item.link.url + '" class="m-neuron-price-list__item">';
						item_close_wrap = '</a></li>';
					}

					if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) || ! _.isEmpty( item.description ) || ! _.isEmpty( item.image ) ) { #>

					{{{ item_open_wrap }}}
					<# if ( item.image && item.image.id ) {

						var image = {
							id: item.image.id,
							url: item.image.url,
							size: settings.image_size_size,
							dimension: settings.image_size_custom_dimension,
							model: view.getEditModel()
						};

						var image_url = elementor.imagesManager.getImageUrl( image );

						if ( image_url ) { #>
							<div class="m-neuron-price-list__image"><img src="{{ image_url }}" alt="{{ item.title }}"></div>
						<# } #>

					<# } #>


					<# if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) || ! _.isEmpty( item.description ) ) { #>
						<div class="m-neuron-price-list__text">

							<# if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) ) { #>
								<div class="m-neuron-price-list__header">

								<# if ( ! _.isEmpty( item.title ) ) { #>
									<span class="m-neuron-price-list__title">{{{ item.title }}}</span>
								<# } #>

								<# if ( 'none' != settings.separator_style ) { #>
									<span class="m-neuron-price-list__separator"></span>
								<# } #>

								<# if ( ! _.isEmpty( item.price ) ) { #>
									<span class="m-neuron-price-list__price">{{{ item.price }}}</span>
								<# } #>

								</div>
							<# } #>

							<# if ( ! _.isEmpty( item.description ) ) { #>
								<p class="m-neuron-price-list__description">{{{ item.description }}}</p>
							<# } #>

						</div>
					<# } #>

					{{{ item_close_wrap }}}

					<# } #>
			 <# } #>
		</ul>
		<?php
	}
}
