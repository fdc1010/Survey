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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['prefix' => 'mobile',
	'namespace' => 'Mobile'], function () {	 
			inf("fred");
			Route::post('login', 'MobileAuthController@login');
			Route::get('logout', 'MobileAuthController@logout');
	
});
/*Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {
	Route::post('/short', 'UrlMapperController@store');
});*/