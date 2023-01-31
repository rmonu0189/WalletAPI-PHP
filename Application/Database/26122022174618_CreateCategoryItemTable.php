<?php

use MRPHPSDK\MRMigration\DBSchema;
use MRPHPSDK\MRMigration\MRMigration;

class CreateCategoryItemTable extends MRMigration{

	public function up(){
		MRMigration::create('CategoryItem', function(DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->bigInteger("userId");
			$schema->string("name");
            $schema->string("icon");
            $schema->dateTime("createdAt")->defaultCurrentTimeStamp();
            $schema->dateTime("updatedAt")->defaultCurrentTimeStamp();
        });
	}

}