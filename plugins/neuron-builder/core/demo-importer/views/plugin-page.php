<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package ocdi
 */

namespace OCDI;

use Neuron\License\Admin;

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === $_GET['import-mode'] ) {
	$predefined_themes = array();
}

$categories = Helpers::get_all_demo_import_categories( $predefined_themes );
$active_demo = get_transient( 'neuron_importer_data' ) ? get_transient( 'neuron_importer_data' ) : false;

if ( $active_demo != false ) {
	$active_demo = $active_demo['selected_index'];
} else {
	$active_demo = -1;
}

?>
<div class="neuron-admin">
	<?php do_action( 'neuron/admin/demo-importer/header', $categories ) ?>

	<?php if ( Admin::is_license_activated() ) { ?>
	
	<div class="neuron-admin__demo-importer">

		<?php ob_start() ?>

		<div class="ocdi__gl  js-ocdi-gl">
			<div class="ocdi__gl-item-container <?php echo ! empty( $predefined_themes ) && count( $predefined_themes ) == 1 ? 'ocdi__gl-item-container--single' : '' ?>">
				<?php foreach ( $predefined_themes as $index => $import_file ) : ?>
					<?php
						// Prepare import item display data.
						$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
						// Default to the theme screenshot, if a custom preview image is not defined.
						if ( empty( $img_src ) ) {
							$theme = wp_get_theme();
							$img_src = $theme->get_screenshot();
						}

					?>
					<div class="ocdi__gl-item js-ocdi-gl-item <?php echo $active_demo == $index ? 'active' : '' ?>" data-categories="<?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ); ?>" data-name="<?php echo esc_attr( strtolower( $import_file['import_file_name'] ) ); ?>">
						<div class="ocdi__gl-item-image-container">
							<?php if ( ! empty( $img_src ) ) : ?>
								<img class="ocdi__gl-item-image" src="<?php echo esc_url( $img_src ) ?>">
							<?php endif; ?>
						</div>

						<div class="ocdi__gl-item-footer">
							<div class="ocdi__gl-item-footer--title">
								<h4 class="ocdi__gl-item-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
								<div class="ocdi__gl-item-footer--category"><?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file, true ) ); ?></div>
							</div>
							<div class="ocdi__gl-item-footer--buttons">
								<button class="button js-ocdi-gl-import-data" value="<?php echo esc_attr( $index ); ?>"><span class="ab-icon"></span><span><?php esc_html_e( 'Import', 'neuron-builder' ); ?></span></button>
								<?php if ( ! empty( $import_file['preview_url'] ) ) : ?>
									<a class="button invert" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Live Preview', 'neuron-builder' ); ?></a>
								<?php endif; ?>
							</div>
						</div>

						<div class="ocdi__gl-item-footer ocdi__gl-item-footer--active">
							<div class="ocdi__gl-item-footer--title">
								<span>Active:</span>
								<h4 class="ocdi__gl-item-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
								<div class="ocdi__gl-item-footer--category"><?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file, true ) ); ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="ocdi__response  js-ocdi-ajax-response"></div>
	</div>

	<?php } else {  
		echo '<div style="margin: 20px; font-size: 16px;">' . esc_html__( 'To unlock the demo importer, please activate your license by clicking at the top right button.', 'neuron-builder' ) . '</div>';
	} ?>

	<?php do_action( 'neuron/admin/demo-importer/footer' ); ?>
</div>

