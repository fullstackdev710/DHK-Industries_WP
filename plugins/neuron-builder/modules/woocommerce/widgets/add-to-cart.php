<?php
namespace Neuron\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use Elementor\Icons_Manager;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Add_To_Cart extends Widget_Button {

	public function get_name() {
		return 'neuron-woo-add-to-cart';
	}

	public function get_title() {
		return __( 'Custom Add To Cart', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-woocommerce neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-woo-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
			];
		}
		return [];
	}

	public function get_custom_help_url() {
		return Base_Widget::$docs_url . '/widgets-' . $this->get_name();
	}

	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	public function unescape_html( $safe_text, $text ) {
		return $text;
    }
    
    public function get_products() {
        $output = [];

        $args = [ 'post_type' => 'product' ];
        $products = get_posts( $args );

        if ( ! empty( $products ) ) {
            foreach ( $products as $product ) {
                $output[$product->ID] = $product->post_title;
            }
        }

        return $output;
    }

	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Product', 'neuron-builder' ),
			]
        );

		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_products(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'show_quantity',
			[
				'label' => __( 'Show Quantity', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'label_on' => __( 'Show', 'neuron-builder' ),
				'description' => __( 'Please note that switching on this option will disable some of the design controls.', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'quantity',
			[
				'label' => __( 'Quantity', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'show_quantity' => '',
				],
			]
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->update_control(
			'link',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => [
					'url' => '',
				],
			]
		);

		$this->update_control(
			'text',
			[
				'default' => __( 'Add to Cart', 'neuron-builder' ),
				'placeholder' => __( 'Add to Cart', 'neuron-builder' ),
			]
		);

		$this->update_control(
			'selected_icon',
			[
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->update_control(
			'size',
			[
				'condition' => [
					'show_quantity' => '',
				],
			]
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		if ( ! empty( $settings['product_id'] ) ) {
			$product_id = $settings['product_id'];
		} elseif ( wp_doing_ajax() ) {
			$product_id = $_POST['post_id'];
		} else {
			$product_id = get_queried_object_id();
		}

		global $product;
        $product = wc_get_product( $product_id );
        
		if ( 'yes' === $settings['show_quantity'] ) {
			$this->render_form_button( $product );
		} else {
			$this->render_ajax_button( $product );
		}
	}

	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}

			$class = implode( ' ', array_filter( [
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			] ) );

			$this->add_render_attribute( 'button',
				[
					'rel' => 'nofollow',
					'href' => $product->add_to_cart_url(),
					'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class' => $class,
				]
			);

		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = __( 'Please set a valid product', 'neuron-builder' );
			$this->set_settings( $settings );
		}

		parent::render();
	}

	private function render_form_button( $product ) {
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo __( 'Please set a valid product', 'neuron-builder' );

			return;
		}

		$text_callback = function() {
			ob_start();
			$this->render_text();

			return ob_get_clean();
		};

		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', [ $this, 'unescape_html' ], 10, 2 );

        ob_start();
        
        woocommerce_template_single_add_to_cart();
        
        $form = ob_get_clean();
        
        $form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );
        
		echo $form;

		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', [ $this, 'unescape_html' ] );
	}

	// Force remote render
	protected function content_template() {}
}
