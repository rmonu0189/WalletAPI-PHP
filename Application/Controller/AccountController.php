<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Services\AccountService;
use Application\Model\Response;

class AccountController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function getIndex(MRRequest $request) {
		$result = AccountService::getAccounts($this->getAuth()->id);
        return Response::json($result);
	}

	public function postIndex(MRRequest $request) {
		$result = AccountService::addNewAccount($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function putIndex(MRRequest $request) {
		$result = AccountService::editAccount($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function deleteIndex(MRRequest $request) {
		$result = AccountService::deleteAccount($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}
}