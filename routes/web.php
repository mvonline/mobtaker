<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\AuthController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});



// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('resetpassword', 'AuthController@generateResetToken');
    $router->put('resetpassword', 'AuthController@resetPassword');
    $router->get('password/reset', ['as'=>'password.reset','uses'=>'AuthController@resetPassword']);

    $router->post('assignrole', ['middleware'=>['auth','role:admin'],'uses'=>'AuthController@assignRole']);
    $router->post('upload',['middleware'=>['auth','role:admin'],'uses'=>'ExcelController@upload']);
    $router->get('export',['middleware'=>['auth','role:admin'], 'uses'=>'ExcelController@export']);


});
