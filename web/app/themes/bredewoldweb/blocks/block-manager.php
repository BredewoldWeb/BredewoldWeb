<?php 

/* Register ACF blocks from the blocks folder */
add_action('init', function(){
  
  foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
    register_block_type( $dir . '/block.json' );
  }

});

/* Loop through folders in blocks folder and include files */
use StoutLogic\AcfBuilder\FieldsBuilder;
foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
  
  /* Get folder name */
  $folder = basename($dir);
  require_once $dir . '/functions.php';
  
  /* Make sure ACF builder file exists */
  if(!file_exists($dir . '/acf.php')){ continue; }
  
  /* Initialize new ACF fields builder if the file exists */
  $block = new FieldsBuilder($folder);
  $block->setLocation('block', '==', 'acf/' . $folder);
  require_once $dir . '/acf.php';
  
  /* Load fields into block */
  add_action('acf/init', function () use ($block) {
    acf_add_local_field_group($block->build());
  });

}

/* Set allowed block types per post type*/
add_filter('allowed_block_types_all', function($allowed_blocks_types, $block_editor_context){

  $allowed_blocks[] = 'core/block';
  foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
     $allowed_blocks[] = 'acf/' . basename($dir);
  }

  return $allowed_blocks;

}, 10, 2);



