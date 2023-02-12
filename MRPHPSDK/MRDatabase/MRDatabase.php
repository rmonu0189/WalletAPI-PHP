<?php

namespace MRPHPSDK\MRDatabase;

use PDO;
use MRPHPSDK\MRConfig\MRConfig;

class MRDatabase{

    protected $connection;

    /**
     * Database initialization.
     */
    function __construct(){
        try {
            $this->connection = new PDO("mysql:host=".MRConfig::get('database')['hostname'].";dbname=".MRConfig::get('database')['databaseName'], MRConfig::get('database')['userName'], MRConfig::get('database')['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Call this method to get singleton
     *
     * @return Route
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new MRDatabase();
        }
        return $inst;
    }

    public static function query($query){
        try {
            $statement = MRDatabase::instance()->connection->prepare($query);
            $statement->execute();
        } catch(\Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function select($query){
        try {
            $statement = MRDatabase::instance()->connection->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch(\Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function getColumns($table){
        try{
            $statement = MRDatabase::instance()->connection->prepare("SHOW COLUMNS FROM $table");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public static function insert($table, $params){
        if(count($params)>0){
            $query = "";
            foreach($params as $key=>$value){ $query.=($query==''?'':',')." $key='$value'"; }
            $query = "INSERT INTO $table SET ".$query;
            MRDatabase::query($query);
            return MRDatabase::instance()->connection->lastInsertId();
        }
        else{
            return "There is not data to save.";
        }
    }

    public static function update($table, $params){
        if(count($params)>0){
            $query = "";
            foreach($params as $key=>$value){
                if($key=="id") continue;
                $query.=($query==''?'':',')." $key='$value'";
            }
            $query = "UPDATE $table SET ".$query." WHERE id='".$params['id']."'";
            MRDatabase::query($query);
        }
        else{
            return "There is not data to update.";
        }
    }

}