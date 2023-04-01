<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateTransactionTable extends MRMigration{

	public function up(){
		MRMigration::create('Transaction', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
            $schema->bigInteger("fromAccountId");
            $schema->bigInteger("toAccountId");
			$schema->string("type"); // accountToAccount/accountToPerson
            $schema->double("amount");
            $schema->string("comment");
            $schema->dateTime("date");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}