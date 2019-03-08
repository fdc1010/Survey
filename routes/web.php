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
Route::post('storeAnswers','SurveyAnswerController@storeAnswers');
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
// Route::get('updateothertallyvotesqualityMayor', 'SurveyAnswerController@updateothertallyvotesqualityMayor')->name('updateothertallyvotesqualityMayor');
// Route::get('updateothertallyvotesqualityViceMayor', 'SurveyAnswerController@updateothertallyvotesqualityViceMayor')->name('updateothertallyvotesqualityViceMayor');
// Route::get('updateothertallyvotesqualityCong', 'SurveyAnswerController@updateothertallyvotesqualityCong')->name('updateothertallyvotesqualityCong');
// Route::get('updateothertallyvotesquality', 'SurveyAnswerController@updateothertallyvotesquality')->name('updateothertallyvotesquality');
// Route::get('insertmissingothertallyvotesqualityViceMayor', 'SurveyAnswerController@insertmissingothertallyvotesqualityViceMayor')->name('insertmissingothertallyvotesqualityViceMayor');
// Route::get('insertmissingothertallyvotesqualityCong', 'SurveyAnswerController@insertmissingothertallyvotesqualityCong')->name('insertmissingothertallyvotesqualityCong');
Route::get('updateOtherTallyVotesQuality', 'SurveyAnswerController@updateOtherTallyVotesQuality')->name('updateOtherTallyVotesQuality');
Route::get('testOtherVotersRelQ', 'SurveyAnswerController@testOtherVotersRelQ')->name('testOtherVotersRelQ');
Route::post('updatedfnvoters', 'VoterController@updatedfnvoters')->name('updatedfnvoters');

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
});

/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);

Route::get('/home', 'HomeController@index')->name('home');
