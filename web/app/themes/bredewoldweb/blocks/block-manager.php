<?php 

/* Register ACF blocks from the blocks folder */
add_action('init', function(){
  
  /* Loop through folders in blocks folder */
  foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
    
    /* Make sure the register block json exists */
    if(!file_exists($dir . '/block.json')){ continue; }
    
    /* Register ACF Gutenberg block */
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

    /* Make admin blocks (blocks with the category 'admin') unavailable to non-admins. */
    if(file_exists($dir . '/block.json')){
      $block = json_decode(file_get_contents($dir . '/block.json'));
      if($block->category == 'admin' && !current_user_can('administrator')){ continue; }
    }

    $allowed_blocks[] = 'acf/' . basename($dir);
  }

  return $allowed_blocks;

}, 10, 2);


/* Create custom block categories */
add_action('block_categories_all', function ($categories) {
  return array_merge(
     $categories,
     array(
        array(
           'slug'  => 'regular',
           'title' => 'Algemeen'
        ),
        array(
          'slug'  => 'admin',
          'title' => 'Admin only'
        )
     )
  );
}, 10, 2);
