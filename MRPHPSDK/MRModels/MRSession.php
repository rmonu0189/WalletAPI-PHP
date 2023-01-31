<?php
namespace MRPHPSDK\MRModels;

class MRSession extends MRModel{


    function __construct($params = array()){
        parent::__construct($params);
    }

    public static function add($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function delete($key){
        unset($_SESSION[$key]);
    }

    public static function isSetValue($key){
        if(isset($_SESSION[$key])){
            return true;
        }
        else{
            return false;
        }
    }

    public static function getValue($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        else{
            return "";
        }
    }

}