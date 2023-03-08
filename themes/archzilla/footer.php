<?php
/**
 * The template for displaying the footer.
 * 
 * @since 1.0.0
 */
?>
<?php 
if ( ! function_exists( 'neuron_theme_do_location' ) || ! neuron_theme_do_location( 'footer' ) ) {
	get_template_part( 'template-parts/footer' );
}

wp_footer();
?>
</body>
</html>