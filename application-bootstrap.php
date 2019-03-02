<?php

/**
 * @return PlanckApplicationBootstrap
 */

if(defined('PLK_INCLUDED_'.__FILE__)) {
    throw new Exception(
        'Planck application bootstrap already loaded'
    );
}
define('PLK_INCLUDED_'.__FILE__, true);


if(!defined('PLK_APPLICATION_FILEPATH_ROOT'))
{
    throw new Exception(
       'You must define an application file path constant (constant PLK_APPLICATION_FILEPATH_ROOT)'
    );
}


require(__DIR__.'/autoload.php');

/*
//=======================================================
if(is_file(__DIR__.'/../static-vendor/phi/phi-core/get-autoloader-with-phi.php')) {
    $autoloader = require(__DIR__.'/../static-vendor/phi/phi-core/get-autoloader-with-phi.php');
}
else {
    throw new Exception("Cannot find Phi autoloader automaticaly (Looking for ".__DIR__.'/static-vendor/phi/phi-core/get-autoloader.php)');
}
*/

require(__DIR__.'/PlanckApplicationBootstrap.php');


$planckApplicationBootstrap = PlanckApplicationBootstrap::getInstance($autoloader, PLK_APPLICATION_FILEPATH_ROOT);

return $planckApplicationBootstrap;

