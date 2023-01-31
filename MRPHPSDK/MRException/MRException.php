<?php

namespace MRPHPSDK\MRException;


class MRException extends \Exception{

    private $statusCode;

    public $type;

    public function getStatusCode(){
        return $this->statusCode;
    }

    function __construct($message, $statusCode){
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    function getType(){
        return $this->type;
    }

}