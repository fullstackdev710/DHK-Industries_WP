<?php
/**
 * Header for archzilla
 *
 * @since 1.0.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head() ?>
</head>
<body <?php body_class() ?>>

<?php 
archzilla_wp_body_open();

if ( ! function_exists( 'neuron_theme_do_location' ) || ! neuron_theme_do_location( 'header' ) ) {
	get_template_part( 'template-parts/header' );
}

