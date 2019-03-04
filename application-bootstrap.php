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
require(__DIR__.'/PlanckApplicationBootstrap.php');



$planckApplicationBootstrap = PlanckApplicationBootstrap::getInstance($autoloader, PLK_APPLICATION_FILEPATH_ROOT);

return $planckApplicationBootstrap;

