<?php

namespace MRPHPSDK\MRMiddleware;

use MRPHPSDK\MRException\MRException;


class MRMiddleware{

    function __construct(){

    }

    protected function failed($message="", $statusCode=401){
        if($message!=""){
            throw new MRException($message, $statusCode);
        }
        return "Failed";
    }

    protected function success(){
        return "Success";
    }

}