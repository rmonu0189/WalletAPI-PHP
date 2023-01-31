<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Services\PersonService;
use Application\Model\Response;

class PersonController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function getIndex(MRRequest $request) {
		$result = PersonService::getPersons($this->getAuth()->id);
        return Response::json($result);
	}

	public function postIndex(MRRequest $request) {
		$result = PersonService::addNewPerson($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function putIndex(MRRequest $request) {
		$result = PersonService::editPerson($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function deleteIndex(MRRequest $request) {
		$result = PersonService::deletePerson($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

}