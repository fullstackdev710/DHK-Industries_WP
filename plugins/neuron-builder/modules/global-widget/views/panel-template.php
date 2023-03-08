<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<script type="text/template" id="tmpl-elementor-panel-global-widget">
	<div id="elementor-global-widget-locked-header" class="elementor-nerd-box elementor-panel-nerd-box">
		<img class="elementor-nerd-box-icon" src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" />
		<div class="elementor-nerd-box-title elementor-panel-nerd-box-title"><?php echo __( 'Your Widget is Now Locked', 'neuron-builder' ); ?></div>
		<div class="elementor-nerd-box-message elementor-panel-nerd-box-message"><?php _e( 'Edit this global widget to simultaneously update every place you used it, or unlink it so it gets back to being regular widget.', 'neuron-builder' ); ?></div>
	</div>
	<div id="elementor-global-widget-locked-tools">
		<div id="elementor-global-widget-locked-edit" class="elementor-global-widget-locked-tool">
			<div class="elementor-global-widget-locked-tool-description"><?php echo __( 'Edit global widget', 'neuron-builder' ); ?></div>
			<button class="elementor-button elementor-button-success"><?php _e( 'Edit', 'neuron-builder' ); ?></button>
		</div>
		<div id="elementor-global-widget-locked-unlink" class="elementor-global-widget-locked-tool">
			<div class="elementor-global-widget-locked-tool-description"><?php echo __( 'Unlink from global', 'neuron-builder' ); ?></div>
			<button class="elementor-button elementor-button-danger"><?php _e( 'Unlink', 'neuron-builder' ); ?></button>
		</div>
	</div>
	<div id="elementor-global-widget-loading" class="elementor-hidden">
		<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
		<span class="elementor-screen-only"><?php _e( 'Loading', 'neuron-builder' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-global-widget-no-templates">
	<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" alt="Elementor Information Nerd Icon" />
	<div class="elementor-nerd-box-title elementor-panel-nerd-box-title"><?php _e( 'Save Your First Global Widget', 'neuron-builder' ); ?></div>
	<div class="elementor-nerd-box-message elementor-panel-nerd-box-message"><?php _e( 'Save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'neuron-builder' ); ?></div>
</script>