<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateMRAccessTokenTable extends MRMigration{

	public function up(){
		MRMigration::create('MRAccessToken', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("tokenId");
            $schema->string("token");
            $schema->string("type")->nullable();
            $schema->text("payload");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("expireAt");
        });
	}

}