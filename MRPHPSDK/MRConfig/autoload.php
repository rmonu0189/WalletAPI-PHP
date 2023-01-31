<?php

/**
 * Autoloading classes
 * @param $class
 */
spl_autoload_register(function ($class){
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    $docRoot = rtrim($docRoot, "public");//str_replace("public", "", $docRoot);
    $class = str_replace("\\", "/", $class).".php";
    $class = $docRoot.str_replace("\\", "/", $class);
    if(is_file($class)&&!class_exists($class)) include_once $class;
});