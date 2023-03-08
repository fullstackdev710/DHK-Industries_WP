<?php
/**
 * Product Meta 
 * SKU, Categories, Tags
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Meta extends Base_Widget {

	public function get_name() {
		return 'neuron-woo-product-meta';
	}

	public function get_title() {
		return __( 'Product Meta', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-product-meta neuron-badge';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'meta', 'data', 'product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_meta_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'column' => __( 'Column', 'neuron-builder' ),
					'row' => __( 'Row', 'neuron-builder' ),
				],
				'default' => 'column',
				'prefix_class' => 'm-neuron-product-meta__style-',
				'selectors' => [
					'{{WRAPPER}} .product_meta' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.m-neuron-product-meta__style-column .product_meta > span:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.m-neuron-product-meta__style-row .product_meta > span:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// $this->add_control(
		// 	'divider',
		// 	[
		// 		'label' => __( 'Divider', 'neuron-builder' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'label_off' => __( 'Off', 'neuron-builder' ),
		// 		'label_on' => __( 'On', 'neuron-builder' ),
		// 		'selectors' => [
		// 			'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'content: ""',
		// 		],
		// 		'return_value' => 'yes',
		// 		'separator' => 'before',
		// 	]
		// );

		// $this->add_control(
		// 	'divider_style',
		// 	[
		// 		'label' => __( 'Style', 'neuron-builder' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => [
		// 			'solid' => __( 'Solid', 'neuron-builder' ),
		// 			'double' => __( 'Double', 'neuron-builder' ),
		// 			'dotted' => __( 'Dotted', 'neuron-builder' ),
		// 			'dashed' => __( 'Dashed', 'neuron-builder' ),
		// 		],
		// 		'default' => 'solid',
		// 		'condition' => [
		// 			'divider' => 'yes',
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'divider_weight',
		// 	[
		// 		'label' => __( 'Weight', 'neuron-builder' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'default' => [
		// 			'size' => 1,
		// 		],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 20,
		// 			],
		// 		],
		// 		'condition' => [
		// 			'divider' => 'yes',
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'divider_height',
		// 	[
		// 		'label' => __( 'Height', 'neuron-builder' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ '%', 'px' ],
		// 		'default' => [
		// 			'unit' => '%',
		// 		],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 			'%' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'condition' => [
		// 			'divider' => 'yes',
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'divider_color',
		// 	[
		// 		'label' => __( 'Color', 'neuron-builder' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '#ddd',
		// 		'global' => [
		// 			'default' => Global_Colors::COLOR_TEXT,
		// 		],
		// 		'condition' => [
		// 			'divider' => 'yes',
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_control(
			'heading_text_style',
			[
				'label' => __( 'Text', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .product_meta',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product_meta' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_link_style',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_meta_captions',
			[
				'label' => __( 'Captions', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_category_caption',
			[
				'label' => __( 'Category', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'category_caption_single',
			[
				'label' => __( 'Singular', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Category', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'category_caption_plural',
			[
				'label' => __( 'Plural', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Categories', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'heading_tag_caption',
			[
				'label' => __( 'Tag', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tag_caption_single',
			[
				'label' => __( 'Singular', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tag', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'tag_caption_plural',
			[
				'label' => __( 'Plural', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tags', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'heading_sku_caption',
			[
				'label' => __( 'SKU', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sku_caption',
			[
				'label' => __( 'SKU', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'SKU', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'sku_missing_caption',
			[
				'label' => __( 'Missing', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'N/A', 'neuron-builder' ),
			]
		);

		$this->end_controls_section();
	}

	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		$sku = $product->get_sku();

		$settings = $this->get_settings_for_display();
		$sku_caption = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : __( 'SKU', 'neuron-builder' );
		$sku_missing = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : __( 'N/A', 'neuron-builder' );
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : __( 'Category', 'neuron-builder' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : __( 'Categories', 'neuron-builder' );
		$tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : __( 'Tag', 'neuron-builder' );
		$tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : __( 'Tags', 'neuron-builder' );
		?>
		<div class="product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper detail-container"><span class="detail-label"><?php echo esc_html( $sku_caption ); ?></span> <span class="sku"><?php echo $sku ? $sku : esc_html( $sku_missing ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_category_ids() ) ) : ?>
				<span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_tag_ids() ) ) : ?>
				<span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>
		<?php
	}

	public function render_plain_content() {}
}
