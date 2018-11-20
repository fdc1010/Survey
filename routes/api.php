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
		info("Mobile");	
		Route::post('mobilelogin', 'MobileController@mobilelogin');
		Route::resource('fred', 'MobileController');
		Route::group(['middleware' => 'auth:api'], function () {
			info("Mobile Middleware");	
			
			Route::get('mobilelogout', 'MobileController@mobilelogout');	
		});
});
/*Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {
	Route::post('/short', 'UrlMapperController@store');
});*/