<?php
/**
 * Admin Header
 * 
 * @since 1.0.0
 */

use Neuron\License\Admin as License_API;

$menu = [
    'getting-started' => [
        'title' => __( 'Getting Started', 'neuron-builder' ),
        'url' => admin_url( 'admin.php' ) . '?page=neuron'
    ],
    'settings' => [
        'title' => __( 'Settings', 'neuron-builder' ),
        'url' => admin_url( 'admin.php' ) . '?page=settings'
    ],
    'demo-importer' => [
        'title' => __( 'Demo Importer', 'neuron-builder' ),
        'url' => admin_url( 'admin.php' ) . '?page=demo-importer'
    ],
    'system-info' => [
       'title' => __( 'System Info', 'neuron-builder' ),
       'url' => admin_url( 'admin.php' ) . '?page=system-info'
    ],
    'get-help' => [
        'title' => __( 'Get Help', 'neuron-builder' ),
        'url' => admin_url( 'admin.php' ) . '?page=get-help'
    ],
    'license' => [
        'title' => __( 'License', 'neuron-builder' ),
        'url' => admin_url( 'admin.php' ) . '?page=license'
    ], 
];

$subscribe_url = 'https://neuronthemes.com/blog#subscribe';

if ( ! class_exists( 'ElementorPro\Plugin' )  ) {
    $menu['custom-fonts'] = [
        'title' => __( 'Custom Fonts', 'neuron-builder' ),
        'url' => admin_url( 'edit.php' ) . '?post_type=neuron_font'
    ];
}

$menu = apply_filters( 'neuron/admin/header/menu', $menu );
?>
<header class="neuron-admin__header">
    <div class="neuron-admin__logo">
        <a href="<?php echo esc_url( $menu['getting-started']['url'] ) ?>">
            <img src="<?php echo NEURON_BUILDER_URL . 'admin/assets/logo.svg' ?>" alt="<?php echo esc_attr( 'Neuron Logo' ) ?>">
        </a>
    </div>
    <div class="neuron-admin__nav">
        <div class="neuron-admin__menu">
            <ul>
                <li><a class="settings" href="<?php echo esc_url( $menu[$active]['url'] ) ?>"><?php echo esc_attr( $menu[$active]['title'] ) ?></a>
                    <ul class="sub-menu">
                        <?php 
                        foreach ( $menu as $key => $value ) : 
                            if ( $key == $active ) {
                                continue;
                            }
                        ?>
                            <li><a href="<?php echo esc_url( $value['url'] ) ?>"><?php echo esc_attr( $value['title'] ) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
        
        <?php if ( ! empty( $categories ) && $categories != false ) :  ?>
            <div class="neuron-admin__menu--center">
                <nav class="neuron-admin__demo-importer--nav">
                    <select name="demo-importer-filters">
                        <option value="#all"><?php echo esc_attr__( 'Show All', 'neuron-builder' ) ?></option>
                        <?php foreach ( $categories as $key => $name ) : ?>
                            <option value="#<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </nav>
                <div class="neuron-admin__demo-importer--search">
                    <input type="search" name="ocdi-gl-search" value="" placeholder="<?php esc_html_e( 'Search...', 'neuron-builder' ); ?>">
                </div>
            </div>
        <?php endif; ?>

        <div class="neuron-admin__menu--right">
            <?php if ( License_API::is_license_activated() ) : ?>
                <a class="button" target="_BLANK" href="<?php echo esc_url( $subscribe_url ) ?>"><?php echo esc_attr__( 'Subscribe', 'neuron-builder' ) ?></a>
            <?php else : ?>
                <a class="button" href="<?php echo esc_url( $menu['license']['url'] ) ?>"><?php echo esc_attr__( 'Activate Neuron Builder', 'neuron-builder' ) ?></a>
            <?php endif; ?>

            <a class="<?php echo $active == 'settings' ? 'settings-cog active' : 'settings-cog'; ?>" href="<?php echo esc_url( $menu['settings']['url'] ) ?>"><img src="<?php echo esc_url( NEURON_BUILDER_URL . 'admin/assets/cog.svg' ) ?>" alt="<?php echo esc_attr__( 'Settings', 'neuron-builder' ) ?>"></a>
        </div>
    </div>
</header>