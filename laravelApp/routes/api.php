<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/gettoken', 'UserController@getKey');
Route::get('/register', 'UserController@createUser');
Route::get('/planttypes', 'PlantTypeController@getTypesList');	

 Route::group(['middleware' => ['auth:api']], function () {
	Route::get('/users', 'UserController@getUser');	
	Route::post('/users/{user}/plants', 'UserController@createPlant');	
	Route::get('/users/{user}/plants', 'UserController@getListOfPlants');
	Route::get('/users/{user}/plants/{plant}', 'PlantController@getPlant');
	Route::post('/users/{user}/plants/{plant}/produce', 'PlantController@produce');
	Route::post('/users/{user}/plants/{plant}/consume', 'PlantController@consume');
	Route::get('/users/{user}/balance', 'UserController@getBalance');	
	Route::get('/users/{user}/summary', 'UserController@getSummary');	
	Route::get('/users/{user}/eventstream', 'UserController@getEventStream');
	
});





