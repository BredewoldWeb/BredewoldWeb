<?php

/* Register main menu */
add_action('init', function(){

    register_nav_menus(array(
        'main-menu' => 'Hoofdmenu'
    ));

});