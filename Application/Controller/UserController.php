<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Model\Response;
use Application\Services\UserService;

class UserController extends MRController {

	function __construct(){
		parent::__construct();
	}

	public function getMe(MRRequest $request) {
		return Response::json(UserService::getUserProfile($this->getAuth()->id));
	}

	public function putMe(MRRequest $request) {
		return Response::json(UserService::updateUserProfile($this->getAuth()->id, $request->input()));
	}
}