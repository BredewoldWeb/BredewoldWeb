<?php

/* Make sure that the sitemanager role can't unlock blocks */
add_filter('block_editor_settings_all', function ($settings, $context) {

    $user = wp_get_current_user();
    $role = reset($user->roles);

    /* Disable block unlocking for sitemanagers */
    if ($role == 'sitemanager') {
        $settings['canLockBlocks'] = false;
    }

    return $settings;

}, 10, 2);


/* Enforce a specific type of block template for site managers */
add_action('admin_init', function () {

    $user = wp_get_current_user();
    $role = reset($user->roles);

    /* Disable block unlocking for sitemanagers */
    if ($role == 'sitemanager') {

        /* Check if this page has a post type */
        if (!isset($_GET['post'])) { return; }

        /* Check if the template is locked */
        $template_locked = get_post_meta($_GET['post'], 'template_locked', true);

        /* Template is not locked, abort */
        if (!$template_locked) { return; }

        /* Get the template */
        $template = get_post_meta($_GET['post'], 'template', true);

        /* Start the locking procedure */
        $block = get_post_type_object(get_post_type($_GET['post']));

        /* Set the template */
        $block->template = $template;

        /* Lock it down */
        $block->template_lock = 'all';
    }

});


/* Add meta box with the option to lock a template for users */
add_action('add_meta_boxes', function () {

    /* Do not make any changes if this user is a site manager */
    $user = wp_get_current_user();
    $role = reset($user->roles);
    if ($role == 'sitemanager') {
       return;
    }

    add_meta_box('template_lock', 'Aanpasbaar door gebruiker', 'template_lock_meta_box', 'page', 'side', 'high');
    add_meta_box('template_lock', 'Aanpasbaar door gebruiker', 'template_lock_meta_box', 'post', 'side', 'high');
    add_meta_box('template_lock', 'Aanpasbaar door gebruiker', 'template_lock_meta_box', 'bredewold_template', 'side', 'high');
});

function template_lock_meta_box(){

    global $post;
    $template_locked = get_post_meta($post->ID, 'template_locked', true);

    ?>
    <div>
       <input type="checkbox" name="lock_template" <?php if( $template_locked ) { ?>checked="checked"<?php } ?> /> Verhinder aanpassingen
    </div>

<?php
}

/* Save template lock information and the template itself */
add_action('save_post', function($post_id){

    /* Do not make any changes if this user is a site manager */
    $user = wp_get_current_user();
    $role = reset($user->roles);
    if ($role == 'sitemanager') {
       return $post_id;
    }

    /* Check if lock_template is set */
    if (isset($_POST['lock_template'])) {
        update_post_meta($post_id, 'template_locked', true);
    } else {
        update_post_meta($post_id, 'template_locked', false);
    }

    /* Save block template so we can use it later */
    global $post;
    if(!isset($post->post_content)){ return; }
	$blocks = parse_blocks( $post->post_content );
	foreach ( $blocks as $block ) {
        
        /* Make sure the block has a name */
        if(empty($block['blockName'])){ continue; }
        
        /* Add the block name to the template */
        $template[] = array($block['blockName']);

    }

    update_post_meta($post_id, 'template', $template);

    return $post_id;

});
