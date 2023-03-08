<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-neuron-publish">
	<# if ( screens.length > 1 ) { #>
	<div id="neuron-publish__tabs" class="neuron-templates-modal__sidebar">
		<# screens.forEach( function( screen ) { #>
		<div class="neuron-publish__tab" data-screen="{{ screen.name }}">
			<div class="neuron-publish__tab__image">
				<img src="{{ screen.image }}">
			</div>
			<div class="neuron-publish__tab__content">
				<div class="neuron-publish__tab__title">{{{ screen.title }}}</div>
				<div class="neuron-publish__tab__description">{{{ screen.description }}}</div>
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="neuron-publish__screen" class="neuron-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-neuron-component-publish">
	<# if ( Object.keys( tabs ).length > 1 ) { #>
	<div id="neuron-publish__tabs" class="neuron-templates-modal__sidebar">
		<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="elementor-component-tab neuron-publish__tab" data-tab="{{ tab }}">
			<div class="neuron-publish__tab__image">
				<img src="{{ args.image }}">
			</div>
			<div class="neuron-publish__tab__content">
				<div class="neuron-publish__tab__title">{{{ args.title }}}</div>
				<!-- <div class="neuron-publish__tab__description">{{{ args.description }}}</div> -->
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="neuron-publish__screen" class="neuron-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-elementor-theme-builder-conditions-view">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo NEURON_MODULES_URL; ?>theme-builder/assets/images/conditions-tab.svg">
	</div>
	<div class="elementor-template-library-blank-title">{{{ neuron.translate( 'conditions_title', [ elementor.config.document.post_type_title ] ) }}}</div>
	<div class="elementor-template-library-blank-message">{{{ neuron.translate( 'conditions_description', [ elementor.config.document.post_type_title ] ) }}}</div>
	<div id="elementor-theme-builder-conditions">
		<div id="elementor-theme-builder-conditions-controls"></div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-theme-builder-conditions-repeater-row">
	<div class="elementor-theme-builder-conditions-repeater-row-controls"></div>
	<div class="elementor-repeater-row-tool elementor-repeater-tool-remove">
		<i class="eicon-close" aria-hidden="true"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Remove this item', 'neuron-builder' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-theme-builder-button-preview">
	<i class="eicon-preview-medium tooltip-target" aria-hidden="true"  data-tooltip="<?php esc_attr_e( 'Preview Changes', 'neuron-builder' ); ?>"></i>
	<span class="elementor-screen-only">
		<?php esc_attr_e( 'Preview Changes', 'neuron-builder' ); ?>
	</span>
	<div class="elementor-panel-footer-sub-menu-wrapper">
		<div class="elementor-panel-footer-sub-menu">
			<div onclick="$e.run( 'theme-builder-publish/preview-settings' );" id="elementor-panel-footer-theme-builder-button-preview-settings" class="elementor-panel-footer-sub-menu-item">
				<i class="eicon-wrench" aria-hidden="true"></i>
				<span class="elementor-title"><?php esc_html_e( 'Settings', 'neuron-builder' ); ?></span>
			</div>
			<div onclick="$e.run( 'editor/documents/preview', { id: elementor.documents.getCurrent().id, force: true } );" id="elementor-panel-footer-theme-builder-button-open-preview" class="elementor-panel-footer-sub-menu-item">
				<i class="eicon-editor-external-link" aria-hidden="true"></i>
				<span class="elementor-title"><?php esc_html_e( 'Preview', 'neuron-builder' ); ?></span>
			</div>
		</div>
	</div>
</script>
