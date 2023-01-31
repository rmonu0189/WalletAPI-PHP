<?php

namespace MRPHPSDK\MRVendor\MRAccessToken;

use MRPHPSDK\MRModels\MRModel;
use MRPHPSDK\MRException\MRException;

class MRAccessToken extends MRModel{

    private $auth;

    private $validity;

    private $authClass;


    /**
     * Call this method to get singleton
     *
     * @return Route
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new MRAccessToken();
        }
        return $inst;
    }

    public static function setAuthClass($class){
        MRAccessToken::instance()->authClass = $class;
    }

    public static function generate($key, $payload, $type="MOBILE"){
        $token = new MRAccessToken();
        $token->tokenId = $key;
        $token->token = MRAccessToken::randomCode();
        $token->payload = json_encode($payload);
        $token->type = $type;
        $token->expireAt = date('Y-m-d H:i:s', strtotime("+30 day", strtotime(date('Y-m-d H:i:s'))));
        $token->save();
        return $token->token;
    }

    public static function delete($accessToken = ""){
        if($accessToken == ""){
            $headers = apache_request_headers();
            $accessToken = isset($headers['Authorization'])?$headers['Authorization']:"";
        }

        if($accessToken == ""){ throw new MRException("Access token is absent.", 401); }
        $accessToken = str_replace("Bearer ", "", $accessToken);
        $token = MRAccessToken::where("token", $accessToken)->first();
        $token->remove();
    }

    public static function randomCode($lenght = 100){
        if($lenght>100) $lenght = 100;
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@';
        $randstring = '';
        for ($i = 0; $i < $lenght; $i++) {
            $char = $characters[rand(0, 62)];
            if($char!=''){
                $randstring.=$char;
            }
        }
        return $randstring;
    }

    public static function authorize($accessToken = "", $type = "MOBILE"){
        if($accessToken == ""){
            $headers = apache_request_headers();
            $accessToken = isset($headers['Authorization'])?$headers['Authorization']:"";
            if($accessToken == ""){
                throw new MRException("Access token is absent.", 401);
            }
        }

        $accessToken = str_replace("Bearer ", "", $accessToken);
        $token = MRAccessToken::where("token", $accessToken)
                ->where('type', $type)
                ->first();
        if($token==null){
            throw new MRException("Invalid access token", 401);
        }

        if($token->expireAt < date('Y-m-d H:i:s')){
            throw new MRException("Access token is expire", 401);
        }

        if(isset(MRAccessToken::instance()->authClass) && MRAccessToken::instance()->authClass!=""){
            $class = MRAccessToken::instance()->authClass;
            MRAccessToken::instance()->auth = $class::where("id", $token->tokenId)->first();
        }
        else{
            throw new MRException("Please set auth class", 401);
        }

        return $token;
    }

    public static function getAuth(){
        return MRAccessToken::instance()->auth;
    }

    public static function getPayload($token){
        return json_decode($token->payload);
    }

}