<?php

namespace Application\Services;

use Application\Model\User;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class UserService {
    public static function getUserProfile($userId) {
        $user = User::where("id", $userId)->first();
        if($user) {
            unset($user->password);
            return Response::data($user, 1, "User profile");
        }
        return Response::data(null, 0, "User not found.");
    }

    public static function updateUserProfile($userId, $params) {
        $validation = new MRValidation($params, [
            'name' => 'required',
            'mobile' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $user = User::where('id', $userId)->first();
        if($user) {
            $user->name = $params['name'];
            $user->mobile = $params['mobile'];
            $user->save();
            return Response::data(null, 1, "Profile successfully update.");
        } else {
            return Response::data(null, 0, "User not found.");
        }
    }
}