<?php
    /* Add Menu Walker: https://awhitepixel.com/blog/wordpress-menu-walkers-tutorial/ */
    /* wp_nav_menu opties: https://developer.wordpress.org/reference/functions/wp_nav_menu/ */

    $desktop_menu = array(
       'theme_location' => 'main-menu',
       'container' => 'nav',
       'container_class' => 'desktop-menu',
    ); 
?>

<header>
    <div class="inner-wide">
        

        <a href="<?= get_home_url(); ?>" class="logo">
            <img src="https://placehold.co/72x72" alt="Logo" />
        </a>

        <?php wp_nav_menu( $desktop_menu ); ?>


    </div>
</header>