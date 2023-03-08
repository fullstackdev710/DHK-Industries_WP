<?php
/**
 * OAuth section
 *
 * @package Envato_Market
 * @since 1.0.0
 */
use Neuron\License\Admin as License_API;
$license = '';

if ( License_API::is_license_activated() ) {
	$license = '<span style="font-weight: bold;">(Activated)<span>';
}

?>
<h2>License Verification <?php echo $license ?></h2>
<h4>
	<?php printf( esc_html__( 'Enable the Neuron Theme License Verfication through Envato, the verification will unlock various features of the theme.', 'neuron-builder' ) ); ?>
</h4>

<p class="h-bold">Ensure the following permissions are enabled</p>

<p>View and search Envato sites, Download your purchased items, List purchases you've made</p>

<?php 
	$error_details = get_site_transient( envato_market()->get_option_name() . '_error_information' );
	if ( $error_details && ! empty( $error_details['title'] ) ) {
		extract( $error_details );
		?>
			<p style="font-size: 18px; margin: 20px 0;"><?php printf( '<strong>Additional Error Details:</strong><br/>%s<br/> %s <br/>', esc_html( $title ), esc_html( $message ) ); ?></p>
		<?php
	}
?>
