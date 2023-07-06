<?php 

/* Make sure there are results */
if($search_results){
    
    /* Loop through the search data */
    foreach($search_results['all_results'] as $post_type => $search_data){
        
        /* If there are results, show the title */ ?>
        <h3 class='result-title bold'> <?= $search_data['title']; ?></h3><?php

        /* Loop through the found results */
        foreach($search_data['results'] as $result){
            
            /* Show the result */ ?>
            <a class='single-result' href='<?= $result['permalink']; ?>'><?= $result['title']; ?></a><?php

        }
    }

}

?>

