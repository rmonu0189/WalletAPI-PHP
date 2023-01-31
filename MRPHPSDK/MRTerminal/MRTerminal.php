<?php

namespace MRPHPSDK\MRTerminal;

class MRTerminal{

    private $controller;
    private $location;
    private $namespace;

    function handle($command, $fileName){
        switch($command){

            case "make:controller":
                $this->makeController($fileName);
                break;

            case "make:model":
                $this->makeModel($fileName);
                break;

            case "make:middleware":
                $this->makeMiddleware($fileName);
                break;

            case "make:route":
                $this->makeRoute($fileName);
                break;

            case "make:app":
                $this->makeApp();
                break;

            case "make:package":
                $this->makePackage($fileName);
                break;

            case "make:migrationTable":
                $this->createMigrationTable();
                break;

            case "make:migration":
                $this->makeMigration($fileName);
                break;

            case "migrate":
                $this->migration();
                break;

            case "log":
                $this->log();
                break;

            case "help":
                $this->help();
                break;

            default:
                MRPrint::error("\"$command\" command is not valid for MRPHPSDK.");
                MRPrint::error("For any help type command: \"php script help\"");
                $this->help();
                break;
        }
    }

    private function makeMigration($fileName){
        $this->location = __DIR__."/../../Application/Database/";
        if(!file_exists($this->location)){
            mkdir($this->location, 0777, true);
        }
        $this->createMigration($fileName, $this->location);
        $this->createMigrationTable();
    }

    private function migration(){
        require_once("autoload_terminal.php");
        $this->location = __DIR__."/../../Application/Database/";
        if(file_exists($this->location)){
            MRPrint::alert("Start migration.");
            $files = array_diff(scandir($this->location), array('.', '..'));
            foreach($files as $file){
                $this->migrateClass($file);
            }
            MRPrint::alert("Migration Completed.");
        }
        else{
            MRPrint::alert("No migration found.");
        }
    }

    private function migrateClass($fileName){
        $this->location = __DIR__."/../../Application/Database/".$fileName;
        if(file_exists($this->location)){

            $migrateDone = \MRPHPSDK\MRMigration\Migrations::where("name",$fileName)
                            ->where("process", "1")
                            ->first();

            if($migrateDone == null){
                $actual = $this->getMigrationClassName($fileName);
                include_once($this->location);
                $obj = new $actual;
                $obj->up();

                $migrate = new \MRPHPSDK\MRMigration\Migrations();
                $migrate->name = $fileName;
                $migrate->process = 1;
                $migrate->save();

                MRPrint::success("Migrate: ".$fileName);
            }

        }
        else{
            MRPrint::error($fileName." file not found");
        }
    }

    private function createMigrationTable(){
        require_once("autoload_terminal.php");
        \MRPHPSDK\MRMigration\MRMigration::create('Migrations', function (\MRPHPSDK\MRMigration\DBSchema $schema){
            $schema->bigIncrement('id');
            $schema->string('name');
            $schema->integer('process')->defaults('0');
        });
        MRPrint::success("Migration table created successfully.");
    }

    private function getMigrationClassName($fileName){
        $component = explode("_", $fileName);
        if(count($component) > 1){
            array_splice($component, 0, 1);
        }
        $actual = "";
        foreach($component as $slice){
            $actual.=($actual==""?"":"_").$slice;
        }
        return rtrim($actual,'.php');
    }

    private function log(){
        if (!file_exists(__DIR__."/../../Application/Logs")) {
            mkdir(__DIR__."/../../Application/Logs", 0777, true);
        }
        MRPrint::alert("Log folder created successfully.");
    }

    private function makePackage($package){
        if($package != ""){
            $this->makeRoute($package);
            $this->makeController($package."@IndexController");
            $this->makeModel($package."@Index");
            $this->makeMiddleware($package."@AuthMiddleware");

            //-- Create package.json file
            if(file_exists(__DIR__.'/../../Application/package.json')){
                $homepage = file_get_contents(__DIR__.'/../../Application/package.json');
                $json = json_decode($homepage, true);
                $temp = $json['package'];
                $temp = ($temp==null)?[]:$temp;
                if(!in_array($package, $temp)){
                    array_push($temp, $package);
                }
                $json = ["package"=>$temp];
                MRPrint::write(__DIR__."/../../Application/package.json", json_encode($json));
            }
            else{
                $json = ["package"=>[$package]];
                MRPrint::write(__DIR__."/../../Application/package.json", json_encode($json));
            }
            MRPrint::alert("Import package into json file");
        }
        else{
            MRPrint::error("Please provide package name.");
            MRPrint::error("For eg.: php script make:package SamplePackage");
        }
    }

    private function makeApp(){
        $this->makeRoute();
        $this->makeController("IndexController");
        $this->makeModel("Index");
        $this->makeMiddleware("AuthMiddleware");
        $this->log();
    }

    private function makeRoute($path=""){
        $package = "";
        if( $path != "") {
            $package = $path;
            $this->location = __DIR__."/../../Application/Packages/".$package."/route";
            if (!file_exists(__DIR__."/../../Application/Packages/".$package)) {
                mkdir(__DIR__."/../../Application/Packages/".$package, 0777, true);
            }
        }
        else{
            $this->location = __DIR__."/../../Application/route";
            if (!file_exists(__DIR__."/../../Application")) {
                mkdir(__DIR__."/../../Application", 0777, true);
            }
        }

        $content = MRTemplate::route($package);
        MRPrint::write($this->location.".php", $content);
        MRPrint::alert("Route created successfully.");

    }

    private function makeMiddleware($path){
        $this->parsePath($path, "Middleware");
        $this->createMiddleware($this->controller, $this->location, $this->namespace);
    }

    private function createMiddleware($model, $location, $namespace){
        $content = MRTemplate::middleware($namespace, $model);
        MRPrint::write($location.".php", $content);
        MRPrint::alert("Middleware \"$model\" created successfully.");
    }

    private function makeModel($path){
        $this->parsePath($path, "Model");
        $this->createModel($this->controller, $this->location, $this->namespace);
    }

    private function createMigration($migration, $location){
        $content = MRTemplate::migration($migration);
        $location.=date('dmYHis')."_".$migration;
        MRPrint::write($location.".php", $content);
        MRPrint::alert("Migration \"$migration\" created successfully.");
    }

    private function createModel($model, $location, $namespace){
        $content = MRTemplate::model($namespace, $model);
        MRPrint::write($location.".php", $content);
        MRPrint::alert("Model \"$model\" created successfully.");
    }

    private function makeController($path){
        $this->parsePath($path, "Controller");
        $this->createController($this->controller, $this->location, $this->namespace);
    }

    private function createController($controller, $path, $namespace){
        $content = MRTemplate::controller($namespace, $controller);
        MRPrint::write($path.".php", $content);
        MRPrint::alert("Controller \"$controller\" created successfully.");
    }

    private function parsePath($path, $type){
        if( strpos( $path, "@" ) !== false ) {
            $extract = explode("@", $path);
            $package = $extract[0];
            $this->controller = $extract[1];
            $this->namespace = "Application\\Packages\\".$package."\\$type";
            $this->location = __DIR__."/../../Application/Packages/".$package."/$type/".$this->controller;
            if (!file_exists(__DIR__."/../../Application/Packages/".$package."/$type")) {
                mkdir(__DIR__."/../../Application/Packages/".$package."/$type", 0777, true);
            }
        }
        else{
            $this->controller = $path;
            $this->namespace = "Application\\$type";
            $this->location = __DIR__."/../../Application/$type/".$this->controller;
            if (!file_exists(__DIR__."/../../Application/$type")) {
                mkdir(__DIR__."/../../Application/$type", 0777, true);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Migrate database.
    |--------------------------------------------------------------------------
    |
    | Migrate all database files with your projects.
    | All files status present in database as a version.
    |
    */
    function migrate($fileName){
        MRTerminalDatabase::query("SHOW TABLES");
    }

    function migrateAll(){

    }


   /*
   |--------------------------------------------------------------------------
   | Help
   |--------------------------------------------------------------------------
   */
    function help(){
        MRPrint::success("###########################################################");
        MRPrint::success("######################### HELP ############################");
        MRPrint::success("###########################################################\n");
        MRPrint::success("------------------- MIGRATION -----------------------------");
        MRPrint::success("migrate             : Migrate all files");
        MRPrint::success("migrate class_name  : Migrate given class");
        MRPrint::success("\n ------------------- MAKE ----------------------------------");
        MRPrint::success("make:app          : Create new application");
        MRPrint::success("make:package      : Create new package");
        MRPrint::success("make:route        : Create route file");
        MRPrint::success("make:controller   : Create new controller on given path");
        MRPrint::success("make:model        : Create new model on given path");
        MRPrint::success("make:middleware   : Create new middleware on given path");
        MRPrint::success("---------------------------------------------------------------");
        MRPrint::success("\n For Eg. php script make:middleware AuthMiddleware");
        MRPrint::success("\t\t\tor");
        MRPrint::success("php script make:middleware API@AuthMiddleware");
        MRPrint::success("\n Where \"API\" is the package name and \"AuthMiddleware\" is middleware name.");
        MRPrint::success("---------------------------------------------------------------");
        MRPrint::success("\n ###########################################################");
        MRPrint::success("###########################################################\n");
    }

}