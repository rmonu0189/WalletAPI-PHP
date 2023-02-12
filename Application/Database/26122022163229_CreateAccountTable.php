<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateAccountTable extends MRMigration{

	public function up(){
		MRMigration::create('Account', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
            $schema->bigInteger("linkedBankId")->defaults(0);
            $schema->string("type");
            $schema->double("balance")->defaults(0);
			$schema->string("bankName");
            $schema->string("accountNumber");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}