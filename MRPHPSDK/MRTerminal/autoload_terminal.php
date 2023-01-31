<?php

/**
 * Autoloading classes
 * @param $class
 */
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class).".php";
    $classFile = rtrim(__DIR__,'MRPHPSDK/MRTerminal');
    $classFile.="/".$class;
    if(is_file($classFile)&&!class_exists($class)) include_once $classFile;
});