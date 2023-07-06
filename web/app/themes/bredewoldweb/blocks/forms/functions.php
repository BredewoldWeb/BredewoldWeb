<?php

function render_forms( $block, $content = '', $is_preview = false, $post_id = 0 ) {
     
    $fields = get_fields();
    
    /* Only render this block if a form has been selected */
    if( empty($fields['form']) ){ return; }

    include 'view.php';

}


/* Add a custom class to the submit button */
add_filter('gform_submit_button', function($button, $form){
    return str_replace('gform_button', 'gform_button cta-1', $button);
}, 10, 2);