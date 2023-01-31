<?php

namespace MRPHPSDK\MRMigration;

use MRPHPSDK\MRMigration\MRColumn;
use MRPHPSDK\MRDatabase\MRDatabase;

class DBSchema{

    public $columns;

    public $tableName;

    public $operation;

    private $primaryKey;

    private $afterColumn;

    public function __construct(){
        $this->columns = [];
        $this->primaryKey = "";
        $this->afterColumn = "";
    }

    public function bigIncrement($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "BIGINT";
        $col->isIncrement = true;
        $this->columns[] = $col;
        $this->primaryKey = $column;
        return $col;
    }

    public function boolean($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "TINYINT";
        $this->columns[] = $col;
        return $col;
    }

    public function integer($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "INT";
        $this->columns[] = $col;
        return $col;
    }

    public function bigInteger($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "BIGINT";
        $this->columns[] = $col;
        return $col;
    }

    public function double($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "DOUBLE";
        $this->columns[] = $col;
        return $col;
    }

    public function dateTime($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "DATETIME";
        $this->columns[] = $col;
        return $col;
    }

    public function timestamp($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "TIMESTAMP";
        $this->columns[] = $col;
        return $col;
    }

    public function text($column){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "TEXT";
        $this->columns[] = $col;
        return $col;
    }

    public function string($column, $length=100){
        $col = new MRColumn();
        $col->name = $column;
        $col->length = $length;
        $col->dataType = "VARCHAR";
        $this->columns[] = $col;
        return $col;
    }

    public function enum($column, $values = []){
        $col = new MRColumn();
        $col->name = $column;
        $col->dataType = "ENUM";
        $col->enumValues = $values;
        $this->columns[] = $col;
        return $col;
    }

    public function after($column){
        $this->afterColumn = $column;
    }

    public function run(){
        if($this->operation == "CREATE"){
            $this->createTable();
        }
        elseif($this->operation == "MODIFY"){
            $this->addColumns();
        }
    }

    //-- Private functions

    private function createTable(){
        $query = "CREATE TABLE IF NOT EXISTS ".$this->tableName."(";
        foreach($this->columns as $column){
            $query.=$column->get();
        }

        if($this->primaryKey != ''){
            $query.=" PRIMARY KEY (".$this->primaryKey.") ";
        }

        $query = rtrim($query,',');
        $query.=");";

        MRDatabase::query($query);
    }

    private function addColumns(){
        $query = "ALTER TABLE ".$this->tableName;
        foreach($this->columns as $column){
            $query.=" ADD COLUMN ".$column->get();
        }

        $query = rtrim($query,',');

        if($this->afterColumn!="") {
            $query.=" AFTER ".$this->afterColumn;
        }

        $query.=";";

        MRDatabase::query($query);
    }



}