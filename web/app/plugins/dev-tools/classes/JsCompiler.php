<?php

use MatthiasMullie\Minify;

class JsCompiler
{
    private $imports = array();
    private $compile_queue = array();
    private $js_string = '';
    private $output_path;
    private $output_filename;


    public function __construct($output_path, $output_filename)
    {
        $this->output_path = $output_path;
        $this->output_filename = $output_filename;
    }


    /* Add JS paths, or files to the compiler */
    public function add_import_paths($import_paths)
    {
        $this->imports = array_merge($this->imports, (array)$import_paths);
    }


    /* Compile all the JS in the paths */
    public function compile()
    {
        /* loop through all imported paths/files, and create a file queue */
        $this->set_compile_queue();

        /* Only compile if one of the source files has been edited */
        if ($this->is_cache_invalid()) {

            /* Init the minifier */
            $minifier = new Minify\JS();

            foreach ($this->compile_queue as $file){

                /* Make a debuggable file for dev */
                $this->js_string .= $this->add_comment($file) . file_get_contents($file);

                /* make an optimised file for staging/production */
                $minifier->add($file);
            }

            /* Compile dev js file */
            file_put_contents($this->output_path . $this->output_filename . '-dev.js', $this->js_string);

            /* minify and uglify js for staging/production */
            $myPacker = new GK\JavascriptPacker($minifier->minify());
            file_put_contents($this->output_path . $this->output_filename . '.js', $myPacker->pack());

        }
    }

    private function is_cache_invalid()
    {
        /* generate a caching string based on the edited time of the source files */
        $caching_string = '';
        foreach ($this->compile_queue as $file) {
            $caching_string .= filemtime($file);
        }

        /* if caching string doesn't match stored caching string, start compiling! */
        if (get_option('rockberg_js_cache_hash_' . $this->output_filename) != $caching_string) {
            update_option('rockberg_js_cache_hash_' . $this->output_filename, $caching_string);
            return true;
        }
        return false;
    }

    /* Set the compile queue from import paths/files */
    private function set_compile_queue()
    {
        foreach ($this->imports as $file_dir) {

            /* if path is a file, directly add to compile_queue */
            if(is_file($file_dir)){
              $this->compile_queue[] = $file_dir;
              continue;
            }

            /* if path is a directory, loop through directory and add all files to the compile queue */
            $dir = new DirectoryIterator($file_dir);
            if ($dir->valid()) {
                foreach ($dir as $fileinfo) {
                    if (!$fileinfo->isDot()) {
                        $this->compile_queue[] = $fileinfo->getPathname();
                    }
                }
            }
        }
    }

    /* add comments for dev */
    private function add_comment($file)
    {
        return '/****************************************************************************/
         /*' . $file . ' */
         /***************************************************************************/
         ';
    }
}
