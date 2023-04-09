<?php
add_theme_support('post-thumbnails');
add_theme_support('title-tag');


/* Add CSS and JS files to the theme */
add_action('wp_enqueue_scripts', function(){
   
   /* Enqueue styles */
   wp_enqueue_style('theme-css', get_template_directory_uri() . '/build/theme.css', '', filemtime(get_template_directory() . '/build/theme.css') );
   
   /* Enqueue scripts */
   wp_enqueue_script('theme-js', get_template_directory_uri() . '/build/theme.js', array('jquery'), filemtime(get_template_directory() . '/build/theme.js'));

});  

/* Make sure that the backend is styled the same as the front-end */
add_action('admin_enqueue_scripts', function(){
   wp_enqueue_style('admin-theme-css', get_template_directory_uri() . '/build/theme.css', '', filemtime(get_template_directory() . '/build/theme.css'));
});

/* Miscellaneous function and additions to enhance (backend) UX */
require_once 'inc/misc.php';

/* Include all the blocks */
require_once 'blocks/block-manager.php';

/* Compiles all scss and js */
require_once 'compiler.php';