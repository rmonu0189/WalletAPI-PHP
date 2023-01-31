<?php

namespace MRPHPSDK\MRRequest;

class MRRequest{

    public $get;

    public $post;

    public $json;

    public $data;

    public $urlParams;

    public $headers;

    function __construct(){
        $this->get = (count($_GET)>0)?$_GET:[];
        $this->post = (count($_POST)>0)?$_POST:[];
        $this->json = json_decode(file_get_contents("php://input"), true);
        if($this->json) {
            $this->json = (count($this->json)>0)?$this->json:[];
        }
        $this->headers = $_SERVER;
        $this->mergeAllRequest();
    }

    /**
     * Call this method to get singleton
     *
     * @return Route
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new MRRequest();
        }
        return $inst;
    }

    public static function setUrlParams($params){
        MRRequest::instance()->urlParams = $params;
    }

    public static function getUrlParams(){
        return MRRequest::instance()->urlParams;
    }

    public static function input($key = ""){
        if($key == ""){
            return MRRequest::instance()->data;
        }
        else{
            if (array_key_exists($key,MRRequest::instance()->data)){
                return MRRequest::instance()->data[$key];
            }
            return "";
        }
    }




    private function mergeAllRequest(){
        $this->data = array();
        $this->data = array_merge($this->get, $this->post);
        if($this->json) {
            $this->data = array_merge($this->data, $this->json);
        }
    }

}