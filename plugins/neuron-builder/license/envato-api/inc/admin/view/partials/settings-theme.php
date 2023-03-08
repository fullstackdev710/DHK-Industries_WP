<?php
/**
 * Settings panel partial
 *
 * @package Envato_Market
 * @since 1.0.0
 */

$token = envato_market()->get_option( 'token' );
$items = envato_market()->get_option( 'items', array() );
?>
<div id="settings" class="panel">
	<div class="envato-market-blocks">
		<?php settings_fields( envato_market()->get_slug() ); ?>
		<?php Envato_Market_Admin::do_settings_sections( envato_market()->get_slug(), false, false, false, true ); ?>
	</div>
</div>