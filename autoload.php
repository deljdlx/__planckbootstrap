<?php


if(!defined('PHI_LIB_PATH')) {
    throw new Exception(
        'You must specify path to Phi libraries'
    );
}

//=======================================================
if(is_file(PHI_LIB_PATH.'/phi-core/get-autoloader-with-phi.php')) {

    $autoloader = require(PHI_LIB_PATH.'/phi-core/get-autoloader-with-phi.php');




    $planckPath = realpath(__DIR__.'/..');

    $autoloader->addNamespace('Planck', $planckPath.'/planck/source/class');
    $autoloader->addNamespace('Planck\Application', $planckPath.'/planck-application/source/class');

    $autoloader->addNamespace('Planck\ApplicationBuilder', $planckPath.'/planck-application-builder/source/class');


    $autoloader->addNamespace('Planck\Routing', $planckPath.'/planck-routing/source/class');
    $autoloader->addNamespace('Planck\Pattern', $planckPath.'/planck-pattern/source/class');
    $autoloader->addNamespace('Planck\Model', $planckPath.'/planck-model/source/class');
    $autoloader->addNamespace('Planck\View', $planckPath.'/planck-view/source/class');
    $autoloader->addNamespace('Planck\Navigation', $planckPath.'/planck-navigation/source/class');

    return $autoloader;





}
else {
    throw new Exception("Cannot find Phi autoloader automaticaly (Looking for ".PHI_LIB_PATH.'/phi-core/get-autoloader-with-phi.php)');
}



