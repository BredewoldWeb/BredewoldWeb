<?php 

use Roots\WPConfig\Config;

/* Make sure the cache is on */
Config::define('WP_CACHE', true);

/* Limit the number of post revisions that are saved */
Config::define('WP_POST_REVISIONS', 25);

/* Allow plugins to be updated */
Config::define('DISALLOW_FILE_MODS', false);