<?php 

/* Removes Gutenberg default styles on front-end */
add_action('wp_print_styles', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});

/* give empty (spacing) paragraphs a tag to allow better frontend styling */
add_filter('the_content', function ($text) {
    $text = str_replace('<p>&nbsp;', '<p class="space">&nbsp;', $text);
    return $text;
});