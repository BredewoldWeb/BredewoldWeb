<?php 
/* Register all post types here */

/* Register project post type */

function myplugin_register_template() {
    $post_type_object = get_post_type_object( 'post' );
    $post_type_object->template = array(
        array('acf/text'),
        array('acf/text-image'),
    );
    $post_type_object->template_lock = 'all';
}
add_action( 'init', 'myplugin_register_template' );