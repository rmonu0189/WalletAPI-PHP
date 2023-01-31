<?php

namespace MRPHPSDK\MRKernel;

use MRPHPSDK\MRException\MRException;
use \MRPHPSDK\MRRequest\MRRequest;
use \MRPHPSDK\MRConfig\MRConfig;
use \MRPHPSDK\MRRoute\Route;
use \MRPHPSDK\MRRoute\RouteDetails;

class MRKernel{

    function __construct(){
        try{
            MRRequest::instance();
            MRConfig::handle();
            $this->handleRoutes();
            $this->call();
        }
        catch (MRException $e){
            throw new MRException($e->getMessage(), $e->getStatusCode());
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function call(){
        if($routeDetails = Route::getCurrentRouteDetails($this->getUri())){
            $this->handleMiddleware($routeDetails);
            $this->compile($routeDetails);
        }
        else{
            throw new \Exception("Rout not found");
        }
    }

    private function getUri(){
        if(isset($_GET["_url"])){
            $parts = explode("/", $_GET["_url"]);
        }
        else{
            $parts = [""];
        }

        unset($parts[0]);
        return array_values($parts);
    }

    private function handleRoutes(){
        //-- Default route
        if(file_exists(__DIR__."/../../Application/route.php")){
            require_once(__DIR__."/../../Application/route.php");
        }

        $packages = MRConfig::get('packages');
        foreach($packages as $value){
            if(file_exists(__DIR__."/../../Application/Packages/".$value."/route.php")){
                require_once(__DIR__."/../../Application/Packages/".$value."/route.php");
            }
        }
    }

    private function compile(RouteDetails $routeDetails){
        $controller = "\\Application\\".(($routeDetails->package!="")?"Packages\\".$routeDetails->package."\\":"")."Controller\\".Route::getController();
        if(class_exists($controller)){
            $controller = new $controller();
            if(method_exists($controller, Route::getMethod())){
                $method = Route::getMethod();
                echo $controller->$method(MRRequest::instance());
            }
            else{
                throw new \Exception("Action not found: ".Route::getMethod());
            }
        }
        else{
            throw new \Exception("Class not found: ".Route::getController());
        }
    }

    private function handleMiddleware(RouteDetails $routeDetails){
        if(count($routeDetails->middleware) > 0){
            foreach($routeDetails->middleware as $middleware){
                $this->checkMiddleware($middleware);
            }
        }
    }

    private function checkMiddleware($middleware){
        $middleware = str_replace("/", "\\", $middleware);
        $middlewareController = new $middleware();
        $middlewareController->handle(MRRequest::instance());
    }

}