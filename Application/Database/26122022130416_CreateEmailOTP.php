<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateEmailOTP extends MRMigration{

	public function up(){
		MRMigration::create('EmailOTP', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->string("email");
            $schema->string("type");
            $schema->text("otp");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("expireAt");
        });
	}

}