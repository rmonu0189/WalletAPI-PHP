<?php

namespace MRPHPSDK\MRMigration;

class MRColumn{

    public $indexType;

    public $name;

    public $dataType;

    public $length;

    public $defaultValue;

    public $isNullable;

    public $isIncrement;

    public $enumValues;

//    public $afterColumn;

    public function __construct(){
        $this->isNullable = false;
        $this->isIncrement = false;
        $this->afterColumn = "";
    }

    public function nullable(){
        $this->isNullable = true;
        $this->defaultValue = "";
        return $this;
    }

    public function unique(){
        $this->indexType = "UNIQUE";
        return $this;
    }

    public function defaults($value){
        $this->defaultValue = $value;
        return $this;
    }

    public function defaultCurrentTimeStamp(){
        $this->defaultValue = "CURRENT_TIMESTAMP";
        return $this;
    }

    public function defaultOnUpdateCurrentTimeStamp(){
        $this->defaultValue = "on update CURRENT_TIMESTAMP";
        return $this;
    }

//    public function after($column){
//        $this->afterColumn = $column;
//        return $this;
//    }

    public function get(){
        $colString = " ";

        if($this->dataType == "ENUM"){
            $colString.=$this->name." ".$this->dataType."(";
            foreach($this->enumValues as $value){
                $colString.="'".$value."',";
            }
            $colString = rtrim($colString,',');
            $colString.=")";
        }
        else{
            $colString.=$this->name." ".$this->dataType;
            $colString.=(($this->length>0)?"(".$this->length.")":"");
        }

        if($this->isNullable == false){
            if($this->defaultValue != ''){
                if($this->defaultValue == "CURRENT_TIMESTAMP"){
                    $colString.=" DEFAULT ".$this->defaultValue;
                }
                elseif($this->defaultValue == "on update CURRENT_TIMESTAMP"){
                    $colString.=" ".$this->defaultValue;
                }
                else{
                    $colString.=" DEFAULT '".$this->defaultValue."'";
                }
            }
            else{
                $colString.=" NOT NULL ";
            }
        }

        if($this->isIncrement == true){
            $colString.=" AUTO_INCREMENT ";
        }

        if($this->indexType != "" && $this->indexType == "UNIQUE"){
            $colString.=" UNIQUE ";
        }

//        if($this->afterColumn!=""){
//            $colString.=" AFTER '".$this->afterColumn."' ";
//        }

        return $colString.",";
    }
}