<?php 
 /* change the output of the all_fields merge tag from gravity forms */
 /* https://gravitywiz.com/gravity-forms-all-fields-template/comment-page-2/#customizing-the-template */

foreach( $items as $item ){ ?>

	<p><strong><?= $item['label']; ?><strong></p>

    <?php 
    /* check if string contains '<ul' */
    if (strpos($item['value'], '<ul') !== false) { 
        echo $item['value'];
    } else { 
        echo "<p>" . $item['value'] . "</p>";
    }
    ?>

    <p>&nbsp;</p>
    <?php 
} 