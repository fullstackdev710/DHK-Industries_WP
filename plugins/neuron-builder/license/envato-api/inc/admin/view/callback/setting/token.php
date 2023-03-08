<?php
/**
 * Token setting
 *
 * @package Envato_Market
 * @since 1.0.0
 */

?>
<input placeholder="<?php echo esc_attr__( 'Enter your Envato API Personal Token.', 'neuron-builder' ) ?>" type="text" name="<?php echo esc_attr( envato_market()->get_option_name() ); ?>[token]" class="widefat" value="<?php echo esc_html( envato_market()->get_option( 'token' ) ); ?>" autocomplete="off">

<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'neuron-builder' ); ?>" />
