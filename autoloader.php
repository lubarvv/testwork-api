<?php

spl_autoload_register(function($className) {

    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $className = str_replace('_', DIRECTORY_SEPARATOR, $className);

    $className = explode(DIRECTORY_SEPARATOR, $className);
    if($className[0] !== 'API') {
        return false;
    }

    $className[0] = dirname(__FILE__);

    $classPath = implode(DIRECTORY_SEPARATOR, $className) . '.php';

    if(!is_readable($classPath)) {
        return false;
    }

    require_once $classPath;

    return true;
});