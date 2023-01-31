<?php

namespace MRPHPSDK\MRController;

use MRPHPSDK\MRVendor\MRAccessToken\MRAccessToken;

class MRController{

    public $error;

    public $view;

    function __construct(){
        $this->error = "";
        $this->view = "";
    }

    public function view($viewName, $params = []){
        $this->add($params);
        $this->view = $viewName;
        include(__DIR__."/../../Application/View/".$viewName.".php");
    }

    public function add($params){
        foreach($params as $key=>$value){
            $this->{$key} = $value;
        }
    }

    public function redirect($url, $params = [], $method="POST"){
        if($method == "POST"){
            $this->post($url, $params);
        }
        else{
            $paramsStr = "";
            foreach($params as $key=>$value){
                $paramsStr.=$key."=".$value."&";
            }
            if($paramsStr != ""){
                header("Location: $url?".$paramsStr);
            }
            else{
                header("Location: $url");
            }
        }
    }

    public function getAuth(){
        return MRAccessToken::getAuth();
    }

    private function post($url, $params){
        echo '<form id="myForm" action="'.$url.'" method="post">';
        foreach($params as $key=>$value){
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }
        echo '</form>';
        echo '<script type="text/javascript">document.getElementById("myForm").submit();</script>';
    }

}