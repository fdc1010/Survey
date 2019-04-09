<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('getQuestions','QuestionController@getQuestions');
Route::get('importexcel', 'VoterController@index')->name('index');
Route::get('importVotersExcel2', 'VoterController@importVotersExcel2');
Route::post('importprecinct', 'VoterController@importprecinct')->name('importprecinct');
Route::post('importvoters', 'VoterController@importvoters')->name('importvoters');
Route::post('importvoters2', 'VoterController@importvoters2')->name('importvoters2');
Route::post('importbarangays', 'BarangayController@importbarangays')->name('importbarangays');
Route::post('importsitios', 'SitioController@importsitios')->name('importsitios');
Route::get('extramiddlename', 'VoterController@extramiddlename')->name('extramiddlename');
Route::get('removeMNfromLN', 'VoterController@removeMNfromLN')->name('removeMNfromLN');

Route::get('dashboard2', 'HomeController@dashboard2')->name('dashboard2');
Route::get('insertUpdateSurvey', 'SurveyAnswerController@insertUpdateSurvey');
Route::get('insertUpdateSurveyQualities', 'SurveyAnswerController@insertUpdateSurveyQualities');
Route::get('insertUpdateSurveyProblems', 'SurveyAnswerController@insertUpdateSurveyProblems');
Route::get('updateSurveyQualities', 'SurveyAnswerController@updateSurveyQualities');
Route::get('updateTallyOtherAnsQualities', 'SurveyAnswerController@updateTallyOtherAnsQualities');
Route::get('checkMissingTally', 'SurveyAnswerController@checkMissingTally');
Route::get('checkMissingTallyQualities', 'SurveyAnswerController@checkMissingTallyQualities');
Route::get('checkMissingTallyProblems', 'SurveyAnswerController@checkMissingTallyProblems');
Route::get('checkDuplicateSurvey', 'SurveyAnswerController@checkDuplicateSurvey');
Route::get('testOtherVotesRelQ', 'SurveyAnswerController@testOtherVotesRelQ');
Route::get('testOtherVotesProblem', 'SurveyAnswerController@testOtherVotesProblem');
Route::post('updatedfnvoters', 'VoterController@updatedfnvoters')->name('updatedfnvoters');
Route::get('deleteVoterDuplicateTally','TallyVoteController@deleteVoterDuplicateTally');
Route::get('deleteVoterDuplicateSurveyAnswer','TallyVoteController@deleteVoterDuplicateSurveyAnswer');
Route::get('updateVoterUndecidedAnonymous','TallyVoteController@updateVoterUndecidedAnonymous');
Route::get('updateVoterBrgyUndecidedAnonymous','TallyVoteController@updateVoterBrgyUndecidedAnonymous');

//Route::get('media/user/{user}/{collection}', 'VoterController@getMedia');

Route::get('/', function () {
    return view('welcome');
});
Route::group([
	 'prefix' => config('backpack.base.route_prefix', 'admin'),
	 'middleware' => ['web', 'auth'],
	 'namespace' => 'Admin'], function () {
    // Backpack\MenuCRUD
    CRUD::resource('menu-item', 'MenuItemCrudController');
	Route::post('stats', 'StatsController@stats');
	Route::post('printsurvey', 'StatsController@printsurvey');
  Route::get('getSurveyorProgressDetails','SurveyorAssignmentController@getSurveyorProgressDetails');
});

/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);

Route::get('/home', 'HomeController@index')->name('home');
