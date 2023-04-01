<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Model\Response;
use Application\Services\PersonService;

class TransactionController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function postAccountTransfer(MRRequest $request) {
		$result = PersonService::getPersons($this->getAuth()->id);
        return Response::json($result);
	}

}