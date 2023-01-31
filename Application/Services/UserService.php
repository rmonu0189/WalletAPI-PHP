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
}