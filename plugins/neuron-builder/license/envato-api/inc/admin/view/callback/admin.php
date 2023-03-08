<?php
/**
 * Admin UI
 *
 * @package Envato_Market
 * @since 1.0.0
 */

?>
<div class="neuron-admin">
	<?php do_action( 'neuron/admin/license/header' ) ?>
	<div class="neuron-admin__content">
		<div class="neuron-admin__card neuron-admin__card--license">
			<form method="POST" action="<?php echo esc_url( ENVATO_MARKET_NETWORK_ACTIVATED ? network_admin_url( 'edit.php?action=envato_market_network_settings' ) : admin_url( 'options.php' ) ); ?>">
				<?php Envato_Market_Admin::render_settings_panel_partial(); ?>
			</form>
		</div>
	</div>
	<?php do_action( 'neuron/admin/license/footer' ) ?>
</div>