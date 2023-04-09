<?php

use MatthiasMullie\Minify;
use ScssPhp\ScssPhp\Compiler;

class SassCompiler
{
    private $compiler;
    private $import_paths = array();
    private $output_path;
    private $output_filename;


    public function __construct($output_path, $output_filename)
    {
        $this->compiler = new Compiler();
        $this->output_path = $output_path;
        $this->output_filename = $output_filename;
    }


    /* Add sass paths to the compiler */
    public function add_import_paths($import_paths)
    {
        $this->import_paths = array_merge($this->import_paths, (array)$import_paths);
    }

    /* Add variables from PHP into the css */
    public function add_variables($vars){
        $this->compiler->setVariables($vars);
    }

    /* Compile scss and write to file */
    public function compile()
    {

      /* only compile if one of the source files has been edited */
        if ($this->is_cache_invalid()) {

         /* load import paths into compiler */
            foreach ($this->import_paths as $path) {
                if (is_file($path)) {
                    $this->compiler->addImportPath(pathinfo($path, PATHINFO_DIRNAME));
                    continue;
                }
                $this->compiler->addImportPath($path);
            }

            /* set import files based on import paths */
            $import_files = $this->get_import_files();

            /* compile and write production file */
            $this->compiler->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');
            file_put_contents($this->output_path . $this->output_filename . '.css', $this->compiler->compile($import_files));

            /* compile and write development file */
            $this->set_source_map($this->output_filename . '-dev');
            $this->compiler->setFormatter('ScssPhp\ScssPhp\Formatter\Expanded');
            file_put_contents($this->output_path . $this->output_filename . '-dev.css', $this->compiler->compile($import_files));
        }
    }


    private function set_source_map($file_name)
    {
        $source_map_url = str_replace(get_template_directory(), get_template_directory_uri(), $this->output_path);

        $this->compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
        $this->compiler->setSourceMapOptions(array(
         'sourceMapWriteTo'  => $this->output_path . $file_name . '.css.map',
         'sourceMapURL'      => $source_map_url . $file_name . '.css.map',
         'sourceMapFilename' => $file_name . '.css',
         'sourceMapBasepath' => $this->output_path,
         'sourceRoot'        => '/',
      ));
    }


    /* set direct .scss/.css children from $import_paths as import files */
    private function get_import_files()
    {
        foreach ($this->import_paths as $path) {
            if (is_file($path)) {
                $import_files[] = '@import "' . pathinfo($path, PATHINFO_BASENAME) . '";';
                continue;
            }
            if (is_dir($path)) {
                $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        if ($file->getExtension() == 'scss' || $file->getExtension() == 'css') {
                            $import_files[] = '@import "' . $file->getFilename() . '";';
                        }
                    }
                }
            }
        }

        return implode('', $import_files);
    }


    /* get last edited time from all source files to check if one of them has changed */
    private function is_cache_invalid()
    {
        $cached_last_edited_hash = get_option('rb_css_cache_hash_'.$this->output_filename);

        foreach ($this->import_paths as $path) {
            if (is_file($path)) {
                $last_edited_hash[] = filemtime($path);
                $path = dirname($path);
            }
            if (is_dir($path)) {
                $dir  = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($dir);

                foreach ($files as $file) {
                    if ($file->isFile()) {
                        if ($file->getExtension() == 'scss' || $file->getExtension() == 'css') {
                            $last_edited_hash[] = $file->getMTime();
                        }
                    }
                }
            }
        }

        /* compare previous hash to new hash to see if one of the files has been edited */
        if ($cached_last_edited_hash != $last_edited_hash) {
            update_option('rb_css_cache_hash_'.$this->output_filename, $last_edited_hash);
            return true;
        }
        return false;
    }
}
