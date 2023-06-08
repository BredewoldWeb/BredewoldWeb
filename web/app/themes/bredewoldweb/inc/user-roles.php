<?php

/* Remove user roles from admin */
remove_role('subscriber');
remove_role('editor');
remove_role('contributor');
remove_role('author');


/* Add sitemanager role (for clients updating the website content) */
if (is_admin()) {
    add_role('sitemanager', 'Site Manager', get_role('administrator')->capabilities);
    $role = get_role('sitemanager');
    $role->add_cap('gform_full_access');
    $role->remove_cap('activate_plugins');
    update_option('sitemanager_added', 'true');
}


/* Customize admin menu */
add_action('admin_init', function () {

    $user = wp_get_current_user();
    $role = reset($user->roles);
    
    /* Hide 'Reacties' */
    remove_menu_page('edit-comments.php');

    if ($role == 'sitemanager') {
        /* Hide 'Updates' submenu' */
        remove_submenu_page('index.php', 'update-core.php');
        remove_menu_page('themes.php');
        remove_menu_page('plugins.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php?post_type=acf-field-group');
    }

}, 999);


/* Reorganize pages for better UX */
add_action('admin_menu', function () {

    /* Add re-usable blocks to the admin menu */
    add_menu_page('Herbruikbare blokken', 'Herbruikbare blokken', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22);
    
    /* Move menu to the main admin menu */
    remove_submenu_page('themes.php', 'nav-menus.php');
    add_menu_page('Menus', 'Menus', 'edit_posts', 'nav-menus.php', '', 'dashicons-menu', 29);

}, 999);


/* Remove ugly boxes from the dashboard */
add_action('wp_dashboard_setup', function () {

    $user = wp_get_current_user();
    $role = reset($user->roles);

    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');

    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');

});
