<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateTransactionTable extends MRMigration{

	public function up(){
		MRMigration::create('Transaction', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
			$schema->string("type");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}