<?php

function render_search( $block, $content = '', $is_preview = false, $post_id = 0 ) {
     
    $fields = get_fields();

    /* Get search input */
    $search = '';
    if(isset($_GET['search'])){
       $search = $_GET['search'];
       $search_results = get_search_results($search);
       $notice = $search_results['total_posts_found'] . ' resultaten gevonden voor: ' . $search;
       
       /* If no results have been found, inform the user */
       if($search_results['total_posts_found'] < 1){
          $notice = 'Geen resultaten gevonden voor: ' . $search;
          unset($search_results);
       }
    }

    include 'view.php';

}

function get_search_results($search){
    
    /* Define the post types to be searched */
    $post_types = array(
       'page' => 'Pagina\'s',
       'post' => 'Actueel',
    );

    $total_posts_found = 0;

    foreach ($post_types as $post_type => $post_title) {
        
        /* Search the post type, exclude posts with noindex(Yoast) */
        $query_args = array(
            'post_type' => $post_type,
            'posts_per_page' => 20,
            'post_status' => 'publish',
            's' => $search,
            'meta_query' => array(
                array(
                    'key' => '_yoast_wpseo_meta-robots-noindex',
                    'value' => 1,
                    'compare' => 'NOT EXISTS',
                ),
            )
        );

        $query = new WP_Query($query_args);
        if ($query->have_posts()) {
            $results[$post_type]['title'] = $post_types[$post_type];
            while ($query->have_posts()) {
                $query->the_post();

                $results[$post_type]['results'][] = array(
                    'id' => get_the_id(),
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                );
            }
        }

        $total_posts_found += $query->found_posts;
    }

    $final['total_posts_found'] = $total_posts_found;
    
    if($final['total_posts_found'] > 0){
       $final['all_results'] = $results;
    }

    return $final;

}

/* Add custom query_vars, so we can put data in the url */
add_filter( 'query_vars', function($vars){
    $vars[] = "search";
    return $vars;
});


require_once 'ajax.php';