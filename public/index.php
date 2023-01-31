<?php
use Application\Model\Response;

session_start();
require_once("appconfig.php");

/**
 * Used for loading library.
 */
require_once(__DIR__."/../MRPHPSDK/MRConfig/autoload.php");

/**
 * Packages Configurations
 */
\MRPHPSDK\MRConfig\MRConfig::setPackages();

/**
 * Handle all app activity.
 */
try {
    $app = new \MRPHPSDK\MRKernel\MRKernel();
}
catch(\MRPHPSDK\MRException\MRException $e) {    
    echo Response::json(Response::data(null, 0, $e->getMessage()));
}
