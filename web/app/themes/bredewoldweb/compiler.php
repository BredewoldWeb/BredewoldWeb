<?php 

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/* Check if dev-tools plugin is active */
if( is_plugin_active('dev-tools/dev-tools.php') && WP_ENV == 'development' ){
    
    $dir = get_template_directory();

    /* Compile all scss files */
    $rb_compiler = new SassCompiler($dir . '/build/', 'theme');
    $rb_compiler->add_import_paths(get_template_directory() . '/styles/theme.scss');
    foreach (glob(__DIR__ . '/blocks/*', GLOB_ONLYDIR) as $dir) {
        $rb_compiler->add_import_paths(array($dir));
    }
    $rb_compiler->compile();

    /* Compile all js files */
    $rb_compiler = new JsCompiler(get_template_directory() . '/build/', 'theme');
    $rb_compiler->add_import_paths(get_template_directory() . '/js/theme.js');
    $rb_compiler->add_import_paths(get_template_directory() . '/js/animations.js');
    
    /* Automatically detect js files in block folders */
    foreach (glob(__DIR__ . '/blocks/*', GLOB_ONLYDIR) as $dir) {

        /* Find js files in folder */
        $files = glob($dir . '/*.js');
        
        /* no JS files found, continue loop */
        if(empty($files)){ continue; }

        /* Add found js files to compiler */
        foreach($files as $file){
            $rb_compiler->add_import_paths($file);
        }
        
    }

    $rb_compiler->compile();

} 