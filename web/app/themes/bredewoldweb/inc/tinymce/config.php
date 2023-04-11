<?php

/* Add custom colors to tinyMCE editor */
add_filter('tiny_mce_before_init', function ($tiny_options) {

    $tiny_options['textcolor_rows'] = 1;
    $tiny_options['textcolor_map'] = '["000000", "Zwart"]';

    return $tiny_options;

});

/* Set tinyMCE editor buttons programmatically */
/* default $buttons: ["formatselect","bold","italic","bullist","numlist","blockquote","alignleft","aligncenter","alignright","link","wp_more","spellchecker","fullscreen","wp_adv"] */
add_filter('mce_buttons', function ($buttons) {

    return ["formatselect", "forecolor", "bold", "italic", "bullist", "numlist", "link", "spellchecker"];

});


/* unset second bar for tinymce */
/* default $buttons: ["strikethrough","hr","forecolor","pastetext","removeformat","charmap","outdent","indent","undo","redo","wp_help"] */
add_filter('mce_buttons_2', function ($buttons) {
    return [];
});


/* Disable tinyMCE from messing with the content */
add_filter('tiny_mce_before_init', function ($tiny_options) {

    $tiny_options['valid_elements'] = '*[*]';
    $tiny_options['extended_valid_elements'] = '*[*]';
    $tiny_options['wpautop'] = false;
    $tiny_options['indent'] = true;
    $tiny_options['tadv_noautop'] = true;
    $tiny_options['paste_as_text'] = true;
    return $tiny_options;

});


/* Add custom tinyMCE editor styles */
add_filter('mce_css', function ($url) {
    
    /* Make sure to append stylesheet */
    if (!empty($url)) { $url .= ','; }

    /* Set version caching based on last updated */
    $version = filemtime(get_template_directory() . '/build/theme.css');
    $url .=  get_template_directory_uri()  . '/build/theme.css?v=' . $version;

    return $url;

});