<?php

class Cropper extends Intervention\Image\ImageManagerStatic{

    private $img_dir;
    private $new_filename;
    private $cache_id;

    public function __construct($cache_id = false){

        /* Set a cache id for orderly folder structure */
        $this->cache_id = $cache_id;
        $post_type = get_post_type($cache_id);

        /* Make sure the image directory exists, if it doesn't, create it */
        if (!file_exists(wp_get_upload_dir()['basedir'] . '/images/')) {
            mkdir(wp_get_upload_dir()['basedir'] . '/images/', 0777, true);
        }

        /* Do not save image crops for revisions */
        if ($post_type == 'revision') {
            $this->cache_id = false;
        }

    }
    

    /* Crops and resizes images so it "fits" the dimensions best. http://image.intervention.io/api/fit */
    public function fit($src, $width, $height = '', $encode = false, $quality = '90'){
        
        /* Make sure a src has been added */
        if(empty($src)){ return 'nosrc'; }

        /* Make sure a caching id has been set */
        if(!$this->cache_id){ return 'nocacheid'; }
        
        /* Create a unique filename */
        $this->set_filename($src, $width, $height, $encode, 'fit');
        
        /* If image already exists, return the image slug */
        if($this->is_cached()){ return $this->get_image_slug(); }

        /* Replace the url in $src with dit for better compatibility */
        $src = str_replace(wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $src);

        /* Create a new cropped image */
        try {
            $image = $this->make($src)->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
        } catch (\Intervention\Image\Exception\NotReadableException $e) {
            return 'createfail';
        }
        
        /* Save the newly cropped image to the cache */
        $this->save_file($image, $encode, $quality);

        /* Return appropriate file url */
        return $this->get_image_slug();

    }


    /* Resizes image (does not crop) based on aspect ratio. http://image.intervention.io/api/resize */
    public function resize($src, $width, $height = null, $encode = false, $quality = '90'){
        /* Make sure a src has been added */
        if(empty($src)){ return 'nosrc'; }

        /* Make sure a caching id has been set */
        if(!$this->cache_id){ return 'nocacheid'; }
        
        /* Create a unique filename */
        $this->set_filename($src, $width, $height, $encode, 'resize');
        
        /* If image already exists, return the image slug */
        if($this->is_cached()){ return $this->get_image_slug(); }

        /* Replace the url in $src with dit for better compatibility */
        $src = str_replace(wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $src);

        /* Create a new cropped image */
        try {
            $image = $this->make($src)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } catch (\Intervention\Image\Exception\NotReadableException $e) {
            print_r($e);
            return 'createfail';
        }
        
        /* Save the newly cropped image to the cache */
        $this->save_file($image, $encode, $quality);

        /* Return appropriate file url */
        return $this->get_image_slug();
    }


    /* Return storage slug. Use dir for filesystem path */
    private function get_image_slug($type = 'url') {
        return wp_get_upload_dir()['base' . $type] . '/images/' . $this->cache_id . '/' . $this->new_filename;
    }


    /* Set new filename based on input paramaters */
    private function set_filename($src, $width, $height, $encode, $manipulate) {

      /* set a new filename with additional parameters */
        $src_array = explode('/', strtok($src, '?'));
        $this->new_filename = $width . 'x' . $height . '-' . $manipulate . '-' . end($src_array);

        /* change file extension when encoding format is specified */
        if ($encode) {
            $file_array = explode('.', $this->new_filename);
            $file_array[(count($file_array) - 1)] = $encode;
            $this->new_filename = implode('.', $file_array);
        }
    }


    /* Saves image to the cache directory in /uploads/rb_image */
    private function save_file($image, $encode, $quality){

        /* Set temporary directory and create folder if it doesn't exist */
        if ($this->cache_id) {
            $this->img_dir = wp_get_upload_dir()['basedir'] . '/images/'. $this->cache_id . '/';
            if (!file_exists($this->img_dir)) {
                mkdir($this->img_dir, 0755, true);
            }

            /* Encode to specified image format */
            if ($encode) {
                $image->encode($encode);
            }

            /* Save the image to the rb_image directory */
            $image->save($this->img_dir . $this->new_filename, $quality);

            /* Destroy image instance to free up memory */
            $image->destroy();
        }
    }


    /* Check if specified file is cached, and update last requested date */
    public function is_cached()
    {
        $img = $this->get_image_slug('dir');

        if (is_file($img)) {
            return true;
        }
        return false;
    }

}



/* make sure uploads do not contain special characters. Special characters give errors when cropping/resizing */
add_filter('sanitize_file_name', function ($filename) {
    $ext = explode('.', $filename);
    $ext = end($ext);
    $sanitized = preg_replace('/[^a-zA-Z0-9-_.]/', '', substr($filename, 0, -(strlen($ext)+1)));
    $sanitized = str_replace('.', '-', $sanitized);
    return strtolower($sanitized.'.'.$ext);
}, 10);



/* Remove all cached images when a post is deleted */ 
add_action('delete_post', function ($post_id) {
    
    /* Check if image folder for this post_id exists */
    if (!file_exists(wp_get_upload_dir()['basedir'] . '/images/' . $post_id)) { return; }
        
    /* Remove all files in the image folder */
    $files = glob(wp_get_upload_dir()['basedir'] . '/images/' . $post_id . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    /* Remove the image folder */
    rmdir(wp_get_upload_dir()['basedir'] . '/images/' . $post_id);
   
});
