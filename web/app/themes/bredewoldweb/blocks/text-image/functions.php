<?php

function render_text_image( $block, $content = '', $is_preview = false, $post_id = 0 ) {
     
    $fields = get_fields();

    /* Crop images to the right size */
    $cropper = new Cropper($post_id);
    $fields['cropped'] = array(
        'desktop' => $cropper->resize($fields['image']['url'], 500),
        'desktop_webp' => $cropper->resize($fields['image']['url'], 500, '', 'webp')	
    );

    include 'view.php';

}