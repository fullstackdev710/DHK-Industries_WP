		
<?php
/**
 * Header Template Part
 * 
 * @since 1.0.0
 */

// Before Header Print
do_action( 'n-theme/header/print/before' );
?>
<header class="n-site-header">
    <?php do_action( 'n-theme/open/container', 'n-container--wide' ) ?>
    
    <div class="n-site-header__inner">

       
        <?php get_template_part('template-parts/logo') ?>
       
       
        <?php
        // Main Menu
        $args = array(
            'theme_location' => 'primary',
            'container' => '',
            'menu_class' => 'n-site-navigation__list'
        );

        if ( has_nav_menu( 'primary' ) ) {
        ?>
            <div class="n-site-header__menu">
                <nav class="n-site-navigation"><?php wp_nav_menu( $args ); ?></nav>
                <nav class="n-site-navigation--mobile">
                    <?php archzilla_hamburger( 'n-site-navigation__hamburger' ); ?>
                    <div class="n-site-navigation--mobile__wrapper">
                        <?php get_template_part('template-parts/logo'); ?>
                        <?php wp_nav_menu( $args ); ?>
                        <div class="n-site-navigation--mobile__close-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.05426 0L10 8.94573L18.9457 0L20 1.05426L11.0543 10L20 18.9457L18.9457 20L10 11.0543L1.05426 20L0 18.9457L8.94573 10L0 1.05426L1.05426 0Z" fill="black"/>
                            </svg>
                        </div>
                    </div>
                </nav>
            </div>
        <?php } ?>


        <?php get_template_part('template-parts/menu-cart') ?>


    </div>

    <?php do_action( 'n-theme/close/container' ) ?>
</header>
<?php 
// After Header Print
do_action( 'n-theme/header/print/after' );