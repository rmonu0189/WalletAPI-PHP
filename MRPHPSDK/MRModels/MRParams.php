<?php
namespace MRPHPSDK\MRModels;


class MRParams extends MRModel{

    public $error;

    function __construct($params = array()){
        parent::__construct($params);
        $this->error = "";
    }

    public function add($params){
        foreach($params as $key=>$value){
            $this->{$key} = $value;
        }
    }

}