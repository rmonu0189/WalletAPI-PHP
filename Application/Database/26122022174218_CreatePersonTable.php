<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreatePersonTable extends MRMigration{

	public function up(){
		MRMigration::create('Person', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
			$schema->string("name");
            $schema->string("mobile");
            $schema->double("balance");
			$schema->double("initialBalance");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}