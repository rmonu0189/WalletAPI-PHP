<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Services\IncomeSourceService;
use Application\Model\Response;

class IncomeSourceController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function getIndex(MRRequest $request) {
		$result = IncomeSourceService::getIncomeSources($this->getAuth()->id);
        return Response::json($result);
	}

	public function postIndex(MRRequest $request) {
		$result = IncomeSourceService::addNewIncomeSource($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function putIndex(MRRequest $request) {
		$result = IncomeSourceService::editIncomeSource($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function deleteIndex(MRRequest $request) {
		$result = IncomeSourceService::deleteIncomeSource($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

}