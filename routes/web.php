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
Route::post('importprecinct', 'VoterController@importprecinct')->name('importprecinct');
Route::post('importvoters', 'VoterController@importvoters')->name('importvoters');
Route::post('importbarangays', 'BarangayController@importbarangays')->name('importbarangays');
Route::post('importsitios', 'SitioController@importsitios')->name('importsitios');
Route::get('extramiddlename', 'VoterController@extramiddlename')->name('extramiddlename');
//Route::get('media/user/{user}/{collection}', 'VoterController@getMedia');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => config('backpack.base.route_prefix', 'admin'), 'middleware' => ['web', 'auth'], 'namespace' => 'Admin'], function () {
    // Backpack\MenuCRUD
    CRUD::resource('menu-item', 'MenuItemCrudController');
	
});
/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);
	
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();
Route::group(['middleware' => ['auth']], function () {
	Route::post('mobilelogin', 'Auth\LoginController@mobilelogin');
});