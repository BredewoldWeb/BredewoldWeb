<?php 

/* Removes Gutenberg default styles on front-end */
add_action('wp_print_styles', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});