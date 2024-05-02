<?php

spl_autoload_register(function ($class) {
    $className = $class . '.php';

    $classPath = str_replace("\\", '/', $className);
    
    require_once $classPath;
});