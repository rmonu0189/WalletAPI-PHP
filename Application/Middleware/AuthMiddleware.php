<?php
namespace Application\Middleware;

use MRPHPSDK\MRMiddleware\MRMiddleware;
use MRPHPSDK\MRModels\MRSession;
use MRPHPSDK\MRRequest\MRRequest;
use MRPHPSDK\MRVendor\MRAccessToken\MRAccessToken;
use MRPHPSDK\MRException\MRException;

class AuthMiddleware extends MRMiddleware{

	/*
	|--------------------------------------------------------------------------
	| Handle all request
	|--------------------------------------------------------------------------
	*/
	public function handle(MRRequest $request){
		try{
			MRAccessToken::setAuthClass("\\Application\\Model\\User");
			$token = MRAccessToken::authorize();
		}
		catch(MRException $e){
			http_response_code(401);
			MRSession::delete("token");
			return $this->failed($e->getMessage(), $e->getStatusCode());
		}
		return $this->success();
	}

}