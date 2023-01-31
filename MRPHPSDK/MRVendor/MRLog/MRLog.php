<?php

namespace MRPHPSDK\MRVendor\MRLog;

class MRLog{

    public static function add($message){

        if(!file_exists(__DIR__."/../../../Application/Logs")){
            mkdir(__DIR__."/../../../Application/Logs", 0777, true);
        }

        $logFileName = __DIR__."/../../../Application/Logs/".date("d-m-Y").".log";
        $content = "\n[".date('Y-m-d H:i:s')."] ".$message;
        if(file_exists($logFileName)){
            $fp = fopen($logFileName, 'a');
            fwrite($fp, $content);
            fclose($fp);
            chmod($logFileName, 0777);
        }
        else{
            $fp = fopen($logFileName, 'w');
            fwrite($fp, $content);
            fclose($fp);
            chmod($logFileName, 0777);
        }
    }

}