<?php

use \MRPHPSDK\MRRoute\Route;

Route::group([
	'prefix' => 'api'
], function(){
	Route::controller("auth", "AuthController");

	Route::group([
        'middleware' => ['/Application/Middleware/AuthMiddleware'],
    ], function(){
        Route::controller("user", "UserController");
        Route::controller("account", "AccountController");
        Route::controller("person", "PersonController");
        Route::controller("incomesource", "IncomeSourceController");
        Route::controller("category", "CategoryItemController");
        Route::controller("transaction", "TransactionController");
    });
});