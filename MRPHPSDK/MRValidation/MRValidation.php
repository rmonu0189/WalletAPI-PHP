<?php
namespace MRPHPSDK\MRValidation;

use MRPHPSDK\MRDatabase\MRDatabase;

class MRValidation{

    private $validationError;

    function getValidationError(){
        return $this->validationError;
    }

    function __construct($params=array(), $validations=array(), $messages=array()){

        $this->validationError = array();

        foreach($validations as $key=>$value){
            $validationName = explode("|", $value);
            foreach($validationName as $name){
                if(strpos($name, ':') !== false){
                    $subNameArray = explode(":", $name);
                    $subName = $subNameArray[0];
                    $tableName = (isset($subNameArray[1])?$subNameArray[1]:'');
                    $column = (isset($subNameArray[2])?$subNameArray[2]:'');
                    if($subName == 'exists'){
                        $this->checkExists($params, $key, (isset($messages[$key.'.exists']))?$messages[$key.'.exists']: $key.' is not exists', $tableName, $column);
                    }
                    elseif($subName == 'unique'){
                        $this->checkUnique($params, $key, (isset($messages[$key.'.unique']))?$messages[$key.'.unique']: $key.' is not unique', $tableName, $column);
                    }
                    elseif($subName == 'digits'){
                        $this->checkDigits($params, $key, (isset($messages[$key.'.digits']))?$messages[$key.'.digits']: $key.' is not having length '.$tableName, $tableName);
                    }
                    elseif($subName == 'max'){
                        $this->checkMax($params, $key, (isset($messages[$key.'.max']))?$messages[$key.'.max']: $key.' is maximum length', $tableName);
                    }
                    elseif($subName == 'in'){
                        $this->checkIn($params, $key, (isset($messages[$key.'.in']))?$messages[$key.'.in']: $key.' does not correct', $tableName );
                    }
                }
                elseif($name == 'required'){
                    $this->checkRequired($params, $key, (isset($messages[$key.'.required']))?$messages[$key.'.required']: $key.' is required');
                }
                elseif($name == 'email'){
                    $this->checkEmail($params, $key, (isset($messages[$key.'.email']))?$messages[$key.'.email']: $key.' is invalid');
                }
            }
        }
    }

    public function validateFailed(){
        return (count($this->validationError)>0)?true:false;
    }

    private function checkRequired($params, $key, $message){
        if(!isset($params[$key]) || empty($params[$key])){
            $this->validationError[] = $message;
        }
    }

    private function checkEmail($params, $key, $message){
        if (isset($params[$key]) && !filter_var($params[$key], FILTER_VALIDATE_EMAIL)) {
            $this->validationError[] = $message;
        }
    }

    private function checkExists($params, $key, $message, $table='', $column=''){
        if((isset($params[$key])) && $params[$key] != ''){
            $query = "SELECT * FROM ".$table." WHERE ".(($column!='')?$column:$key)."='".$params[$key]."'";
            $result = MRDatabase::select($query);
            if(count($result)<=0){
                $this->validationError[] = $message;
            }
        }
    }

    private function checkUnique($params, $key, $message, $table='', $column=''){
        if((isset($params[$key])) && $params[$key] != ''){
            $query = "SELECT * FROM ".$table." WHERE ".(($column!='')?$column:$key)."='".$params[$key]."'";
            $result = MRDatabase::select($query);
            if(count($result)>0){
                $this->validationError[] = $message;
            }
        }
    }

    private function checkDigits($params, $key, $message, $length){
        if(isset($params[$key]) && strlen($params[$key]) != $length){
            $this->validationError[] = $message;
        }
    }

    private function checkMax($params, $key, $message, $length){
        if(isset($params[$key]) && strlen($params[$key]) > $length){
            $this->validationError[] = $message;
        }
    }

    private function checkIn($params, $key, $message, $inParams){
        $in = explode(",", $inParams);
        if(isset($params[$key]) && !in_array($params[$key], $in)){
            $this->validationError[] = $message;
        }
    }
}