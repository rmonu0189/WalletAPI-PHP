<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateAccountTable extends MRMigration{

	public function up(){
		MRMigration::create('Account', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
            $schema->string("type");
            $schema->double("balance");
			$schema->string("info");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}