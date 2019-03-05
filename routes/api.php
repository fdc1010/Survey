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
		//info("Mobile");
		Route::post('login', 'MobileController@login');
		Route::get('getCivilStatus', 'CivilStatusController@getCivilStatus');
		Route::get('getEmploymentStatus', 'EmploymentStatusController@getEmploymentStatus');
		Route::get('getOccupancyStatus', 'OccupancyStatusController@getOccupancyStatus');
		Route::get('getVoterStatuses', 'VoterController@getVoterStatuses');
		Route::get('getVoterInfoByName', 'VoterController@getVoterInfoByName');
		Route::post('storeAnswers','SurveyAnswerController@storeAnswers');
		Route::get('getSurveyorProgress','SurveyAnswerController@getSurveyorProgress');
		Route::get('getSurveyorProgressB','SurveyAnswerController@getSurveyorProgressB');
		Route::get('getSurveyorInfo','MobileController@getSurveyorInfo');

		Route::group(['middleware' => 'auth:api'], function () {
			//info("Mobile Middleware");
			Route::get('logout', 'MobileController@logout');
			Route::get('getQuestions','QuestionController@getQuestions');
			Route::resource('voter', 'VoterController');
			Route::post('sendInfo','VoterController@sendInfo');
			Route::post('syncInData', 'VoterController@syncInData');
		});
});
/*Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {
	Route::post('/short', 'UrlMapperController@store');
});*/
