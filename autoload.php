<?php

////////////////////////////
// APPLICATION AUTOLOADER //
////////////////////////////

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

/////////////////////////
// COMPOSER AUTOLOADER //
/////////////////////////

$composer_autoload_file = __DIR__ . '/vendor/autoload.php';
if(file_exists($composer_autoload_file)){
    require_once $composer_autoload_file;
}else{
    die('Unable to find composer autoload. Did you run composer install?');
}

///////////////
// DEBUG DDD //
///////////////

require_once __DIR__ . '/debug.php';

////////////////////////////
// ENVIROMENTAL VARIABLES //
////////////////////////////

// include .env file
$env_file = __DIR__ . '/.env';
if(file_exists($env_file)) {
    require_once $env_file;
}

// convert all variables within env file into env's
foreach ($variables as $k => $v) {
    putenv("$k=$v");
}

// create function to get env with default...
if(!function_exists('en')) {

    function en($key, $default = null){

        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }

}

