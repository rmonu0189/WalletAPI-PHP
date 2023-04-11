<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Model\Response;
use Application\Services\TransactionService;

class TransactionController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function postAddAccountToAccountTransfer(MRRequest $request) {
		$result = TransactionService::addAccountToAccountTransaction($this->getAuth()->id, $request->input());
        return Response::json($result);
	}

	public function postAddAccountToPersonTransfer(MRRequest $request) {
		$result = TransactionService::addAccountToPersonTransaction($this->getAuth()->id, $request->input());
        return Response::json($result);
	}

	public function postAddPersonToAccountTransfer(MRRequest $request) {
		$result = TransactionService::addPersonToAccountTransaction($this->getAuth()->id, $request->input());
        return Response::json($result);
	}

	public function postAddIncome(MRRequest $request) {
		$result = TransactionService::adIncome($this->getAuth()->id, $request->input());
        return Response::json($result);
	}
}