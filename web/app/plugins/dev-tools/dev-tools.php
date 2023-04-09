<?php
/*
Plugin Name: Bredewold Development Tools
description: Supercharges development with hot reloading and compiling.
Version: 1.14
Author: Gerritjan Boeve
Author URI: https://bredewold.nl
License: Contact me
*/


require __DIR__ . '/vendor/autoload.php';

//include __DIR__ . '/RockbergMailjetAPI.php';

include __DIR__ . '/classes/SassCompiler.php';

include __DIR__ . '/classes/JsCompiler.php';

include __DIR__ . '/HotReload.php';
