<?php 

/* Removes Gutenberg default styles on front-end */
add_action('wp_print_styles', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});

/* Give empty (spacing) paragraphs a tag to allow better frontend styling */
add_filter('the_content', function ($text) {
    $text = str_replace('<p>&nbsp;', '<p class="space">&nbsp;', $text);
    return $text;
});

/* Increase standard cropping jpg quality for better end-results */
add_filter('jpeg_quality', function ($arg) {
    return 100;
});

/* Defer javascript for beter pagespeed scores / experience */
add_filter('script_loader_tag', function ($url) {

    /* Do not change admin JS/CSS */
    if (is_user_logged_in()) return $url;

    /* Some files should not be deferred, list them here.  */
    $do_not_defer = array('jquery.js', 'jquery.min.js', 'dist/i18n', 'dist/hooks');
    if (str_replace($do_not_defer, '', $url) != $url) return $url;

    /* Remove un-used JS */
    $do_not_load = array('jquery-migrate', 'wp-embed');
    if (str_replace($do_not_load, '', $url) != $url) return '';

    /* No objections found, defer JS */
    if (strpos($url, '.js')) return str_replace(' src', ' defer src', $url);

    /* Load CSS Async for better pagespeeds */
    if (strpos($url, '.css')) return str_replace(' href', ' rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"> href', $url);

}, 10);