<?php

namespace Application\Services;

use Application\Model\User;
use Application\Model\EmailOTP;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;
use MRPHPSDK\MRVendor\MRAccessToken\MRAccessToken;

class AuthService {
    public static function createUser($user) {
        $validation = new MRValidation($user, [
            'name' => 'required',
            'email' => 'required|unique:User',
            'password' => 'required'
        ], [
            'name.required' => 'Please enter a valid name',
            'email.unique' => 'Email is already registered',
            'password.required' => 'Please enter password'
        ]);

        if($validation->validateFailed()){
            return Response::data(null, 0, $validation->getValidationError()[0]);
        }

        $newUser = new User($user);
        $newUser->save();
        return Response::data(null, 1, "Successfully created.");
    }

    public static function loginUser($user) {
        $validation = new MRValidation($user, [
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Incorrect email address',
            'password.required' => 'Incorrect password'
        ]);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $user = User::where("email", $user["email"])->where("password", $user["password"])->first();
        if($user) {
            unset($user->password);
            $accessToken = MRAccessToken::generate($user->id, ["email"=>$user->email, "mobile"=>$user->mobile], "MOBILE");
            $user->accessToken = $accessToken;
            return Response::data($user, 1, "Success");
        } else {
            return Response::data(null, 0, "Email or password incorrect.");
        }
    }

    public static function recoverPassword($params) {
        $validation = new MRValidation($params, [
            'email' => 'required|exists:User'
        ], [
            'name.required' => 'Please enter a valid name',
            'email.exists' => 'Email is not registered'
        ]);

        if($validation->validateFailed()){
            return Response::data(null, 0, $validation->getValidationError()[0]);
        }

        $otp = new EmailOTP([
            "email" => $params['email'],
            "otp" => AuthService::randomCode(),
            "type" => "RecoverEmail",
            "expireAt" => date('Y-m-d H:i:s', strtotime("+30 minutes", strtotime(date('Y-m-d H:i:s'))))
        ]);
        $otp->save();
        return Response::data(null, 1, "OTP sent to your registered email.");
    }

    public static function changePassword($params) {
        $validation = new MRValidation($params, [
            'email' => 'required',
            'otp' => 'required',
            'password' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data(null, 0, $validation->getValidationError()[0]);
        }

        $otp = EmailOTP::where('email', $params['email'])->where('otp', $params['otp'])->first();
        if($otp) {
            $otp->remove();
            $user = User::where("email", $$params["email"])->first();
            if($user) {
                $user->password = $params['password'];
                $user->save();
                return Response::data(null, 1, "Password changed successfully");
            } else {
                return Response::data(null, 0, "Invalid email");
            }
        } else {
            return Response::data(null, 0, "Invalid OTP");
        }
    }

    static function randomCode() {
        $characters = '0123456789';
        $randstring = '';
        for ($i = 0; $i < 4; $i++) {
            $char = $characters[rand(0, 9)];
            if($char!=''){
                $randstring.=$char;
            }
        }   
        return $randstring;
    }
}