<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Services\AuthService;
use Application\Model\Response;

class AuthController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function postSignUp(MRRequest $request) {
        return Response::json(AuthService::createUser($request->input()));
	}

	public function postLogin(MRRequest $request) {
        return Response::json(AuthService::loginUser($request->input()));
	}

	public function postRecoverPassword(MRRequest $request) {
        return Response::json(AuthService::recoverPassword($request->input()));
	}

	public function postChangePassword(MRRequest $request) {
        return Response::json(AuthService::changePassword($request->input()));
	}
}