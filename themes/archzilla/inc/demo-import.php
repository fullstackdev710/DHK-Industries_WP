<?php
/**
 * Demo Import
 * 
 * @since 1.0.0
 */

add_filter( 'neuron/admin/demo-importer/import_files', 'archzilla_ocdi_import_files' );

add_action( 'neuron/admin/demo-import/after_import', 'archzilla_ocdi_after_import_setup' );


if ( ! function_exists( 'archzilla_ocdi_import_files' ) ) {
    /**
     * Demo Importer
     * 
     * Import the content, widgets and
     * the customizer settings via the
     * plugin one click demo importer.
     */
    function archzilla_ocdi_import_files() {
        $revslider_external_path = 'https://neuronthemes.com/api/plugins/revslider.zip';

        $plugins = [
            'woocommerce' => [
                'slug' => 'woocommerce',
                'path' => 'woocommerce/woocommerce.php'
            ],
            'revslider' => [
                'slug' => 'revslider',
                'path' => 'revslider/revslider.php',
                'external_path' => $revslider_external_path,
            ],
        ];

        return array(
            array(
                'import_file_name'           => esc_html__('Main Demo', 'archzilla'),
                'categories'                 => [ 'Main Demo' ],
                'import_file_url'            => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/content.xml',
                'import_preview_image_url'   => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/screenshot.jpg',
                'import_customizer_file_url' => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/customizer.dat',
                'revslider_path'             => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/revslider-1.zip',
                'revslider_path'             => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/revslider-1.zip',
                'import_widget_file_url'     => 'https://neuron-s3-bucket.s3.eu-central-1.amazonaws.com/archzilla/widgets.json',
                'preview_url'                => 'https://neuronthemes.com/archzilla/landing',
            ),
        );
    }
}

if ( ! function_exists( 'archzilla_ocdi_after_import_setup' ) ) {
    /**
     * After Import Setup
     * 
     * Set the Classic Home Page as front
     * page and assign the menu to 
     * the main menu location.
     */
    function archzilla_ocdi_after_import_setup($selected_import) {
        $front_page_id = get_page_by_title( 'Home' );

        if ( $front_page_id ) {
            update_option( 'page_on_front', $front_page_id->ID );
            update_option( 'show_on_front', 'page' );
        }	

        $blog_page_id = get_page_by_title( 'Blog' );

        if ( $blog_page_id ) {
            update_option( 'page_for_posts', $blog_page_id->ID );
        }
    }
}