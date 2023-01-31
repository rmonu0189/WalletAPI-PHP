<?php

namespace MRPHPSDK\MRModels;

use MRPHPSDK\MRDatabase\MRDatabase;

class MRModel{

    private $query;
    private $selected;
    private $where;
    private $whereOr;
    private $orderBy;
    private $limit;
    private $leftJoin;
    private $groupBy;


    function __construct($params = array()){
        if(count($params) > 0){
            $this->setParams($params);
        }
    }

    public function remove(){
        $query = "DELETE FROM ".$this->getClassName()." WHERE id='".$this->id."'";
        MRDatabase::query($query);
    }

    public function save($data = array()){
        if(count($data) > 0){
            $this->setParams($data);
        }
        $columns = MRDatabase::getColumns($this->getClassName());
        $params = [];
        foreach($columns as $attribute){
            if(isset($this->{$attribute->Field})){
                $params[$attribute->Field] = $this->{$attribute->Field};
            }
        }
        if(isset($this->id) && $this->id>0){
            MRDatabase::update($this->getClassName(), $params);
        }
        else{
            $result = MRDatabase::insert($this->getClassName(), $params);
            if($result > 0){
                $this->id = $result;
            }
        }
    }

    public function get(){
        $result = MRDatabase::select($this->generateQuery());
        $this->refresh();
        return $result;
    }

    public function first(){
        $result = MRDatabase::select($this->generateQuery());
        $this->refresh();
        if(count($result)>0){
            $class = get_called_class();
            $classObject = new $class();
            foreach($result[0] as $key=>$value){
                $classObject->{$key} = $value;
            }
            return $classObject;
        }
        else{
            return null;
        }
    }

    public function whereOr($value){
        $this->whereOr = $value;
        return $this;
    }

    public function limit($count, $start=0){
        $this->limit.=" LIMIT $start, $count";
        return $this;
    }

    public function orderBy($key,$order){
        $this->orderBy.=(($this->orderBy=="")?" ORDER BY ":", ")."$key $order";
        return $this;
    }

    public function selectField($fields){
        $this->selected = implode(",", $fields);
        return $this;
    }

    public function leftJoin($table, $join, $on){
        $this->leftJoin .= " LEFT JOIN $table ON $table.$join=".$this->getClassName().".$on ";
        return $this;
    }

    public function groupBy($groupBy){
        $this->groupBy = " GROUP BY ".$groupBy;
        return $this;
    }

    public function generateQuery(){
        $query = "SELECT ".(($this->selected=='')?"*":$this->selected)." FROM ".$this->getClassName();
        $query.=$this->leftJoin;
        $query.=($this->where=="")?"":" WHERE ".$this->where;
        $query.=($this->whereOr=="")?"":((strpos($query, "WHERE")!== false)?" and ":" WHERE ").$this->whereOr;
        $query.=($this->groupBy=='')?"":$this->groupBy;
        $query.=($this->orderBy=="")?"":$this->orderBy;
        $query.=($this->limit=="")?"":$this->limit;
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $class = get_called_class();
            $inst = new $class();
        }
        return $inst;
    }

    public static function where($key, $value){
        static $inst = null;
        if ($inst === null) {
            $class = get_called_class();
            $inst = new $class();
        }
        $inst->where .= (($inst->where=="")?"":" AND ").$key."='".$value."'";
        return $inst;
    }

    public static function count($key, $value){
        $fullClassName = explode("\\", get_called_class());
        $calledClass = array_pop($fullClassName);  
        $query = "SELECT COUNT($key) as count FROM $calledClass WHERE $key='$value'";
        $result = MRDatabase::select($query);
        return $result[0]['count'];
    }

    public static function removeWithKeys($condition = []){
        $fullClassName = explode("\\", get_called_class());
        $calledClass = array_pop($fullClassName);  
        $query = "DELETE FROM $calledClass";
        foreach ($condition as $key => $value) {
            $query.=(strpos($query, "WHERE")!== false)?" AND $key='$value'":" WHERE $key='$value'";
        }
        MRDatabase::query($query);
    }

    public static function getCalledClass(){
        $value = explode("\\", get_called_class());
        $extension = array_pop($value);
        return $extension;
    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */
    private function setParams($params){
        $columns = MRDatabase::getColumns($this->getClassName());
        foreach($columns as $attribute){
            if(isset($params[$attribute->Field])){
                $this->{$attribute->Field} = $params[$attribute->Field];
            }
        }
    }

    private function getClassName(){
        $value = explode("\\", get_class($this));
        $extension = array_pop($value);
        return $extension;
    }

    private function refresh(){
        $this->query = "";
        $this->selected = "";
        $this->where = "";
        $this->whereOr = "";
        $this->orderBy = "";
        $this->limit = "";
        $this->leftJoin = "";
    }
}
