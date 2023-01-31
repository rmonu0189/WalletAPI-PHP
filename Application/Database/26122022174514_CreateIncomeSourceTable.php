<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateIncomeSourceTable extends MRMigration{

	public function up(){
		MRMigration::create('IncomeSource', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
			$schema->string("name");
            $schema->double("balance");
			$schema->double("initialBalance");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}