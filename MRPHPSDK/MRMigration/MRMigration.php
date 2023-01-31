<?php

namespace MRPHPSDK\MRMigration;

use MRPHPSDK\MRMigration\DBSchema;

class MRMigration{

    public static function create($tableName, $dbSchema){
        $schema = new DBSchema();
        $schema->tableName = $tableName;
        $schema->operation = "CREATE";
        $dbSchema($schema);
        $schema->run();
    }

    public static function table($tableName, $dbSchema){
        $schema = new DBSchema();
        $schema->tableName = $tableName;
        $schema->operation = "MODIFY";
        $dbSchema($schema);
        $schema->run();
    }

}