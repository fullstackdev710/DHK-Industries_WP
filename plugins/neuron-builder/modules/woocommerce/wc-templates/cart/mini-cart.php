<?php
/**
 * Mini Cart
 * 
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'neuron_render_mini_cart_item' ) ) {
	function neuron_render_mini_cart_item( $cart_item_key, $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$is_product_visible = ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) );

		if ( ! $is_product_visible ) {
			return;
		}

		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
		$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
		?>
		<div class="m-neuron-menu-cart__product woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

			<div class="m-neuron-menu-cart__product-image product-thumbnail">
				<?php
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

				if ( ! $product_permalink ) :
					echo wp_kses_post( $thumbnail );
				else :
					printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
				endif;
				?>
			</div>

			<div class="m-neuron-menu-cart__meta">
				<div class="m-neuron-menu-cart__product-name product-name" data-title="<?php esc_attr_e( 'Product', 'neuron-builder' ); ?>">
					<?php
					if ( ! $product_permalink ) :
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
					else :
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
					endif;

					do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

					echo wc_get_formatted_cart_item_data( $cart_item ); 
					?>
				</div>

				<div class="m-neuron-menu-cart__product-price product-price" data-title="<?php esc_attr_e( 'Price', 'neuron-builder' ); ?>">
					<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
				</div>

			</div>

			<div class="m-neuron-menu-cart__product-remove product-remove">
				<?php
				echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
					'<a class="remove remove_from_cart_button" href="%s" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
					esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
					__( 'Remove this item', 'neuron-builder' ),
					esc_attr( $product_id ),
					esc_attr( $cart_item_key ),
					esc_attr( $_product->get_sku() )
				), $cart_item_key );
				?>
			</div>
		</div>
		<?php
	}
}

$cart_items = WC()->cart->get_cart();

if ( ! WC()->cart->is_empty() ) {
	?>
		<div class="m-neuron-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">
			<?php
			do_action( 'woocommerce_before_mini_cart_contents' );

			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				neuron_render_mini_cart_item( $cart_item_key, $cart_item );
			}

			do_action( 'woocommerce_mini_cart_contents' );
			?>
		</div>

		<div class="m-neuron-menu-cart__bottom">
			<div class="m-neuron-menu-cart__subtotal">
				<strong><?php echo __( 'Subtotal', 'neuron-builder' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?>
			</div>
			<div class="m-neuron-menu-cart__footer-buttons">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button a-neuron-button a-neuron-button--view-cart h-neuron-size-md">
					<?php echo __( 'View cart', 'neuron-builder' ); ?>
				</a>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button a-neuron-button a-neuron-button--checkout h-neuron-size-md">
					<?php echo __( 'Checkout', 'neuron-builder' ); ?>
				</a>
			</div>
		</div>
	<?php

} else {
	?>
		<div class="m-neuron-menu-cart__empty-message"><?php esc_attr_e( 'No products in the cart.', 'neuron-builder' ); ?></div>
	<?php
}
