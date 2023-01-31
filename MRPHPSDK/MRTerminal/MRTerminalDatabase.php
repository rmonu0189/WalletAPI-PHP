<?php

namespace MRPHPSDK\MRTerminal;

class MRTerminalDatabase{

    protected $connection;

    /**
     * Database initialization.
     */
    function __construct(){
        try {
            $this->connection = new \PDO("mysql:host=".DATABASE_HOSTNAME.";dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
            $inst = new MRTerminalDatabase();
        }
        return $inst;
    }

    public static function query($query){
        try {
            $statement = MRTerminalDatabase::instance()->connection->prepare($query);
            $statement->execute();
        } catch(\Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

}