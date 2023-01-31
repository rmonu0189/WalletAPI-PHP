<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateUserTable extends MRMigration{

	public function up(){
		MRMigration::create('User', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->string("name");
			$schema->string("email");
			$schema->string("password");
			$schema->string("mobile");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}