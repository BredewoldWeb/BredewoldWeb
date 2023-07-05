<?php 

add_action('wp_ajax_ajax_search', 'ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');

function ajax_search() {

    /* Get search input */
    $search = $_POST['search'];
    
    /* Get search results (functions.php) */
    $search_results = get_search_results($search);
    $json['notice'] = $search_results['total_posts_found'] . ' resultaten gevonden voor: ' . $search;
       
    /* If no results have been found, inform the user */
    if($search_results['total_posts_found'] < 1){
        $json['notice'] = 'Geen resultaten gevonden voor: ' . $search;
        unset($search_results);
    }
    
    /* Render the search results html */
    ob_start();
    include 'view-results.php';
    $json['html'] = ob_get_clean();
    
    /* Pass the results back to the javascript */
    return wp_send_json($json);

}