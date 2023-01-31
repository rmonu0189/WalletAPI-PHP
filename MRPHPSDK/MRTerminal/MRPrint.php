<?php

namespace MRPHPSDK\MRTerminal;

//-- Color Codes
define("COLOR_BLUE", "0;34");
define("COLOR_RED", "0;31");
define("COLOR_GREEN", "0;32");
define("COLOR_YELLOW", "1;33");
define("COLOR_WHITE", "1;37");


class MRPrint{

    public static function error($message){
        echo "\033[".COLOR_RED."m ".$message." \033[0m\n";
    }

    public static function success($message){
        echo "\033[".COLOR_GREEN."m ".$message." \033[0m\n";
    }

    public static function alert($message){
        echo "\033[".COLOR_BLUE."m ".$message." \033[0m\n";
    }

    public static function write($filePath, $content){
        $fp = fopen($filePath, 'w');
        fwrite($fp, $content);
        fclose($fp);
        chmod($filePath, 0777);
    }

}