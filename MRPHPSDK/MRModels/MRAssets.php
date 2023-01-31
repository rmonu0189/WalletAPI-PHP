<?php

namespace MRPHPSDK\MRModels;

use MRPHPSDK\MRVendor\MRAccessToken\MRAccessToken;
use MRPHPSDK\MRException\MRException;

class MRAssets{

	public static function uploadImages($files, $atPath){
		$fileNames = [];
		$auth = MRAccessToken::getAuth();
		foreach ($files as $key => $value) {

			if ($value["size"] > 1024*1024*1.5) {
		    	throw new MRException("Logo size is too large. Please upload small size logo", 201);
			}

			$imageFileType = pathinfo($value["name"],PATHINFO_EXTENSION);
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
			    throw new MRException("Sorry, only JPG, JPEG, & PNG files are allowed.".$imageFileType, 201);
			}

			$name = $auth->id."_".MRAccessToken::randomCode(15).".".$imageFileType;

			if (move_uploaded_file($value["tmp_name"], __DIR__."/../../".$atPath.$name)) {
		        $fileNames[] = $name;
		    } else {
		    	throw new MRException("Sorry, there was an error uploading your file.", 201);
		    }
		}

		return $fileNames;
	}

}