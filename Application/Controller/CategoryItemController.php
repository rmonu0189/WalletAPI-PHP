<?php
namespace Application\Controller;

use MRPHPSDK\MRController\MRController;
use MRPHPSDK\MRRequest\MRRequest;
use Application\Services\CategoryItemService;
use Application\Model\Response;

class CategoryItemController extends MRController{

	function __construct(){
		parent::__construct();
	}

	public function getIndex(MRRequest $request) {
		$result = CategoryItemService::getCategoryItems($this->getAuth()->id);
        return Response::json($result);
	}

	public function postIndex(MRRequest $request) {
		$result = CategoryItemService::addNewCategoryItem($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function putIndex(MRRequest $request) {
		$result = CategoryItemService::editCategoryItem($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

	public function deleteIndex(MRRequest $request) {
		$result = CategoryItemService::deleteCategoryItem($request->input(), $this->getAuth()->id);
        return Response::json($result);
	}

}