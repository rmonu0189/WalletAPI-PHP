<?php

namespace MRPHPSDK\MRRoute;


class RouteDetails{

    public $middleware;

    public $method;

    public $routes;

    public $package;

    public $prefix;

    public $controller;

    public $classMethod;

    public $domain;

    function __construct($security = [], $route = "/", $method="GET", $controller=''){
        $this->middleware = (isset($security['middleware'])?$security['middleware']:[]);
        $this->domain = (isset($security['domain'])?$security['domain']:[]);
        $this->routes = $route;
        $this->method = strtoupper($method);
        $this->package = (isset($security['package'])?$security['package']:"");
        $this->prefix = (isset($security['prefix']))?$security['prefix']: "";
        $this->controller = $controller;
    }

}