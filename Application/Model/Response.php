<?php
namespace Application\Model;

use MRPHPSDK\MRModels\MRModel;

class Response extends MRModel{

    public $status;

    public $message;

    public $data;

    function __construct($params = array()){
        $this->status = 0;
        $this->message = "Something went wrong";
        parent::__construct($params);
    }

    public static function data($data, $status = 1, $message = "" ){
        header("Content-type:application/json");
        if($data) {
            return ["data" => $data, "status"=>$status, "message"=>$message];
        } else {
            return ["status"=>$status, "message"=>$message];
        }
    }

    public static function toObject($message, $status=0, $data=[]) {
        $result = new Response();
        $result->status = $status;
        $result->message = $message;
        $data["response"] = ($status == 1) ? "success" : "failed";
        $result->data = $data;
        return $result;
    }

    public static function toJson($data, $status = 1, $message = ""){
        $data["response"] = ($status == 1) ? "success" : "failed";
        return json_encode(["data" => $data, "status" => $status, "message" => $message]);
    }

    public static function json($object){
        header("Content-type:application/json");
        return json_encode($object);
    }
}