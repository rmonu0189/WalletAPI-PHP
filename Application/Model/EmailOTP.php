<?php
namespace Application\Model;

use MRPHPSDK\MRModels\MRModel;

class EmailOTP extends MRModel{

	function __construct($params = array()){
		parent::__construct($params);
	}

}