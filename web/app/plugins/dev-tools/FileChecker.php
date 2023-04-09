<?php

require __DIR__ . '/vendor/autoload.php';

use Sse\Event;
use Sse\SSE;

class FileChecker implements Event {

   /* when check() returns true, function update will be called */
   public function update(){
      return 'file has changed';
   }

   /* loop through all files, and check if they have been edited */
   public function check(){
      if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
         session_start();
      }

      $return = false;
      $path = str_replace(array('plugins', 'dev-tools'), array('themes', 'bredewoldweb'), __DIR__);
      $dir  = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
      $files = new RecursiveIteratorIterator($dir);

      $file_string = '';

      foreach ($files as $file) {
         if ($file->isFile()) {
            $file_string .= $file->getMTime();
         }
      }

      if(isset($_SESSION['file_string'])){
         if($file_string != $_SESSION['file_string']){
            $_SESSION['file_string'] = $file_string;
            $return = true;
         }
      }else{
         $_SESSION['file_string'] = $file_string;
      }
      return $return;
   }
}

$sse = new SSE();
$sse->addEventListener('file_updated', new FileChecker());
$sse->keep_alive_time = 30;
$sse->use_chunked_encoding = true;
$sse->start();

?>
