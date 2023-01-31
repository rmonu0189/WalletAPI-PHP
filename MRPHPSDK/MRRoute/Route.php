<?php

namespace MRPHPSDK\MRRoute;

use MRPHPSDK\MRRoute\RouteDetails;
use MRPHPSDK\MRRequest\MRRequest;

final class Route{

    public $groupSecurity = [];

    public $routes;

    private $controller;

    private $method;

    /**
     * Call this method to get singleton
     *
     * @return Route
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Route();
            $inst->groupSecurity = [];
        }
        return $inst;
    }


    public static function group($security, $funcAsParams){
        if(count($security)>0){
            if(count(Route::instance()->groupSecurity) > 0){
                Route::instance()->groupSecurity = array_merge(Route::instance()->groupSecurity, $security);
            }
            else{
                Route::instance()->groupSecurity = $security;
            }
        }

        $funcAsParams();

        foreach ($security as $key => $value) {
            unset(Route::instance()->groupSecurity[$key]);
        }
    }

    public static function get($uri){
        if(isset(Route::instance()->groupSecurity['domain'])){
            if(Route::instance()->groupSecurity['domain'] == $_SERVER['SERVER_NAME']){
                Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, $uri, "GET");
            }
        }
        else{
            Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, $uri, "GET");
        }
    }

    public static function post($uri){
        if(isset(Route::instance()->groupSecurity['domain'])){
            if(Route::instance()->groupSecurity['domain'] == $_SERVER['SERVER_NAME']){
                Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, $uri, "POST");
            }
        }
        else{
            Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, $uri, "POST");
        }
    }

    public static function controller($uri, $name){
        if(isset(Route::instance()->groupSecurity['domain'])){
            if(Route::instance()->groupSecurity['domain'] == $_SERVER['SERVER_NAME']){
                Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, "/".$uri, "CONTROLLER", $name);
            }
        }
        else{
            Route::instance()->routes[] = new RouteDetails(Route::instance()->groupSecurity, "/".$uri, "CONTROLLER", $name);
        }
    }

    public static function getCurrentRouteDetails($routes){
        foreach (Route::instance()->routes as $routeDetails) {
            if($routeDetails->method == "CONTROLLER"){
                if($routeDetails->prefix != "" && $routeDetails->prefix==$routes[0]){
                    $uri = "";
                    $uri = "/".(isset($routes[1])?$routes[1]:"");
                    if($routeDetails->routes == $uri){
                        Route::setController($routeDetails->controller);

                        if(count($routes) > 2){
                            $classMethod = strtolower($_SERVER['REQUEST_METHOD']).$routes[2];
                            Route::setMethod($classMethod);
                        }
                        else{
                            $classMethod = strtolower($_SERVER['REQUEST_METHOD'])."Index";
                            Route::setMethod($classMethod);
                        }

                        if(count($routes)>3){
                            unset($routes[0]);
                            unset($routes[1]);
                            unset($routes[2]);
                            MRRequest::setUrlParams(array_values($routes));
                        }
                        return $routeDetails;
                    }
                }
                else{
                    $uri = "";
                    $uri = "/".(isset($routes[0])?$routes[0]:"");
                    if($routeDetails->routes == $uri){
                        Route::setController($routeDetails->controller);

                        if(count($routes) > 1){
                            $classMethod = strtolower($_SERVER['REQUEST_METHOD']).$routes[1];
                            Route::setMethod($classMethod);
                        }
                        else{
                            $classMethod = strtolower($_SERVER['REQUEST_METHOD'])."Index";
                            Route::setMethod($classMethod);
                        }

                        if(count($routes)>2){
                            unset($routes[0]);
                            unset($routes[1]);
                            MRRequest::setUrlParams(array_values($routes));
                        }
                        return $routeDetails;
                    }
                }
            }
            elseif($routeDetails->prefix != "" && (count($routes)>0) && $routeDetails->prefix==$routes[0] && $_SERVER['REQUEST_METHOD'] == $routeDetails->method){
                $uri = "";
                $uri .= (isset($routes[1])?"/".$routes[1]:"");
                $uri .= (isset($routes[2])?"/".$routes[2]:"");
                if($routeDetails->routes == $uri){
                    Route::setController((isset($routes[1])?$routes[1]:"")."Controller");
                    Route::setMethod((isset($routes[2])?$routes[2]:"index"));
                    if(count($routes)>3){
                        unset($routes[0]);
                        unset($routes[1]);
                        unset($routes[2]);
                        MRRequest::setUrlParams(array_values($routes));
                    }
                    return $routeDetails;
                }
            }
            elseif($_SERVER['REQUEST_METHOD'] == $routeDetails->method){
                $uri = "/".(isset($routes[0])?$routes[0]:"");
                $uri .= (isset($routes[1])?"/".$routes[1]:"");
                if($routeDetails->routes == $uri){
                    Route::setController((isset($routes[0])?$routes[0]:"")."Controller");
                    Route::setMethod((isset($routes[1])?$routes[1]:"index"));
                    if(count($routes)>2){
                        unset($routes[0]);
                        unset($routes[1]);
                        MRRequest::setUrlParams(array_values($routes));
                    }
                    return $routeDetails;
                }
            }
        }
        return null;
    }

    public static function setController($controller){
        Route::instance()->controller = $controller;
    }

    public static function getController(){
        return Route::instance()->controller;
    }

    public static function setMethod($method){
        Route::instance()->method = $method;
    }

    public static function getMethod(){
        return Route::instance()->method;
    }

}